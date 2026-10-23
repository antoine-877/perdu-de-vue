<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $items = $place->items()
            ->with('tags')
            ->latest()
            ->paginate(15);

        return view('places.show', compact('place', 'items'));
    }

    public function updatePhoto(Request $request, Place $place)
    {
        $validated = $request->validate([
            'photo' => ['nullable', 'image', 'max:5120', 'required_without:remove_photo'],
            'remove_photo' => ['sometimes', 'boolean'],
        ]);

        if ($request->boolean('remove_photo')) {
            if ($place->photo_path) {
                Storage::disk('public')->delete($place->photo_path);
            }

            $place->update(['photo_path' => null]);

            return redirect()->route('places.show', $place)->with('success', __('site.place_photo_removed'));
        }

        $path = $request->file('photo')->store('places', 'public');

        if ($place->photo_path) {
            Storage::disk('public')->delete($place->photo_path);
        }

        $place->update(['photo_path' => $path]);

        return redirect()->route('places.show', $place)->with('success', __('site.place_photo_updated'));
    }
}
