<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactSubmissionRequest;
use App\Models\ContactSubmission;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact.create');
    }

    public function store(StoreContactSubmissionRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()?->id;

        ContactSubmission::create($data);

        return redirect()
            ->route('contact.create')
            ->with('status', 'Thanks — we received your message and will get back to you shortly.');
    }
}
