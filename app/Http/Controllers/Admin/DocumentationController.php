<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentationController extends Controller
{
    public function index(Request $request)
    {
        $query = Documentation::with(['activity', 'creator']);

        // Filters
        if ($request->filled('status')) {
            switch($request->status) {
                case 'published':
                    $query->published();
                    break;
                case 'draft':
                    $query->draft();
                    break;
                case 'scheduled':
                    $query->where('is_published', true)
                        ->where('published_at', '>', now());
                    break;
            }
        }

        if ($request->filled('activity_id')) {
            $query->where('activity_id', $request->activity_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        $documentations = $query->latest()->paginate(12);

        return view('admin.documentations.index', compact('documentations'));
    }

    public function create()
    {
        $activities = Activity::completed()
            ->whereDoesntHave('documentation')
            ->get();

        return view('admin.documentations.create', compact('activities'));
    }

    public function edit(Documentation $documentation)
    {
        $activities = Activity::completed()->get();

        return view('admin.documentations.edit', compact('documentation', 'activities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id|unique:documentations,activity_id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Handle file uploads
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('documentations', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('documentations/gallery', 'public');
            }
            $validated['gallery_images'] = $galleryPaths;
        }

        Documentation::create($validated + [
            'created_by' => auth()->id()
        ]);

        return redirect()->route('admin.documentations.index')
            ->with('success', 'Dokumentasi berhasil dibuat.');
    }

    public function show(Documentation $documentation)
    {
        $documentation->load(['activity', 'creator']);

        return view('admin.documentations.show', compact('documentation'));
    }

    public function update(Request $request, Documentation $documentation)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Handle featured image update
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($documentation->featured_image) {
                Storage::disk('public')->delete($documentation->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('documentations', 'public');
        }

        // Handle gallery images update
        if ($request->hasFile('gallery_images')) {
            $galleryPaths = $documentation->gallery_images ?? [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('documentations/gallery', 'public');
            }
            $validated['gallery_images'] = $galleryPaths;
        }

        $documentation->update($validated);

        return redirect()->route('admin.documentations.show', $documentation)
            ->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy(Documentation $documentation)
    {
        // Delete associated files
        if ($documentation->featured_image) {
            Storage::disk('public')->delete($documentation->featured_image);
        }

        if ($documentation->gallery_images) {
            foreach ($documentation->gallery_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $documentation->delete();

        return redirect()->route('admin.documentations.index')
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }

    public function publish(Documentation $documentation)
    {
        $documentation->publish();

        return back()->with('success', 'Dokumentasi berhasil dipublikasikan.');
    }

    public function unpublish(Documentation $documentation)
    {
        $documentation->unpublish();

        return back()->with('success', 'Dokumentasi berhasil diunpublish.');
    }

    public function removeGalleryImage(Documentation $documentation, $imageIndex)
    {
        $gallery = $documentation->gallery_images;

        if (isset($gallery[$imageIndex])) {
            Storage::disk('public')->delete($gallery[$imageIndex]);
            unset($gallery[$imageIndex]);

            $documentation->update(['gallery_images' => array_values($gallery)]);
        }

        return back()->with('success', 'Gambar berhasil dihapus dari galeri.');
    }
}