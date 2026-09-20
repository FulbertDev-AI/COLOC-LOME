<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\Visit;
use App\Models\Application;

final class MessageController extends Controller
{
    public function index(): void
    {
        $user = $this->requireAuth();
        $inbox = (new Conversation())->inboxFor((int) $user['id']);
        $activeId = $inbox[0]['id'] ?? null;
        if ($activeId) {
            $this->redirect('/messages/' . $activeId);
        }

        $this->render('messages/index', [
            'title' => 'Messages — ColocLomé',
            'inbox' => [],
            'conversation' => null,
            'messages' => [],
            'peer' => null,
            'visit' => null,
        ]);
    }

    public function show(string $id): void
    {
        $user = $this->requireAuth();
        $this->renderThread((int) $id, $user);
    }

    public function send(string $id): void
    {
        $user = $this->requireAuth();
        $conversation = (new Conversation())->findForUser((int) $id, (int) $user['id']);
        if (!$conversation) {
            $this->redirect('/messages');
        }

        $body = trim((string) $this->input('body', ''));
        if ($body !== '') {
            (new Message())->create([
                'conversation_id' => (int) $id,
                'sender_id' => (int) $user['id'],
                'body' => $body,
            ]);
            (new Conversation())->update((int) $id, [
                'last_message_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $this->redirect('/messages/' . $id);
    }

    private function renderThread(int $id, array $user): void
    {
        $conversations = new Conversation();
        $inbox = $conversations->inboxFor((int) $user['id']);
        $conversation = $conversations->findForUser($id, (int) $user['id']);
        if (!$conversation) {
            Session::flash('error', 'Conversation introuvable.');
            $this->redirect('/messages');
        }

        $peerId = (int) $user['id'] === (int) $conversation['student_id']
            ? (int) $conversation['host_id']
            : (int) $conversation['student_id'];
        $peer = (new User())->publicProfile($peerId);
        $messages = (new Message())->forConversation($id);

        $application = null;
        $apps = (new Application())->forStudent((int) $conversation['student_id']);
        foreach ($apps as $app) {
            if ((int) $app['listing_id'] === (int) $conversation['listing_id']) {
                $application = $app;
                break;
            }
        }
        $visit = $application ? (new Visit())->forApplication((int) $application['id']) : null;

        $this->render('messages/index', [
            'title' => 'Messages — ColocLomé',
            'inbox' => $inbox,
            'conversation' => $conversation,
            'messages' => $messages,
            'peer' => $peer,
            'visit' => $visit,
            'application' => $application,
        ]);
    }
}
