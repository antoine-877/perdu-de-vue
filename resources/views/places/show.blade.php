@extends('layouts.app')

@section('title', 'Lieu : ' . $place->name)

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>{{ $place->name }}</h1>

            <a href="{{ route('places.index') }}" class="btn">
                Retour à la liste des lieux
            </a>
        </header>

        <section class="place-details">
            <h2>Informations sur le lieu</h2>

            <p>
                <strong>Nom :</strong>
                {{ $place->name }}
            </p>

            <p>
                <strong>Nombre d'objets trouvés :</strong>
                {{ $items->total() }}
            </p>
        </section>

        <section class="place-items">
            <h2>Objets trouvés dans ce lieu</h2>

            @forelse ($items as $item)
                <article class="item-card">
                    <h3>
                        <a href="{{ route('items.show', $item) }}">
                            {{ $item->name }}
                        </a>
                    </h3>

                    @if ($item->description)
                        <p>{{ $item->description }}</p>
                    @endif

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

                    <a href="{{ route('items.show', $item) }}" class="btn">
                        Voir les détails
                    </a>
                </article>
            @empty
                <p>Aucun objet n'a été trouvé dans ce lieu pour le moment.</p>
            @endforelse

            {{ $items->links() }}
        </section>
    </div>
@endsection