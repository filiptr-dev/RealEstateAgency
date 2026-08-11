<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->orderBy('name')->paginate(25);

        return view('panel.admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('panel.admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(array_column(UserRole::cases(), 'value'))],
        ]);

        $user->update(['role' => $data['role']]);

        return redirect()->route('panel.admin.users.index')->with('status', 'User updated.');
    }
}
