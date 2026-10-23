<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function create()
    {
        return view('claims.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'claimant_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
        ], [], [
            'claimant_name' => __('site.claimant_attribute'),
            'email' => __('site.email_attribute'),
            'description' => __('site.lost_description_attribute'),
        ]);

        $claim = Claim::create($validated);

        return redirect()
            ->route('claims.create')
            ->with('success', __('site.report_saved', ['id' => $claim->id]));
    }

    public function link(Request $request, Claim $claim)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
        ]);

        $claim->update(['item_id' => Item::findOrFail($validated['item_id'])->id]);

        return response()->json(['message' => __('site.claim_linked'), 'claim_id' => $claim->id]);
    }
}
