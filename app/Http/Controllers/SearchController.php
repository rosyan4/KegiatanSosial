<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Documentation;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return redirect()->back()->with('error', 'Masukkan kata kunci pencarian.');
        }

        // Search activities
        $activities = Activity::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('location', 'LIKE', "%{$query}%");
            })
            ->with('category')
            ->orderBy('start_date', 'desc')
            ->paginate(10, ['*'], 'activities_page');

        // Search documentations
        $documentations = Documentation::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('summary', 'LIKE', "%{$query}%");
            })
            ->with('activity')
            ->orderBy('published_at', 'desc')
            ->paginate(10, ['*'], 'docs_page');

        return view('search.results', compact(
            'activities',
            'documentations',
            'query'
        ));
    }
}