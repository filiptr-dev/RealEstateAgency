@csrf
<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title', $property->title) }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="5" class="form-control" required>{{ old('description', $property->description) }}</textarea>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <label>Type</label>
                <select name="type" class="form-control" required>
                    @foreach(\App\Enums\PropertyType::cases() as $t)
                        <option value="{{ $t->value }}" @selected(old('type', $property->type?->value)===$t->value)>{{ $t->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    @foreach(\App\Enums\PropertyStatus::cases() as $s)
                        <option value="{{ $s->value }}" @selected(old('status', $property->status?->value)===$s->value)>{{ $s->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <label>Price (€)</label>
                <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $property->price_cents ? $property->price_cents/100 : '') }}" class="form-control" required>
            </div>
            <div class="col-sm-4">
                <label>Bedrooms</label>
                <input type="number" min="0" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" class="form-control" required>
            </div>
            <div class="col-sm-4">
                <label>Bathrooms</label>
                <input type="number" min="0" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label>Size (acres)</label>
            <input type="number" step="0.01" min="0" name="size_acres" value="{{ old('size_acres', $property->size_acres) }}" class="form-control" required>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <label>Address</label>
                <input type="text" name="address" value="{{ old('address', $property->address) }}" class="form-control" required>
            </div>
            <div class="col-sm-3">
                <label>Zip</label>
                <input type="text" name="zip" value="{{ old('zip', $property->zip) }}" class="form-control" required>
            </div>
            <div class="col-sm-3">
                <label>City</label>
                <input type="text" name="city" value="{{ old('city', $property->city) }}" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <label>Country</label>
                <input type="text" name="country" value="{{ old('country', $property->country) }}" class="form-control" required>
            </div>
            <div class="col-sm-6">
                <label>Area (optional)</label>
                <input type="text" name="area" value="{{ old('area', $property->area) }}" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label>Features (one per line)</label>
            @php
                $features = old('features_text', is_array($property->features ?? null) ? implode("\n", $property->features) : '');
            @endphp
            <textarea name="features_text" rows="4" class="form-control">{{ $features }}</textarea>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $property->is_featured))> Featured</label>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="published" value="1" @checked(old('published', (bool) $property->published_at))> Published</label>
        </div>

        @if($property->exists && $property->photos->isNotEmpty())
            <h4>Existing photos</h4>
            <ul style="list-style:none;padding:0;">
                @foreach($property->photos as $photo)
                    <li style="margin-bottom:10px;">
                        <img src="{{ asset('storage/'.$photo->path) }}" style="width:100%;">
                        <label><input type="checkbox" name="delete_photo_ids[]" value="{{ $photo->id }}"> Delete</label>
                        @if($photo->is_cover) <span>(cover)</span> @endif
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="form-group">
            <label>Add photos</label>
            <input type="file" name="photos[]" multiple accept="image/*">
            <small>New photo index for cover (0-based):</small>
            <input type="number" min="0" name="cover_index" value="{{ old('cover_index') }}" class="form-control">
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary" style="margin-top:20px;">Save</button>
<a href="{{ route('panel.properties.index') }}" class="btn">Cancel</a>
