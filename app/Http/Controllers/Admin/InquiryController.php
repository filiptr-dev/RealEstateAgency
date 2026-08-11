<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = ContactSubmission::query()->with('property', 'user');
        if ($user->isAgent()) {
            $query->whereHas('property', fn ($q) => $q->where('agent_id', $user->id));
        }
        $submissions = $query->latest()->paginate(20);

        return view('panel.inquiries.index', compact('submissions'));
    }

    public function show(Request $request, ContactSubmission $submission)
    {
        $user = $request->user();
        if ($user->isAgent()) {
            abort_unless(
                $submission->property && $submission->property->agent_id === $user->id,
                403
            );
        }

        if ($submission->read_at === null) {
            $submission->update(['read_at' => now()]);
        }

        return view('panel.inquiries.show', compact('submission'));
    }
}
