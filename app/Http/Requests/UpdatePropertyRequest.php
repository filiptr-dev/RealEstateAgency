<?php

namespace App\Http\Requests;

class UpdatePropertyRequest extends StorePropertyRequest
{
    public function authorize(): bool
    {
        $property = $this->route('property');

        return $property !== null && $this->user()?->can('update', $property);
    }
}
