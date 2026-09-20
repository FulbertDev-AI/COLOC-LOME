<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ListingController;
use App\Controllers\SearchController;
use App\Controllers\MessageController;
use App\Controllers\ApplicationController;
use App\Controllers\HostController;
use App\Controllers\PublishController;
use App\Controllers\ProfileController;

return [
    'GET /' => [HomeController::class, 'index'],
    'GET /recherche' => [SearchController::class, 'index'],
    'GET /preferences' => [SearchController::class, 'preferences'],
    'POST /preferences' => [SearchController::class, 'savePreferences'],
    'GET /logement/{id}' => [ListingController::class, 'show'],
    'GET /publier' => [PublishController::class, 'create'],
    'POST /publier' => [PublishController::class, 'store'],
    'GET /messages' => [MessageController::class, 'index'],
    'GET /messages/{id}' => [MessageController::class, 'show'],
    'POST /messages/{id}' => [MessageController::class, 'send'],
    'GET /candidatures' => [ApplicationController::class, 'index'],
    'POST /candidatures' => [ApplicationController::class, 'store'],
    'POST /visites/{id}/confirmer' => [ApplicationController::class, 'confirmVisit'],
    'GET /hote' => [HostController::class, 'dashboard'],
    'POST /hote/candidatures/{id}' => [HostController::class, 'updateApplication'],
    'GET /connexion' => [AuthController::class, 'loginForm'],
    'POST /connexion' => [AuthController::class, 'login'],
    'GET /inscription' => [AuthController::class, 'registerForm'],
    'POST /inscription' => [AuthController::class, 'register'],
    'POST /deconnexion' => [AuthController::class, 'logout'],
    'GET /profil' => [ProfileController::class, 'show'],
];
