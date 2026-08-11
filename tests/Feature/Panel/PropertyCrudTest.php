<?php

namespace Tests\Feature\Panel;

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_is_403_on_panel(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/panel')->assertForbidden();
        $this->actingAs($user)->get('/panel/properties')->assertForbidden();
    }

    public function test_agent_sees_only_own_properties_in_index(): void
    {
        $agent = User::factory()->agent()->create();
        $other = User::factory()->agent()->create();
        Property::factory()->create(['agent_id' => $agent->id, 'title' => 'Mine']);
        Property::factory()->create(['agent_id' => $other->id, 'title' => 'Not mine']);

        $this->actingAs($agent)->get('/panel/properties')
            ->assertOk()
            ->assertSee('Mine')
            ->assertDontSee('Not mine');
    }

    public function test_agent_cannot_edit_another_agents_property(): void
    {
        $agent = User::factory()->agent()->create();
        $other = User::factory()->agent()->create();
        $property = Property::factory()->create(['agent_id' => $other->id]);

        $this->actingAs($agent)->get(route('panel.properties.edit', $property))->assertForbidden();
    }

    public function test_admin_can_edit_any_property(): void
    {
        $admin = User::factory()->admin()->create();
        $property = Property::factory()->create();
        $this->actingAs($admin)->get(route('panel.properties.edit', $property))->assertOk();
    }

    public function test_agent_can_create_a_property(): void
    {
        $agent = User::factory()->agent()->create();

        $this->actingAs($agent)->post('/panel/properties', [
            'title' => 'My new one',
            'description' => 'Nice place',
            'type' => 'sale',
            'status' => 'apartment',
            'price' => 250000,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'size_acres' => 0.5,
            'address' => '1 Main St',
            'zip' => '10001',
            'city' => 'New York',
            'country' => 'USA',
            'features_text' => "Parking\nGarden",
            'published' => 1,
        ])->assertRedirect(route('panel.properties.index'));

        $property = Property::firstOrFail();
        $this->assertSame('My new one', $property->title);
        $this->assertSame($agent->id, $property->agent_id);
        $this->assertSame(25_000_000, $property->price_cents);
        $this->assertEquals(['Parking', 'Garden'], $property->features);
    }

    public function test_agent_cannot_update_others_property(): void
    {
        $agent = User::factory()->agent()->create();
        $other = User::factory()->agent()->create();
        $property = Property::factory()->create(['agent_id' => $other->id]);

        $this->actingAs($agent)->put(route('panel.properties.update', $property), [
            'title' => 'Hack',
            'description' => 'x',
            'type' => 'sale',
            'status' => 'apartment',
            'price' => 1,
            'bedrooms' => 0,
            'bathrooms' => 0,
            'size_acres' => 0.1,
            'address' => 'x',
            'zip' => 'x',
            'city' => 'x',
            'country' => 'x',
        ])->assertForbidden();
    }
}
