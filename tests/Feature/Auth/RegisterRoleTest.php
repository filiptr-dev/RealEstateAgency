<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_user_with_role_user(): void
    {
        $this->post('/register', [
            'name' => 'New Guy',
            'email' => 'new@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $user = User::where('email', 'new@example.com')->firstOrFail();
        $this->assertSame(UserRole::User, $user->role);
    }
}
