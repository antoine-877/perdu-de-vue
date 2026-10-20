@extends('layouts.app')

@section('title', 'Objets trouvés')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Objets trouvés</h1>

            <a href="{{ route('items.create') }}" class="btn btn-primary">
                Ajouter un objet
            </a>
        </header>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($items as $item)
            <article class="item-card">
                <h2>
                    <a href="{{ route('items.show', $item) }}">
                        {{ $item->name }}
                    </a>
                </h2>

                @if ($item->description)
                    <p>{{ $item->description }}</p>
                @endif

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

                @if ($item->tags->isNotEmpty())
                    <p>
                        <strong>Tags :</strong>

                        @foreach ($item->tags as $tag)
                            <a href="{{ route('tags.show', $tag) }}">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </p>
                @endif

                <div class="item-actions">
                    <a href="{{ route('items.show', $item) }}" class="btn">
                        Voir
                    </a>

                    <a href="{{ route('items.edit', $item) }}" class="btn">
                        Modifier
                    </a>

                    <form
                        action="{{ route('items.destroy', $item) }}"
                        method="POST"
                        onsubmit="return confirm('Voulez-vous vraiment supprimer cet objet ?');"
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
            <p>Aucun objet trouvé pour le moment.</p>
        @endforelse

        {{ $items->links() }}
    </div>
@endsection