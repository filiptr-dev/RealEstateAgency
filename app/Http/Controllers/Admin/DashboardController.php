<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\Post;
use App\Models\Property;
use App\Models\User;
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
            // ContactSubmission has read_at (nullable timestamp), not a `read` boolean.
            // Open inquiries = unread = whereNull('read_at').
            'open_inquiries' => ContactSubmission::whereNull('read_at')->count(),
            'published_posts' => $user->isAgent()
                ? Post::published()->where('author_id', $user->id)->count()
                : Post::published()->count(),
        ];

        if ($user->isAdmin()) {
            $counts['users'] = User::count();
        }

        $recentProperties = (clone $propertiesQuery)->latest()->take(5)->get();
        $recentInquiries = ContactSubmission::latest()->take(5)->get();

        return view('panel.dashboard', compact('counts', 'recentProperties', 'recentInquiries'));
    }
}
