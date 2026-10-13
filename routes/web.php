<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProduitController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/clients');

Route::middleware('guest')->group(function (): void {
    Route::get('/connexion', [LoginController::class, 'create'])->name('login');
    Route::post('/connexion', [LoginController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/deconnexion', [LoginController::class, 'destroy'])->name('logout');

    Route::get('commandes/etat-sortie', [CommandeController::class, 'sorties'])->name('commandes.sorties');
    Route::resource('produits', ProduitController::class);
    Route::resource('commandes', CommandeController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('clients', ClientController::class);
});
