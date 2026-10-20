 @extends('layouts.app')

 @section('title', 'Modifier un objet')

 @section('content')
     <div class="container">
         <h1>Modifier l'objet : {{ $item->name }}</h1>

         <form action="{{ route('items.update', $item) }}" method="POST">
             @csrf
             @method('PUT')

             <div class="mb-3">
                 <x-label for="name">Nom de l'objet</x-label>
                 <x-input name="name" id="name" :value="old('name', $item->name)" required />
                 @error('name')
                     <p class="text-danger">{{ $message }}</p>
                 @enderror
             </div>

             <div class="mb-3">
                 <x-label for="description">Description</x-label>
                 <x-textarea name="description" id="description"
                     rows="4">{{ old('description', $item->description) }}</x-textarea>
                 @error('description')
                     <p class="text-danger">{{ $message }}</p>
                 @enderror
             </div>


             <div class="mb-3">
                 <x-label for="place_id">Lieu de découverte *</x-label>

                 <x-select name="place_id" id="place_id" required>
                     <option value="">Choisir un lieu</option>

                     @foreach ($places as $place)
                         <option value="{{ $place->id }}" @selected((string) old('place_id', $item->place_id) === (string) $place->id)>
                             {{ $place->name }}
                         </option>
                     @endforeach
                 </x-select>

                 @error('place_id')
                     <p class="text-danger">{{ $message }}</p>
                 @enderror

                 @if ($places->isEmpty())
                     <p class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                         Aucun lieu prédéfini n’est disponible. Contacte l’administrateur.
                     </p>
                 @endif
             </div>


             <div class="mb-3">
                 <x-label for="color">Couleur</x-label>
                 <x-select name="color" id="color" required>
                     <option value="">Choisir une couleur</option>

                     @foreach ($colors as $color)
                         <option value="{{ $color->value }}" @selected(old('color', $item->color->value) == $color->value)>
                             {{ ucfirst($color->value) }}
                         </option>
                     @endforeach
                 </x-select>
                 @error('color')
                     <p class="text-danger">{{ $message }}</p>
                 @enderror
             </div>

             <div class="mb-3">
                 <x-label for="found_on">Date de découverte</x-label>
                 <x-input type="date" name="found_on" id="found_on" :value="old('found_on', $item->found_on?->format('Y-m-d'))" required />
                 @error('found_on')
                     <p class="text-danger">{{ $message }}</p>
                 @enderror
             </div>

             <div class="mb-3">
                 <x-label for="returned_at">Date de restitution</x-label>
                 <x-input type="date" name="returned_at" id="returned_at" :value="old('returned_at', $item->returned_at?->format('Y-m-d'))" />
                 @error('returned_at')
                     <p class="text-danger">{{ $message }}</p>
                 @enderror
             </div>

             <fieldset class="mb-3">
                 <legend>Tags</legend>

                 @foreach ($tags as $tag)
                     <div>
                         <input type="checkbox" name="tags[]" id="tag-{{ $tag->id }}" value="{{ $tag->id }}"
                             @checked(in_array($tag->id, old('tags', $item->tags->pluck('id')->all())))>

                         <label for="tag-{{ $tag->id }}">
                             {{ $tag->name }}
                         </label>
                     </div>
                 @endforeach

                 @error('tags')
                     <p class="text-danger">{{ $message }}</p>
                 @enderror

                 @error('tags.*')
                     <p class="text-danger">{{ $message }}</p>
                 @enderror
             </fieldset>

             <button type="submit" class="btn btn-primary">
                 Enregistrer les modifications
             </button>

             <a href="{{ route('items.show', $item) }}" class="btn">
                 Annuler
             </a>
         </form>
     </div>
 @endsection
