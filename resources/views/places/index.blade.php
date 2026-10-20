@extends('layouts.app')

@section('title', 'Lieux')

@section('content')
    <div class="container">
        <h1>Liste des lieux</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($places as $place)
            <article class="place-card">
                <h2>
                    <a href="{{ route('places.show', $place) }}">
                        {{ $place->name }}
                    </a>
                </h2>

                <p>
                    <strong>Nombre d'objets trouvés :</strong>
                    {{ $place->items_count ?? $place->items->count() }}
                </p>

                <a href="{{ route('places.show', $place) }}" class="btn">
                    Voir les objets de ce lieu
                </a>
            </article>
        @empty
            <p>Aucun lieu enregistré pour le moment.</p>
        @endforelse

        {{ $places->links() }}
    </div>
@endsection