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

        return view('home', compact('featured', 'latest'));
    }
}
