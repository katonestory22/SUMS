@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number)
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('invoices.index') }}">Back to Invoices</a>
@endsection

@section('content')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .wrapper {
            max-width: 980px;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            gap: 20px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 26px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        .head-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 14px;
        }

        h2 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 4px;
        }

        .sub {
            font-size: 13px;
            color: #6b7280;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-paid {
            background: #dcfce7;
            color: #166534;
        }

        .badge-unpaid {
            background: #f3f4f6;
            color: #4b5563;
        }

        .badge-partially_paid {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-overdue {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-cancelled {
            background: #e5e7eb;
            color: #6b7280;
        }

        .action-bar {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.15s ease;
        }

        .btn-preview {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .btn-preview:hover {
            background: #dbeafe;
        }

        .btn-download {
            background: #1f3a5f;
            color: #fff;
        }

        .btn-download:hover {
            background: #16283f;
        }

        .btn-edit {
            background: #f0fdf4;
            color: #15803d;
        }

        .btn-edit:hover {
            background: #dcfce7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th {
            background: #1f3a5f;
            color: #C9A84C;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 12px;
            text-align: left;
        }

        td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            color: #374151;
        }

        .totals-box {
            margin-left: auto;
            width: 320px;
            margin-top: 10px;
        }

        .totals-box .row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
            color: #4b5563;
        }

        .totals-box .row.total {
            border-top: 2px solid #1f3a5f;
            margin-top: 6px;
            padding-top: 10px;
            font-weight: 700;
            font-size: 16px;
            color: #111827;
        }

        .totals-box .row.balance {
            font-weight: 700;
            color: #b91c1c;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            align-items: end;
        }

        label {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
            display: block;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 9px 10px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 13px;
            box-sizing: border-box;
        }

        .btn {
            background: #1f3a5f;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-danger {
            background: #fff;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 10px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        .empty {
            color: #9ca3af;
            font-size: 13px;
            padding: 12px 0;
        }

        /* ---------- Preview modal (same pattern as Reports page) ---------- */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-box {
            background: #fff;
            width: 92%;
            max-width: 900px;
            height: 85vh;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #1f3a5f;
            color: #fff;
        }

        .modal-title {
            font-size: 15px;
            font-weight: 700;
        }

        .modal-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #fff;
            font-size: 20px;
            line-height: 1;
            padding: 4px 6px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .modal-body {
            flex: 1;
            overflow: hidden;
        }

        .modal-body iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>

    <div class="wrapper">

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert-error">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="head-row">
                <div>
                    <h2>{{ $invoice->invoice_number }}</h2>
                    <div class="sub">{{ $invoice->title ?? 'Invoice' }} &middot; Bill To: {{ $invoice->bill_to_name }}
                    </div>
                    <div class="sub">Issued {{ $invoice->issue_date->format('M j, Y') }}
                        @if ($invoice->due_date)
                            &middot; Due {{ $invoice->due_date->format('M j, Y') }}
                        @endif
                    </div>
                </div>
                <span
                    class="badge badge-{{ $invoice->payment_status }}">{{ str_replace('_', ' ', $invoice->payment_status) }}</span>
            </div>

            <div class="action-bar">
                <button type="button" class="action-btn btn-preview" onclick="openInvoicePreview()">
                    &#128065; Preview
                </button>
                <a href="{{ route('invoices.download', $invoice) }}" class="action-btn btn-download">
                    &#8659; Download PDF
                </a>
                @if (!$invoice->payments->count() && auth()->user()->role !== 'technical')
                    <a href="{{ route('invoices.edit', $invoice) }}" class="action-btn btn-edit">
                        &#9998; Edit Invoice
                    </a>
                @endif
            </div>

            @if (auth()->user()->role === 'technical')
                <div class="sub"
                    style="margin-top:14px; background:#eff6ff; color:#1d4ed8; padding:10px 14px; border-radius:8px;">
                    <strong>Technical access:</strong> you can prepare, preview and download invoices. Payment recording and
                    invoice editing are handled by the Finance team.
                </div>
            @endif
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                            <td>TZS {{ number_format($item->rate, 2) }}</td>
                            <td>TZS {{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals-box">
                <div class="row"><span>Subtotal</span><span>TZS {{ number_format($invoice->subtotal, 2) }}</span></div>

                @if ($invoice->discount_amount > 0)
                    <div class="row">
                        <span>
                            Discount
                            @if ($invoice->discount_type === 'percentage')
                                ({{ rtrim(rtrim(number_format($invoice->discount_value, 2), '0'), '.') }}%)
                            @endif
                        </span>
                        <span>&minus; TZS {{ number_format($invoice->discount_amount, 2) }}</span>
                    </div>
                @endif

                <div class="row"><span>Tax
                        ({{ rtrim(rtrim(number_format($invoice->tax_percentage, 2), '0'), '.') }}%)</span><span>TZS
                        {{ number_format($invoice->tax_amount, 2) }}</span></div>
                <div class="row total"><span>Total</span><span>TZS {{ number_format($invoice->total_amount, 2) }}</span>
                </div>
                <div class="row"><span>Paid</span><span>TZS {{ number_format($totalPaid, 2) }}</span></div>
                <div class="row balance"><span>Balance Due</span><span>TZS {{ number_format($balance, 2) }}</span></div>
            </div>

            @if ($invoice->notes)
                <hr style="border:none;border-top:1px solid #e5e7eb;margin:20px 0;">
                <div class="sub"><strong>Notes:</strong> {{ $invoice->notes }}</div>
            @endif
        </div>

        @if (auth()->user()->role !== 'technical')
            <div class="card">
                <h2 style="font-size:16px;">Payment History</h2>

                @if ($invoice->payments->isEmpty())
                    <div class="empty">No payments recorded yet.</div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Recorded By</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('M j, Y') }}</td>
                                    <td>TZS {{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</td>
                                    <td>{{ $payment->reference_number ?? '—' }}</td>
                                    <td>{{ $payment->user->name ?? '—' }}</td>
                                    <td>
                                        <form method="POST"
                                            action="{{ route('invoices.payments.destroy', [$invoice, $payment]) }}"
                                            onsubmit="return confirm('Remove this payment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                @if ($balance > 0 && $invoice->status !== 'cancelled')
                    <hr style="border:none;border-top:1px solid #e5e7eb;margin:18px 0;">
                    <h2 style="font-size:14px;">Record a Payment</h2>
                    <form method="POST" action="{{ route('invoices.payments.store', $invoice) }}">
                        @csrf
                        <div class="form-grid">
                            <div>
                                <label>Amount (max TZS {{ number_format($balance, 2) }})</label>
                                <input type="text" name="amount" required>
                            </div>
                            <div>
                                <label>Payment Date</label>
                                <input type="date" name="payment_date" required value="{{ now()->format('Y-m-d') }}">
                            </div>
                            <div>
                                <label>Method</label>
                                <select name="method" required>
                                    <option value="bank">Bank</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="cash">Cash</option>
                                </select>
                            </div>
                            <div>
                                <label>Reference # (optional)</label>
                                <input type="text" name="reference_number">
                            </div>
                            <div style="grid-column: span 4;">
                                <label>Notes (optional)</label>
                                <textarea name="notes" rows="2"></textarea>
                            </div>
                            <div>
                                <button type="submit" class="btn">Record Payment</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        @endif
    </div>

    {{-- PREVIEW MODAL --}}
    <div class="modal-overlay" id="previewModal">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title">{{ $invoice->invoice_number }}.pdf</div>
                <button class="modal-close" onclick="closeInvoicePreview()" aria-label="Close preview">&#x2715;</button>
            </div>
            <div class="modal-body" id="previewModalBody"></div>
        </div>
    </div>

    <script>
        function openInvoicePreview() {
            const body = document.getElementById('previewModalBody');
            body.innerHTML = `<iframe src="{{ route('invoices.preview', $invoice) }}"></iframe>`;
            document.getElementById('previewModal').classList.add('open');
        }

        function closeInvoicePreview() {
            document.getElementById('previewModal').classList.remove('open');
            document.getElementById('previewModalBody').innerHTML = '';
        }

        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('previewModal')) closeInvoicePreview();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeInvoicePreview();
        });
    </script>

@endsection
