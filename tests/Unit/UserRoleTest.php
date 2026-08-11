<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    public function test_role_helpers(): void
    {
        $admin = new User(['role' => UserRole::Admin->value]);
        $admin->setRawAttributes(['role' => 'admin'], true);
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isAgent());
        $this->assertFalse($admin->isRegularUser());

        $agent = new User;
        $agent->setRawAttributes(['role' => 'agent'], true);
        $this->assertTrue($agent->isAgent());
        $this->assertFalse($agent->isAdmin());

        $user = new User;
        $user->setRawAttributes(['role' => 'user'], true);
        $this->assertTrue($user->isRegularUser());
        $this->assertFalse($user->isAdmin());
    }
}
