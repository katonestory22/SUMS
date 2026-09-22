@extends('layouts.app')

@section('title', 'Edit Invoice')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('invoices.show', $invoice) }}">Back to Invoice</a>
    <a href="{{ route('invoices.index') }}">All Invoices</a>
@endsection

@section('content')

    <style>
        body { font-family: 'Inter', sans-serif; background: #f4f6f9; }
        .wrapper { max-width: 980px; margin: 0 auto; padding: 20px; }
        .card { background: #fff; border-radius: 14px; padding: 28px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); }
        h2 { font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 6px; }
        .subtitle { font-size: 13px; color: #6b7280; margin-bottom: 18px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .full { grid-column: span 2; }
        label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; display: block; }
        input, select, textarea { width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 14px; background: #fff; box-sizing: border-box; }
        input:focus, select:focus, textarea:focus { border-color: #1f3a5f; outline: none; box-shadow: 0 0 0 3px rgba(31,58,95,0.12); }
        textarea { resize: vertical; }
        hr.divider { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0; }
        table.items-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .items-table th { background: #1f3a5f; color: #C9A84C; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px 10px; text-align: left; }
        .items-table td { padding: 6px; border-bottom: 1px solid #f0f0f0; vertical-align: top; }
        .items-table input { padding: 8px; }
        .items-table .amount-cell { font-weight: 600; color: #111827; padding-top: 14px; white-space: nowrap; }
        .remove-row { background: none; border: none; color: #dc2626; cursor: pointer; font-size: 18px; padding: 4px 8px; }
        .add-row-btn { background: #eef2ff; color: #1f3a5f; border: 1px dashed #c7d2fe; padding: 8px 14px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; margin-bottom: 20px; }
        .totals-box { margin-left: auto; width: 320px; }
        .totals-box .row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; color: #4b5563; }
        .totals-box .row.total { border-top: 2px solid #1f3a5f; margin-top: 6px; padding-top: 10px; font-weight: 700; font-size: 16px; color: #111827; }
        .btn { background: #1f3a5f; color: white; padding: 11px 20px; border-radius: 10px; border: none; font-weight: 600; font-size: 14px; cursor: pointer; }
        .btn:hover { background: #16283f; }
        .error-list { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 18px; font-size: 13px; }
        @media (max-width: 700px) { .form-grid { grid-template-columns: 1fr; } .full { grid-column: span 1; } .totals-box { width: 100%; } }
    </style>

    <div class="wrapper">
        <div class="card">

            <h2>Edit Invoice {{ $invoice->invoice_number }}</h2>
            <div class="subtitle">No payments have been recorded yet, so this invoice can still be edited freely.</div>

            @if ($errors->any())
                <div class="error-list">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('invoices.update', $invoice) }}" id="invoice-form">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="full">
                        <label>Invoice Title (optional)</label>
                        <input type="text" name="title" value="{{ old('title', $invoice->title) }}">
                    </div>

                    <div>
                        <label>Bill To</label>
                        <input type="text" name="bill_to_name" required value="{{ old('bill_to_name', $invoice->bill_to_name) }}">
                    </div>

                    <div>
                        <label>Bill To Address (optional)</label>
                        <input type="text" name="bill_to_address" value="{{ old('bill_to_address', $invoice->bill_to_address) }}">
                    </div>

                    <div>
                        <label>Issue Date</label>
                        <input type="date" name="issue_date" required value="{{ old('issue_date', $invoice->issue_date->format('Y-m-d')) }}">
                    </div>

                    <div>
                        <label>Due Date (optional)</label>
                        <input type="date" name="due_date" value="{{ old('due_date', optional($invoice->due_date)->format('Y-m-d')) }}">
                    </div>

                    <div>
                        <label>Status</label>
                        <select name="status">
                            @foreach (['draft', 'sent', 'cancelled'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $invoice->status) === $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="divider">

                <label>Line Items</label>
                <table class="items-table" id="items-table">
                    <thead>
                        <tr>
                            <th style="width:50%">Description</th>
                            <th style="width:15%">Quantity</th>
                            <th style="width:15%">Rate (TZS)</th>
                            <th style="width:15%">Amount</th>
                            <th style="width:5%"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body"></tbody>
                </table>

                <button type="button" class="add-row-btn" id="add-row-btn">+ Add Line Item</button>

                <div class="totals-box">
                    <div class="row"><span>Subtotal</span><span id="subtotal-display">TZS 0.00</span></div>
                    <div class="row">
                        <span>Tax (%)</span>
                        <input type="number" name="tax_percentage" id="tax_percentage" value="{{ old('tax_percentage', $invoice->tax_percentage) }}" min="0" max="100" step="0.1" style="width:80px; text-align:right;">
                    </div>
                    <div class="row"><span>Tax Amount</span><span id="tax-display">TZS 0.00</span></div>
                    <div class="row total"><span>Total</span><span id="total-display">TZS 0.00</span></div>
                </div>

                <hr class="divider">

                <div class="form-grid">
                    <div class="full">
                        <label>Notes / Terms (optional)</label>
                        <textarea name="notes" rows="4">{{ old('notes', $invoice->notes) }}</textarea>
                    </div>

                    <div class="full">
                        <button class="btn" type="submit">Update Invoice</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <template id="row-template">
        <tr class="item-row">
            <td><input type="text" name="items[__i__][description]" required></td>
            <td><input type="number" name="items[__i__][quantity]" class="qty-input" value="1" min="0" step="0.01" required></td>
            <td><input type="number" name="items[__i__][rate]" class="rate-input" value="0" min="0" step="0.01" required></td>
            <td class="amount-cell">TZS 0.00</td>
            <td><button type="button" class="remove-row">&times;</button></td>
        </tr>
    </template>

    <script>
        const existingItems = @json($invoice->items->map(fn($i) => ['description' => $i->description, 'quantity' => (float) $i->quantity, 'rate' => (float) $i->rate]));

        let rowIndex = 0;
        const itemsBody = document.getElementById('items-body');
        const template = document.getElementById('row-template');

        function formatMoney(n) {
            return 'TZS ' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function addRow(data = null) {
            const html = template.innerHTML.replaceAll('__i__', rowIndex);
            const tmp = document.createElement('tbody');
            tmp.innerHTML = html;
            const row = tmp.firstElementChild;

            if (data) {
                row.querySelector('input[name*="[description]"]').value = data.description;
                row.querySelector('.qty-input').value = data.quantity;
                row.querySelector('.rate-input').value = data.rate;
            }

            itemsBody.appendChild(row);
            rowIndex++;
            recalculate();
        }

        function recalculate() {
            let subtotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
                const amount = qty * rate;
                row.querySelector('.amount-cell').textContent = formatMoney(amount);
                subtotal += amount;
            });

            const taxPct = parseFloat(document.getElementById('tax_percentage').value) || 0;
            const taxAmount = subtotal * (taxPct / 100);
            const total = subtotal + taxAmount;

            document.getElementById('subtotal-display').textContent = formatMoney(subtotal);
            document.getElementById('tax-display').textContent = formatMoney(taxAmount);
            document.getElementById('total-display').textContent = formatMoney(total);
        }

        document.getElementById('add-row-btn').addEventListener('click', () => addRow());
        document.getElementById('tax_percentage').addEventListener('input', recalculate);

        itemsBody.addEventListener('input', function (e) {
            if (e.target.classList.contains('qty-input') || e.target.classList.contains('rate-input')) {
                recalculate();
            }
        });

        itemsBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('.item-row').remove();
                recalculate();
            }
        });

        if (existingItems.length) {
            existingItems.forEach(item => addRow(item));
        } else {
            addRow();
        }
    </script>

@endsection
