<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * List saved invoices.
     */
    public function index()
    {
        $invoices = Invoice::with('creator')
            ->latest()
            ->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the invoice builder.
     */
    public function create()
    {
        $nextInvoiceNumber = Invoice::generateInvoiceNumber();

        return view('invoices.create', compact('nextInvoiceNumber'));
    }

    /**
     * Validate, calculate totals, generate the branded PDF, and save.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bill_to_name' => 'required|string|max:255',
            'bill_to_address' => 'nullable|string|max:500',
            'bill_to_email' => 'nullable|email|max:255',
            'bill_to_phone' => 'nullable|string|max:50',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.rate' => 'required|numeric|min:0',
        ]);

        // Server-side recalculation — never trust client-side totals.
        $items = collect($validated['items'])->map(function ($item) {
            $amount = round((float) $item['quantity'] * (float) $item['rate'], 2);
            return [
                'description' => $item['description'],
                'quantity' => (float) $item['quantity'],
                'rate' => (float) $item['rate'],
                'amount' => $amount,
            ];
        })->values()->all();

        $total = collect($items)->sum('amount');

        $invoiceNumber = Invoice::generateInvoiceNumber();

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'created_by' => auth()->id(),
            'bill_to_name' => $validated['bill_to_name'],
            'bill_to_address' => $validated['bill_to_address'] ?? null,
            'bill_to_email' => $validated['bill_to_email'] ?? null,
            'bill_to_phone' => $validated['bill_to_phone'] ?? null,
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'items' => $items,
            'total' => $total,
        ]);

        // Render branded PDF, mirroring ReportController::generate()
        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => false,
            'isPhpEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 96,
            'enable_php' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('invoices.pdf', compact('invoice'));

        // Filename = timestamp combination, as requested
        $filename = time() . '_' . $invoiceNumber . '.pdf';
        $path = 'invoices/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        $invoice->update(['file_path' => $path]);

        return redirect()
            ->route('invoices.index')
            ->with('success', "Invoice {$invoiceNumber} created successfully.");
    }

    /**
     * View a saved invoice's details.
     */
    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Download the generated PDF.
     */
    public function download(Invoice $invoice)
    {
        if (!$invoice->file_path || !Storage::disk('public')->exists($invoice->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download(
            $invoice->file_path,
            $invoice->invoice_number . '.pdf'
        ); // @phpstan-ignore-line
    }

    /**
     * Preview the PDF inline in the browser.
     */
    public function preview(Invoice $invoice)
    {
        if (!$invoice->file_path || !Storage::disk('public')->exists($invoice->file_path)) {
            abort(404);
        }

        $fullPath = storage_path('app/public/' . $invoice->file_path);

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($invoice->file_path) . '"',
        ]);
    }
}
