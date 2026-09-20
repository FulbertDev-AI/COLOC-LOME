<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Listing;

final class HomeController extends Controller
{
    public function index(): void
    {
        $listings = (new Listing())->search([]);
        $this->render('home/index', [
            'title' => 'ColocLomé — Colocation étudiante vérifiée à Lomé',
            'listings' => array_slice($listings, 0, 3),
            'stats' => [
                'count' => count($listings) > 0 ? 142 : 0,
                'fees' => 0,
                'verified' => '100%',
                'match' => '98,4%',
            ],
        ]);
    }
}
