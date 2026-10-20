@extends('layouts.app')

@section('title', 'Liste des tags')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Liste des tags</h1>

            <a href="{{ route('tags.create') }}" class="btn btn-primary">
                Créer un tag
            </a>
        </header>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($tags as $tag)
            <article class="tag-card">
                <h2>
                    <a href="{{ route('tags.show', $tag) }}">
                        {{ $tag->name }}
                    </a>
                </h2>

                <p>
                    <strong>Nombre d'objets associés :</strong>
                    {{ $tag->items_count }}
                </p>

                <div class="tag-actions">
                    <a href="{{ route('tags.show', $tag) }}" class="btn">
                        Voir
                    </a>

                    <a href="{{ route('tags.edit', $tag) }}" class="btn btn-primary">
                        Modifier
                    </a>

                    <form
                        action="{{ route('tags.destroy', $tag) }}"
                        method="POST"
                        onsubmit="return confirm('Voulez-vous vraiment supprimer ce tag ?');"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">
                            Supprimer
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <p>Aucun tag enregistré pour le moment.</p>
        @endforelse

        {{ $tags->links() }}
    </div>
@endsection