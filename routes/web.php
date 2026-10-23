<?php

use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\TagController;
use App\Http\Middleware\EnsureAgentToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Page d'accueil
|--------------------------------------------------------------------------
*/

// Redirige toutes les requêtes vers "/" vers la liste des objets.
// Attention : Route::any accepte toutes les méthodes HTTP.
// Une Route::get('/') serait préférable si seule la page d'accueil est visée.
Route::any('/', function () {
    return redirect('/objets');
});

Route::post('/langue', [LocaleController::class, 'update'])->name('locale.update');

/*
|--------------------------------------------------------------------------
| Objets (Items)
|--------------------------------------------------------------------------
*/

// Afficher la liste de tous les objets
Route::get('/objets', [ItemController::class, 'index'])
    ->name('items.index');

Route::get('/perdu', [ClaimController::class, 'create'])->name('claims.create');
Route::post('/perdu', [ClaimController::class, 'store'])->name('claims.store');
Route::post('/declarations/{claim}/associer', [ClaimController::class, 'link'])
    ->middleware(EnsureAgentToken::class)
    ->name('claims.link');
Route::get('/statistiques', [StatisticsController::class, 'index'])->name('statistics.index');
Route::post('/objets/{item}/photos', [PhotoController::class, 'store'])
    ->middleware(EnsureAgentToken::class)->name('photos.store');
Route::patch('/objets/{item}/photos/{photo}/couverture', [PhotoController::class, 'cover'])
    ->middleware(EnsureAgentToken::class)->name('photos.cover');
Route::delete('/objets/{item}/photos/{photo}', [PhotoController::class, 'destroy'])
    ->middleware(EnsureAgentToken::class)->name('photos.destroy');

// Afficher le formulaire de création d'un objet
Route::get('/objets/nouveau', [ItemController::class, 'create'])
    ->name('items.create');

// Enregistrer un nouvel objet en base de données
Route::post('/objets', [ItemController::class, 'store'])
    ->name('items.store');

// Afficher les détails d'un objet précis
Route::get('/objets/{item}', [ItemController::class, 'show'])
    ->name('items.show');

// Afficher le formulaire de modification d'un objet
Route::get('/objets/{item}/modifier', [ItemController::class, 'edit'])
    ->name('items.edit');

// Enregistrer les modifications d'un objet existant
Route::put('/objets/{item}', [ItemController::class, 'update'])
    ->name('items.update');

// Marquer un objet comme restitué
Route::patch('/objets/{item}/restitution', [ItemController::class, 'return'])
    ->name('items.return');

// Supprimer un objet
Route::delete('/objets/{item}', [ItemController::class, 'destroy'])
    ->name('items.destroy');

/*
|--------------------------------------------------------------------------
| Lieux (Places)
|--------------------------------------------------------------------------
*/

// Afficher tous les lieux
Route::get('/lieux', [PlaceController::class, 'index'])
    ->name('places.index');

// Afficher un lieu et les objets associés
Route::get('/lieux/{place}', [PlaceController::class, 'show'])
    ->name('places.show');
Route::post('/lieux/{place}/photo', [PlaceController::class, 'updatePhoto'])
    ->middleware(EnsureAgentToken::class)
    ->name('places.photo.update');

/*
|--------------------------------------------------------------------------
| Tags
|--------------------------------------------------------------------------
*/

// Afficher la liste de tous les tags
Route::get('/tags', [TagController::class, 'index'])
    ->name('tags.index');

// Afficher le formulaire de création d'un tag
Route::get('/tags/create', [TagController::class, 'create'])
    ->name('tags.create');

// Enregistrer un nouveau tag
Route::post('/tags', [TagController::class, 'store'])
    ->name('tags.store');

// Afficher un tag et les objets qui lui sont associés
Route::get('/tags/{tag}', [TagController::class, 'show'])
    ->name('tags.show');

// Afficher le formulaire de modification d'un tag
Route::get('/tags/{tag}/edit', [TagController::class, 'edit'])
    ->name('tags.edit');

// Enregistrer les modifications d'un tag
Route::put('/tags/{tag}', [TagController::class, 'update'])
    ->name('tags.update');

// Supprimer un tag
Route::delete('/tags/{tag}', [TagController::class, 'destroy'])
    ->name('tags.destroy');

/*
|--------------------------------------------------------------------------
| Styleguide / Composants
|--------------------------------------------------------------------------
*/

// Afficher la page de démonstration des composants visuels
Route::view('/composants', 'styleguide')
    ->name('styleguide');
