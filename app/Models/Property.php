<?php

namespace App\Models;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use Database\Factories\PropertyFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Property extends Model
{
    /** @use HasFactory<PropertyFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agent_id', 'title', 'slug', 'description', 'type', 'status',
        'price_cents', 'bedrooms', 'bathrooms', 'size_acres',
        'address', 'zip', 'city', 'country', 'area',
        'lat', 'lng',
        'features', 'nearby', 'is_featured', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => PropertyType::class,
            'status' => PropertyStatus::class,
            'features' => 'array',
            'nearby' => 'array',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'price_cents' => 'integer',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'size_acres' => 'decimal:2',
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Property $property) {
            if (empty($property->slug)) {
                $property->slug = static::uniqueSlug($property->title);
            }
        });
    }

    public static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'property';
        $slug = $base;
        $n = 2;
        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$n}";
            $n++;
        }

        return $slug;
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(PropertyPhoto::class)->orderBy('sort_order');
    }

    public function coverPhoto(): ?PropertyPhoto
    {
        return $this->photos->firstWhere('is_cover', true) ?? $this->photos->first();
    }

    public function priceFormatted(): Attribute
    {
        return Attribute::get(function () {
            $euros = intdiv((int) $this->price_cents, 100);

            return '€'.number_format($euros, 0, '.', ',');
        });
    }

    public function scopeFilter(Builder $query, Request $request): Builder
    {
        $data = $request->only([
            'city', 'type', 'status', 'bedrooms', 'bathrooms', 'min_price', 'max_price',
        ]);

        if (! empty($data['city'])) {
            $query->where('city', $data['city']);
        }
        if (! empty($data['type'])) {
            $query->where('type', $data['type']);
        }
        if (! empty($data['status'])) {
            $query->where('status', $data['status']);
        }
        if (! empty($data['bedrooms'])) {
            $query->where('bedrooms', '>=', (int) $data['bedrooms']);
        }
        if (! empty($data['bathrooms'])) {
            $query->where('bathrooms', '>=', (int) $data['bathrooms']);
        }
        if (isset($data['min_price']) && $data['min_price'] !== '' && $data['min_price'] !== null) {
            $query->where('price_cents', '>=', ((int) $data['min_price']) * 100);
        }
        if (isset($data['max_price']) && $data['max_price'] !== '' && $data['max_price'] !== null) {
            $query->where('price_cents', '<=', ((int) $data['max_price']) * 100);
        }

        return $query;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
