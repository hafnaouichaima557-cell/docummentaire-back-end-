<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\UtilisateurController;

// Routes publiques
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Documents — tous les rôles
    Route::get('/documents', [DocumentController::class, 'index']);
    Route::get('/documents/{id}', [DocumentController::class, 'show']);

    // Documents — utilisateur seulement
    Route::middleware('role:utilisateur,admin')->group(function () {
        Route::post('/documents', [DocumentController::class, 'store']);
        Route::put('/documents/{id}', [DocumentController::class, 'update']);
        Route::delete('/documents/{id}', [DocumentController::class, 'destroy']);
        Route::post('/documents/{id}/soumettre', [DocumentController::class, 'soumettre']);
    });

    // Peer review — utilisateur
    Route::middleware('role:utilisateur,admin')->group(function () {
        Route::post('/documents/{id}/valider-peer', [DocumentController::class, 'validerPeer']);
        Route::post('/documents/{id}/rejeter-peer', [DocumentController::class, 'rejeter']);
    });

    // Validation finale — responsable seulement
    Route::middleware('role:responsable,admin')->group(function () {
        Route::post('/documents/{id}/publier', [DocumentController::class, 'publier']);
        Route::post('/documents/{id}/rejeter', [DocumentController::class, 'rejeter']);
    });

    // Equipes — tous
    Route::get('/equipes', [EquipeController::class, 'index']);
    Route::get('/equipes/{id}', [EquipeController::class, 'show']);

    // Equipes — admin seulement
    Route::middleware('role:admin')->group(function () {
        Route::post('/equipes', [EquipeController::class, 'store']);
        Route::put('/equipes/{id}', [EquipeController::class, 'update']);
        Route::delete('/equipes/{id}', [EquipeController::class, 'destroy']);
    });

    // Platforms — tous
    Route::get('/platforms', [PlatformController::class, 'index']);
    Route::get('/platforms/{id}', [PlatformController::class, 'show']);

    // Platforms — admin seulement
    Route::middleware('role:admin')->group(function () {
        Route::post('/platforms', [PlatformController::class, 'store']);
        Route::put('/platforms/{id}', [PlatformController::class, 'update']);
        Route::delete('/platforms/{id}', [PlatformController::class, 'destroy']);
    });

    // Utilisateurs — admin seulement
    Route::middleware('role:admin')->group(function () {
        Route::get('/utilisateurs', [UtilisateurController::class, 'index']);
        Route::get('/utilisateurs/{id}', [UtilisateurController::class, 'show']);
        Route::put('/utilisateurs/{id}', [UtilisateurController::class, 'update']);
        Route::delete('/utilisateurs/{id}', [UtilisateurController::class, 'destroy']);
        Route::post('/utilisateurs/{id}/role', [UtilisateurController::class, 'assignerRole']);
        Route::post('/utilisateurs', [UtilisateurController::class, 'store']);
    });
});