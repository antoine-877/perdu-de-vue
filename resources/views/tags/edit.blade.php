@extends('layouts.app')

@section('title', 'Modifier un tag')

@section('content')
    <div class="container">
        <h1>Modifier le tag : {{ $tag->name }}</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <p>Veuillez corriger les erreurs suivantes :</p>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tags.update', $tag) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <x-label for="name">Nom du tag</x-label>

                <x-input
                    name="name"
                    id="name"
                    :value="old('name', $tag->name)"
                    required
                    maxlength="255"
                    autofocus
                />

                @error('name')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Enregistrer les modifications
            </button>

            <a href="{{ route('tags.index') }}" class="btn">
                Annuler
            </a>
        </form>
    </div>
@endsection