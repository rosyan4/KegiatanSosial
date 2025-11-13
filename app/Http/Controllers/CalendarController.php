<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $categories = ActivityCategory::active()->ordered()->get();
        
        return view('calendar.index', compact('categories'));
    }

    public function getEvents(Request $request)
    {
        $user = Auth::user();
        
        $query = Activity::published()
            ->with('category')
            ->where(function($q) use ($user) {
                $q->where('type', 'umum')
                  ->orWhereHas('invitations', function($q2) use ($user) {
                      $q2->where('user_id', $user->id)
                         ->where('status', 'accepted');
                  });
            });

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $activities = $query->get();

        $events = [];
        foreach ($activities as $activity) {
            $events[] = [
                'id' => $activity->id,
                'title' => $activity->title,
                'start' => $activity->start_date->toIso8601String(),
                'end' => $activity->end_date->toIso8601String(),
                'color' => $activity->category->color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type' => $activity->type,
                    'location' => $activity->location,
                    'category' => $activity->category->name,
                    'slug' => $activity->slug,
                    'status' => $activity->status,
                ]
            ];
        }

        return response()->json($events);
    }
}