@props(['tag'])

@php
    $color = $tag->color instanceof \App\Enums\Color ? $tag->color->value : $tag->color ?? 'multicolor';

    $styles = [
        'black' => 'background-color: #000000; color: #ffffff;',
        'white' => 'background-color: #ffffff; color: #222222; border: 1px solid #cccccc;',
        'grey' => 'background-color: #808080; color: #ffffff;',
        'red' => 'background-color: #ef4444; color: #ffffff;',
        'blue' => 'background-color: #3b82f6; color: #ffffff;',
        'green' => 'background-color: #22c55e; color: #ffffff;',
        'yellow' => 'background-color: #facc15; color: #222222;',
        'orange' => 'background-color: #f97316; color: #ffffff;',
        'brown' => 'background-color: #92400e; color: #ffffff;',
        'pink' => 'background-color: #f472b6; color: #222222;',
        'purple' => 'background-color: #a855f7; color: #ffffff;',
        'multicolor' => 'background: linear-gradient(to right, #ef4444, #facc15, #3b82f6); color: #222222;',
    ];
@endphp

<span
    {{ $attributes->merge([
        'class' => 'inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold',
    ]) }}
    style="{{ $styles[$color] ?? $styles['multicolor'] }}">
    {{ $tag->name }}
</span>
