<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Message extends Model
{
    protected string $table = 'messages';

    public function forConversation(int $conversationId): array
    {
        $stmt = $this->db()->prepare(
            'SELECT m.*, u.first_name, u.last_name, u.avatar, u.role
             FROM messages m JOIN users u ON u.id = m.sender_id
             WHERE m.conversation_id = :id ORDER BY m.created_at ASC'
        );
        $stmt->execute(['id' => $conversationId]);

        return $stmt->fetchAll();
    }
}
