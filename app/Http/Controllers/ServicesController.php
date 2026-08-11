<?php

namespace App\Http\Controllers;

use App\Models\Property;

class ServicesController extends Controller
{
    public function show()
    {
        // Template's 05-Services.html includes a `.properties` sub-section — per the
        // batch-2 extension plan, reuse the same-context Eloquent query the home
        // page already uses rather than shipping hard-coded template placeholders.
        $latest = Property::published()
            ->with('photos')
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('services', compact('latest'));
    }
}
