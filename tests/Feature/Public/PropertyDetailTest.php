<?php

namespace Tests\Feature\Public;

use App\Models\Property;
use App\Models\PropertyPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_page_renders_with_photos_and_agent(): void
    {
        $property = Property::factory()->create([
            'published_at' => now(),
            'title' => 'Beach House',
            'features' => ['Parking', 'Garden'],
            'nearby' => [['label' => 'Beach', 'distance_km' => 0.3]],
        ]);
        PropertyPhoto::create(['property_id' => $property->id, 'path' => 'properties/x/img.jpg', 'is_cover' => true, 'sort_order' => 0]);

        $this->get("/properties/{$property->slug}")
            ->assertOk()
            ->assertSee('Beach House')
            ->assertSee('Parking')
            ->assertSee('Beach');
    }

    public function test_unpublished_show_returns_404(): void
    {
        $property = Property::factory()->create(['published_at' => null]);
        $this->get("/properties/{$property->slug}")->assertNotFound();
    }
}
