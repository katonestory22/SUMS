@extends('layouts.app')

@section('title', 'New Invoice')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('finance.dashboard') }}">Dashboard</a>
    <a href="{{ route('invoices.index') }}">Back to Invoices</a>
@endsection

@section('content')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .wrapper {
            max-width: 960px;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        h2 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 18px;
        }

        .invoice-number-badge {
            display: inline-block;
            background: #f8f3e6;
            border: 1px solid #e8dfc8;
            color: #1f3a5f;
            font-weight: 700;
            font-size: 13px;
            padding: 6px 14px;
            border-radius: 8px;
            margin-bottom: 18px;
            letter-spacing: 0.5px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .full {
            grid-column: span 2;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            display: block;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            background: #fff;
            transition: 0.2s ease;
            box-sizing: border-box;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        textarea {
            resize: vertical;
        }

        .section-heading {
            font-size: 13px;
            font-weight: 700;
            color: #1f3a5f;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin: 28px 0 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #C9A84C;
        }

        /* ── ITEMS TABLE ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            padding: 0 8px 8px;
        }

        .items-table td {
            padding: 6px 8px;
            vertical-align: top;
        }

        .items-table td.col-desc {
            width: 44%;
        }

        .items-table td.col-qty,
        .items-table td.col-rate {
            width: 16%;
        }

        .items-table td.col-amount {
            width: 16%;
        }

        .items-table td.col-remove {
            width: 40px;
            text-align: center;
        }

        .amount-display {
            padding: 10px 12px;
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        .remove-row-btn {
            background: none;
            border: none;
            color: #dc2626;
            font-size: 18px;
            cursor: pointer;
            line-height: 1;
            padding: 6px;
        }

        .remove-row-btn:hover {
            color: #b91c1c;
        }

        .add-row-btn {
            background: #eff6ff;
            color: #2563eb;
            border: 1px dashed #93c5fd;
            padding: 9px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            margin-top: 8px;
        }

        .add-row-btn:hover {
            background: #dbeafe;
        }

        .total-strip {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 14px;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 2px solid #C9A84C;
        }

        .total-label {
            font-size: 13px;
            font-weight: 700;
            color: #1f3a5f;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .total-value {
            font-size: 24px;
            font-weight: 800;
            color: #1f3a5f;
        }

        .btn {
            background: #2563eb;
            color: white;
            padding: 12px 14px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: 0.2s;
            width: 100%;
            margin-top: 24px;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        @media (max-width: 700px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: span 1;
            }

            .items-table thead {
                display: none;
            }

            .items-table td {
                display: block;
                width: 100% !important;
            }
        }
    </style>

    <div class="wrapper">
        <div class="card">

            <h2>New Invoice</h2>
            <div class="subtitle">Build a custom invoice with your own line items</div>
            <div class="invoice-number-badge">{{ $nextInvoiceNumber }}</div>

            <form method="POST" action="{{ route('invoices.store') }}" id="invoice-form">
                @csrf

                <div class="form-grid">

                    {{-- BILL TO --}}
                    <div class="full">
                        <label>Bill To (Name / Company)</label>
                        <input type="text" name="bill_to_name" required value="{{ old('bill_to_name') }}">
                    </div>

                    <div class="full">
                        <label>Address (optional)</label>
                        <textarea name="bill_to_address" rows="2">{{ old('bill_to_address') }}</textarea>
                    </div>

                    <div>
                        <label>Email (optional)</label>
                        <input type="email" name="bill_to_email" value="{{ old('bill_to_email') }}">
                    </div>

                    <div>
                        <label>Phone (optional)</label>
                        <input type="text" name="bill_to_phone" value="{{ old('bill_to_phone') }}">
                    </div>

                    <div>
                        <label>Invoice Date</label>
                        <input type="date" name="invoice_date" required value="{{ old('invoice_date', now()->format('Y-m-d')) }}">
                    </div>

                    <div>
                        <label>Due Date (optional)</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}">
                    </div>

                </div>

                <div class="section-heading">Line Items</div>

                <table class="items-table" id="items-table">
                    <thead>
                        <tr>
                            <th class="col-desc">Description</th>
                            <th class="col-qty">Qty</th>
                            <th class="col-rate">Rate</th>
                            <th class="col-amount">Amount</th>
                            <th class="col-remove"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body">
                        {{-- rows injected by JS --}}
                    </tbody>
                </table>

                <button type="button" class="add-row-btn" id="add-row-btn">+ Add Line Item</button>

                <div class="total-strip">
                    <span class="total-label">Total</span>
                    <span class="total-value" id="total-display">0.00</span>
                </div>

                <div class="form-grid" style="margin-top: 20px;">
                    <div class="full">
                        <label>Notes (optional)</label>
                        <textarea name="notes" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <button class="btn" type="submit">Save & Generate Invoice</button>

            </form>

        </div>
    </div>

    <script>
        const itemsBody = document.getElementById('items-body');
        const addRowBtn = document.getElementById('add-row-btn');
        const totalDisplay = document.getElementById('total-display');
        let rowCount = 0;

        function formatMoney(value) {
            return Number(value || 0).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function addRow() {
            const index = rowCount++;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="col-desc">
                    <input type="text" name="items[${index}][description]" placeholder="Item description" required>
                </td>
                <td class="col-qty">
                    <input type="number" step="0.01" min="0.01" name="items[${index}][quantity]" class="qty-input" value="1" required>
                </td>
                <td class="col-rate">
                    <input type="number" step="0.01" min="0" name="items[${index}][rate]" class="rate-input" value="0" required>
                </td>
                <td class="col-amount">
                    <div class="amount-display">0.00</div>
                </td>
                <td class="col-remove">
                    <button type="button" class="remove-row-btn" title="Remove">&times;</button>
                </td>
            `;
            itemsBody.appendChild(row);

            const qtyInput = row.querySelector('.qty-input');
            const rateInput = row.querySelector('.rate-input');
            const amountDisplay = row.querySelector('.amount-display');
            const removeBtn = row.querySelector('.remove-row-btn');

            function recalcRow() {
                const qty = parseFloat(qtyInput.value) || 0;
                const rate = parseFloat(rateInput.value) || 0;
                amountDisplay.textContent = formatMoney(qty * rate);
                recalcTotal();
            }

            qtyInput.addEventListener('input', recalcRow);
            rateInput.addEventListener('input', recalcRow);

            removeBtn.addEventListener('click', function() {
                if (itemsBody.children.length > 1) {
                    row.remove();
                    recalcTotal();
                }
            });

            recalcRow();
        }

        function recalcTotal() {
            let total = 0;
            itemsBody.querySelectorAll('tr').forEach(function(row) {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
                total += qty * rate;
            });
            totalDisplay.textContent = formatMoney(total);
        }

        addRowBtn.addEventListener('click', addRow);

        // start with one row
        addRow();
    </script>

@endsection
