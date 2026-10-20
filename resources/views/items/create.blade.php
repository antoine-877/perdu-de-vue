@extends('layouts.app')

@section('title', 'Ajouter un objet')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-8">
            <a href="{{ route('items.index') }}" class="text-sm font-medium text-teal-700 hover:underline dark:text-teal-300">
                ← Retour aux objets
            </a>

            <h1 class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">
                Ajouter un objet trouvé
            </h1>

            <p class="mt-2 text-slate-600 dark:text-slate-400">
                Renseigne les informations de l’objet découvert.
            </p>
        </div>

        @if ($errors->any())
            <div
                class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
                <p class="font-semibold">Certains champs doivent être corrigés :</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('items.store') }}" method="POST"
            class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
            @csrf

            <div>
                <x-label for="name">Nom de l’objet *</x-label>
                <x-input name="name" id="name" :value="old('name')" required placeholder="Ex. : portefeuille noir" />
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="description">Description</x-label>
                <x-textarea name="description" id="description" rows="4"
                    placeholder="Décris l’objet, ses particularités...">{{ old('description') }}</x-textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <x-label for="place_id">Lieu de découverte *</x-label>

                <x-select name="place_id" id="place_id" required>
                    <option value="">Choisir un lieu</option>

                    @foreach ($places as $place)
                        <option value="{{ $place->id }}" @selected((string) old('place_id', $item->place_id ?? '') === (string) $place->id)>
                            {{ $place->name }}
                        </option>
                    @endforeach
                </x-select>

                @error('place_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if ($places->isEmpty())
                    <p class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                        Aucun lieu prédéfini n’est disponible. Contacte l’administrateur.
                    </p>
                @endif
            </div>


            <div>
                <x-label for="color">Couleur de l’objet *</x-label>

                <x-select name="color" id="color" required>
                    <option value="">Choisir une couleur</option>

                    @foreach ($colors as $color)
                        <option value="{{ $color->value }}" @selected(old('color') === $color->value)>
                            {{ ucfirst($color->value) }}
                        </option>
                    @endforeach
                </x-select>

                @error('color')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="found_on">Date de découverte *</x-label>
                <x-input type="date" name="found_on" id="found_on" :value="old('found_on', now()->format('Y-m-d'))" required />
                @error('found_on')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <fieldset>
                <legend class="mb-3 text-sm font-semibold text-slate-700 dark:text-slate-200">
                    Tags (facultatif)
                </legend>

                @if ($tags->isNotEmpty())
                    <div class="flex flex-wrap gap-3">
                        @foreach ($tags as $tag)
                            <label
                                class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-slate-200 px-3 py-2 text-sm dark:border-slate-700">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                    @checked(in_array($tag->id, old('tags', [])))
                                    class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                <span>{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Aucun tag disponible pour le moment.
                    </p>
                @endif

                @error('tags')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </fieldset>

            <div class="flex flex-wrap justify-end gap-3 border-t border-slate-200 pt-6 dark:border-slate-800">
                <a href="{{ route('items.index') }}"
                    class="rounded-xl border border-slate-300 px-5 py-2.5 font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                    Annuler
                </a>

                <button type="submit"
                    class="rounded-xl bg-teal-600 px-5 py-2.5 font-semibold text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    @disabled($places->isEmpty())>
                    Enregistrer l’objet
                </button>
            </div>
        </form>
    </div>
@endsection
