@extends('layouts.app')

@section('title', __('site.tag_title'))

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>{{ __('site.tag_heading', ['name' => $tag->name]) }}</h1>

            <div class="tag-actions">
                <a href="{{ route('tags.index') }}" class="btn">
                    {{ __('site.back_to_tags') }}
                </a>

                <a href="{{ route('tags.edit', $tag) }}" class="btn btn-primary">
                    {{ __('site.edit_tag') }}
                </a>
            </div>
        </header>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <section>
            <h2>{{ __('site.associated_items') }}</h2>

            @if ($items->isEmpty())
                <p>{{ __('site.no_tag_items') }}</p>
            @else
                <p>
                    {{ $items->total() }}
                    {{ __('site.tag_items_count') }}
                </p>

                <div class="items-list">
                    @foreach ($items as $item)
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

                            @if ($item->place)
                                <p>
                                    <strong>{{ __('site.location_label') }}</strong>
                                    <a href="{{ route('places.show', $item->place) }}">
                                        {{ app('translator')->has('site.seeded_places.' . $item->place->name) ? __('site.seeded_places.' . $item->place->name) : $item->place->name }}
                                    </a>
                                </p>
                            @endif

                            @if ($item->found_on)
                                <p>
                                    <strong>{{ __('site.found_date_label') }}</strong>
                                    {{ $item->found_on->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}
                                </p>
                            @endif

                            <p>
                                <strong>{{ __('site.status_label') }}</strong>
                                @if ($item->isReturned())
                                    {{ __('site.returned') }}
                                @else
                                    {{ __('site.not_returned') }}
                                @endif
                            </p>

                            <a href="{{ route('items.show', $item) }}" class="btn">
                                {{ __('site.view_item') }}
                            </a>
                        </article>
                    @endforeach
                </div>

                {{ $items->links() }}
            @endif
        </section>
    </div>
@endsection
