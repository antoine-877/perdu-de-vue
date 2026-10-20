
@props([
    'name',
    'id' => null,
    'rows' => 4,
])

<textarea
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    rows="{{ $rows }}"
    {{ $attributes->merge(['class' => 'form-control']) }}
>{{ $slot }}</textarea>
