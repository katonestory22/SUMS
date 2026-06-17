<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Expense;
use App\Models\ExpenseEdit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function edit(Expense $expense)
    {
        $expense->load('allocation.project');
        $categories = [
            'Labour',
            'Equipment',
            'Travel',
            'Operations',
            'Consulting',
            'Miscellaneous'
        ];
        $spent = $expense->allocation->expenses()->sum('amount');
        $remaining = $expense->allocation->amount - $spent;

        return view('expenses.edit', compact('expense', 'categories', 'remaining'));
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
            $path = $request->file('receipt')->store('receipts', 'public');
            $validated['receipt'] = $path;
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

    public function update(Request $request, Expense $expense)
    {
        $request->merge([
            'amount' => str_replace(',', '', $request->amount),
        ]);

        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['required'],
            'date' => ['required', 'date'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        // Revalidate budget — exclude current expense from spent
        $allocation = $expense->allocation;
        $spent = $allocation->expenses()
            ->where('id', '!=', $expense->id)
            ->sum('amount');
        $remaining = $allocation->amount - $spent;

        if ($validated['amount'] > $remaining) {
            return back()->withErrors([
                'amount' => 'Amount exceeds remaining allocation of TSh '
                    . number_format($remaining, 2),
            ])->withInput();
        }

        // Track changes
        $trackable = ['category', 'amount', 'description', 'date'];
        foreach ($trackable as $field) {
            $old = (string) ($expense->$field ?? '');
            $new = (string) ($validated[$field] ?? '');
            if ($old !== $new) {
                ExpenseEdit::create([
                    'expense_id' => $expense->id,
                    'edited_by' => auth()->id(),
                    'field_changed' => $field,
                    'old_value' => $old,
                    'new_value' => $new,
                    'reason' => $validated['reason'],
                ]);
            }
        }

        // Handle receipt
        if ($request->hasFile('receipt')) {
            if ($expense->receipt) {
                Storage::disk('public')->delete($expense->receipt);
            }
            $path = $request->file('receipt')->store('receipts', 'public');
            $expense->receipt = $path;

            ExpenseEdit::create([
                'expense_id' => $expense->id,
                'edited_by' => auth()->id(),
                'field_changed' => 'receipt',
                'old_value' => 'previous file',
                'new_value' => 'new file uploaded',
                'reason' => $validated['reason'],
            ]);
        }

        $expense->update([
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'date' => $validated['date'],
        ]);

        return redirect()
            ->route('projects.expenses', $expense->allocation->project_id)
            ->with('success', 'Expense updated and changes recorded.');
    }
}
