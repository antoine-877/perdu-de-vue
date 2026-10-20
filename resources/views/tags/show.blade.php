@extends('layouts.app')

@section('title', 'Détails du tag')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Tag : {{ $tag->name }}</h1>

            <div class="tag-actions">
                <a href="{{ route('tags.index') }}" class="btn">
                    Retour aux tags
                </a>

                <a href="{{ route('tags.edit', $tag) }}" class="btn btn-primary">
                    Modifier le tag
                </a>
            </div>
        </header>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <section>
            <h2>Objets associés</h2>

            @if ($items->isEmpty())
                <p>Aucun objet n'est associé à ce tag pour le moment.</p>
            @else
                <p>
                    {{ $items->total() }}
                    objet(s) associé(s) à ce tag.
                </p>

                <div class="items-list">
                    @foreach ($items as $item)
                        <article class="item-card">
                            <h3>
                                <a href="{{ route('items.show', $item) }}">
                                    {{ $item->name }}
                                </a>
                            </h3>

                            @if ($item->description)
                                <p>{{ $item->description }}</p>
                            @endif

                            @if ($item->place)
                                <p>
                                    <strong>Lieu :</strong>
                                    <a href="{{ route('places.show', $item->place) }}">
                                        {{ $item->place->name }}
                                    </a>
                                </p>
                            @endif

                            @if ($item->found_on)
                                <p>
                                    <strong>Date de découverte :</strong>
                                    {{ $item->found_on->format('d/m/Y') }}
                                </p>
                            @endif

                            <p>
                                <strong>Statut :</strong>
                                @if ($item->isReturned())
                                    Restitué
                                @else
                                    En attente de restitution
                                @endif
                            </p>

                            <a href="{{ route('items.show', $item) }}" class="btn">
                                Voir l'objet
                            </a>
                        </article>
                    @endforeach
                </div>

                {{ $items->links() }}
            @endif
        </section>
    </div>
@endsection