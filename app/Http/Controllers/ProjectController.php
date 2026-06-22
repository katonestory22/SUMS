<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Expense;
use App\Models\ProjectType;
use App\Models\ProjectEdit;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['client', 'type', 'allocations.expenses']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('project_name', 'like', '%' . $request->search . '%')
                    ->orWhere('contract_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        if ($request->filled('type')) {
            $query->where('project_type_id', $request->type);
        }

        $projects = $query->paginate(10)->withQueryString();

        $projectTypes = ProjectType::orderBy('name')->get();

        $regions = [
            'Arusha',
            'Dar es Salaam',
            'Dodoma',
            'Geita',
            'Iringa',
            'Kagera',
            'Katavi',
            'Kigoma',
            'Kilimanjaro',
            'Lindi',
            'Manyara',
            'Mara',
            'Mbeya',
            'Morogoro',
            'Mtwara',
            'Mwanza',
            'Njombe',
            'Pemba North',
            'Pemba South',
            'Pwani',
            'Rukwa',
            'Ruvuma',
            'Shinyanga',
            'Simiyu',
            'Singida',
            'Songwe',
            'Tabora',
            'Tanga',
            'Unguja North',
            'Unguja South',
            'Zanzibar West'
        ];

        return view('projects.index', compact('projects', 'projectTypes', 'regions'));
    }

    public function create()
    {
        $clients = Client::all();
        $projectTypes = ProjectType::all();

        return view('projects.create', compact('clients', 'projectTypes'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'contract_amount' => str_replace(',', '', $request->contract_amount),
        ]);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_name' => 'required|string|max:255',
            'project_type_id' => 'required|exists:project_types,id',
            'location' => 'nullable|string|max:100',
            'contract_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('projects', 'contract_number'),
            ],
            'contract_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $clients = Client::all();
        $projectTypes = ProjectType::all();

        return view('projects.edit', compact('project', 'clients', 'projectTypes'));
    }

    public function update(Request $request, Project $project)
    {
        $request->merge([
            'contract_amount' => str_replace(',', '', $request->contract_amount),
        ]);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_name' => 'required|string|max:255',
            'project_type_id' => 'required|exists:project_types,id',
            'location' => 'nullable|string|max:100',
            'contract_number' => 'required|string|max:255',
            'contract_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $trackable = [
            'client_id',
            'project_name',
            'project_type_id',
            'location',
            'contract_number',
            'contract_amount',
            'start_date',
            'end_date'
        ];

        foreach ($trackable as $field) {
            $old = (string) ($project->$field ?? '');
            $new = (string) ($validated[$field] ?? '');
            if ($old !== $new) {
                ProjectEdit::create([
                    'project_id' => $project->id,
                    'edited_by' => auth()->id(),
                    'field_changed' => $field,
                    'old_value' => $old,
                    'new_value' => $new,
                    'reason' => $validated['reason'],
                ]);
            }
        }

        unset($validated['reason']);
        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    public function destroy(Project $project)
    {
        // safety check (optional but wise)
        if ($project->allocations()->exists()) {
            return back()->withErrors([
                'error' => 'Cannot delete project with existing allocations.'
            ]);
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function expenses(Project $project, Request $request)
    {
        $expenses = Expense::with(['allocation', 'user'])
            ->whereHas('allocation', function ($q) use ($project) {
                $q->where('project_id', $project->id);
            })
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return view('projects.partials.expense_cards', compact('expenses'))->render();
        }

        return view('projects.expenses', compact('project', 'expenses'));
    }
    public function overview(Project $project)
    {
        $project->load([
            'client',
            'allocations.expenses',
            'phases.activities.histories',
            'phases.activities.evidences',
        ]);


        return view('director.overview', compact('project'));
    }
}
