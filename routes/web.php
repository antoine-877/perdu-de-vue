<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PlaceController;

Route::any('/', function () {
    return redirect('/objets');
});

Route::get('/objets', [ItemController::class, 'index'])
    ->name('items.index');

Route::get('/objets/nouveau', [ItemController::class, 'create'])
    ->name('items.create');

Route::post('/objets', [ItemController::class, 'store'])
    ->name('items.store');

Route::get('/objets/{item}', [ItemController::class, 'show'])
    ->name('items.show');

Route::get('/objets/{item}/modifier', [ItemController::class, 'edit'])
    ->name('items.edit');

Route::put('/objets/{item}', [ItemController::class, 'update'])
    ->name('items.update');

Route::patch('/objets/{item}/restitution', [ItemController::class, 'return'])
    ->name('items.return');

Route::delete('/objets/{item}', [ItemController::class, 'destroy'])
    ->name('items.destroy');

Route::get('/lieux/{place}', [PlaceController::class, 'show'])
    ->name('places.show');

Route::view('/composants', 'styleguide')
    ->name('styleguide');
