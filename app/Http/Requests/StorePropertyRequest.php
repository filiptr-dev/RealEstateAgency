<?php

namespace App\Http\Requests;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && ($this->user()->isAdmin() || $this->user()->isAgent());
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('features_text') && ! $this->has('features')) {
            $lines = preg_split('/\r?\n/', (string) $this->input('features_text'));
            $features = array_values(array_filter(array_map('trim', $lines), fn ($v) => $v !== ''));
            $this->merge(['features' => $features]);
        }

        // photo_urls comes in as a textarea (one URL per line); normalise to an array
        // BEFORE validation so the `photo_urls.*` rules can fire on each entry.
        if ($this->filled('photo_urls') && is_string($this->input('photo_urls'))) {
            $lines = preg_split('/\r?\n/', (string) $this->input('photo_urls'));
            $urls = array_values(array_filter(array_map('trim', $lines), fn ($v) => $v !== ''));
            $this->merge(['photo_urls' => $urls]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['required', Rule::in(array_column(PropertyType::cases(), 'value'))],
            'status' => ['required', Rule::in(array_column(PropertyStatus::cases(), 'value'))],
            'price' => ['required', 'numeric', 'min:0'],
            'bedrooms' => ['required', 'integer', 'min:0', 'max:255'],
            'bathrooms' => ['required', 'integer', 'min:0', 'max:255'],
            'size_acres' => ['required', 'numeric', 'min:0'],
            'address' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:32'],
            'city' => ['required', 'string', 'max:120'],
            'country' => ['required', 'string', 'max:120'],
            'area' => ['nullable', 'string', 'max:120'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:120'],
            'nearby' => ['nullable', 'array'],
            'nearby.*.label' => ['required_with:nearby', 'string', 'max:120'],
            'nearby.*.distance_km' => ['required_with:nearby', 'numeric', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'published' => ['nullable', 'boolean'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'max:4096', 'mimes:jpg,jpeg,png,webp'],
            'photo_urls' => ['nullable', 'array'],
            'photo_urls.*' => ['url', 'max:2048'],
            'cover_index' => ['nullable', 'integer', 'min:0'],
            'delete_photo_ids' => ['nullable', 'array'],
            'delete_photo_ids.*' => ['integer'],
        ];
    }
}
