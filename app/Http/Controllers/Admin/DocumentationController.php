<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentationController extends Controller
{
    public function index()
    {
        $documentations = Documentation::with(['activity', 'creator'])
            ->latest()
            ->paginate(9);

        return view('admin.documentations.index', compact('documentations'));
    }

    public function create()
    {
        $activities = Activity::all();
        return view('admin.documentations.create', compact('activities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
        ]);

        // Upload featured image
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('documentations/featured', 'public');
        }

        // Upload gallery images
        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('documentations/gallery', 'public');
            }
            $validated['gallery_images'] = $galleryPaths;
        }

        $validated['created_by'] = auth()->id();

        Documentation::create($validated);

        return redirect()->route('admin.documentations.index')
            ->with('success', 'Dokumentasi berhasil dibuat.');
    }

    public function show(Documentation $documentation)
    {
        $documentation->load(['activity', 'creator']);
        return view('admin.documentations.show', compact('documentation'));
    }

    public function edit(Documentation $documentation)
    {
        $activities = Activity::all();
        return view('admin.documentations.edit', compact('documentation', 'activities'));
    }

    public function update(Request $request, Documentation $documentation)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
        ]);

        // Update featured image
        if ($request->hasFile('featured_image')) {
            if ($documentation->featured_image) {
                Storage::disk('public')->delete($documentation->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')
                ->store('documentations/featured', 'public');
        }

        // Update gallery images (replace only if new upload)
        if ($request->hasFile('gallery_images')) {
            // Hapus file lama
            if ($documentation->gallery_images) {
                foreach ($documentation->gallery_images as $img) {
                    Storage::disk('public')->delete($img);
                }
            }
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('documentations/gallery', 'public');
            }
            $validated['gallery_images'] = $galleryPaths;
        }

        $documentation->update($validated);

        return redirect()->route('admin.documentations.index')
            ->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy(Documentation $documentation)
    {
        // Hapus featured image
        if ($documentation->featured_image) {
            Storage::disk('public')->delete($documentation->featured_image);
        }

        // Hapus gallery images
        if ($documentation->gallery_images) {
            foreach ($documentation->gallery_images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $documentation->delete();

        return redirect()->route('admin.documentations.index')
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }
}
