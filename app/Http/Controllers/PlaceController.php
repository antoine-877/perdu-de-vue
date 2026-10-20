<?php

namespace App\Http\Controllers;

use App\Models\Place;

class PlaceController extends Controller
{
    /**
     * Afficher tous les lieux.
     */
public function index()
{
    $places = Place::withCount('items')
        ->orderBy('name')
        ->paginate(16);

    return view('places.index', compact('places'));
}

    /**
     * Afficher un lieu et les objets qui lui sont associés.
     */
    public function show(Place $place)
    {
        // Récupérer les objets associés au lieu, par pages de 15.
        $items = $place->items()->paginate(15);

        return view('places.show', compact('place', 'items'));
    }

}
