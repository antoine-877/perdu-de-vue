@props(['tag'])

<x-color-badge :color="$tag->color ?? \App\Enums\Color::Multicolor" :label="app('translator')->has('site.seeded_tags.' . $tag->name) ? __('site.seeded_tags.' . $tag->name) : $tag->name" {{ $attributes }} />
