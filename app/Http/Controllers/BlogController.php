<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::published()->latest('published_at')->paginate(6);
        $categories = Post::published()->select('category')->distinct()->pluck('category');
        $recentPosts = Post::published()->latest('published_at')->take(3)->get();

        return view('blog.index', compact('posts', 'categories', 'recentPosts'));
    }

    public function show(Post $post)
    {
        abort_if($post->published_at === null || $post->published_at->isFuture(), 404);
        $post->load('author', 'comments.user');
        $categories = Post::published()->select('category')->distinct()->pluck('category');
        $recentPosts = Post::published()->where('id', '!=', $post->id)->latest('published_at')->take(3)->get();

        return view('blog.show', compact('post', 'categories', 'recentPosts'));
    }

    public function storeComment(Request $request, Post $post)
    {
        $request->validate(['body' => 'required|string|min:2|max:2000']);
        $post->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return back()->with('success', 'Comment posted.');
    }
}
