<?php

namespace App\Http\Controllers;

use App\Models\User;

class AgentsController extends Controller
{
    public function index()
    {
        // Same-context (auth) User read — reuses the exact query pattern the
        // About page established (AboutController::show). Kept inline rather
        // than extracted into a shared query, because two callers of a plain
        // Eloquent read don't yet justify a new indirection.
        $agents = User::where('role', 'agent')->orderBy('name')->get();

        return view('agents', compact('agents'));
    }
}
