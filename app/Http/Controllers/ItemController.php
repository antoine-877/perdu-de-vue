<?php

namespace App\Http\Controllers;

use App\Enums\Color;
use App\Models\Item;
use App\Models\Place;
use App\Models\Tag;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Afficher tous les objets.
     */
    public function index()
    {
        $items = Item::with(['place', 'tags'])
            ->latest()
            ->paginate(16);

        return view('items.index', compact('items'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        $places = Place::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $colors = Color::cases();

        return view('items.create', compact(
            'places',
            'tags',
            'colors'
        ));
    }

    /**
     * Enregistrer un nouvel objet.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'place_id' => ['required', 'exists:places,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'color' => ['required', 'in:' . implode(',', array_column(Color::cases(), 'value'))],
            'found_on' => ['required', 'date'],
            'returned_at' => ['nullable', 'date', 'after_or_equal:found_on'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'distinct', 'exists:tags,id'],
        ]);

        // Extraire les tags avant de créer l'objet.
        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);

        $item = Item::create($validated);

        // Associer les tags sélectionnés à l'objet.
        $item->tags()->sync($tagIds);

        return redirect()
            ->route('items.show', $item)
            ->with('success', 'Objet créé avec succès.');
    }

    /**
     * Afficher les détails d'un objet.
     */
    public function show(Item $item)
    {
        $item->load(['place', 'tags']);

        return view('items.show', compact('item'));
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Item $item)
    {
        $places = Place::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $colors = Color::cases();

        $item->load('tags');

        return view('items.edit', compact(
            'item',
            'places',
            'tags',
            'colors'
        ));
    }

    /**
     * Enregistrer les modifications d'un objet.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'place_id' => ['required', 'exists:places,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'color' => ['required', 'in:' . implode(',', array_column(Color::cases(), 'value'))],
            'found_on' => ['required', 'date'],
            'returned_at' => ['nullable', 'date', 'after_or_equal:found_on'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'distinct', 'exists:tags,id'],
        ]);

        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);

        $item->update($validated);

        // Remplacer les associations par les tags sélectionnés.
        $item->tags()->sync($tagIds);

        return redirect()
            ->route('items.show', $item)
            ->with('success', 'Objet modifié avec succès.');
    }

/**
 * Marquer un objet comme restitué.
 */
public function return(Item $item)
{
    if ($item->isReturned()) {
        return redirect()
            ->route('items.show', $item)
            ->with('success', "Cet objet a déjà été restitué le {$item->returned_at->format('d/m/Y')}.");
    }

    $item->update([
        'returned_at' => now()->toDateString(),
    ]);

    return redirect()
        ->route('items.show', $item)
        ->with('success', "{$item->name} a été restitué.");
}

    /**
     * Supprimer un objet.
     */
    public function destroy(Item $item)
    {
        // La suppression de l'objet ne supprime pas les tags.
        // Les associations de la table pivot sont supprimées en cascade.
        $item->delete();

        return redirect()
            ->route('items.index')
            ->with('success', 'Objet supprimé avec succès.');
    }
}
