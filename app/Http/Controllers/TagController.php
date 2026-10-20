<?php

namespace App\Http\Controllers;

use App\Enums\Color;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * Afficher tous les tags, 20 par page.
     */
    public function index()
    {
        $tags = Tag::orderBy('name')->paginate(20);

        return view('tags.index', compact('tags'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Enregistrer un nouveau tag.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name'],
            'color' => ['required', Rule::enum(Color::class)],
        ]);

        Tag::create($validated);

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag créé avec succès.');
    }

    /**
     * Afficher un tag et ses objets.
     */
    public function show(Tag $tag)
    {
        $items = $tag->items()->paginate(15);

        return view('tags.show', compact('tag', 'items'));
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    /**
     * Enregistrer les modifications.
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags', 'name')->ignore($tag->id),
            ],
            'color' => ['required', Rule::enum(Color::class)],
        ]);

        $tag->update($validated);

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag modifié avec succès.');
    }

    /**
     * Supprimer un tag.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag supprimé avec succès.');
    }
}
