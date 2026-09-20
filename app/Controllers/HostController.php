<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Application;
use App\Models\Listing;
use App\Models\Visit;

final class HostController extends Controller
{
    public function dashboard(): void
    {
        $user = $this->requireAuth('host');
        $listings = array_values(array_filter(
            (new Listing())->search([]),
            static fn (array $l) => (int) $l['host_id'] === (int) $user['id']
        ));

        $applications = (new Application())->forHost((int) $user['id']);
        $visits = (new Visit())->upcomingForHost((int) $user['id']);

        $this->render('host/dashboard', [
            'title' => 'Tableau de bord hôte — ColocLomé',
            'listings' => $listings,
            'listing' => $listings[0] ?? null,
            'applications' => $applications,
            'visits' => $visits,
        ]);
    }

    public function updateApplication(string $id): void
    {
        $this->requireAuth('host');
        $status = (string) $this->input('status', 'pending');
        $allowed = ['pending', 'accepted', 'visit', 'rejected', 'waitlist'];
        if (!in_array($status, $allowed, true)) {
            $status = 'pending';
        }
        (new Application())->update((int) $id, ['status' => $status]);
        Session::flash('success', 'Candidature mise à jour.');
        $this->redirect('/hote');
    }
}
