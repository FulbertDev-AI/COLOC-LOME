<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Application extends Model
{
    protected string $table = 'applications';

    public function forStudent(int $studentId): array
    {
        $sql = 'SELECT a.*, l.title, l.rent, l.deposit_months, n.name AS neighborhood,
                       u.first_name, u.last_name, u.program,
                       (SELECT filename FROM listing_photos p WHERE p.listing_id = l.id ORDER BY sort_order LIMIT 1) AS cover
                FROM applications a
                JOIN listings l ON l.id = a.listing_id
                JOIN neighborhoods n ON n.id = l.neighborhood_id
                JOIN users u ON u.id = l.host_id
                WHERE a.student_id = :id
                ORDER BY a.created_at DESC';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['id' => $studentId]);
        $rows = $stmt->fetchAll();
        foreach ($rows as &$row) {
            $row['cover'] = listing_photo($row['cover'] ?? null);
        }
        unset($row);

        return $rows;
    }

    public function forHost(int $hostId): array
    {
        $sql = 'SELECT a.*, l.title, s.first_name, s.last_name, s.program, s.school, s.avatar, s.verified,
                       s.budget_max, s.lifestyle
                FROM applications a
                JOIN listings l ON l.id = a.listing_id
                JOIN users s ON s.id = a.student_id
                WHERE l.host_id = :id
                ORDER BY a.match_score DESC';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['id' => $hostId]);

        return $stmt->fetchAll();
    }
}
