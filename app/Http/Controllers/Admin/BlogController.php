<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Post::with('author')->latest('published_at');
        if ($user->isAgent()) {
            $query->where('author_id', $user->id);
        }
        $posts = $query->paginate(20);

        return view('panel.blog.index', compact('posts'));
    }
}
