<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityProposal;
use App\Models\Activity;
use App\Models\ActivityCategory;
use Illuminate\Http\Request;

class ActivityProposalController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityProposal::with(['proposer', 'reviewer']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('proposed_date')) {
            $query->whereDate('proposed_date', $request->proposed_date);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $proposals = $query->latest()->paginate(10);

        $stats = [
            'pending' => ActivityProposal::pending()->count(),
            'under_review' => ActivityProposal::underReview()->count(),
            'approved' => ActivityProposal::approved()->count(),
            'rejected' => ActivityProposal::rejected()->count(),
        ];

        return view('admin.proposals.index', compact('proposals', 'stats'));
    }

    public function review(ActivityProposal $proposal)
    {
        $categories = ActivityCategory::active()->get();

        return view('admin.proposals.review', compact('proposal', 'categories'));
    }

    public function show(ActivityProposal $proposal)
    {
        $proposal->load(['proposer', 'reviewer', 'convertedActivity']);

        return view('admin.proposals.show', compact('proposal'));
    }

    public function markUnderReview(ActivityProposal $proposal)
    {
        $proposal->markAsUnderReview(auth()->id());

        return back()->with('success', 'Proposal ditandai sedang direview.');
    }

    public function approve(Request $request, ActivityProposal $proposal)
    {
        $request->validate([
            'admin_notes' => 'nullable|string',
            'convert_to_activity' => 'boolean',
            'category_id' => 'required_if:convert_to_activity,true|exists:activity_categories,id',
            'start_date' => 'required_if:convert_to_activity,true|date',
            'end_date' => 'required_if:convert_to_activity,true|date|after:start_date',
            'location' => 'required_if:convert_to_activity,true|string',
        ]);

        $proposal->approve(auth()->id(), $request->admin_notes);

        if ($request->convert_to_activity) {
            $this->convertToActivity($proposal, $request->all());
        }

        return redirect()->route('admin.proposals.index')
            ->with('success', 'Proposal berhasil disetujui.');
    }

    public function reject(Request $request, ActivityProposal $proposal)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $proposal->reject(auth()->id(), $request->rejection_reason);

        return redirect()->route('admin.proposals.index')
            ->with('success', 'Proposal berhasil ditolak.');
    }

    public function requestRevision(Request $request, ActivityProposal $proposal)
    {
        $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $proposal->requestRevision(auth()->id(), $request->admin_notes);

        return redirect()->route('admin.proposals.index')
            ->with('success', 'Permintaan revisi berhasil dikirim.');
    }

    // PERBAIKAN: Method convertToActivity yang lebih robust
    private function convertToActivity(ActivityProposal $proposal, $data)
    {
        $activity = Activity::create([
            'title' => $proposal->title,
            'description' => $proposal->description,
            'category_id' => $data['category_id'],
            'type' => 'umum',
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'location' => $data['location'],
            'max_participants' => $proposal->estimated_participants,
            'requires_attendance_confirmation' => true,
            'status' => 'published',
            'created_by' => auth()->id(),
            'proposal_id' => $proposal->id,
        ]);

        // Notify users about new activity
        $users = \App\Models\User::warga()->active()->get();
        foreach ($users as $user) {
            \App\Models\Notification::createNewActivityNotification($user->id, $activity->id);
        }

        return $activity;
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,mark_review',
            'proposals' => 'required|array',
            'proposals.*' => 'exists:activity_proposals,id',
        ]);

        $proposals = ActivityProposal::whereIn('id', $request->proposals)->get();

        foreach ($proposals as $proposal) {
            switch ($request->action) {
                case 'approve':
                    $proposal->approve(auth()->id());
                    break;
                case 'reject':
                    $proposal->reject(auth()->id(), 'Aksi bulk');
                    break;
                case 'mark_review':
                    $proposal->markAsUnderReview(auth()->id());
                    break;
            }
        }

        return back()->with('success', 'Aksi bulk berhasil dilakukan.');
    }
}