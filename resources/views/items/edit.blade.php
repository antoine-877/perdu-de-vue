 @extends('layouts.app')

 @section('title', __('site.edit_item_title'))

 @section('content')
     <div class="mx-auto max-w-3xl">
         <header class="mb-8">
             <a href="{{ route('items.show', $item) }}"
                 class="text-sm font-medium text-teal-700 hover:underline focus:outline-none focus:ring-2 focus:ring-teal-500 dark:text-teal-300">
                 {{ __('site.back_to_item') }}
             </a>
             <h1 class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">
                 {{ __('site.edit_item_heading', ['name' => $item->name]) }}</h1>
         </header>

         <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data"
             class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
             @csrf
             @method('PUT')

             <div>
                 <x-label for="name">{{ __('site.item_name') }}</x-label>
                 <x-input name="name" id="name" :value="old('name', $item->name)" minlength="2" maxlength="60" required />
                 @error('name')
                     <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                 @enderror
             </div>

             <div>
                 <x-label for="description">{{ __('site.description') }}</x-label>
                 <x-textarea name="description" id="description" rows="4"
                     maxlength="1000">{{ old('description', $item->description) }}</x-textarea>
                 @error('description')
                     <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                 @enderror
             </div>


             <div>
                 <x-label for="photo">{{ __('site.main_photo') }}</x-label>
                 @if ($item->photo_path)
                     <img src="{{ Storage::disk('public')->url($item->photo_path) }}" alt="Photo de {{ $item->name }}"
                         class="mb-3 h-40 w-40 rounded-lg object-cover">
                     <label class="mb-3 flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                         <input type="checkbox" name="remove_photo" value="1" @checked(old('remove_photo'))>
                         {{ __('site.remove_current_photo') }}
                     </label>
                 @endif
                 <x-input type="file" name="photo" id="photo" accept="image/*" />
                 <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('site.replace_photo_help') }}</p>
                 @error('photo')
                     <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                 @enderror
             </div>

             <div>
                 <x-label for="place_id">{{ __('site.place_required') }}</x-label>

                 <x-select name="place_id" id="place_id" required>
                     <option value="">{{ __('site.choose_location') }}</option>

                     @foreach ($places as $place)
                         <option value="{{ $place->id }}" @selected((string) old('place_id', $item->place_id) === (string) $place->id)>
                             {{ app('translator')->has('site.seeded_places.' . $place->name) ? __('site.seeded_places.' . $place->name) : $place->name }}
                         </option>
                     @endforeach
                 </x-select>

                 @error('place_id')
                     <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                 @enderror

                 @if ($places->isEmpty())
                     <p class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                         {{ __('site.no_places_available') }}
                     </p>
                 @endif
             </div>


             <div>
                 <x-label for="color">{{ __('site.color') }}</x-label>
                 <x-select name="color" id="color" required>
                     <option value="">{{ __('site.choose_color') }}</option>

                     @foreach ($colors as $color)
                         <option value="{{ $color->value }}" @selected(old('color', $item->color->value) == $color->value)>
                             {{ __('site.color_' . $color->value) }}
                         </option>
                     @endforeach
                 </x-select>
                 @error('color')
                     <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                 @enderror
             </div>

             <div>
                 <x-label for="found_on">{{ __('site.found_date') }}</x-label>
                 <x-input type="date" name="found_on" id="found_on" :value="old('found_on', $item->found_on?->format('Y-m-d'))" required />
                 @error('found_on')
                     <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                 @enderror
             </div>

             <fieldset>
                 <legend class="mb-3 text-sm font-semibold text-slate-700 dark:text-slate-200">{{ __('site.labels') }}
                 </legend>
                 <input type="hidden" name="tags_submitted" value="1">

                 @foreach ($tags as $tag)
                     <label
                         class="mb-2 inline-flex cursor-pointer items-center gap-2 rounded-full border border-slate-200 px-3 py-2 text-sm hover:bg-slate-50 focus-within:ring-2 focus-within:ring-teal-500 dark:border-slate-700 dark:hover:bg-slate-800">
                         <input type="checkbox" name="tags[]" id="tag-{{ $tag->id }}" value="{{ $tag->id }}"
                             @checked(in_array($tag->id, old('tags', old('tags_submitted') ? [] : $item->tags->pluck('id')->all())))>
                         <span>{{ app('translator')->has('site.seeded_tags.' . $tag->name) ? __('site.seeded_tags.' . $tag->name) : $tag->name }}</span>
                     </label>
                 @endforeach

                 @error('tags')
                     <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                 @enderror

                 @error('tags.*')
                     <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                 @enderror
             </fieldset>

             <div class="flex flex-wrap justify-end gap-3 border-t border-slate-200 pt-6 dark:border-slate-800">
                 <a href="{{ route('items.show', $item) }}"
                     class="rounded-lg border border-slate-300 px-5 py-2.5 font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-teal-500 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">{{ __('site.cancel') }}</a>
                 <button type="submit"
                     class="rounded-lg bg-teal-700 px-5 py-2.5 font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">Enregistrer
                     {{ __('site.save_changes') }}</button>
             </div>
         </form>
     </div>
 @endsection
