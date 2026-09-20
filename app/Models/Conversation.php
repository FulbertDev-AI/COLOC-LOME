<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Conversation extends Model
{
    protected string $table = 'conversations';

    public function inboxFor(int $userId): array
    {
        $sql = 'SELECT c.*, l.title, l.rent, n.name AS neighborhood,
                       student.first_name AS student_first, student.last_name AS student_last, student.avatar AS student_avatar, student.school AS student_school, student.verified AS student_verified,
                       host.first_name AS host_first, host.last_name AS host_last, host.avatar AS host_avatar,
                       (SELECT body FROM messages m WHERE m.conversation_id = c.id ORDER BY m.id DESC LIMIT 1) AS last_body,
                       (SELECT created_at FROM messages m WHERE m.conversation_id = c.id ORDER BY m.id DESC LIMIT 1) AS last_at
                FROM conversations c
                JOIN listings l ON l.id = c.listing_id
                JOIN neighborhoods n ON n.id = l.neighborhood_id
                JOIN users student ON student.id = c.student_id
                JOIN users host ON host.id = c.host_id
                WHERE c.student_id = :uid OR c.host_id = :uid2
                ORDER BY c.last_message_at DESC';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['uid' => $userId, 'uid2' => $userId]);

        return $stmt->fetchAll();
    }

    public function findForUser(int $id, int $userId): ?array
    {
        $sql = 'SELECT c.*, l.title, l.rent, l.charges, l.deposit_months, l.walk_minutes, l.host_id,
                       n.name AS neighborhood
                FROM conversations c
                JOIN listings l ON l.id = c.listing_id
                JOIN neighborhoods n ON n.id = l.neighborhood_id
                WHERE c.id = :id AND (c.student_id = :uid OR c.host_id = :uid2)
                LIMIT 1';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['id' => $id, 'uid' => $userId, 'uid2' => $userId]);
        $row = $stmt->fetch();

        return $row ?: null;
    }
}
