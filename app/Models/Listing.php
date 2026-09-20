<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Listing extends Model
{
    protected string $table = 'listings';

    public function withRelations(int $id): ?array
    {
        $sql = 'SELECT l.*, n.name AS neighborhood, n.zone, n.slug AS neighborhood_slug,
                       u.first_name, u.last_name, u.avatar, u.program, u.school, u.verified AS host_verified
                FROM listings l
                JOIN neighborhoods n ON n.id = l.neighborhood_id
                JOIN users u ON u.id = l.host_id
                WHERE l.id = :id LIMIT 1';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $listing = $stmt->fetch();
        if (!$listing) {
            return null;
        }

        $photos = $this->db()->prepare('SELECT * FROM listing_photos WHERE listing_id = :id ORDER BY sort_order');
        $photos->execute(['id' => $id]);
        $listing['photos'] = $photos->fetchAll();
        foreach ($listing['photos'] as &$photo) {
            $photo['filename'] = listing_photo($photo['filename'] ?? null);
        }
        unset($photo);

        return $listing;
    }

    public function search(array $filters): array
    {
        $sql = 'SELECT l.*, n.name AS neighborhood, n.slug AS neighborhood_slug,
                       u.first_name, u.last_name, u.avatar, u.program, u.school
                FROM listings l
                JOIN neighborhoods n ON n.id = l.neighborhood_id
                JOIN users u ON u.id = l.host_id
                WHERE l.status = "published"';
        $params = [];

        if (!empty($filters['neighborhoods']) && is_array($filters['neighborhoods'])) {
            $in = [];
            foreach (array_values($filters['neighborhoods']) as $i => $nid) {
                $key = 'n' . $i;
                $in[] = ':' . $key;
                $params[$key] = (int) $nid;
            }
            $sql .= ' AND l.neighborhood_id IN (' . implode(',', $in) . ')';
        }

        if (!empty($filters['budget_max'])) {
            $sql .= ' AND l.rent <= :budget_max';
            $params['budget_max'] = (int) $filters['budget_max'];
        }

        if (!empty($filters['budget_min'])) {
            $sql .= ' AND l.rent >= :budget_min';
            $params['budget_min'] = (int) $filters['budget_min'];
        }

        if (!empty($filters['fiber'])) {
            $sql .= ' AND l.fiber = 1';
        }
        if (!empty($filters['cash_power'])) {
            $sql .= ' AND l.cash_power = "individual"';
        }
        if (!empty($filters['water'])) {
            $sql .= ' AND l.water_included = 1';
        }
        if (!empty($filters['walk'])) {
            $sql .= ' AND l.walk_minutes <= :walk';
            $params['walk'] = (int) $filters['walk'];
        }
        if (!empty($filters['lifestyle'])) {
            $sql .= ' AND l.lifestyle = :lifestyle';
            $params['lifestyle'] = $filters['lifestyle'];
        }

        $sql .= ' ORDER BY l.match_score DESC, l.rent ASC';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $photo = $this->db()->prepare('SELECT filename FROM listing_photos WHERE listing_id = :id ORDER BY sort_order LIMIT 1');
            $photo->execute(['id' => $row['id']]);
            $row['cover'] = listing_photo($photo->fetchColumn() ?: 'listing-1a.jpg');
        }

        return $rows;
    }

    public function featured(int $limit = 3): array
    {
        return $this->search([]);
    }

    public function countPublished(): int
    {
        return (int) $this->db()->query('SELECT COUNT(*) FROM listings WHERE status = "published"')->fetchColumn();
    }
}
