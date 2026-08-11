<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $propertiesQuery = Property::query();
        if ($user->isAgent()) {
            $propertiesQuery->where('agent_id', $user->id);
        }
        $counts = [
            'properties' => (clone $propertiesQuery)->count(),
            'featured' => (clone $propertiesQuery)->where('is_featured', true)->count(),
        ];

        return view('panel.dashboard', compact('counts'));
    }
}
