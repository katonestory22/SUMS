<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('allocation.project', 'user')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('expenses.index', compact('expenses'));
    }

    public function create($allocationId)
    {
        $allocation = Allocation::with('project')->findOrFail($allocationId);

        $spent = $allocation->expenses()->sum('amount');
        $remaining = $allocation->amount - $spent;

        $categories = [
            'Labour',
            'Equipment',
            'Travel',
            'Operations',
            'Consulting',
            'Miscellaneous'
        ];

        return view('expenses.create', compact('allocation', 'remaining', 'categories'));
    }
    public function store(Request $request)
    {
        // normalize amount input
        $request->merge([
            'amount' => str_replace(',', '', $request->amount),
        ]);

        $validated = $request->validate([
            'allocation_id' => ['required', 'exists:allocations,id'],
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['required'],
            'date' => ['required', 'date'],

            // 🧾 NEW: receipt file
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $validated['recorded_by'] = auth()->id();

        // check remaining budget
        $allocation = Allocation::findOrFail($validated['allocation_id']);
        $spent = $allocation->expenses()->sum('amount');
        $remaining = $allocation->amount - $spent;

        if ($validated['amount'] > $remaining) {
            return back()->withErrors([
                'amount' => 'Expense exceeds remaining allocation of TSh ' . number_format($remaining, 2),
            ])->withInput();
        }

        // 🧾 handle receipt upload
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/receipts'), $filename);
            $validated['receipt'] = 'uploads/receipts/' . $filename;
        }

        Expense::create($validated);

        return redirect()
            ->route('allocations.index')
            ->with('success', 'Expense recorded successfully with receipt.');
    }

    public function show(Expense $expense)
    {
        $expense->load('allocation.project', 'user');

        return view('expenses.show', compact('expense'));
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
