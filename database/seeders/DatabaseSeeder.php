<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\ContactSubmission;
use App\Models\Post;
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
                'lat' => 41.9961, 'lng' => 21.4316,
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
                'lat' => 41.1172, 'lng' => 20.8016,
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
                'lat' => 41.9968, 'lng' => 21.4317,
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
                'lat' => 41.9680, 'lng' => 21.4624,
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
                'lat' => 41.0297, 'lng' => 21.3291,
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
                'lat' => 41.0090, 'lng' => 20.9710,
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
                'lat' => 42.1322, 'lng' => 21.7144,
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
                'lat' => 41.1786, 'lng' => 20.6783,
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
                'lat' => 41.3461, 'lng' => 21.5544,
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
                'lat' => 41.9980, 'lng' => 21.3780,
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
                'lat' => 41.9659, 'lng' => 21.3979,
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
                'lat' => 40.6401, 'lng' => 22.9444,
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
                'lat' => 42.6977, 'lng' => 23.3219,
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
                'lat' => 41.0700, 'lng' => 20.7420,
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
                'lat' => 41.9945, 'lng' => 21.3614,
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
        $poolCount = count($photoPool);

        // ---- Agent photos (deterministic picsum seeds) ------------------------
        $agentsDir = storage_path('app/public/agents');
        if (is_dir($agentsDir)) {
            File::deleteDirectory($agentsDir);
        }
        File::makeDirectory($agentsDir, 0755, true, true);

        foreach ([$agent, $agent2] as $a) {
            $agentDir = storage_path("app/public/agents/{$a->id}");
            if (! is_dir($agentDir)) {
                File::makeDirectory($agentDir, 0755, true);
            }
            $rel = "agents/{$a->id}/photo.jpg";
            $abs = $agentDir.DIRECTORY_SEPARATOR.'photo.jpg';
            $written = false;
            try {
                $response = Http::timeout(15)->get("https://picsum.photos/seed/agent-{$a->id}/400/500");
                if ($response->successful() && $response->body() !== '') {
                    $disk->put($rel, $response->body());
                    $written = true;
                }
            } catch (Throwable $e) {
                // fall through to bundled fallback
            }
            if (! $written) {
                $fallback = public_path('images/agent-'.($a->id % 4 + 1).'.jpg');
                if (is_file($fallback)) {
                    @copy($fallback, $abs);
                }
            }
            $a->update(['photo_path' => 'storage/'.$rel]);
        }

        // ---- Static section images (services / blog cards / avatars) ----------
        // Unlike agent/property photos, these are plain public/images/*.jpg
        // files referenced directly in Blade views via asset('images/...'),
        // not DB-backed paths — so we overwrite them in place. Same
        // download-first strategy as above; on failure we simply leave the
        // existing bundled file untouched (same fallback behaviour as the
        // property-photo loop below).
        $staticImageSeeds = [
            'service-img-1.jpg' => ['seed' => 'service-1', 'w' => 600, 'h' => 400],
            'service-img-2.jpg' => ['seed' => 'service-2', 'w' => 600, 'h' => 400],
            'service-img-3.jpg' => ['seed' => 'service-3', 'w' => 600, 'h' => 400],
            'service-img-4.jpg' => ['seed' => 'service-4', 'w' => 600, 'h' => 400],
            'b-img-1.jpg' => ['seed' => 'blog-1', 'w' => 800, 'h' => 500],
            'b-img-2.jpg' => ['seed' => 'blog-2', 'w' => 800, 'h' => 500],
            'b-img-3.jpg' => ['seed' => 'blog-3', 'w' => 800, 'h' => 500],
            'b-img-4.jpg' => ['seed' => 'blog-4', 'w' => 800, 'h' => 500],
            'b-img-5.jpg' => ['seed' => 'blog-5', 'w' => 800, 'h' => 500],
            'avatar-1.jpg' => ['seed' => 'avatar-1', 'w' => 100, 'h' => 100],
            'avatar-2.jpg' => ['seed' => 'avatar-2', 'w' => 100, 'h' => 100],
            'avatar-3.jpg' => ['seed' => 'avatar-3', 'w' => 100, 'h' => 100],
        ];

        foreach ($staticImageSeeds as $filename => $spec) {
            $absPath = $publicImages.DIRECTORY_SEPARATOR.$filename;
            try {
                $response = Http::timeout(15)->get("https://picsum.photos/seed/{$spec['seed']}/{$spec['w']}/{$spec['h']}");
                if ($response->successful() && $response->body() !== '') {
                    File::put($absPath, $response->body());
                }
            } catch (Throwable $e) {
                // leave the existing bundled file untouched
            }
        }

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

                // Try picsum first — deterministic seed, pinned landscape 800×500.
                $url = "https://picsum.photos/seed/property-{$property->id}-{$n}/800/500";
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

        // ---- Blog posts -------------------------------------------------------
        Schema::disableForeignKeyConstraints();
        Comment::truncate();
        Post::truncate();
        Schema::enableForeignKeyConstraints();

        $postData = [
            ['title' => '5 Things Every First-Time Buyer Should Know', 'category' => 'Buying',
                'excerpt' => 'Buying your first home is one of the largest financial decisions you will ever make.',
                'body' => "Buying your first home can feel overwhelming, but breaking it into clear steps makes it manageable.\n\nFirst, get pre-approved for a mortgage before you start viewing properties — knowing your budget saves time and signals to sellers that you are serious. Second, don't skip the inspection; even new builds have issues that only a qualified surveyor will catch. Third, factor in all costs beyond the purchase price: notary fees, agent commissions, property transfer taxes, and moving costs can add 5–8% to the headline price.\n\nFourth, think about the neighbourhood as much as the property itself — proximity to schools, transport links, and green space will affect your resale value more than the kitchen worktops. Finally, be patient. The right property appears when it appears; the ones that stay on the market for months are usually overpriced or hiding a problem."],
            ['title' => 'How to Price Your Home to Sell in 30 Days', 'category' => 'Selling',
                'excerpt' => 'Setting the right asking price is the single most important decision you will make as a seller.',
                'body' => "Overpricing is the number one reason homes sit unsold. Buyers and their agents track days-on-market obsessively — a listing that has been available for two months carries a stigma that knocks 5–10% off what you can realistically achieve at closing.\n\nThe right approach: pull comparable sales (same neighbourhood, similar size, sold within the last 90 days) and price at or just below the median. If you receive multiple offers in the first week, you priced correctly. If the phone goes silent, your price is the problem, not the market.\n\nPresentation matters too: a professional photographer, a clean and decluttered interior, and an honest description will generate more qualified viewings than a higher asking price with poor photos."],
            ['title' => 'Why Lakeside Properties Hold Their Value', 'category' => 'Investing',
                'excerpt' => 'Water-adjacent real estate has historically outperformed the broader market. Here is why.',
                'body' => "Scarcity is the primary driver. There is a finite amount of lakeside or riverfront land, and supply cannot increase to meet demand the way it can for standard residential plots. This is especially true for protected shorelines like Lake Ohrid, where development restrictions keep new supply extremely limited.\n\nThe second driver is international demand. Lakeside properties attract buyers from multiple countries, which insulates values from purely local economic downturns. A recession that reduces domestic purchasing power may have little effect if foreign buyers remain interested.\n\nFinally, lifestyle trends post-2020 have accelerated demand for properties outside city centres with access to nature — a shift that real estate economists expect to be structural rather than temporary."],
            ['title' => 'The Investor\'s Guide to North Macedonia Real Estate', 'category' => 'Market Trends',
                'excerpt' => 'A practical overview of the market for buyers considering North Macedonia for the first time.',
                'body' => "North Macedonia offers some of the most accessible entry points for real estate investment in the Western Balkans. Apartment prices in Skopje's central districts range from €1,000–1,800 per square metre — significantly below comparable cities in Slovenia or Croatia.\n\nRental yields in the capital run between 5–7% gross for well-located residential units, driven partly by a growing expatriate community and a student population that prefers the private rental market. Ohrid and Struga command premium prices for tourist-season rentals.\n\nThe legal framework for foreign ownership is straightforward for EU and most non-EU nationals; a notary-registered purchase contract is the primary document. Property transfer tax is 2% of the assessed value. Always engage a local lawyer for due diligence — the cadastre system is reliable but checking for encumbrances requires access to official registers."],
            ['title' => 'Kitchen Upgrades That Add Real Resale Value', 'category' => 'Home Tips',
                'excerpt' => 'Not every renovation pays for itself. Focus on the upgrades buyers actually value.',
                'body' => "The kitchen is the room buyers scrutinise most. A tired kitchen can drop an offer by 10%; a smart renovation can recover that and more. But not all upgrades are equal.\n\nHigh-return moves: replace cabinet doors and hardware without touching the carcases (fraction of the cost of a full refit); install a new worktop in stone or engineered stone (buyers notice); upgrade the tap and add a decent extractor fan.\n\nLow-return moves: bespoke appliances that a buyer may not want; custom cabinetry that pushes the kitchen cost above neighbourhood comparables; removing walls to create an open-plan space without checking structural implications first.\n\nThe rule of thumb: keep the kitchen's total renovation cost below 10% of the home's value. Above that, you are spending for your own enjoyment, not for resale."],
            ['title' => "Understanding Rental Yields: A Beginner's Guide", 'category' => 'Investing',
                'excerpt' => 'Gross yield, net yield, and cash-on-cash return — what they mean and which one to trust.',
                'body' => "Rental yield is the annual rent expressed as a percentage of the property's purchase price. Gross yield ignores all costs; net yield deducts management fees, maintenance, insurance, and periods of vacancy.\n\nA 6% gross yield might become 3.5–4% net once you factor in a 10% management fee, an annual maintenance allowance of 1% of property value, building insurance, and two months of vacancy per year. Net yield is the number that actually tells you whether the investment makes sense.\n\nCash-on-cash return goes further: it measures your annual income as a percentage of the cash you personally invested (after mortgage financing). If you put 30% down and finance the rest, your cash-on-cash return will be higher than your net yield — which is the fundamental appeal of leveraged property investment."],
            ['title' => 'What to Inspect Before Signing Any Property Contract', 'category' => 'Buying',
                'excerpt' => 'Due diligence on a property purchase starts long before the notary appointment.',
                'body' => "Title check: confirm the seller is the registered owner and that the property is free of encumbrances, mortgages, or legal disputes. In North Macedonia this means requesting an extract from the cadastre (imotno-pravna evidencija). Do this yourself or through your lawyer — never take the seller's word for it.\n\nPhysical inspection: hire a structural engineer for anything over twenty years old or showing visible cracks, damp patches, or roof damage. The cost is €200–400 and can save you from a six-figure repair.\n\nPlanning permissions: verify that any extensions or conversions were built with the necessary permits. Unpermitted structures can prevent you from reselling and may attract fines.\n\nUtility arrears: ask for the last twelve months of utility bills and confirm there are no outstanding balances. These can transfer to the new owner."],
            ['title' => 'How Rising Interest Rates Are Reshaping the Market', 'category' => 'Market Trends',
                'excerpt' => 'Higher rates change who can buy, at what price, and how long properties sit unsold.',
                'body' => "When interest rates rise, the monthly payment on a given mortgage increases — which reduces how much a buyer can borrow for the same monthly outlay. A buyer who could afford a €150,000 mortgage at 2.5% may only qualify for €120,000 at 4.5%. This compression of purchasing power puts downward pressure on prices in rate-sensitive segments.\n\nThe effect is not uniform. Cash buyers — who account for a larger share of the market in the Balkans than in Western Europe — are unaffected. Luxury properties, where buyers are less leveraged, hold value better than mid-market apartments bought primarily with financing.\n\nFor sellers: expect longer days-on-market and more negotiation. For buyers: higher rates create an opportunity to negotiate prices that were previously out of reach. The key discipline is stress-testing your affordability at rates 2% higher than your current offer — because fixed-rate periods end."],
        ];

        foreach ($postData as $index => $data) {
            Post::create([
                'title' => $data['title'],
                'excerpt' => $data['excerpt'],
                'body' => $data['body'],
                'category' => $data['category'],
                'featured_image' => 'images/b-img-'.(($index % 5) + 1).'.jpg',
                'author_id' => $index % 2 === 0 ? $agent->id : $agent2->id,
                'published_at' => Carbon::now()->subDays($index * 7 + rand(0, 6)),
            ]);
        }
    }
}
