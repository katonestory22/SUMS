<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityProgressHistory;
use Illuminate\Http\Request;

class ActivityProgressHistoryController extends Controller
{
    /**
     * Show history for ONE activity
     */
    public function index(Activity $activity, Request $request)
    {
        $histories = ActivityProgressHistory::where('activity_id', $activity->id)
            ->with('activity.phase.project.client')
            ->latest()
            ->paginate(3);

        // 🧠 BACK ROUTE LOGIC (PROJECT CONTEXT CARRY)
        $projectId = $request->query('project');

        $backUrl = $projectId
            ? route('projects.show', $projectId)
            : route('projects.index');

        return view('activities.history', compact('activity', 'histories', 'backUrl'));
    }

    /**
     * Store progress update
     */
    public function store(Request $request, Activity $activity)
    {
        $request->validate([
            'new_percentage' => 'required|numeric|min:0|max:100',
            'comment' => 'nullable|string|max:500',
        ]);

        $old = $activity->current_progress;

        if ($request->new_percentage < $old) {
            return back()->withErrors([
                'new_percentage' => 'Progress cannot go backwards.',
            ]);
        }

        ActivityProgressHistory::create([
            'activity_id' => $activity->id,
            'old_percentage' => $old,
            'new_percentage' => $request->new_percentage,
            'updated_by' => auth()->id(),
            'comment' => $request->comment,
        ]);

        $activity->update([
            'current_progress' => $request->new_percentage
        ]);

        return back()->with('success', 'Progress logged.');
    }

    public function edit()
    {
        abort(403);
    }

    public function update()
    {
        abort(403);
    }

    public function destroy()
    {
        abort(403);
    }
}
