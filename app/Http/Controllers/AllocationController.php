<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\AllocationTopup;
use Illuminate\Support\Facades\DB;

class AllocationController extends Controller
{
    public function index(Request $request)
    {
        $query = Allocation::with(['project.client'])
            ->withSum('expenses', 'amount')
            ->latest('allocation_date');

        if ($request->filled('search')) {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('project_name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('client', function ($q2) use ($request) {
                        $q2->where('first_name', 'like', '%' . $request->search . '%')
                            ->orWhere('last_name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->when($request->status === 'healthy', function ($q) {
                $q->whereRaw('(amount - COALESCE((SELECT SUM(amount) FROM expenses WHERE expenses.allocation_id = allocations.id), 0)) / amount * 100 > 50');
            })->when($request->status === 'warning', function ($q) {
                $q->whereRaw('(amount - COALESCE((SELECT SUM(amount) FROM expenses WHERE expenses.allocation_id = allocations.id), 0)) / amount * 100 BETWEEN 20 AND 50');
            })->when($request->status === 'critical', function ($q) {
                $q->whereRaw('(amount - COALESCE((SELECT SUM(amount) FROM expenses WHERE expenses.allocation_id = allocations.id), 0)) / amount * 100 <= 20');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('allocation_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('allocation_date', '<=', $request->date_to);
        }

        $allocations = $query->paginate(5)->withQueryString();

        return view('allocations.index', compact('allocations'));
    }

    public function edit(Allocation $allocation)
    {
        $allocation->load('project.client');
        $spent = $allocation->expenses()->sum('amount');
        $remaining = $allocation->amount - $spent;

        return view('allocations.edit', compact('allocation', 'spent', 'remaining'));
    }

    public function update(Request $request, Allocation $allocation)
    {
        $request->merge([
            'amount' => str_replace(',', '', $request->amount),
        ]);

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'allocation_date' => ['required', 'date'],
            'notes' => ['nullable'],
        ]);

        // Validate amount doesn't exceed contract minus other allocations
        $totalAllocated = Allocation::where('project_id', $allocation->project_id)
            ->where('id', '!=', $allocation->id)
            ->sum('amount');

        $remainingContract = $allocation->project->contract_amount - $totalAllocated;

        if ($validated['amount'] > $remainingContract) {
            return back()->withErrors([
                'amount' => 'Amount exceeds remaining contract balance of TSh '
                    . number_format($remainingContract, 2),
            ])->withInput();
        }

        // Also can't allocate less than what's already been spent
        $spent = $allocation->expenses()->sum('amount');
        if ($validated['amount'] < $spent) {
            return back()->withErrors([
                'amount' => 'Amount cannot be less than already spent TSh '
                    . number_format($spent, 2),
            ])->withInput();
        }

        $difference = $validated['amount'] - $allocation->amount;

        DB::transaction(function () use ($allocation, $validated, $difference) {
            if ($difference != 0) {
                $allocation->topups()->create([
                    'amount' => $difference,
                    'received_date' => $validated['allocation_date'],
                    'notes' => 'Manual adjustment (edit)',
                    'recorded_by' => auth()->id(),
                ]);
            }

            $allocation->update($validated);
        });

        return redirect()
            ->route('allocations.index')
            ->with('success', 'Allocation updated successfully.');
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
        $request->merge(['amount' => str_replace(',', '', $request->amount)]);

        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'allocation_date' => ['required', 'date'],
            'notes' => ['nullable'],
        ]);

        $project = Project::findOrFail($validated['project_id']);

        $existing = (float) Allocation::where('project_id', $project->id)->value('amount');
        $remainingContract = $project->contract_amount - $existing;

        if ($validated['amount'] > $remainingContract) {
            return back()->withErrors([
                'amount' => 'Allocation exceeds remaining contract balance of ' . number_format($remainingContract, 2),
            ])->withInput();
        }

        DB::transaction(function () use ($validated, $project) {
            $allocation = Allocation::firstOrCreate(
                ['project_id' => $project->id],
                ['amount' => 0, 'allocation_date' => $validated['allocation_date']]
            );

            // Log this income
            $allocation->topups()->create([
                'amount' => $validated['amount'],
                'received_date' => $validated['allocation_date'],
                'notes' => $validated['notes'] ?? null,
                'recorded_by' => auth()->id(),
            ]);

            // Add to the running total on the same line
            $allocation->update([
                'amount' => $allocation->amount + $validated['amount'],
                'allocation_date' => $validated['allocation_date'], // latest income date
            ]);
        });

        return redirect()
            ->route('allocations.index')
            ->with('success', 'Income added to the project allocation.');
    }

    public function show(Allocation $allocation)
    {
        $allocation->load([
            'project.client',
            'expenses',
            'topups.user'
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


    public function destroyTopup(AllocationTopup $topup)
    {
        $allocation = $topup->allocation()->with('project')->first();

        $newAmount = $allocation->amount - $topup->amount;
        $spent = $allocation->expenses()->sum('amount');

        // Can't remove income that has already been spent
        if ($newAmount < $spent) {
            return back()->with(
                'error',
                'Cannot delete: remaining income would be TSh ' . number_format($newAmount, 0)
                . ' but TSh ' . number_format($spent, 0) . ' is already spent.'
            );
        }

        // Safety for deleting a negative adjustment
        if ($newAmount > $allocation->project->contract_amount) {
            return back()->with('error', 'Cannot delete: total would exceed the contract amount.');
        }

        DB::transaction(function () use ($topup, $allocation, $newAmount) {
            $topup->delete();

            // Nothing left and no expenses: remove the empty allocation line
            if ($allocation->topups()->count() === 0 && $allocation->expenses()->count() === 0) {
                $allocation->delete();
                return;
            }

            $allocation->update([
                'amount' => $newAmount,
                'allocation_date' => $allocation->topups()->max('received_date') ?? $allocation->allocation_date,
            ]);
        });

        if (!Allocation::whereKey($allocation->id)->exists()) {
            return redirect()->route('allocations.index')
                ->with('success', 'Income deleted and the empty allocation removed.');
        }

        return back()->with('success', 'Income entry deleted and totals updated.');
    }
}
