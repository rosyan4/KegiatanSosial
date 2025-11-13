<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityCategory;
use Illuminate\Http\Request;

class ActivityCategoryController extends Controller
{
    public function index()
    {
        $categories = ActivityCategory::withCount([
            'activities', 
            'activities as active_activities_count' => function($query) {
                $query->active();
            }
        ])->ordered()->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:activity_categories,name',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        ActivityCategory::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(ActivityCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, ActivityCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:activity_categories,name,' . $category->id,
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(ActivityCategory $category)
    {
        if ($category->activities()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang memiliki kegiatan.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    public function toggleStatus(ActivityCategory $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Kategori berhasil {$status}.");
    }
}