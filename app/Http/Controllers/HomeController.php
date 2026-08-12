<?php

namespace App\Http\Controllers;

use App\Models\Property;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Property::published()
            ->where('is_featured', true)
            ->with('photos')
            ->latest('published_at')
            ->limit(6)
            ->get();

        $latest = Property::published()
            ->with('photos')
            ->latest('published_at')
            ->limit(6)
            ->get();

        // City-centre fallback lookup for properties with no stored lat/lng.
        // Hardcoded — deliberately no external geocoding API (plan constraint).
        $cityCentres = [
            'Skopje' => ['lat' => 41.9981, 'lng' => 21.4254],
            'Ohrid' => ['lat' => 41.1231, 'lng' => 20.8016],
            'Bitola' => ['lat' => 41.0297, 'lng' => 21.3291],
            'Tetovo' => ['lat' => 42.0100, 'lng' => 20.9713],
            'Kumanovo' => ['lat' => 42.1322, 'lng' => 21.7144],
            'Struga' => ['lat' => 41.1786, 'lng' => 20.6783],
            'Prilep' => ['lat' => 41.3461, 'lng' => 21.5495],
            'Kavadarci' => ['lat' => 41.4328, 'lng' => 22.0086],
            'Veles' => ['lat' => 41.7146, 'lng' => 21.7745],
            'Strumica' => ['lat' => 41.4378, 'lng' => 22.6431],
            'Thessaloniki' => ['lat' => 40.6401, 'lng' => 22.9444],
            'Sofia' => ['lat' => 42.6977, 'lng' => 23.3219],
        ];

        $mapProperties = Property::published()
            ->get(['id', 'title', 'price_cents', 'lat', 'lng', 'slug', 'city'])
            ->map(function ($p) use ($cityCentres) {
                $lat = $p->lat !== null ? (float) $p->lat : null;
                $lng = $p->lng !== null ? (float) $p->lng : null;

                if ($lat === null || $lng === null) {
                    $centre = $cityCentres[$p->city] ?? null;
                    if ($centre === null) {
                        return null; // skip — no coords and unrecognised city
                    }
                    $lat = $centre['lat'];
                    $lng = $centre['lng'];
                }

                return [
                    'lat' => $lat,
                    'lng' => $lng,
                    'title' => $p->title,
                    'price' => $p->priceFormatted,
                    'url' => route('properties.show', $p),
                ];
            })
            ->filter()
            ->values();

        return view('home', compact('featured', 'latest', 'mapProperties'));
    }
}
