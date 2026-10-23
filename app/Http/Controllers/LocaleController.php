<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'locale' => ['required', 'in:fr,en'],
        ]);

        $request->session()->put('locale', $validated['locale']);

        return back();
    }
}
