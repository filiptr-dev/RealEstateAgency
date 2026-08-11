<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Property\SyncPropertyPhotos;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct(private SyncPropertyPhotos $syncPhotos) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $query = Property::query()->with('photos', 'agent');
        if ($user->isAgent()) {
            $query->where('agent_id', $user->id);
        }
        $properties = $query->latest()->paginate(15);

        return view('panel.properties.index', compact('properties'));
    }

    public function create()
    {
        $this->authorize('create', Property::class);

        return view('panel.properties.create', ['property' => new Property]);
    }

    public function store(StorePropertyRequest $request)
    {
        $data = $request->validated();
        $property = new Property;
        $this->fill($property, $data, $request);
        $property->save();

        $coverIndex = $request->filled('cover_index') ? (int) $request->input('cover_index') : null;

        $skipped = $this->syncPhotos->handle(
            $property,
            $request->file('photos', []),
            $coverIndex,
            (array) $request->input('delete_photo_ids', []),
            (array) $request->input('photo_urls', []),
        );

        return redirect()->route('panel.properties.index')
            ->with('status', 'Property created.')
            ->with('warning', $this->skippedFlash($skipped));
    }

    public function edit(Property $property)
    {
        $this->authorize('update', $property);
        $property->load('photos');

        return view('panel.properties.edit', compact('property'));
    }

    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $data = $request->validated();
        $this->fill($property, $data, $request);
        $property->save();

        $coverIndex = $request->filled('cover_index') ? (int) $request->input('cover_index') : null;

        $skipped = $this->syncPhotos->handle(
            $property,
            $request->file('photos', []),
            $coverIndex,
            (array) $request->input('delete_photo_ids', []),
            (array) $request->input('photo_urls', []),
        );

        return redirect()->route('panel.properties.index')
            ->with('status', 'Property updated.')
            ->with('warning', $this->skippedFlash($skipped));
    }

    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);
        $property->delete();

        return redirect()->route('panel.properties.index')->with('status', 'Property deleted.');
    }

    /**
     * @param  array<int, string>  $skipped
     */
    private function skippedFlash(array $skipped): ?string
    {
        if (empty($skipped)) {
            return null;
        }

        return 'Could not fetch '.count($skipped).' photo URL(s) — they were skipped.';
    }

    private function fill(Property $property, array $data, Request $request): void
    {
        $property->fill([
            'title' => $data['title'],
            'description' => $data['description'],
            'type' => $data['type'],
            'status' => $data['status'],
            'price_cents' => (int) round(((float) $data['price']) * 100),
            'bedrooms' => (int) $data['bedrooms'],
            'bathrooms' => (int) $data['bathrooms'],
            'size_acres' => $data['size_acres'],
            'address' => $data['address'],
            'zip' => $data['zip'],
            'city' => $data['city'],
            'country' => $data['country'],
            'area' => $data['area'] ?? null,
            'features' => $data['features'] ?? [],
            'nearby' => $data['nearby'] ?? [],
            'is_featured' => (bool) ($data['is_featured'] ?? false),
        ]);

        // Only admins can (re)assign an arbitrary agent; agents own their own creations.
        if ($request->user()->isAdmin()) {
            $property->agent_id = $request->input('agent_id') ?: ($property->agent_id ?: $request->user()->id);
        } elseif (! $property->exists || $property->agent_id === null) {
            $property->agent_id = $request->user()->id;
        }

        if (($data['published'] ?? false) && ! $property->published_at) {
            $property->published_at = now();
        } elseif (! ($data['published'] ?? false)) {
            $property->published_at = null;
        }
    }
}
