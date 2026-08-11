<?php

namespace Database\Seeders;

use App\Models\ContactSubmission;
use App\Models\Property;
use App\Models\PropertyPhoto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Throwable;

class DatabaseSeeder extends Seeder
{
    /**
     * Stable Unsplash photo IDs used to seed realistic listing imagery.
     * The URL pattern `images.unsplash.com/photo-{id}` returns the raw image,
     * so we can save it directly to the public disk without an API key.
     */
    private const PHOTO_IDS = [
        '1564013799919-ab600027ffc6', // modern living room
        '1600596542815-ffad4c1539a9', // house exterior
        '1512917774080-9991f1c4c750', // luxury home
        '1560184897-ae75f418493e',    // apartment interior
        '1493809842364-78817add7ffb', // cozy living space
        '1571460149396-84b0d1e64cb1', // kitchen
        '1484154218962-a197022b5858', // bedroom
        '1600607687939-ce8a6c25118c', // modern kitchen
        '1604014237800-1c9102c219da', // pool villa
        '1558618666-fcd25c85cd64',    // terrace
        '1613490493576-4d03d3299386', // luxury apartment
        '1568605114967-8130f3a36994', // house with garden
        '1583608205776-bfd35f0d9f83', // suburban home
        '1598928506311-c55ded91a20c', // modern bathroom
        '1596436508249-61fd99b596a8', // cozy bedroom
    ];

    public function run(): void
    {
        // Idempotency: wipe listings and their child rows so re-seeding is safe.
        // Users are upserted (not truncated) so their IDs stay stable across re-seeds.
        Schema::disableForeignKeyConstraints();
        ContactSubmission::truncate();
        PropertyPhoto::truncate();
        Property::truncate();
        Schema::enableForeignKeyConstraints();

        // Wipe stale seeded photos on disk so re-runs don't accumulate.
        $propertiesDir = storage_path('app/public/properties');
        if (is_dir($propertiesDir)) {
            File::deleteDirectory($propertiesDir);
        }
        File::makeDirectory($propertiesDir, 0755, true, true);

        // ---- Users -------------------------------------------------------------
        User::updateOrCreate(
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
                'phone' => '+389 2 123 4567',
                'bio' => 'Ten years of experience across residential and commercial listings in North Macedonia.',
                'photo_path' => 'images/agent-2.jpg',
                'email_verified_at' => now(),
            ]
        );

