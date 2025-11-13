<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentationController extends Controller
{
    public function index()
    {
        $documentations = Documentation::with(['activity', 'creator'])
            ->published()
            ->latestPublished()
            ->paginate(12);

        return view('documentations.index', compact('documentations'));
    }

    public function show(Documentation $documentation)
    {
        // Authorization untuk dokumentasi yang belum publish
        if (!$documentation->isPublished() && !$this->canViewUnpublished($documentation)) {
            abort(404);
        }

        // Increment view count
        $documentation->incrementViewCount();

        $documentation->load(['activity', 'creator']);

        return view('documentations.show', compact('documentation'));
    }

    private function canViewUnpublished(Documentation $documentation): bool
    {
        return Auth::check() && (
            Auth::user()->isAdmin() || 
            $documentation->created_by === Auth::id()
        );
    }
}