@props([
    'name' => 'color',
    'selected' => null,
])

@php
    use App\Enums\Color;

    $selectedColor = old($name, $selected);

    $colorClasses = [
        'black' => 'bg-black',
        'white' => 'bg-white border border-gray-400',
        'grey' => 'bg-gray-500',
        'red' => 'bg-red-500',
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'yellow' => 'bg-yellow-400',
        'orange' => 'bg-orange-500',
        'brown' => 'bg-amber-800',
        'pink' => 'bg-pink-400',
        'purple' => 'bg-purple-500',
        'multicolor' => 'bg-gradient-to-r from-red-500 via-yellow-400 to-blue-500',
    ];
@endphp

<fieldset>
    <legend class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-200">
        Couleur *
    </legend>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
        @foreach (Color::cases() as $color)
            <label class="cursor-pointer">
                <input type="radio" name="{{ $name }}" value="{{ $color->value }}" class="peer sr-only"
                    @checked($selectedColor === $color->value) required>

                <span
                    class="flex items-center gap-2 rounded-lg border border-gray-300 p-3
                    peer-checked:border-blue-600 peer-checked:ring-2 peer-checked:ring-blue-500
                    dark:border-gray-600 dark:text-gray-100">
                    <span class="h-5 w-5 shrink-0 rounded-full {{ $colorClasses[$color->value] }}"></span>
                    <span class="text-sm">{{ $color->label() }}</span>
                </span>
            </label>
        @endforeach
    </div>

    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</fieldset>
