
@props([
    'name',
    'id' => null,
])

<select
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    {{ $attributes->merge(['class' => 'form-select']) }}
>
    {{ $slot }}
</select>
