@extends('layouts.app')

@section('title', 'New Invoice')
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
        }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        .invoice-heading {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 18px 8px 14px;
            border-radius: 999px;
            background: linear-gradient(135deg, #1f3a5f, #16283f);
            box-shadow: 0 6px 16px rgba(31, 58, 95, 0.25);
            margin-bottom: 10px;
        }

        .invoice-heading svg {
            width: 20px;
            height: 20px;
            color: #C9A84C;
            flex-shrink: 0;
        }

        .invoice-heading-text {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.6px;
            background: linear-gradient(90deg, #ffffff, #e9d9a8);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .live-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            color: #16a34a;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.6);
            animation: pulse-dot 1.8s infinite;
        }

        @keyframes pulse-dot {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.55); }
            70% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        .subline-divider {
            color: #d1d5db;
        }

        .subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
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
            box-sizing: border-box;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #1f3a5f;
            outline: none;
            box-shadow: 0 0 0 3px rgba(31, 58, 95, 0.12);
        }

        textarea {
            resize: vertical;
        }

        hr.divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 24px 0;
        }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .items-table th {
            background: #1f3a5f;
            color: #C9A84C;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            text-align: left;
        }

        .items-table td {
            padding: 6px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        .items-table input {
            padding: 8px;
        }

        .items-table .amount-cell {
            font-weight: 600;
            color: #111827;
            padding-top: 14px;
            white-space: nowrap;
        }

        .remove-row {
            background: none;
            border: none;
            color: #dc2626;
            cursor: pointer;
            font-size: 18px;
            padding: 4px 8px;
        }

        .add-row-btn {
            background: #eef2ff;
            color: #1f3a5f;
            border: 1px dashed #c7d2fe;
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .totals-box {
            margin-left: auto;
            width: 360px;
        }

        .totals-box .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            font-size: 14px;
            color: #4b5563;
            gap: 10px;
        }

        .totals-box .row.total {
            border-top: 2px solid #1f3a5f;
            margin-top: 6px;
            padding-top: 10px;
            font-weight: 700;
            font-size: 16px;
            color: #111827;
        }

        .discount-controls {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .discount-controls select {
            width: 92px;
            padding: 7px 8px;
            font-size: 12px;
        }

        .discount-controls input {
            width: 100px;
            padding: 7px 8px;
            text-align: right;
        }

        .btn {
            background: #1f3a5f;
            color: white;
            padding: 11px 20px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
        }

        .btn:hover {
            background: #16283f;
        }

        .error-list {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 13px;
        }

        @media (max-width: 700px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: span 1;
            }

            .totals-box {
                width: 100%;
            }
        }
    </style>

    <div class="wrapper">
        <div class="card">

            <div class="invoice-heading">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m9.75 0a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                </svg>
                <span class="invoice-heading-text">New Invoice</span>
            </div>
            <div class="subtitle">
                <span class="live-tag"><span class="live-dot"></span> Invoice #{{ $invoiceNumber }}</span>
                <span class="subline-divider">&middot;</span>
                standalone invoice, not linked to a project record
            </div>

            @if ($errors->any())
                <div class="error-list">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('invoices.store') }}" id="invoice-form">
                @csrf

                <div class="form-grid">
                    <div class="full">
                        <label>Invoice Title (optional)</label>
                        <input type="text" name="title" placeholder="e.g. Architectural Drawings" value="{{ old('title') }}">
                    </div>

                    <div>
                        <label>Bill To</label>
                        <input type="text" name="bill_to_name" required value="{{ old('bill_to_name') }}" placeholder="Client name">
                    </div>

                    <div>
                        <label>Bill To Address (optional)</label>
                        <input type="text" name="bill_to_address" value="{{ old('bill_to_address') }}">
                    </div>

                    <div>
                        <label>Issue Date</label>
                        <input type="date" name="issue_date" required value="{{ old('issue_date', now()->format('Y-m-d')) }}">
                    </div>

                    <div>
                        <label>Due Date (optional)</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}">
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
                    <div class="row">
                        <span>Subtotal</span>
                        <span id="subtotal-display">TZS 0.00</span>
                    </div>

                    <div class="row">
                        <span>Discount</span>
                        <div class="discount-controls">
                            <select name="discount_type" id="discount_type">
                                <option value="" {{ in_array(old('discount_type'), [null, ''], true) ? 'selected' : '' }}>None</option>
                                <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>%</option>
                                <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>TZS</option>
                            </select>
                            <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value', 0) }}" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="row">
                        <span>Discount Amount</span>
                        <span id="discount-amount-display">TZS 0.00</span>
                    </div>

                    <div class="row">
                        <span>Tax (%)</span>
                        <input type="number" name="tax_percentage" id="tax_percentage" value="{{ old('tax_percentage', 0) }}" min="0" max="100" step="0.1" style="width:80px; text-align:right;">
                    </div>
                    <div class="row">
                        <span>Tax Amount</span>
                        <span id="tax-display">TZS 0.00</span>
                    </div>
                    <div class="row total">
                        <span>Total</span>
                        <span id="total-display">TZS 0.00</span>
                    </div>
                </div>

                <hr class="divider">

                <div class="form-grid">
                    <div class="full">
                        <label>Notes / Terms (optional)</label>
                        <textarea name="notes" rows="4" placeholder="e.g. Payments can be made in two installments of 70% and 30%.">{{ old('notes') }}</textarea>
                    </div>

                    <div class="full">
                        <button class="btn" type="submit">Save Invoice</button>
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
        let rowIndex = 0;
        const itemsBody = document.getElementById('items-body');
        const template = document.getElementById('row-template');
        const discountType = document.getElementById('discount_type');
        const discountValue = document.getElementById('discount_value');
        const taxPercentageInput = document.getElementById('tax_percentage');

        function formatMoney(n) {
            return 'TZS ' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function addRow() {
            const html = template.innerHTML.replaceAll('__i__', rowIndex);
            const tmp = document.createElement('tbody');
            tmp.innerHTML = html;
            itemsBody.appendChild(tmp.firstElementChild);
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

            // Discount: percentage of subtotal, or a flat TZS amount (capped at subtotal)
            let discountAmount = 0;
            const dType = discountType.value;
            const dValue = parseFloat(discountValue.value) || 0;

            if (dType === 'percentage' && dValue > 0) {
                discountAmount = subtotal * (dValue / 100);
            } else if (dType === 'fixed' && dValue > 0) {
                discountAmount = Math.min(dValue, subtotal);
            }

            const discountedSubtotal = subtotal - discountAmount;

            const taxPct = parseFloat(taxPercentageInput.value) || 0;
            const taxAmount = discountedSubtotal * (taxPct / 100);
            const total = discountedSubtotal + taxAmount;

            document.getElementById('subtotal-display').textContent = formatMoney(subtotal);
            document.getElementById('discount-amount-display').textContent = formatMoney(discountAmount);
            document.getElementById('tax-display').textContent = formatMoney(taxAmount);
            document.getElementById('total-display').textContent = formatMoney(total);
        }

        document.getElementById('add-row-btn').addEventListener('click', addRow);
        taxPercentageInput.addEventListener('input', recalculate);
        discountType.addEventListener('change', recalculate);
        discountValue.addEventListener('input', recalculate);

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

        // Start with one row
        addRow();
    </script>

@endsection
