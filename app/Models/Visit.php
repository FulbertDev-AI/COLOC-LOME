<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Visit extends Model
{
    protected string $table = 'visits';

    public function forApplication(int $applicationId): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM visits WHERE application_id = :id ORDER BY id DESC LIMIT 1');
        $stmt->execute(['id' => $applicationId]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function upcomingForHost(int $hostId): array
    {
        $sql = 'SELECT v.*, a.student_id, s.first_name, s.last_name, s.program
                FROM visits v
                JOIN applications a ON a.id = v.application_id
                JOIN listings l ON l.id = a.listing_id
                JOIN users s ON s.id = a.student_id
                WHERE l.host_id = :id
                ORDER BY v.scheduled_at ASC';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['id' => $hostId]);

        return $stmt->fetchAll();
    }
}
