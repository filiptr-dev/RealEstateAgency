<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PropertyPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'path', 'is_cover', 'sort_order'];

    protected function casts(): array
    {
        return [
            'is_cover' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::disk('public')->url($this->path),
        );
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
