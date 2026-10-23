@extends('layouts.app')

@section('title', $item->name)

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>{{ $item->name }}</h1>

            <div class="item-actions">
                <a href="{{ route('items.edit', $item) }}" class="btn btn-primary">
                    {{ __('site.edit') }}
                </a>

                <a href="{{ route('items.index') }}" class="btn">
                    {{ __('site.back_to_list') }}
                </a>
            </div>
        </header>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <section class="item-details">
            <h2>{{ __('site.item_details') }}</h2>

            @if ($item->photo_path)
                <img src="{{ Storage::disk('public')->url($item->photo_path) }}"
                    alt="{{ __('site.photo_of', ['name' => $item->name]) }}"
                    class="mb-6 max-h-96 w-full rounded-xl object-contain">
            @endif

            <p>
                <strong>{{ __('site.name_label') }}</strong>
                {{ $item->name }}
            </p>

            <p>
                <strong>{{ __('site.description_label') }}</strong>
                {{ $item->description ?: __('site.no_description') }}
            </p>

            <p>
                <strong>{{ __('site.location_label') }}</strong>
                @if ($item->place)
                    <a href="{{ route('places.show', $item->place) }}">
                        {{ app('translator')->has('site.seeded_places.' . $item->place->name) ? __('site.seeded_places.' . $item->place->name) : $item->place->name }}
                    </a>
                @else
                    {{ __('site.unknown_location') }}
                @endif
            </p>

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

            @if ($item->returned_at)
                <p>
                    <strong>{{ __('site.returned_date_label') }}</strong>
                    {{ $item->returned_at->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}
                </p>
            @endif
        </section>


        <section class="item-tags">
            <h2>{{ __('site.labels') }}</h2>

            <div class="grid grid-cols-4 gap-3">
                @forelse ($item->tags as $tag)
                    <a href="{{ route('tags.show', $tag) }}" class="inline-block no-underline">
                        <x-tag-badge :tag="$tag" />
                    </a>
                @empty
                    <p class="col-span-4">{{ __('site.no_item_tags') }}</p>
                @endforelse
            </div>
        </section>

        <section class="mt-8 space-y-4" aria-labelledby="photo-gallery-title">
            <h2 id="photo-gallery-title">{{ __('site.photo_gallery') }}</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @forelse ($item->photos as $photo)
                    <figure class="space-y-2 rounded-lg border border-slate-200 p-3 dark:border-slate-700">
                        <img src="{{ Storage::disk('public')->url($photo->path) }}" alt="Photo de {{ $item->name }}"
                            class="aspect-square w-full rounded object-cover">
                        @if ($photo->is_cover)
                            <figcaption class="text-sm font-semibold">{{ __('site.cover_photo') }}</figcaption>
                        @endif
                        <form action="{{ route('photos.cover', [$item, $photo]) }}" method="POST" class="space-y-2">
                            @csrf
                            @method('PATCH')
                            <label class="block text-xs">{{ __('site.agent_token') }}
                                <input type="password" name="agent_token" required autocomplete="off"
                                    class="mt-1 w-full rounded border border-slate-300 bg-white px-2 py-1 text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            </label>
                            <button
                                class="text-sm font-semibold text-teal-700 hover:underline focus:ring-2 focus:ring-teal-500 dark:text-teal-300"
                                type="submit">{{ __('site.set_cover') }}</button>
                        </form>
                        <form action="{{ route('photos.destroy', [$item, $photo]) }}" method="POST" class="space-y-2"
                            onsubmit="return confirm(@json(__('site.delete_photo_confirm')));">
                            @csrf
                            @method('DELETE')
                            <label class="block text-xs">{{ __('site.agent_token') }}
                                <input type="password" name="agent_token" required autocomplete="off"
                                    class="mt-1 w-full rounded border border-slate-300 bg-white px-2 py-1 text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            </label>
                            <button
                                class="text-sm font-semibold text-red-700 hover:underline focus:ring-2 focus:ring-red-500 dark:text-red-300"
                                type="submit">{{ __('site.delete_photo') }}</button>
                        </form>
                    </figure>
                @empty
                    <p>{{ __('site.no_extra_photos') }}</p>
                @endforelse
            </div>

            <form action="{{ route('photos.store', $item) }}" method="POST" enctype="multipart/form-data"
                class="max-w-xl space-y-3 rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                @csrf
                <label for="photos" class="block font-semibold">{{ __('site.add_photos_help') }}</label>
                <input type="file" id="photos" name="photos[]" accept="image/*" multiple required
                    class="block w-full text-slate-800 file:mr-4 file:rounded-md file:border-0 file:bg-slate-200 file:px-3 file:py-2 dark:text-slate-100 dark:file:bg-slate-700">
                <label class="block text-xs">{{ __('site.agent_token') }}
                    <input type="password" name="agent_token" required autocomplete="off"
                        class="mt-1 w-full rounded border border-slate-300 bg-white px-2 py-1 text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                </label>
                <button type="submit"
                    class="rounded bg-teal-700 px-4 py-2 font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500">{{ __('site.add_photos') }}</button>
            </form>
        </section>



        @if (!$item->isReturned())
            <form action="{{ route('items.return', $item) }}" method="POST"
                onsubmit="return confirm(@json(__('site.return_confirm')));">
                @csrf
                @method('PATCH')

                <button type="submit" class="btn btn-success">
                    {{ __('site.mark_returned') }}
                </button>
            </form>
        @endif


        <form action="{{ route('items.destroy', $item) }}" class="mt-6" method="POST"
            onsubmit="return confirm(@json(__('site.delete_item_confirm')));">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger">
                {{ __('site.delete_item') }}
            </button>
        </form>
    </div>
@endsection