        $agent2 = User::updateOrCreate(
            ['email' => 'agent2@example.com'],
            [
                'name' => 'Marko Petrov',
                'password' => Hash::make('password'),
                'role' => 'agent',
                'phone' => '+389 70 234 567',
                'bio' => 'Specialist in luxury villas and lakeside properties across the country.',
                'photo_path' => 'images/agent-1.jpg',
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

        // ---- Photo pool --------------------------------------------------------
        $publicImages = public_path('images');
        $photoPool = collect([
            'img-1.jpg', 'img-2.jpg', 'img-3.jpg', 'img-4.jpg', 'img-5.jpg', 'img-6.jpg',
            'propt-img-1.jpg', 'propt-img-2.jpg', 'propt-img-3.jpg',
            'single-img-1.jpg', 'single-img-2.jpg', 'single-img-3.jpg', 'single-img-4.jpg',
            'detail-img.jpg', 'detail-img-1.jpg',
        ])->filter(fn ($f) => is_file($publicImages.DIRECTORY_SEPARATOR.$f))->values()->all();

        // ---- 15 hardcoded property records ------------------------------------
        $records = [
            [
                'title' => 'Modern Two-Bedroom Apartment in Debar Maalo',
                'type' => 'sale', 'status' => 'apartment',
                'price_cents' => 12500000, 'bedrooms' => 2, 'bathrooms' => 1, 'size_acres' => 68.00,
                'address' => 'Ul. Orce Nikolov 145', 'zip' => '1000', 'city' => 'Skopje', 'country' => 'North Macedonia',
                'area' => 'Debar Maalo',
                'features' => ['Balcony', 'Central heating', 'Elevator', 'Parking', 'Renovated kitchen'],
                'nearby' => [
                    ['label' => 'City Park', 'distance_km' => 0.4],
                    ['label' => 'Vero Supermarket', 'distance_km' => 0.3],
                    ['label' => 'Skopje City Hospital', 'distance_km' => 1.2],
                ],
                'description' => 'Bright top-floor apartment in one of Skopje\'s most walkable neighbourhoods. Recently renovated with a new kitchen and hardwood floors, and steps from the cafés of Debar Maalo. Secure building with a lift and dedicated parking.',
                'is_featured' => true,
            ],
            [
                'title' => 'Lakeside Villa with Private Dock in Ohrid',
                'type' => 'sale', 'status' => 'villa',
                'price_cents' => 42000000, 'bedrooms' => 5, 'bathrooms' => 4, 'size_acres' => 320.00,
                'address' => 'Kej Marshal Tito 88', 'zip' => '6000', 'city' => 'Ohrid', 'country' => 'North Macedonia',
                'area' => 'Old Town Waterfront',
                'features' => ['Private dock', 'Swimming pool', 'Garden', 'Lake view', 'Fireplace', 'Garage'],
                'nearby' => [
                    ['label' => 'Ohrid Old Bazaar', 'distance_km' => 0.8],
                    ['label' => 'St. Sofia Church', 'distance_km' => 1.1],
                    ['label' => 'Ohrid Airport', 'distance_km' => 9.5],
                ],
                'description' => 'A one-of-a-kind stone villa on the shore of Lake Ohrid with a private dock and mature garden. Five spacious bedrooms, a full-height living room facing the water, and a heated pool for shoulder-season use.',
                'is_featured' => true,
            ],
            [
                'title' => 'Bright Studio for Rent Near City Square',
                'type' => 'rent', 'status' => 'apartment',
                'price_cents' => 35000, 'bedrooms' => 1, 'bathrooms' => 1, 'size_acres' => 32.00,
                'address' => 'Ul. Makedonija 24', 'zip' => '1000', 'city' => 'Skopje', 'country' => 'North Macedonia',
                'area' => 'Centar',
                'features' => ['Furnished', 'Air conditioning', 'Wi-Fi included', 'Balcony'],
                'nearby' => [
                    ['label' => 'Macedonia Square', 'distance_km' => 0.2],
                    ['label' => 'Stone Bridge', 'distance_km' => 0.3],
                    ['label' => 'GTC Shopping Centre', 'distance_km' => 0.4],
                ],
                'description' => 'Fully furnished studio right off the main pedestrian street. Ideal for a single professional or a student — utilities included, ready to move in.',
                'is_featured' => true,
            ],
            [
                'title' => 'Family House with Garden in Aerodrom',
                'type' => 'sale', 'status' => 'house',
                'price_cents' => 21500000, 'bedrooms' => 4, 'bathrooms' => 3, 'size_acres' => 210.00,
                'address' => 'Ul. Jane Sandanski 62', 'zip' => '1000', 'city' => 'Skopje', 'country' => 'North Macedonia',
                'area' => 'Aerodrom',
                'features' => ['Garden', 'Garage', 'Central heating', 'Storage room', 'Solar water heater'],
                'nearby' => [
                    ['label' => 'City Mall', 'distance_km' => 1.5],
                    ['label' => 'International Primary School', 'distance_km' => 0.9],
                    ['label' => 'Aerodrom Sports Centre', 'distance_km' => 0.7],
                ],
                'description' => 'Detached two-storey house with a mature garden on a quiet residential street. Four bedrooms and a family-friendly layout, moments from schools and green space.',
                'is_featured' => true,
            ],
            [
                'title' => 'Two-Bedroom Rental in Old Bitola',
                'type' => 'rent', 'status' => 'apartment',
                'price_cents' => 45000, 'bedrooms' => 2, 'bathrooms' => 1, 'size_acres' => 74.00,
                'address' => 'Sirok Sokak 51', 'zip' => '7000', 'city' => 'Bitola', 'country' => 'North Macedonia',
                'area' => 'Sirok Sokak',
                'features' => ['Furnished', 'High ceilings', 'Original woodwork', 'Central heating'],
                'nearby' => [
                    ['label' => 'Sirok Sokak Promenade', 'distance_km' => 0.1],
                    ['label' => 'Bitola Old Bazaar', 'distance_km' => 0.5],
                    ['label' => 'Heraclea Lyncestis', 'distance_km' => 2.0],
                ],
                'description' => 'Character apartment in a restored 1920s building on Bitola\'s famous pedestrian street. Original details preserved, modern amenities added.',
                'is_featured' => true,
            ],
            [
                'title' => 'Mountain View House in Tetovo',
                'type' => 'sale', 'status' => 'house',
                'price_cents' => 14800000, 'bedrooms' => 3, 'bathrooms' => 2, 'size_acres' => 165.00,
                'address' => 'Ul. Ilindenska 118', 'zip' => '1200', 'city' => 'Tetovo', 'country' => 'North Macedonia',
                'area' => 'Poroj',
                'features' => ['Mountain view', 'Terrace', 'Garden', 'Fireplace', 'Parking'],
                'nearby' => [
                    ['label' => 'Popova Sapka Ski Resort', 'distance_km' => 18.0],
                    ['label' => 'Tetovo Bazaar', 'distance_km' => 2.5],
                    ['label' => 'SEE University', 'distance_km' => 3.1],
                ],
                'description' => 'Three-bedroom family home on the outskirts of Tetovo with unbroken views toward the Šar mountains. Large terrace, fenced garden, and a fireplace for winter evenings.',
                'is_featured' => true,
            ],
            [
                'title' => 'Renovated One-Bedroom for Rent in Kumanovo',
                'type' => 'rent', 'status' => 'apartment',
                'price_cents' => 38000, 'bedrooms' => 1, 'bathrooms' => 1, 'size_acres' => 48.00,
                'address' => 'Ul. 11 Oktomvri 7', 'zip' => '1300', 'city' => 'Kumanovo', 'country' => 'North Macedonia',
                'area' => 'Centar',
                'features' => ['Furnished', 'New appliances', 'Air conditioning', 'Balcony'],
                'nearby' => [
                    ['label' => 'Kumanovo Central Park', 'distance_km' => 0.3],
                    ['label' => 'Tinex Supermarket', 'distance_km' => 0.4],
                    ['label' => 'Bus Station', 'distance_km' => 1.0],
                ],
                'description' => 'Freshly renovated one-bedroom in the heart of Kumanovo. Everything new, from the kitchen to the bathroom fittings, and available immediately.',
                'is_featured' => false,
            ],
            [
                'title' => 'Traditional Stone House in Struga',
                'type' => 'sale', 'status' => 'house',
                'price_cents' => 9800000, 'bedrooms' => 3, 'bathrooms' => 2, 'size_acres' => 140.00,
                'address' => 'Ul. Marshal Tito 39', 'zip' => '6330', 'city' => 'Struga', 'country' => 'North Macedonia',
                'area' => 'Old Struga',
                'features' => ['Stone facade', 'Courtyard', 'Original beams', 'Wood stove'],
                'nearby' => [
                    ['label' => 'Struga Poetry Bridge', 'distance_km' => 0.6],
                    ['label' => 'Lake Ohrid Waterfront', 'distance_km' => 0.7],
                    ['label' => 'Struga Bazaar', 'distance_km' => 0.4],
                ],
                'description' => 'Restored traditional stone house with a small inner courtyard, minutes from the Struga waterfront. Original beams and detailing preserved throughout.',
                'is_featured' => false,
            ],
            [
                'title' => 'Building Plot near Prilep Ring Road',
                'type' => 'sale', 'status' => 'land',
                'price_cents' => 4800000, 'bedrooms' => 0, 'bathrooms' => 0, 'size_acres' => 1200.00,
                'address' => 'Regional Road P-1104', 'zip' => '7500', 'city' => 'Prilep', 'country' => 'North Macedonia',
                'area' => 'Industrial Zone North',
                'features' => ['Utilities on plot', 'Road access', 'Zoned residential/commercial'],
                'nearby' => [
                    ['label' => 'Prilep Ring Road', 'distance_km' => 0.4],
                    ['label' => 'Prilep City Centre', 'distance_km' => 3.5],
                    ['label' => 'Marble Quarry', 'distance_km' => 6.0],
                ],
                'description' => '1,200 m² building plot with utilities on the boundary and direct access to the P-1104 regional road. Flexible zoning suits either a family home or a small commercial project.',
                'is_featured' => false,
            ],
            [
                'title' => 'Three-Bedroom Rental with Terrace in Karpos',
                'type' => 'rent', 'status' => 'apartment',
                'price_cents' => 85000, 'bedrooms' => 3, 'bathrooms' => 2, 'size_acres' => 96.00,
                'address' => 'Ul. Ivan Agovski 12', 'zip' => '1000', 'city' => 'Skopje', 'country' => 'North Macedonia',
                'area' => 'Karpos 4',
                'features' => ['Terrace', 'Parking', 'Air conditioning', 'Central heating', 'Storage'],
                'nearby' => [
                    ['label' => 'Vodno cable car', 'distance_km' => 2.8],
                    ['label' => 'French International School', 'distance_km' => 1.1],
                    ['label' => 'Karpos Green Market', 'distance_km' => 0.6],
                ],
                'description' => 'Spacious three-bedroom on the fifth floor of a well-kept building in Karpos 4. Generous terrace facing south, dedicated parking spot, and easy access to international schools.',
                'is_featured' => false,
            ],
            [
                'title' => 'Modern Villa with Pool in Vodno Foothills',
                'type' => 'sale', 'status' => 'villa',
                'price_cents' => 39500000, 'bedrooms' => 5, 'bathrooms' => 4, 'size_acres' => 380.00,
                'address' => 'Ul. Praska 8', 'zip' => '1000', 'city' => 'Skopje', 'country' => 'North Macedonia',
                'area' => 'Vodno',
                'features' => ['Swimming pool', 'Garden', 'Garage for two', 'Home office', 'Underfloor heating', 'City view'],
                'nearby' => [
                    ['label' => 'Millennium Cross viewpoint', 'distance_km' => 3.5],
                    ['label' => 'American College Skopje', 'distance_km' => 2.2],
                    ['label' => 'Vodno hiking trails', 'distance_km' => 0.5],
                ],
                'description' => 'Contemporary five-bedroom villa on the Vodno foothills with a heated pool and panoramic view over Skopje. Open-plan living, dedicated home office, and a garage for two vehicles.',
                'is_featured' => false,
            ],
            [
                'title' => 'One-Bedroom Rental Near Ladadika, Thessaloniki',
                'type' => 'rent', 'status' => 'apartment',
                'price_cents' => 70000, 'bedrooms' => 1, 'bathrooms' => 1, 'size_acres' => 52.00,
                'address' => 'Odos Katouni 14', 'zip' => '54625', 'city' => 'Thessaloniki', 'country' => 'Greece',
                'area' => 'Ladadika',
                'features' => ['Furnished', 'Air conditioning', 'Sea view', 'Elevator'],
                'nearby' => [
                    ['label' => 'Aristotelous Square', 'distance_km' => 0.5],
                    ['label' => 'Port of Thessaloniki', 'distance_km' => 0.3],
                    ['label' => 'White Tower', 'distance_km' => 1.4],
                ],
                'description' => 'Furnished one-bedroom in the Ladadika district with a partial sea view. Walk to Aristotelous Square in five minutes; ideal for short-term professional relocations.',
                'is_featured' => false,
            ],
            [
                'title' => 'Two-Bedroom Apartment for Sale in Sofia Centre',
                'type' => 'sale', 'status' => 'apartment',
                'price_cents' => 18900000, 'bedrooms' => 2, 'bathrooms' => 2, 'size_acres' => 82.00,
                'address' => 'Ul. Graf Ignatiev 45', 'zip' => '1000', 'city' => 'Sofia', 'country' => 'Bulgaria',
                'area' => 'Centre',
                'features' => ['Renovated', 'Parking', 'Elevator', 'Central heating', 'Wooden floors'],
                'nearby' => [
                    ['label' => 'Vitosha Boulevard', 'distance_km' => 0.4],
                    ['label' => 'Sofia University', 'distance_km' => 0.9],
                    ['label' => 'National Palace of Culture', 'distance_km' => 1.1],
                ],
                'description' => 'Fully renovated two-bedroom in a well-maintained pre-war building on Graf Ignatiev. High ceilings, wooden floors, and a dedicated parking spot in the internal courtyard.',
                'is_featured' => false,
            ],
            [
                'title' => 'Agricultural Land near Ohrid Airport',
                'type' => 'sale', 'status' => 'land',
                'price_cents' => 6200000, 'bedrooms' => 0, 'bathrooms' => 0, 'size_acres' => 3400.00,
                'address' => 'Village of Kosel', 'zip' => '6000', 'city' => 'Ohrid', 'country' => 'North Macedonia',
                'area' => 'Kosel',
                'features' => ['Road access', 'Water source', 'Fenced', 'Existing orchard'],
                'nearby' => [
                    ['label' => 'Ohrid Airport', 'distance_km' => 4.5],
                    ['label' => 'Lake Ohrid shore', 'distance_km' => 6.2],
                    ['label' => 'Ohrid city centre', 'distance_km' => 9.0],
                ],
                'description' => '3,400 m² of level agricultural land with an established apple orchard and reliable water source. Suitable for continued agricultural use or, subject to permission, tourism development.',
                'is_featured' => false,
            ],
            [
                'title' => 'Family House for Rent in Gjorche Petrov',
                'type' => 'rent', 'status' => 'house',
                'price_cents' => 120000, 'bedrooms' => 4, 'bathrooms' => 2, 'size_acres' => 180.00,
                'address' => 'Ul. Kiro Krstevski 22', 'zip' => '1000', 'city' => 'Skopje', 'country' => 'North Macedonia',
                'area' => 'Gjorche Petrov',
                'features' => ['Garden', 'Garage', 'Central heating', 'Furnished', 'Pet-friendly'],
                'nearby' => [
                    ['label' => 'Gjorche Petrov Municipality', 'distance_km' => 0.8],
                    ['label' => 'Nova International School', 'distance_km' => 2.4],
                    ['label' => 'Zajchev Rid park', 'distance_km' => 1.2],
                ],
                'description' => 'Furnished four-bedroom family house with a private garden and garage. Pet-friendly landlord, minimum twelve-month lease, available from the start of next month.',
                'is_featured' => false,
            ],
        ];

        // ---- Create properties, alternating between the two agents ------------
        $properties = collect();
        foreach ($records as $index => $record) {
            $record['agent_id'] = $index % 2 === 0 ? $agent->id : $agent2->id;
            $record['published_at'] = Carbon::now()->subDays($index * 2);
            // Slug is auto-filled by the Property::saving observer when empty — don't set it.
            $properties->push(Property::create($record));
        }

        // ---- Fetch photos from Unsplash (with offline fallback) --------------
        //
        // Strategy: try to grab a real Unsplash image over HTTP; if that fails
        // (offline env, DNS, timeout), fall back to copying a bundled template
        // image so seeding still succeeds. Either way we always create the
        // PropertyPhoto rows so the DB state is deterministic.
        $disk = Storage::disk('public');
        $photoIds = self::PHOTO_IDS;
        $photoIdCount = count($photoIds);
        $poolCount = count($photoPool);

        foreach ($properties as $i => $property) {
            $storageDir = storage_path("app/public/properties/{$property->id}");
            if (! is_dir($storageDir)) {
                File::makeDirectory($storageDir, 0755, true);
            }

            for ($n = 0; $n < 3; $n++) {
                $filename = "img-{$n}.jpg";
                $relPath = "properties/{$property->id}/{$filename}";
                $absPath = $storageDir.DIRECTORY_SEPARATOR.$filename;

                $written = false;

                // Try Unsplash first.
                $photoId = $photoIds[($i * 3 + $n) % $photoIdCount];
                $url = "https://images.unsplash.com/photo-{$photoId}?w=1200&q=80&fit=crop";
                try {
                    $response = Http::timeout(15)->get($url);
                    if ($response->successful() && $response->body() !== '') {
                        $disk->put($relPath, $response->body());
                        $written = true;
                    }
                } catch (Throwable $e) {
                    // swallow — we'll fall back to the bundled image below
                }

                // Fallback: copy a bundled template image so offline seeds still work.
                if (! $written && $poolCount > 0) {
                    $sourceName = $photoPool[($i * 3 + $n) % $poolCount];
                    $src = $publicImages.DIRECTORY_SEPARATOR.$sourceName;
                    if (is_file($src) && ! file_exists($absPath)) {
                        @copy($src, $absPath);
                    }
                }

                PropertyPhoto::create([
                    'property_id' => $property->id,
                    'path' => $relPath,
                    'is_cover' => $n === 0,
                    'sort_order' => $n,
                ]);
            }
        }

        // ---- Sample contact submissions ---------------------------------------
        ContactSubmission::factory()->count(3)->create([
            'property_id' => $properties->random()->id,
        ]);
    }
}
