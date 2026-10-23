<?php

namespace App\Http\Requests;

use App\Enums\Color;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:60'],
            'color' => ['required', Rule::enum(Color::class)],
            'found_on' => ['required', 'date', 'before_or_equal:today'],
            'place_id' => ['required', 'integer', 'exists:places,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'remove_photo' => ['sometimes', 'boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('site.item_attribute'),
            'color' => __('site.color_attribute'),
            'found_on' => __('site.found_date_attribute'),
            'place_id' => __('site.place_attribute'),
            'description' => __('site.description_attribute'),
            'photo' => __('site.photo_attribute'),
            'tags' => __('site.tags_attribute'),
            'tags.*' => __('site.tag_attribute'),
        ];
    }
}
