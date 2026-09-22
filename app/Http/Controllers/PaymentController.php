<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, Invoice $invoice)
    {
        $request->merge([
            'amount' => str_replace(',', '', $request->amount),
        ]);

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'method' => ['required', 'in:bank,mobile_money,cash'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $balance = $invoice->balance();

        if ($validated['amount'] > $balance) {
            return back()
                ->withErrors([
                    'amount' => 'Payment exceeds the remaining balance of TSh ' . number_format($balance, 2),
                ])
                ->withInput();
        }

        Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'method' => $validated['method'],
            'reference_number' => $validated['reference_number'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'recorded_by' => auth()->id(),
        ]);

        // Invoice is considered "sent" the moment payment activity starts, unless already cancelled.
        if ($invoice->status === 'draft') {
            $invoice->update(['status' => 'sent']);
        }

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Payment recorded successfully.');
    }

    public function destroy(Invoice $invoice, Payment $payment)
    {
        if ($payment->invoice_id !== $invoice->id) {
            abort(404);
        }

        $payment->delete();

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Payment removed.');
    }
}
