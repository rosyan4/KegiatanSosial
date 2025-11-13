<?php

namespace App\Http\Controllers;

use App\Models\ActivityProposal;
use App\Http\Requests\StoreActivityProposalRequest;
use App\Http\Requests\UpdateActivityProposalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityProposalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $proposals = ActivityProposal::with(['reviewer'])
            ->byUser($user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('proposals.index', compact('proposals'));
    }

    public function create()
    {
        return view('proposals.create');
    }

    public function store(StoreActivityProposalRequest $request)
    {
        $validated = $request->validated();

        $proposal = ActivityProposal::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'objectives' => $validated['objectives'],
            'benefits' => $validated['benefits'],
            'proposed_date' => $validated['proposed_date'],
            'proposed_location' => $validated['proposed_location'],
            'estimated_participants' => $validated['estimated_participants'],
            'estimated_budget' => $validated['estimated_budget'],
            'required_support' => $validated['required_support'],
            'proposed_by' => Auth::id(),
        ]);

        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Usulan kegiatan berhasil diajukan!');
    }

    public function show(ActivityProposal $proposal)
    {
        // Authorization
        if ($proposal->proposed_by !== Auth::id()) {
            abort(403);
        }

        $proposal->load(['reviewer', 'convertedActivity']);

        return view('proposals.show', compact('proposal'));
    }

    public function edit(ActivityProposal $proposal)
    {
        // Authorization
        if ($proposal->proposed_by !== Auth::id()) {
            abort(403);
        }

        if (!$proposal->canBeEditedByProposer()) {
            return redirect()->route('proposals.show', $proposal)
                ->with('error', 'Usulan tidak dapat diedit karena sudah direview.');
        }

        return view('proposals.edit', compact('proposal'));
    }

    public function update(UpdateActivityProposalRequest $request, ActivityProposal $proposal)
    {
        // Authorization
        if ($proposal->proposed_by !== Auth::id()) {
            abort(403);
        }

        if (!$proposal->canBeEditedByProposer()) {
            return redirect()->route('proposals.show', $proposal)
                ->with('error', 'Usulan tidak dapat diedit karena sudah direview.');
        }

        $validated = $request->validated();

        $proposal->update($validated);

        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Usulan berhasil diperbarui!');
    }
}