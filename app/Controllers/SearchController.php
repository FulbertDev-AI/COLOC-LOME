<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Listing;
use App\Models\Neighborhood;

final class SearchController extends Controller
{
    public function index(): void
    {
        $filters = [
            'neighborhoods' => array_filter((array) $this->input('quartier', [])),
            'budget_min' => (int) $this->input('budget_min', 20000),
            'budget_max' => (int) $this->input('budget_max', 50000),
            'fiber' => $this->input('fibre'),
            'cash_power' => $this->input('cash_power'),
            'water' => $this->input('eau'),
            'walk' => $this->input('walk') ? 10 : null,
            'lifestyle' => $this->input('lifestyle'),
        ];

        $listings = (new Listing())->search($filters);
        $neighborhoods = (new Neighborhood())->all();

        $this->render('search/index', [
            'title' => 'Rechercher une colocation — ColocLomé',
            'listings' => $listings,
            'neighborhoods' => $neighborhoods,
            'filters' => $filters,
        ]);
    }

    public function preferences(): void
    {
        $this->requireAuth('student');
        $neighborhoods = (new Neighborhood())->all();
        $this->render('search/preferences', [
            'title' => 'Préférences de logement — ColocLomé',
            'neighborhoods' => $neighborhoods,
        ]);
    }

    public function savePreferences(): void
    {
        $this->requireAuth('student');
        $max = match ((string) $this->input('budget')) {
            'lt25' => 25000,
            '25-40' => 40000,
            '40-60' => 60000,
            default => 80000,
        };

        $query = http_build_query([
            'quartier' => (array) $this->input('quartier', []),
            'budget_max' => $max,
            'lifestyle' => $this->input('lifestyle', 'calm'),
        ]);

        $this->redirect('/recherche?' . $query);
    }
}
