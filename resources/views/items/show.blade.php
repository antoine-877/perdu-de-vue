@extends('layouts.app')

@section('title', $item->name)

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>{{ $item->name }}</h1>

            <div class="item-actions">
                <a href="{{ route('items.edit', $item) }}" class="btn btn-primary">
                    Modifier
                </a>

                <a href="{{ route('items.index') }}" class="btn">
                    Retour à la liste
                </a>
            </div>
        </header>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <section class="item-details">
            <h2>Informations sur l'objet</h2>

            <p>
                <strong>Nom :</strong>
                {{ $item->name }}
            </p>

            <p>
                <strong>Description :</strong>
                {{ $item->description ?: 'Aucune description disponible.' }}
            </p>

            <p>
                <strong>Lieu :</strong>
                @if ($item->place)
                    <a href="{{ route('places.show', $item->place) }}">
                        {{ $item->place->name }}
                    </a>
                @else
                    Lieu inconnu
                @endif
            </p>

            <p>
                <strong>Couleur :</strong>
                {{ $item->color->value }}
            </p>

            <p>
                <strong>Date de découverte :</strong>
                {{ $item->found_on?->format('d/m/Y') ?? 'Non précisée' }}
            </p>

            <p>
                <strong>Statut :</strong>
                {{ $item->isReturned() ? 'Restitué' : 'Non restitué' }}
            </p>

            @if ($item->returned_at)
                <p>
                    <strong>Date de restitution :</strong>
                    {{ $item->returned_at->format('d/m/Y') }}
                </p>
            @endif
        </section>

        <section class="item-tags">
            <h2>Tags</h2>

            @forelse ($item->tags as $tag)
                <a href="{{ route('tags.show', $tag) }}" class="tag">
                    {{ $tag->name }}
                </a>
            @empty
                <p>Aucun tag associé à cet objet.</p>
            @endforelse
        </section>

        @if (! $item->isReturned())
            <form
                action="{{ route('items.return', $item) }}"
                method="POST"
            >
                @csrf
                @method('PATCH')

                <button type="submit" class="btn btn-success">
                    Marquer comme restitué
                </button>
            </form>
        @endif

        <form
            action="{{ route('items.destroy', $item) }}"
            method="POST"
            onsubmit="return confirm('Voulez-vous vraiment supprimer cet objet ?');"
        >
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger">
                Supprimer cet objet
            </button>
        </form>
    </div>
@endsection