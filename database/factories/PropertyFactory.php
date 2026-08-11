<?php

namespace Database\Factories;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->words(3, true);

        return [
            'agent_id' => User::factory()->agent(),
            'title' => $title,
            // Slug filled by model observer when empty.
            'description' => fake()->paragraph(4),
            'type' => fake()->randomElement([PropertyType::Sale->value, PropertyType::Rent->value]),
            'status' => fake()->randomElement(array_column(PropertyStatus::cases(), 'value')),
            'price_cents' => fake()->numberBetween(50_000, 5_000_000) * 100,
            'bedrooms' => fake()->numberBetween(1, 6),
            'bathrooms' => fake()->numberBetween(1, 4),
            'size_acres' => fake()->randomFloat(2, 0.1, 20),
            'address' => fake()->streetAddress(),
            'zip' => fake()->postcode(),
            'city' => fake()->randomElement(['New York', 'Boston', 'Miami', 'Chicago', 'Seattle']),
            'country' => 'USA',
            'area' => fake()->words(2, true),
            'features' => fake()->randomElements(
                ['Parking', 'Swimming pool', 'Balcony', 'Garden', 'Fireplace', 'Air conditioning', 'Heating', 'Security'],
                fake()->numberBetween(3, 6)
            ),
            'nearby' => [
                ['label' => 'School', 'distance_km' => fake()->randomFloat(1, 0.1, 5)],
                ['label' => 'Supermarket', 'distance_km' => fake()->randomFloat(1, 0.1, 3)],
                ['label' => 'Hospital', 'distance_km' => fake()->randomFloat(1, 0.5, 10)],
            ],
            'is_featured' => false,
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
