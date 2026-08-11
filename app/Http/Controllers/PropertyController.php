<?php

namespace App\Http\Controllers;

use App\Http\Requests\PropertySearchRequest;
use App\Models\Property;

class PropertyController extends Controller
{
    public function index(PropertySearchRequest $request)
    {
        $properties = Property::query()
            ->published()
            ->filter($request)
            ->with('photos', 'agent')
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $mapProperties = Property::published()
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get(['id', 'title', 'price_cents', 'lat', 'lng', 'slug'])
            ->map(fn ($p) => [
                'lat' => (float) $p->lat,
                'lng' => (float) $p->lng,
                'title' => $p->title,
                'price' => $p->priceFormatted,
                'url' => route('properties.show', $p),
            ]);

        return view('properties.index', compact('properties', 'mapProperties'));
    }

    public function show(Property $property)
    {
        abort_if($property->published_at === null || $property->published_at->isFuture(), 404);
        $property->load('photos', 'agent');
        $recent = Property::published()
            ->where('id', '!=', $property->id)
            ->with('photos')
            ->latest('published_at')
            ->limit(4)
            ->get();

        return view('properties.show', compact('property', 'recent'));
    }
}
