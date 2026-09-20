<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Listing;

final class ListingController extends Controller
{
    public function show(string $id): void
    {
        $listing = (new Listing())->withRelations((int) $id);
        if (!$listing) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Annonce introuvable']);
            return;
        }

        $this->render('listings/show', [
            'title' => $listing['title'] . ' — ColocLomé',
            'listing' => $listing,
        ]);
    }
}
