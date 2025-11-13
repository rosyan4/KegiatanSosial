<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $invitations = Invitation::with(['activity.category', 'activity.creator'])
            ->forUser($user->id)
            ->valid()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('invitations.index', compact('invitations'));
    }

    public function show(Invitation $invitation)
    {
        // Authorization check
        if ($invitation->user_id !== Auth::id()) {
            abort(403);
        }

        $invitation->load(['activity.category', 'activity.creator', 'activity.attendanceConfirmations']);

        return view('invitations.show', compact('invitation'));
    }

    public function accept(Request $request, Invitation $invitation)
    {
        if ($invitation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($invitation->accept()) {
            // Otomatis buat konfirmasi kehadiran
            $invitation->createAttendanceConfirmation();
            
            return redirect()->route('invitations.show', $invitation)
                ->with('success', 'Undangan berhasil diterima!');
        }

        return redirect()->back()->with('error', 'Tidak dapat menerima undangan. Mungkin sudah kedaluwarsa.');
    }

    public function decline(Request $request, Invitation $invitation)
    {
        if ($invitation->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'decline_reason' => 'nullable|string|max:500',
        ]);

        if ($invitation->decline($validated['decline_reason'] ?? null)) {
            return redirect()->route('invitations.show', $invitation)
                ->with('success', 'Undangan berhasil ditolak.');
        }

        return redirect()->back()->with('error', 'Tidak dapat menolak undangan. Mungkin sudah kedaluwarsa.');
    }
}