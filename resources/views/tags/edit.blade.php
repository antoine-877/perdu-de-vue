@extends('layouts.app')

@section('title', 'Modifier un tag')

@section('content')
    <div class="container mx-auto max-w-2xl px-4 py-8">
        <h1 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
            Modifier le tag : {{ $tag->name }}
        </h1>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 p-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-800">
                <p class="font-semibold">Veuillez corriger les erreurs suivantes :</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tags.update', $tag) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-label for="name">Nom du tag</x-label>

                <x-input name="name" id="name" :value="old('name', $tag->name)" required maxlength="255" autofocus />

                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-color-picker name="color" :selected="$tag->color" />
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn btn-primary">
                    Enregistrer les modifications
                </button>

                <a href="{{ route('tags.index') }}" class="btn">
                    Annuler
                </a>
            </div>
        </form>
    </div>
@endsection
