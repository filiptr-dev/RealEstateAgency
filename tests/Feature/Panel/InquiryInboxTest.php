<?php

namespace Tests\Feature\Panel;

use App\Models\ContactSubmission;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryInboxTest extends TestCase
{
    use RefreshDatabase;

    public function test_agent_sees_only_own_property_inquiries(): void
    {
        $agent = User::factory()->agent()->create();
        $other = User::factory()->agent()->create();

        $mine = Property::factory()->create(['agent_id' => $agent->id]);
        $theirs = Property::factory()->create(['agent_id' => $other->id]);

        ContactSubmission::factory()->create(['property_id' => $mine->id, 'subject' => 'MINE-SUBJ']);
        ContactSubmission::factory()->create(['property_id' => $theirs->id, 'subject' => 'THEIR-SUBJ']);

        $this->actingAs($agent)->get('/panel/inquiries')
            ->assertOk()
            ->assertSee('MINE-SUBJ')
            ->assertDontSee('THEIR-SUBJ');
    }

    public function test_admin_sees_all(): void
    {
        $admin = User::factory()->admin()->create();
        $property = Property::factory()->create();
        ContactSubmission::factory()->create(['property_id' => $property->id, 'subject' => 'ANY-SUBJ']);

        $this->actingAs($admin)->get('/panel/inquiries')->assertSee('ANY-SUBJ');
    }

    public function test_opening_marks_as_read(): void
    {
        $admin = User::factory()->admin()->create();
        $submission = ContactSubmission::factory()->create(['read_at' => null]);

        $this->actingAs($admin)->get(route('panel.inquiries.show', $submission))->assertOk();

        $this->assertNotNull($submission->fresh()->read_at);
    }

    public function test_agent_403_on_others_inquiry(): void
    {
        $agent = User::factory()->agent()->create();
        $other = User::factory()->agent()->create();
        $property = Property::factory()->create(['agent_id' => $other->id]);
        $submission = ContactSubmission::factory()->create(['property_id' => $property->id]);

        $this->actingAs($agent)->get(route('panel.inquiries.show', $submission))->assertForbidden();
    }
}
