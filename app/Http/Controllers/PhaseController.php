<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use App\Models\Project;
use Illuminate\Http\Request;

class PhaseController extends Controller
{
    /**
     * List phases with project info.
     */
    public function index()
    {
        $phases = Phase::with('project')
            ->withCount('activities')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('phases.index', compact('phases'));
    }

    /**
     * Show create form.
     */
    public function create(Project $project)
    {
        return view('phases.create', compact('project'));
    }

    /**
     * Store new phase with weight validation.
     */
    public function store(Request $request, $projectId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight_percentage' => 'required|numeric|min:1|max:100',
        ]);

        $project = Project::findOrFail($projectId);

        $totalWeight = Phase::where('project_id', $project->id)
            ->sum('weight_percentage');

        if (($totalWeight + $validated['weight_percentage']) > 100) {
            return back()->withErrors([
                'weight_percentage' => 'Total phase weight cannot exceed 100%.',
            ])->withInput();
        }

        $validated['project_id'] = $project->id;

        Phase::create($validated);

        return redirect()
            ->route('projects.show', $project->id)
            ->with('success', 'Phase added successfully.');
    }

    /**
     * Show phase with activities.
     */
    public function show(Phase $phase)
    {
        $phase->load('project', 'activities');

        return view('phases.show', compact('phase'));
    }

    /**
     * Edit phase.
     */
    public function edit(Phase $phase)
    {
        $projects = Project::orderBy('project_name')->get();

        return view('phases.edit', compact('phase', 'projects'));
    }

    /**
     * Update phase with weight recalculation.
     */
    public function update(Request $request, Phase $phase)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight_percentage' => 'required|numeric|min:1|max:100',
        ]);

        $totalWeight = Phase::where('project_id', $validated['project_id'])
            ->where('id', '!=', $phase->id)
            ->sum('weight_percentage');

        if (($totalWeight + $validated['weight_percentage']) > 100) {
            return back()->withErrors([
                'weight_percentage' => 'Total phase weight exceeds 100% for this project.',
            ])->withInput();
        }

        $phase->update($validated);

        return redirect()
            ->route('phases.index')
            ->with('success', 'Phase updated successfully.');
    }

    /**
     * Delete phase only if no activities exist.
     */
    public function destroy(Phase $phase)
    {
        if ($phase->activities()->exists()) {
            return redirect()
                ->route('phases.index')
                ->with('error', 'Cannot delete phase with existing activities.');
        }

        $phase->delete();

        return redirect()
            ->route('phases.index')
            ->with('success', 'Phase deleted successfully.');
    }
}
