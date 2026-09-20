<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Listing;
use App\Models\Neighborhood;

final class PublishController extends Controller
{
    public function create(): void
    {
        $this->requireAuth('host');
        $this->render('listings/create', [
            'title' => 'Déposer une annonce — ColocLomé',
            'neighborhoods' => (new Neighborhood())->all(),
        ]);
    }

    public function store(): void
    {
        $user = $this->requireAuth('host');
        $title = trim((string) $this->input('title', 'Nouvelle chambre à Lomé'));
        $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $title), '-')) . '-' . time();

        $id = (new Listing())->create([
            'host_id' => $user['id'],
            'neighborhood_id' => (int) $this->input('neighborhood_id', 1),
            'title' => $title,
            'slug' => $slug,
            'description' => (string) $this->input('description', ''),
            'housing_type' => (string) $this->input('housing_type', 'Villa'),
            'room_surface' => (float) $this->input('room_surface', 12),
            'rooms_total' => (int) $this->input('rooms_total', 3),
            'rent' => (int) $this->input('rent', 0),
            'charges' => (int) $this->input('charges', 0),
            'deposit_months' => (int) $this->input('deposit_months', 2),
            'water_included' => $this->input('water_included') ? 1 : 0,
            'fiber' => $this->input('fiber') ? 1 : 0,
            'cash_power' => $this->input('cash_power') === 'shared' ? 'shared' : 'individual',
            'walk_minutes' => (int) $this->input('walk_minutes', 12),
            'lifestyle' => (string) $this->input('lifestyle', 'calm'),
            'status' => 'published',
            'verified' => 0,
            'available_from' => $this->input('available_from') ?: date('Y-m-d'),
        ]);

        Session::flash('success', 'Annonce enregistrée. Vérification technique en cours.');
        $this->redirect('/logement/' . $id);
    }
}
