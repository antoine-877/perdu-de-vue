@extends('layouts.app')

@section('title', 'Créer un tag')

@section('content')
    <div class="container">
        <h1>Créer un tag</h1>

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

        <form action="{{ route('tags.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <x-label for="name">Nom du tag</x-label>

                <x-input name="name" id="name" :value="old('name')" required maxlength="255" autofocus />

                @error('name')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Créer le tag
            </button>

            <a href="{{ route('tags.index') }}" class="btn">
                Annuler
            </a>
        </form>
    </div>
@endsection
