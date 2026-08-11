<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_lands_on_panel(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->get('/dashboard')->assertRedirect(route('panel.dashboard'));
    }

    public function test_agent_dashboard_lands_on_panel(): void
    {
        $agent = User::factory()->agent()->create();
        $this->actingAs($agent)->get('/dashboard')->assertRedirect(route('panel.dashboard'));
    }

    public function test_regular_user_dashboard_is_ok(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/dashboard')->assertOk();
    }
}
