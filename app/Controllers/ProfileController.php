<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class ProfileController extends Controller
{
    public function show(): void
    {
        $user = $this->requireAuth();
        $this->render('auth/profile', [
            'title' => 'Mon profil — ColocLomé',
            'profile' => $user,
        ]);
    }
}
