@extends('layouts.app')

@section('title', __('site.location') . ': ' . (app('translator')->has('site.seeded_places.' . $place->name) ?
    __('site.seeded_places.' . $place->name) : $place->name))

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>{{ app('translator')->has('site.seeded_places.' . $place->name) ? __('site.seeded_places.' . $place->name) : $place->name }}
            </h1>

            <a href="{{ route('places.index') }}" class="btn">
                {{ __('site.back_to_places') }}
            </a>
        </header>

        <section class="place-details">
            <h2>{{ __('site.places_details') }}</h2>

            @if ($place->photo_path)
                <img src="{{ Storage::disk('public')->url($place->photo_path) }}"
                    alt="{{ __('site.place_photo_of', ['name' => __('site.seeded_places.' . $place->name, [], $place->name)]) }}"
                    class="mb-6 max-h-96 w-full rounded-xl object-cover">
            @endif

            <p>
                <strong>{{ __('site.name_label') }}</strong>
                {{ app('translator')->has('site.seeded_places.' . $place->name) ? __('site.seeded_places.' . $place->name) : $place->name }}
            </p>

            <p>
                <strong>{{ __('site.items_found_count') }}</strong>
                {{ $items->total() }}
            </p>
        </section>

        <form action="{{ route('places.photo.update', $place) }}" method="POST" enctype="multipart/form-data"
            class="my-6 max-w-xl space-y-3 rounded-lg border border-slate-200 p-4 dark:border-slate-700">
            @csrf
            <label for="place-photo" class="block font-semibold">{{ __('site.place_photo') }}</label>
            <input type="file" id="place-photo" name="photo" accept="image/*"
                class="block w-full text-slate-800 file:mr-4 file:rounded-md file:border-0 file:bg-slate-200 file:px-3 file:py-2 dark:text-slate-100 dark:file:bg-slate-700">
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('site.photo_max') }}</p>
            <label class="block text-xs">{{ __('site.agent_token') }}
                <input type="password" name="agent_token" required autocomplete="off"
                    class="mt-1 w-full rounded border border-slate-300 bg-white px-2 py-1 text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
            </label>
            <button type="submit"
                class="rounded bg-teal-700 px-4 py-2 font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500">{{ __('site.save_place_photo') }}</button>
        </form>

        @if ($place->photo_path)
            <form action="{{ route('places.photo.update', $place) }}" method="POST" class="mb-6"
                onsubmit="return confirm(@json(__('site.remove_place_photo_confirm')));">
                @csrf
                <input type="hidden" name="remove_photo" value="1">
                <label class="mb-3 block max-w-xl text-xs">{{ __('site.agent_token') }}
                    <input type="password" name="agent_token" required autocomplete="off"
                        class="mt-1 w-full rounded border border-slate-300 bg-white px-2 py-1 text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                </label>
                <button type="submit"
                    class="font-semibold text-red-700 hover:underline dark:text-red-300">{{ __('site.remove_place_photo') }}</button>
            </form>
        @endif

        <section class="place-items">
            <h2>{{ __('site.items_at_place') }}</h2>

            @forelse ($items as $item)
                <article class="item-card">
                    @if ($item->photo_path)
                        <img src="{{ Storage::disk('public')->url($item->photo_path) }}"
                            alt="{{ __('site.photo_of', ['name' => $item->name]) }}"
                            class="mb-4 aspect-[4/3] w-full rounded-lg object-cover">
                    @endif
                    <h3>
                        <a href="{{ route('items.show', $item) }}">
                            {{ $item->name }}
                        </a>
                    </h3>

                    @if ($item->description)
                        <p>{{ $item->description }}</p>
                    @endif

                    <p>
                        <strong>{{ __('site.color_label') }}</strong>
                        <x-color-badge :color="$item->color" />
                    </p>

                    <p>
                        <strong>{{ __('site.found_date_label') }}</strong>
                        @if ($item->found_on)
                            <time datetime="{{ $item->found_on->toDateString() }}"
                                title="{{ $item->found_on->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}">
                                {{ $item->found_on->locale(app()->getLocale())->diffForHumans() }}
                            </time>
                        @else
                            {{ __('site.not_specified') }}
                        @endif
                    </p>

                    <p>
                        <strong>{{ __('site.status_label') }}</strong>
                        {{ $item->isReturned() ? __('site.returned') : __('site.not_returned') }}
                    </p>

                    @if ($item->tags->isNotEmpty())
                        <div class="flex flex-wrap gap-2" aria-label="{{ __('site.labels') }}">
                            @foreach ($item->tags as $tag)
                                <a href="{{ route('tags.show', $tag) }}"
                                    class="rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <x-tag-badge :tag="$tag" />
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <a href="{{ route('items.show', $item) }}" class="btn">
                        {{ __('site.view_details') }}
                    </a>
                </article>
            @empty
                <p>{{ __('site.no_items_at_place') }}</p>
            @endforelse

            {{ $items->links() }}
        </section>
    </div>
@endsection
