
@props([
    'name',
    'type' => 'text',
    'id' => null,
])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    value="{{ old($name, $attributes->get('value')) }}"
    {{ $attributes->except('value')->merge(['class' => 'form-control']) }}
>
