@props(['color', 'label' => null])

@php
    $rawColor = $color instanceof \App\Enums\Color ? $color->value : $color;
    $color =
        $color instanceof \App\Enums\Color
            ? $color
            : (is_string($rawColor)
                ? \App\Enums\Color::tryFrom($rawColor)
                : null);
    $styles = [
        'black' => 'background-color: #171717; color: #ffffff;',
        'white' => 'background-color: #ffffff; color: #222222; border: 1px solid #d1d5db;',
        'grey' => 'background-color: #6b7280; color: #ffffff;',
        'red' => 'background-color: #dc2626; color: #ffffff;',
        'blue' => 'background-color: #2563eb; color: #ffffff;',
        'green' => 'background-color: #15803d; color: #ffffff;',
        'yellow' => 'background-color: #facc15; color: #222222;',
        'orange' => 'background-color: #ea580c; color: #ffffff;',
        'brown' => 'background-color: #854d0e; color: #ffffff;',
        'pink' => 'background-color: #ec4899; color: #222222;',
        'purple' => 'background-color: #9333ea; color: #ffffff;',
        'multicolor' => 'background: linear-gradient(to right, #ef4444, #facc15, #3b82f6); color: #222222;',
    ];
    $style = $color ? $styles[$color->value] : null;

    if ($style === null && is_string($rawColor) && preg_match('/^#[0-9a-fA-F]{6}$/', $rawColor)) {
        $style = "background-color: {$rawColor}; color: #ffffff;";
    }

    $label ??= $color ? __('site.color_' . $color->value) : null;
@endphp

@if ($style && $label)
    <span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold']) }}
        style="{{ $style }}">
        {{ $label }}
    </span>
@endif
