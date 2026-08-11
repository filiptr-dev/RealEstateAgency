<?php

namespace App\Http\Controllers;

use App\Models\User;

class AboutController extends Controller
{
    public function show()
    {
        // Plain read of a same-context (auth) User model — no cross-context boundary
        // here, so an inline Eloquent read is appropriate. If the About page ever
        // grows real domain logic (agent stats, etc.) this moves into an Action.
        $agents = User::where('role', 'agent')->orderBy('name')->get();

        return view('about', compact('agents'));
    }
}
