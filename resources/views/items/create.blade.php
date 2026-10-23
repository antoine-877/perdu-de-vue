@extends('layouts.app')

@section('title', __('site.add_item'))

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-8">
            <a href="{{ route('items.index') }}" class="text-sm font-medium text-teal-700 hover:underline dark:text-teal-300">
                ← {{ __('site.back_to_items') }}
            </a>

            <h1 class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">
                {{ __('site.add_item_title') }}
            </h1>

            <p class="mt-2 text-slate-600 dark:text-slate-400">
                {{ __('site.create_item_intro') }}
            </p>
        </div>

        @if ($errors->any())
            <div
                class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
                <p class="font-semibold">{{ __('site.validation_errors') }}</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
            @csrf

            <div>
                <x-label for="name">{{ __('site.item_name_required') }}</x-label>
                <x-input name="name" id="name" :value="old('name')" minlength="2" maxlength="60" required
                    placeholder="{{ __('site.wallet_example') }}" />
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="description">{{ __('site.description') }}</x-label>
                <x-textarea name="description" id="description" rows="4" maxlength="1000"
                    placeholder="{{ __('site.description_hint') }}">{{ old('description') }}</x-textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <x-label for="photo">{{ __('site.main_photo') }}</x-label>
                <x-input type="file" name="photo" id="photo" accept="image/*" />
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('site.photo_max') }}</p>
                @error('photo')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="place_id">{{ __('site.place_required') }}</x-label>

                <x-select name="place_id" id="place_id" required>
                    <option value="">{{ __('site.choose_location') }}</option>

                    @foreach ($places as $place)
                        <option value="{{ $place->id }}" @selected((string) old('place_id', '') === (string) $place->id)>
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
                <x-label for="color">{{ __('site.item_color_required') }}</x-label>

                <x-select name="color" id="color" required>
                    <option value="">{{ __('site.choose_color') }}</option>

                    @foreach ($colors as $color)
                        <option value="{{ $color->value }}" @selected(old('color') === $color->value)>
                            {{ __('site.color_' . $color->value) }}
                        </option>
                    @endforeach
                </x-select>

                @error('color')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="found_on">{{ __('site.found_date_required') }}</x-label>
                <x-input type="date" name="found_on" id="found_on" :value="old('found_on', now()->format('Y-m-d'))" required />
                @error('found_on')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <fieldset>
                <legend class="mb-3 text-sm font-semibold text-slate-700 dark:text-slate-200">
                    {{ __('site.optional_tags') }}
                </legend>

                <input type="hidden" name="tags_submitted" value="1">

                @if ($tags->isNotEmpty())
                    <div class="flex flex-wrap gap-3">
                        @foreach ($tags as $tag)
                            <label
                                class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-slate-200 px-3 py-2 text-sm dark:border-slate-700">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                    @checked(in_array($tag->id, old('tags', [])))
                                    class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                <span>{{ app('translator')->has('site.seeded_tags.' . $tag->name) ? __('site.seeded_tags.' . $tag->name) : $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ __('site.no_tags_available') }}
                    </p>
                @endif

                @error('tags')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('tags.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </fieldset>

            <div class="flex flex-wrap justify-end gap-3 border-t border-slate-200 pt-6 dark:border-slate-800">
                <a href="{{ route('items.index') }}"
                    class="rounded-xl border border-slate-300 px-5 py-2.5 font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                    {{ __('site.cancel') }}
                </a>

                <button type="submit"
                    class="rounded-xl bg-teal-600 px-5 py-2.5 font-semibold text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    @disabled($places->isEmpty())>
                    {{ __('site.save_item') }}
                </button>
            </div>
        </form>
    </div>
@endsection
