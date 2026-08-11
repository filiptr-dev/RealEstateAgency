<?php

namespace Tests\Feature\Public;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertySearchTest extends TestCase
{
    use RefreshDatabase;

    private function assertOnlyTitles(array $expected, string $url): void
    {
        $response = $this->get($url);
        $response->assertOk();
        $properties = $response->viewData('properties');
        $titles = collect($properties->items())->pluck('title')->sort()->values()->all();
        sort($expected);
        $this->assertSame($expected, $titles, "Filter mismatch on {$url}");
    }

    public function test_listing_renders(): void
    {
        Property::factory()->count(3)->create(['published_at' => now()]);
        $this->get('/properties')->assertOk();
    }

    public function test_city_filter_narrows_results(): void
    {
        Property::factory()->create(['city' => 'Boston', 'published_at' => now(), 'title' => 'Boston Home']);
        Property::factory()->create(['city' => 'Miami', 'published_at' => now(), 'title' => 'Miami Home']);
        $this->assertOnlyTitles(['Boston Home'], '/properties?city=Boston');
    }

    public function test_type_filter(): void
    {
        Property::factory()->create(['type' => PropertyType::Sale->value, 'published_at' => now(), 'title' => 'Sale One']);
        Property::factory()->create(['type' => PropertyType::Rent->value, 'published_at' => now(), 'title' => 'Rent One']);
        $this->assertOnlyTitles(['Sale One'], '/properties?type=sale');
    }

    public function test_status_filter(): void
    {
        Property::factory()->create(['status' => PropertyStatus::Villa->value, 'published_at' => now(), 'title' => 'Villa X']);
        Property::factory()->create(['status' => PropertyStatus::Land->value, 'published_at' => now(), 'title' => 'Land Y']);
        $this->assertOnlyTitles(['Villa X'], '/properties?status=villa');
    }

    public function test_bedrooms_and_bathrooms_are_minimums(): void
    {
        Property::factory()->create(['bedrooms' => 2, 'bathrooms' => 2, 'published_at' => now(), 'title' => 'Small']);
        Property::factory()->create(['bedrooms' => 5, 'bathrooms' => 3, 'published_at' => now(), 'title' => 'Big']);
        $this->assertOnlyTitles(['Big'], '/properties?bedrooms=4&bathrooms=3');
    }

    public function test_price_range(): void
    {
        Property::factory()->create(['price_cents' => 100_000_00, 'published_at' => now(), 'title' => 'Cheap']);
        Property::factory()->create(['price_cents' => 900_000_00, 'published_at' => now(), 'title' => 'Pricey']);
        $this->assertOnlyTitles(['Pricey'], '/properties?min_price=500000');
        $this->assertOnlyTitles(['Cheap'], '/properties?max_price=200000');
    }

    public function test_invalid_filter_returns_validation_error_not_500(): void
    {
        $this->get('/properties?type=nonsense')->assertStatus(302);
        $this->get('/properties?bedrooms=abc')->assertStatus(302);
    }

    public function test_unpublished_properties_are_hidden(): void
    {
        Property::factory()->create(['published_at' => null, 'title' => 'Draft One']);
        $response = $this->get('/properties')->assertOk();
        $titles = collect($response->viewData('properties')->items())->pluck('title')->all();
        $this->assertNotContains('Draft One', $titles);
    }
}
