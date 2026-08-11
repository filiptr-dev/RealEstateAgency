<?php

namespace Tests\Feature\Public;

use App\Models\ContactSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_anonymous_submit_persists_with_null_user(): void
    {
        $this->post('/contact', [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'message' => 'Hello there',
        ])->assertRedirect(route('contact.create'));

        $s = ContactSubmission::firstOrFail();
        $this->assertNull($s->user_id);
        $this->assertSame('Alice', $s->name);
    }

    public function test_authenticated_submit_records_user_id(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/contact', [
            'name' => $user->name,
            'email' => $user->email,
            'message' => 'From logged-in',
        ])->assertRedirect(route('contact.create'));

        $this->assertSame($user->id, ContactSubmission::firstOrFail()->user_id);
    }

    public function test_missing_fields_return_validation_error(): void
    {
        $this->from('/contact')->post('/contact', [])->assertRedirect('/contact')->assertSessionHasErrors(['name', 'email', 'message']);
    }
}
