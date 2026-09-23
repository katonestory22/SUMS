<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::withSum('payments', 'amount')->latest('issue_date');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                    ->orWhere('bill_to_name', 'like', '%' . $request->search . '%')
                    ->orWhere('title', 'like', '%' . $request->search . '%');
            });
        }

        $invoices = $query->paginate(10)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $invoiceNumber = Invoice::generateInvoiceNumber();

        return view('invoices.create', compact('invoiceNumber'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateInvoice($request);

        $invoice = DB::transaction(function () use ($validated) {
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'bill_to_name' => $validated['bill_to_name'],
                'bill_to_address' => $validated['bill_to_address'] ?? null,
                'title' => $validated['title'] ?? null,
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'] ?? null,
                'status' => $validated['status'] ?? 'draft',
                'discount_type' => $validated['discount_type'] ?? null,
                'discount_value' => $validated['discount_value'] ?? 0,
                'tax_percentage' => $validated['tax_percentage'] ?? 0,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $this->syncItems($invoice, $validated['items']);
            $invoice->recalculateTotals();

            return $invoice;
        });

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'payments.user', 'creator']);

        return view('invoices.show', [
            'invoice' => $invoice,
            'totalPaid' => $invoice->totalPaid(),
            'balance' => $invoice->balance(),
        ]);
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->payments()->exists()) {
            return redirect()
                ->route('invoices.show', $invoice)
                ->with('error', 'This invoice already has payments recorded and cannot be edited. You may still update its status.');
        }

        $invoice->load('items');

        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->payments()->exists()) {
            return back()->withErrors([
                'invoice' => 'This invoice already has payments recorded and cannot be edited.',
            ]);
        }

        $validated = $this->validateInvoice($request);

        DB::transaction(function () use ($invoice, $validated) {
            $invoice->update([
                'bill_to_name' => $validated['bill_to_name'],
                'bill_to_address' => $validated['bill_to_address'] ?? null,
                'title' => $validated['title'] ?? null,
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'] ?? null,
                'status' => $validated['status'] ?? $invoice->status,
                'discount_type' => $validated['discount_type'] ?? null,
                'discount_value' => $validated['discount_value'] ?? 0,
                'tax_percentage' => $validated['tax_percentage'] ?? 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            $invoice->items()->delete();
            $this->syncItems($invoice, $validated['items']);
            $invoice->recalculateTotals();
        });

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->payments()->exists()) {
            return redirect()
                ->route('invoices.index')
                ->with('error', 'Cannot delete an invoice that already has payments recorded.');
        }

        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    public function download(Invoice $invoice)
    {
        $invoice->load('items');

        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => false,
            'isPhpEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 96,
            'enable_php' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('invoices.pdf', compact('invoice'));

        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    public function preview(Invoice $invoice)
    {
        $invoice->load('items');

        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => false,
            'isPhpEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 96,
            'enable_php' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('invoices.pdf', compact('invoice'));

        return $pdf->stream($invoice->invoice_number . '.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function validateInvoice(Request $request): array
    {
        $request->merge([
            'discount_value' => str_replace(',', '', (string) $request->input('discount_value', 0)),
        ]);

        return $request->validate([
            'bill_to_name' => ['required', 'string', 'max:255'],
            'bill_to_address' => ['nullable', 'string'],
            'title' => ['nullable', 'string', 'max:255'],
            'issue_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'status' => ['nullable', 'in:draft,sent,cancelled'],
            'discount_type' => ['nullable', 'in:percentage,fixed'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'min:0'],
            'items.*.rate' => ['required', 'numeric', 'min:0'],
        ]);
    }

    private function syncItems(Invoice $invoice, array $items): void
    {
        foreach ($items as $index => $item) {
            $quantity = (float) $item['quantity'];
            $rate = (float) $item['rate'];

            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $quantity,
                'rate' => $rate,
                'amount' => round($quantity * $rate, 2),
                'sort_order' => $index,
            ]);
        }
    }
}
