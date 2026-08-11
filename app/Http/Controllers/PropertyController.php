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

        return view('properties.index', compact('properties'));
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
