<form method="GET" action="{{ route('properties.index') }}" class="find-sec">
    <ul class="row">
        <li class="col-sm-3">
            <label>City</label>
            <input type="text" name="city" value="{{ request('city') }}" placeholder="e.g. New York" class="form-control">
        </li>
        <li class="col-sm-3">
            <label>Property Status</label>
            <select name="status" class="selectpicker">
                <option value="">Any</option>
                @foreach(\App\Enums\PropertyStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(request('status')===$status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </li>
        <li class="col-sm-3">
            <label>Property Type</label>
            <select name="type" class="selectpicker">
                <option value="">Any</option>
                @foreach(\App\Enums\PropertyType::cases() as $type)
                    <option value="{{ $type->value }}" @selected(request('type')===$type->value)>{{ $type->label() }}</option>
                @endforeach
            </select>
        </li>
        <li class="col-sm-3">
            <label>Bedrooms (min)</label>
            <select name="bedrooms" class="selectpicker">
                <option value="">Any</option>
                @for($i=1; $i<=6; $i++)
                    <option value="{{ $i }}" @selected((int)request('bedrooms')===$i)>{{ $i }}+</option>
                @endfor
            </select>
        </li>
        <li class="col-sm-3">
            <label>Bathrooms (min)</label>
            <select name="bathrooms" class="selectpicker">
                <option value="">Any</option>
                @for($i=1; $i<=5; $i++)
                    <option value="{{ $i }}" @selected((int)request('bathrooms')===$i)>{{ $i }}+</option>
                @endfor
            </select>
        </li>
        <li class="col-sm-3">
            <label>Min Price (€)</label>
            <input type="number" min="0" step="1" name="min_price" value="{{ request('min_price') }}" class="form-control">
        </li>
        <li class="col-sm-3">
            <label>Max Price (€)</label>
            <input type="number" min="0" step="1" name="max_price" value="{{ request('max_price') }}" class="form-control">
        </li>
        <li class="col-sm-3 search" style="padding-top:24px;">
            <button type="submit" class="btn">Search</button>
        </li>
    </ul>
</form>
