<?php

namespace App\Http\Controllers;

use App\Enums\Color;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Models\Place;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Afficher tous les objets.
     */
    public function index()
    {
        $query = Item::with(['place', 'tags'])
            ->whereNull('returned_at');

        if (request()->filled('lieu')) {
            $query->where('place_id', request('lieu'));
        }

        if (request()->filled('couleur')) {
            $query->where('color', request('couleur'));
        }

        if (request()->filled('q')) {
            $query->where('name', 'like', '%'.request('q').'%');
        }

        $sort = in_array(request('tri'), ['date', 'nom'], true) ? request('tri') : 'date';
        $query->when($sort === 'nom', fn ($query) => $query->orderBy('name'))
            ->when($sort === 'date', fn ($query) => $query->orderByDesc('found_on')->orderByDesc('created_at'));

        $items = $query
            ->paginate(9)
            ->withQueryString();

        $places = Place::orderBy('name')->get();
        $colors = Color::cases();

        return view('items.index', compact('items', 'places', 'colors'));
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
    public function store(ItemRequest $request)
    {
        $validated = $request->validated();

        // Extraire les tags avant de créer l'objet.
        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);
        $photo = $request->file('photo');
        $removePhoto = $request->boolean('remove_photo');
        unset($validated['photo'], $validated['remove_photo']);

        $item = Item::create($validated);

        if ($photo) {
            $path = $photo->store('items', 'public');
            $item->update(['photo_path' => $path]);
            $item->photos()->create(['path' => $path, 'is_cover' => true]);
        }

        // Associer les tags sélectionnés à l'objet.
        $item->tags()->sync($tagIds);

        return redirect()
            ->route('items.show', $item)
            ->with('success', __('site.create_item_success'));
    }

    /**
     * Afficher les détails d'un objet.
     */
    public function show(Item $item)
    {
        $item->load(['place', 'tags', 'photos']);

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
    public function update(ItemRequest $request, Item $item)
    {
        $validated = $request->validated();

        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);

        $item->update($validated);

        if ($request->file('photo')) {
            $item->photos()->where('path', $item->photo_path)->delete();
            if ($item->photo_path) {
                Storage::disk('public')->delete($item->photo_path);
            }

            $path = $request->file('photo')->store('items', 'public');
            $item->photos()->update(['is_cover' => false]);
            $item->photos()->create(['path' => $path, 'is_cover' => true]);
            $item->update(['photo_path' => $path]);
        } elseif ($request->boolean('remove_photo') && $item->photo_path) {
            $oldPath = $item->photo_path;
            $item->photos()->where('path', $oldPath)->delete();
            Storage::disk('public')->delete($oldPath);
            $nextCover = $item->photos()->oldest()->first();
            $nextCover?->update(['is_cover' => true]);
            $item->update(['photo_path' => $nextCover?->path]);
        }

        // Remplacer les associations par les tags sélectionnés.
        $item->tags()->sync($tagIds);

        return redirect()
            ->route('items.show', $item)
            ->with('success', __('site.update_item_success'));
    }

    /**
     * Marquer un objet comme restitué.
     */
    public function return(Item $item)
    {
        if ($item->isReturned()) {
            return redirect()
                ->route('items.show', $item)
                ->with('success', __('site.already_returned', ['date' => $item->returned_at->locale(app()->getLocale())->isoFormat('D MMMM YYYY')]));
        }

        $item->update(['returned_at' => today()]);

        return redirect()
            ->route('items.show', $item)
            ->with('success', __('site.item_returned', ['name' => $item->name]));
    }

    /**
     * Supprimer un objet.
     */
    public function destroy(Item $item)
    {
        Storage::disk('public')->delete(array_filter(array_unique(array_merge(
            $item->photos->pluck('path')->all(),
            [$item->photo_path],
        ))));
        $item->photos()->delete();

        // La suppression de l'objet ne supprime pas les tags.
        // Les associations de la table pivot sont supprimées en cascade.
        $item->delete();

        return redirect()
            ->route('items.index')
            ->with('success', __('site.delete_item_success'));
    }
}
