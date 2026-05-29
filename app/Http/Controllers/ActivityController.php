<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Phase;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * List activities with phase and project info.
     */
    public function index()
    {
        $activities = Activity::with('phase.project')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('activities.index', compact('activities'));
    }

    /**
     * Show create form.
     */
    public function create($phase)
    {
        $phase = Phase::with('project')->findOrFail($phase);

        return view('activities.create', compact('phase'));
    }

    /**
     * Store new activity.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'phase_id' => ['required', 'exists:phases,id'],

            'name' => ['required', 'string', 'max:255'],

            'weight_percentage' => [
                'required',
                'numeric',
                'min:1',
                'max:100'
            ],

            // NEW: MULTIPLE EVIDENCE IMAGES
            'evidences' => ['nullable', 'array'],

            'evidences.*' => [
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ],

        ]);

        $phase = Phase::with('activities')->findOrFail($validated['phase_id']);

        /*
        |--------------------------------------------------------------------------
        | Prevent total activity weight exceeding 100%
        |--------------------------------------------------------------------------
        */

        $totalWeight = $phase->activities()->sum('weight_percentage');

        if (($totalWeight + $validated['weight_percentage']) > 100) {

            return back()
                ->withErrors([
                    'weight_percentage' =>
                        'Total activity weight exceeds 100% for this phase.'
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Create Activity
        |--------------------------------------------------------------------------
        */

        $activity = Activity::create([

            'phase_id' => $validated['phase_id'],

            'name' => $validated['name'],

            'weight_percentage' => $validated['weight_percentage'],

            'current_progress' => 0

        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Evidence Images
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('evidences')) {

            foreach ($request->file('evidences') as $file) {

                $path = $file->store('activity-evidence', 'public');

                $activity->evidences()->create([

                    'file_path' => $path,

                    'uploaded_by' => auth()->id(),

                ]);
            }
        }

        return redirect()
            ->route('projects.show', $phase->project_id)
            ->with('success', 'Activity added successfully.');
    }

    /**
     * Show activity details.
     */
    public function show(Activity $activity)
    {
        $activity->load('phase.project', 'progressHistories');

        return view('activities.show', compact('activity'));
    }

    /**
     * Edit activity.
     */
    public function edit(Activity $activity)
    {
        $phases = Phase::with('project')->get();

        return view('activities.edit', compact('activity', 'phases'));
    }

    /**
     * Update activity.
     */
    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'phase_id' => 'required|exists:phases,id',
            'name' => 'required|string|max:255',
            'weight_percentage' => 'required|numeric|min:1|max:100',
            'current_progress' => 'required|numeric|min:0|max:100',
        ]);

        // Recalculate weight excluding current activity
        $totalWeight = Activity::where('phase_id', $validated['phase_id'])
            ->where('id', '!=', $activity->id)
            ->sum('weight_percentage');

        if (($totalWeight + $validated['weight_percentage']) > 100) {
            return back()->withErrors([
                'weight_percentage' => 'Total activity weight exceeds 100% for this phase.',
            ])->withInput();
        }

        $activity->update($validated);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity updated successfully.');
    }

    /**
     * Delete activity safely.
     */
    public function destroy(Activity $activity)
    {
        if ($activity->progressHistories()->exists()) {
            return redirect()
                ->route('activities.index')
                ->with('error', 'Cannot delete activity with progress history records.');
        }

        $activity->delete();

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity deleted successfully.');
    }
}
