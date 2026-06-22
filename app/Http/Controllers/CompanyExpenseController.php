<?php

namespace App\Http\Controllers;

use App\Models\CompanyExpense;
use App\Models\CompanyExpenseEdit;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CompanyExpenseController extends Controller
{
    private array $categories = [
        'Salaries',
        'Office Operation Cost',
        'Transport',
        'Medical Insurance',
        'Taxes and Fines',
        'Miscellaneous',
    ];
    public function index()
    {
        $expenses = CompanyExpense::with('recorder')
            ->latest('date')
            ->paginate(15);

        $totalThisMonth = CompanyExpense::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $totalThisYear = CompanyExpense::whereYear('date', now()->year)
            ->sum('amount');

        $totalAll = CompanyExpense::sum('amount');

        $reports = Report::where('type', 'Company Expense Report')
            ->latest()
            ->get();

        return view('company-expenses.index', compact(
            'expenses',
            'totalThisMonth',
            'totalThisYear',
            'totalAll',
            'reports'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = $this->categories;
        return view('company-expenses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'amount' => str_replace(',', '', $request->amount),
        ]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:' . implode(',', $this->categories)],
            'amount' => ['required', 'numeric', 'min:1'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $validated['recorded_by'] = auth()->id();

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('company-receipts', 'public');
            $validated['receipt'] = $path;
        }

        CompanyExpense::create($validated);

        return redirect()
            ->route('company-expenses.index')
            ->with('success', 'Company expense recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyExpense $companyExpense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyExpense $companyExpense)
    {
        $categories = $this->categories;
        $expense = $companyExpense;
        return view('company-expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanyExpense $companyExpense)
    {
        $request->merge([
            'amount' => str_replace(',', '', $request->amount),
        ]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:' . implode(',', $this->categories)],
            'amount' => ['required', 'numeric', 'min:1'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        // Track each changed field
        $trackable = ['title', 'category', 'amount', 'date', 'description'];

        foreach ($trackable as $field) {
            $old = (string) $companyExpense->$field;
            $new = (string) ($validated[$field] ?? '');

            if ($old !== $new) {
                CompanyExpenseEdit::create([
                    'company_expense_id' => $companyExpense->id,
                    'edited_by' => auth()->id(),
                    'field_changed' => $field,
                    'old_value' => $old,
                    'new_value' => $new,
                    'reason' => $validated['reason'],
                ]);
            }
        }

        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($companyExpense->receipt) {
                Storage::disk('public')->delete($companyExpense->receipt);
            }
            $path = $request->file('receipt')->store('company-receipts', 'public');
            $companyExpense->receipt = $path;

            CompanyExpenseEdit::create([
                'company_expense_id' => $companyExpense->id,
                'edited_by' => auth()->id(),
                'field_changed' => 'receipt',
                'old_value' => 'previous file',
                'new_value' => 'new file uploaded',
                'reason' => $validated['reason'],
            ]);
        }

        $companyExpense->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'date' => $validated['date'],
            'description' => $validated['description'],
        ]);

        return redirect()
            ->route('company-expenses.index')
            ->with('success', 'Expense updated and changes recorded.');
    }

    public function audit()
    {
        $edits = CompanyExpenseEdit::with('editor', 'expense')
            ->latest()
            ->paginate(20);

        return view('company-expenses.audit', compact('edits'));
    }


    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => ['required', 'in:month,range'],
            'month' => ['required_if:report_type,month', 'nullable', 'string'],
            'date_from' => ['required_if:report_type,range', 'nullable', 'date'],
            'date_to' => ['required_if:report_type,range', 'nullable', 'date'],
        ]);

        $query = CompanyExpense::query();

        if ($request->report_type === 'month') {
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('date', $year)->whereMonth('date', $month);
            $label = \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y');
            $filename = 'Company_Expenses_' . $label . '.pdf';
        } else {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
            $label = \Carbon\Carbon::parse($request->date_from)->format('d M Y')
                . ' to '
                . \Carbon\Carbon::parse($request->date_to)->format('d M Y');
            $filename = 'Company_Expenses_' . str_replace(' ', '_', $label) . '.pdf';
        }

        $expenses = $query->orderBy('date')->get();
        $total = $expenses->sum('amount');
        $byCategory = $expenses->groupBy('category')
            ->map(fn($g) => $g->sum('amount'));

        $pdf = Pdf::loadView('reports.company-expense', compact(
            'expenses',
            'total',
            'byCategory',
            'label'
        ));

        $path = 'reports/' . time() . '_' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        // Save to reports table so it appears in View Reports
        Report::create([
            'project_id' => null,
            'uploaded_by' => auth()->id(),
            'title' => 'Company Expenses — ' . $label,
            'type' => 'Company Expense Report',
            'source' => 'generated',
            'file_path' => $path,
            'notes' => null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Company expense report generated. View it in My Reports.');
    }
}
