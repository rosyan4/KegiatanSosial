<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function show(User $user)
    {
        $user->load(['createdActivities.category', 'attendanceConfirmations.activity']);

        $attendanceStats = [
            'total' => $user->attendanceConfirmations()->count(),
            'hadir' => $user->attendanceConfirmations()->hadir()->count(),
            'tidak_hadir' => $user->attendanceConfirmations()->tidakHadir()->count(),
        ];

        return view('admin.users.show', compact('user', 'attendanceStats'));
    }

    public function index(Request $request)
    {
        $query = User::withCount(['createdActivities', 'attendanceConfirmations']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }

        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:warga,admin',
            'address' => 'nullable|string',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:warga,admin',
            'address' => 'nullable|string',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Pengguna berhasil {$status}.");
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $request->users)->get();

        switch ($request->action) {
            case 'activate':
                $users->each->update(['is_active' => true]);
                $message = 'Pengguna berhasil diaktifkan.';
                break;
            case 'deactivate':
                $users->each->update(['is_active' => false]);
                $message = 'Pengguna berhasil dinonaktifkan.';
                break;
            case 'delete':
                $users->each->delete();
                $message = 'Pengguna berhasil dihapus.';
                break;
        }

        return back()->with('success', $message);
    }
}