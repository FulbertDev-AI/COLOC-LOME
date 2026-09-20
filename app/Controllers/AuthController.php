<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

final class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->render('auth/login', ['title' => 'Connexion — ColocLomé']);
    }

    public function login(): void
    {
        $email = trim((string) $this->input('email', ''));
        $password = (string) $this->input('password', '');

        if (!Auth::attempt($email, $password)) {
            Session::flash('error', 'Identifiants incorrects.');
            $this->redirect('/connexion');
        }

        $user = Auth::user();
        $this->redirect(($user['role'] ?? '') === 'host' ? '/hote' : '/recherche');
    }

    public function registerForm(): void
    {
        $this->render('auth/register', ['title' => 'Inscription — ColocLomé']);
    }

    public function register(): void
    {
        $email = trim((string) $this->input('email', ''));
        $users = new User();
        if ($users->findByEmail($email)) {
            Session::flash('error', 'Cet e-mail est déjà utilisé.');
            $this->redirect('/inscription');
        }

        $id = $users->create([
            'role' => $this->input('role') === 'host' ? 'host' : 'student',
            'email' => $email,
            'password' => password_hash((string) $this->input('password'), PASSWORD_BCRYPT),
            'first_name' => trim((string) $this->input('first_name')),
            'last_name' => trim((string) $this->input('last_name')),
            'school' => trim((string) $this->input('school')),
            'program' => trim((string) $this->input('program')),
            'verified' => 0,
        ]);

        $user = $users->find($id);
        Auth::login($user);
        $this->redirect($user['role'] === 'host' ? '/publier' : '/preferences');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/');
    }
}
