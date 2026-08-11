<?php

namespace App\Http\Controllers;

class BlogController extends Controller
{
    // v1 is deliberately static — no Post model, no migration, no CMS. See
    // plan `2026-08-11-batch-2-extension-items-10-14.md` § "Item 13 — Blog +
    // Single Post" for the scope call. Route names (`blog.index`, `blog.show`)
    // are used everywhere so a future dynamic upgrade is a URL-compatible swap.
    public function index()
    {
        return view('blog.index');
    }

    public function show()
    {
        // Single fixed demo post — the template ships filler for both pages,
        // and the plan explicitly says "a single hard-coded slug is enough
        // for the demo link; do not build a real slug-routing table".
        return view('blog.show');
    }
}
