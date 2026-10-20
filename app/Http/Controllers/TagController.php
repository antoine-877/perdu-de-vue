<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * Afficher tous les tags.
     */
    public function index()
    {
        $tags = Tag::orderBy('name')->paginate(15);

        return view('tags.index', compact('tags'));
    }

    /**
     * Afficher le formulaire de création d'un tag.
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
        // Vérifier les données envoyées par le formulaire.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name'],
        ]);

        // Créer le tag en base de données.
        $tag = Tag::create($validated);

        // Rediriger vers la liste des tags.
        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag créé avec succès.');
    }

    /**
     * Afficher un tag et les objets qui lui sont associés.
     */
    public function show(Tag $tag)
    {
        // Récupérer les objets associés au tag, par pages de 15.
        $items = $tag->items()->paginate(15);

        return view('tags.show', compact('tag', 'items'));
    }

    /**
     * Afficher le formulaire de modification d'un tag.
     */
    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    /**
     * Enregistrer les modifications d'un tag.
     */
    public function update(Request $request, Tag $tag)
    {
        // Le nom doit être unique, sauf pour le tag modifié lui-même.
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags', 'name')->ignore($tag->id),
            ],
        ]);

        // Mettre à jour le tag en base de données.
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
        // Supprimer uniquement le tag.
        // Les objets associés restent en base de données.
        $tag->delete();

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag supprimé avec succès.');
    }
}