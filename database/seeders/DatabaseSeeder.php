<?php

namespace Database\Seeders;

use App\Enums\PropertyType;
use App\Models\ContactSubmission;
use App\Models\Property;
use App\Models\PropertyPhoto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $agent = User::updateOrCreate(
            ['email' => 'agent@example.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
                'role' => 'agent',
                'phone' => '+1 234 567 89',
                'bio' => 'Ten years of experience across residential and commercial listings.',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Copy some demo photos into storage/app/public/properties/{id}/ so the
        // gallery has something real to show without requiring an upload flow.
        $sourceImages = [];
        $publicImages = public_path('images');
        if (is_dir($publicImages)) {
            for ($i = 1; $i <= 6; $i++) {
                $candidate = $publicImages.DIRECTORY_SEPARATOR."img-{$i}.jpg";
                if (is_file($candidate)) {
                    $sourceImages[] = $candidate;
                }
            }
        }

        $properties = Property::factory()
            ->count(12)
            ->sequence(fn ($sequence) => [
                'agent_id' => $agent->id,
                'is_featured' => $sequence->index < 6,
                'type' => $sequence->index % 2 === 0
                    ? PropertyType::Sale->value
                    : PropertyType::Rent->value,
            ])
            ->create();

        foreach ($properties as $index => $property) {
            $storageDir = storage_path("app/public/properties/{$property->id}");
            if (! is_dir($storageDir)) {
                File::makeDirectory($storageDir, 0755, true);
            }

            if (empty($sourceImages)) {
                continue;
            }

            $count = 3;
            for ($n = 0; $n < $count; $n++) {
                $src = $sourceImages[($index + $n) % count($sourceImages)];
                $filename = "img-{$n}.jpg";
                $dst = $storageDir.DIRECTORY_SEPARATOR.$filename;
                if (! file_exists($dst)) {
                    @copy($src, $dst);
                }
                PropertyPhoto::create([
                    'property_id' => $property->id,
                    'path' => "properties/{$property->id}/{$filename}",
                    'is_cover' => $n === 0,
                    'sort_order' => $n,
                ]);
            }
        }

        ContactSubmission::factory()->count(3)->create([
            'property_id' => $properties->random()->id,
        ]);
    }
}
