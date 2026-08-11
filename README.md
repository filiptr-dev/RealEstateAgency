# Real Estate Agency

A full-featured real estate listing platform for a boutique agency operating across North Macedonia and the wider Balkans. Visitors can browse for-sale and for-rent listings, filter by city, price, and property type, view detailed listings with a photo gallery, and contact the agent handling a property. Agents manage their own portfolios; admins oversee the full catalog, all agents, and every incoming enquiry.

## Features

- **Public catalog** — browse and filter properties by city, type (sale / rent), status (apartment, house, villa, land), bedroom / bathroom counts, and price range.
- **Listing detail** — full description, photo gallery with cover image, features list, nearby points of interest, and the assigned agent's contact card.
- **Contact submissions** — visitors send an enquiry against a listing; the assigned agent and admins are notified.
- **Agent dashboard** — each agent manages the properties assigned to them (create, edit, publish, upload photos).
- **Admin dashboard** — full oversight of users, properties, and contact submissions across the platform.
- **Role-based auth** — three roles (`admin`, `agent`, `user`) with route- and policy-level protection.
- **EUR pricing** — prices stored as integer cents to avoid float rounding; formatted `€` on display.
- **Soft deletes** on properties so removed listings can be restored and slugs stay unique across the trash.

## Tech Stack

- **Laravel 12** on PHP 8.3+
- **Blade** views with **Tailwind CSS** and **Vite**
- **MySQL** via **Laravel Sail** (Docker)
- **Pest** for tests
- **Spatie MediaLibrary conventions** applied directly to `storage/app/public/properties/{id}/` (no separate media package required)

## Roles and Permissions

| Role  | Can do                                                                                    |
|-------|-------------------------------------------------------------------------------------------|
| admin | Manage all users, all properties, all contact submissions; assign agents to listings.     |
| agent | Manage only the properties assigned to them; view enquiries against their own listings.   |
| user  | Browse listings, submit contact enquiries, manage their own profile.                      |

## Prerequisites

- Docker and Docker Compose (Sail ships its own PHP / MySQL / Redis containers)
- Node.js 20+ and npm (for Vite asset builds)
- Git

No local PHP or MySQL installation is required — everything runs inside Sail.

## Getting Started

```bash
# 1. Clone and enter the project
git clone <this-repo> real-estate-agency-app
cd real-estate-agency-app

# 2. Install PHP dependencies (uses a throwaway container so no local PHP needed)
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

# 3. Environment
cp .env.example .env

# 4. Bring up the Sail stack (app, MySQL, Redis)
./vendor/bin/sail up -d

# 5. Generate the app key
./vendor/bin/sail artisan key:generate

# 6. Run migrations and seed demo data (15 listings + test accounts + photos)
./vendor/bin/sail artisan migrate --seed

# 7. Publish the storage symlink so uploaded photos are web-accessible
./vendor/bin/sail artisan storage:link

# 8. Install and build front-end assets
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

The app is now available at [http://localhost](http://localhost).

## Test Accounts

The seeder provisions four accounts. All use the password `password` (the second agent included for portfolio-diversity testing).

| Role  | Email                | Password | Notes                                      |
|-------|----------------------|----------|--------------------------------------------|
| admin | admin@example.com    | password | Full platform access                       |
| agent | agent@example.com    | password | Jane Smith — residential + commercial      |
| agent | agent2@example.com   | password | Marko Petrov — villas + lakeside           |
| user  | user@example.com     | password | Regular visitor account                    |

Re-running `sail artisan migrate:fresh --seed` (or just `sail artisan db:seed`) is idempotent: properties, photos, and contact submissions are wiped and re-created; users are upserted so their IDs and passwords stay stable.

## Running Tests

```bash
./vendor/bin/sail test
```

Tests run against an in-memory SQLite database configured in `phpunit.xml` — they never touch your development MySQL data.

## Project Structure

```
app/
├── Enums/                  # PropertyType, PropertyStatus, UserRole
├── Http/
│   ├── Controllers/        # Thin controllers, delegate to models/queries
│   ├── Middleware/         # Role-guarding middleware
│   └── Requests/           # Form request validation
├── Models/                 # Property, PropertyPhoto, User, ContactSubmission
└── Policies/               # Property + submission policies
database/
├── factories/              # Test-data factories
├── migrations/             # Schema
└── seeders/DatabaseSeeder  # 15 realistic demo listings + 4 test users
resources/
├── views/                  # Blade templates (public + dashboards)
└── css/                    # Tailwind entry
public/images/              # Static template imagery (bundled with the app)
storage/app/public/properties/{id}/   # Uploaded and seeded listing photos
tests/
├── Feature/                # HTTP + auth + role tests
└── Unit/                   # Model/enum tests
```

## Photo Storage

Listing photos live under `storage/app/public/properties/{property_id}/` and are served via the public storage symlink at `public/storage/properties/{property_id}/...`. The seeder copies a rotating selection of bundled template images from `public/images/` into each seeded property's directory so the gallery has real content out of the box — no external asset fetch is performed during seeding.

## License

Proprietary — internal to the agency. Do not redistribute.
