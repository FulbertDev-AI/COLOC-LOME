<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Application;
use App\Models\Conversation;
use App\Models\Listing;
use App\Models\Visit;

final class ApplicationController extends Controller
{
    public function index(): void
    {
        $user = $this->requireAuth('student');
        $applications = (new Application())->forStudent((int) $user['id']);
        foreach ($applications as &$app) {
            $app['visit'] = (new Visit())->forApplication((int) $app['id']);
        }

        $this->render('student/applications', [
            'title' => 'Mes candidatures — ColocLomé',
            'applications' => $applications,
        ]);
    }

    public function store(): void
    {
        $user = $this->requireAuth('student');
        $listingId = (int) $this->input('listing_id');
        $listing = (new Listing())->find($listingId);
        if (!$listing) {
            $this->redirect('/recherche');
        }

        $id = $this->ensureConversation($listing, $user);

        (new Application())->create([
            'listing_id' => $listingId,
            'student_id' => (int) $user['id'],
            'conversation_id' => $id,
            'status' => 'pending',
            'match_score' => $listing['match_score'] ?? 80,
            'note' => 'Candidature envoyée depuis la fiche logement',
        ]);

        Session::flash('success', 'Candidature envoyée. Vous pouvez écrire au référent.');
        $this->redirect('/messages/' . $id);
    }

    public function confirmVisit(string $id): void
    {
        $this->requireAuth();
        (new Visit())->update((int) $id, ['status' => 'confirmed']);
        Session::flash('success', 'Présence confirmée pour la visite.');
        $this->back();
    }

    private function ensureConversation(array $listing, array $user): int
    {
        $pdo = \App\Core\Database::pdo();
        $stmt = $pdo->prepare('SELECT id FROM conversations WHERE listing_id = :l AND student_id = :s LIMIT 1');
        $stmt->execute(['l' => $listing['id'], 's' => $user['id']]);
        $existing = $stmt->fetchColumn();
        if ($existing) {
            return (int) $existing;
        }

        return (new Conversation())->create([
            'listing_id' => $listing['id'],
            'student_id' => $user['id'],
            'host_id' => $listing['host_id'],
            'last_message_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
