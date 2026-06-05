<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Project;
use Illuminate\Http\Request;

class AllocationController extends Controller
{
    public function index()
    {
        $allocations = Allocation::with(['project.client'])
            ->withSum('expenses', 'amount')
            ->latest('allocation_date')
            ->paginate(10);

        return view('allocations.index', compact('allocations'));
    }

    public function create()
    {
        $projects = Project::with('client')
            ->orderBy('project_name')
            ->get();

        return view('allocations.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'amount' => str_replace(',', '', $request->amount),
        ]);
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'allocation_date' => ['required', 'date'],
            'notes' => ['nullable'],
        ]);

        $project = Project::findOrFail($validated['project_id']);

        // Total allocations already made
        $totalAllocated = Allocation::where('project_id', $project->id)
            ->sum('amount');

        $remainingContract = $project->contract_amount - $totalAllocated;

        if ($validated['amount'] > $remainingContract) {
            return back()
                ->withErrors([
                    'amount' => 'Allocation exceeds remaining contract balance of ' . number_format($remainingContract, 2),
                ])
                ->withInput();
        }

        Allocation::create($validated);

        return redirect()
            ->route('allocations.index')
            ->with('success', 'Allocation recorded successfully.');
    }

    public function show(Allocation $allocation)
    {
        $allocation->load([
            'project.client',
            'expenses',
        ]);

        $totalExpenses = $allocation->expenses->sum('amount');

        $remaining = $allocation->amount - $totalExpenses;

        return view('allocations.show', [
            'allocation' => $allocation,
            'totalExpenses' => $totalExpenses,
            'remaining' => $remaining,
        ]);
    }

    public function destroy(Allocation $allocation)
    {
        if ($allocation->expenses()->exists()) {
            return redirect()
                ->route('allocations.index')
                ->with('error', 'Cannot delete allocation that already has expenses recorded.');
        }

        $allocation->delete();

        return redirect()
            ->route('allocations.index')
            ->with('success', 'Allocation deleted successfully.');
    }
}
