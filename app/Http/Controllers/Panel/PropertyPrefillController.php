<?php

namespace App\Http\Controllers\Panel;

use App\Actions\Property\PrefillPropertyFromUrl;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyPrefillController extends Controller
{
    public function __construct(private PrefillPropertyFromUrl $prefill) {}

    public function fetch(Request $request): JsonResponse
    {
        // Built-in `url` rule — spec requires Laravel validation over regex.
        $data = $request->validate(['url' => ['required', 'url']]);

        return response()->json($this->prefill->handle($data['url']));
    }
}
