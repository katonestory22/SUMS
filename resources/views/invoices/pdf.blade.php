<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #2d2d2d;
            margin: 0;
            padding: 30px 40px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .header-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .company-name {
            font-size: 13px;
            font-weight: bold;
            color: #1f3a5f;
            letter-spacing: 0.5px;
            margin-top: 8px;
        }

        .doc-title {
            font-size: 26px;
            font-weight: bold;
            color: #374151;
            text-align: right;
        }

        .bill-to-label {
            font-size: 10px;
            color: #9ca3af;
            text-transform: uppercase;
            margin-top: 18px;
        }

        .bill-to-name {
            font-size: 13px;
            font-weight: bold;
            color: #1f3a5f;
        }

        .meta-table {
            width: 220px;
            margin-left: auto;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 4px 0;
            font-size: 11px;
            border: none;
        }

        .meta-table .meta-label {
            color: #6b7280;
        }

        .balance-due-row td {
            background: #f3f4f6;
            padding: 8px 10px !important;
            font-weight: bold;
            color: #1f3a5f;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
        }

        table.items th {
            background: #1f3a5f;
            color: #C9A84C;
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table.items th.num,
        table.items td.num {
            text-align: right;
        }

        table.items td {
            padding: 6px 10px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }

        .totals-table {
            width: 260px;
            margin-left: auto;
            margin-top: 12px;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 4px 10px;
            font-size: 11px;
            border: none;
        }

        .totals-table .label {
            color: #6b7280;
            text-align: left;
        }

        .totals-table .value {
            text-align: right;
        }

        .totals-table .grand-total td {
            border-top: 2px solid #1f3a5f;
            font-weight: bold;
            font-size: 13px;
            color: #1f3a5f;
            padding-top: 8px;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1f3a5f;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #C9A84C;
            padding-bottom: 4px;
            margin-top: 28px;
            margin-bottom: 8px;
        }

        .notes-list, .terms-block {
            font-size: 11px;
            color: #4b5563;
            line-height: 1.6;
        }

        .terms-block .payment-mode {
            font-weight: bold;
            color: #1f3a5f;
            margin-top: 6px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width:55%;">
                <img src="{{ public_path('images/swahililogo.png') }}" style="height:55px;">
                <div class="company-name">SWAHILI UNITS</div>
            </td>
            <td style="width:45%;">
                <div class="doc-title">{{ $invoice->title ?: 'Invoice' }}</div>
            </td>
        </tr>
    </table>

    <table class="header-table">
        <tr>
            <td style="width:55%;">
                <div class="bill-to-label">Bill To:</div>
                <div class="bill-to-name">{{ $invoice->bill_to_name }}</div>
                @if ($invoice->bill_to_address)
                    <div style="font-size:11px;color:#6b7280;">{{ $invoice->bill_to_address }}</div>
                @endif
            </td>
            <td style="width:45%;">
                <table class="meta-table">
                    <tr>
                        <td class="meta-label">Invoice #:</td>
                        <td style="text-align:right;">{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Date:</td>
                        <td style="text-align:right;">{{ $invoice->issue_date->format('M j, Y') }}</td>
                    </tr>
                    @if ($invoice->due_date)
                        <tr>
                            <td class="meta-label">Due Date:</td>
                            <td style="text-align:right;">{{ $invoice->due_date->format('M j, Y') }}</td>
                        </tr>
                    @endif
                    <tr class="balance-due-row">
                        <td>Balance Due:</td>
                        <td style="text-align:right;">TZS {{ number_format($invoice->balance(), 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Item</th>
                <th class="num">Quantity</th>
                <th class="num">Rate</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="num">{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                    <td class="num">TZS {{ number_format($item->rate, 2) }}</td>
                    <td class="num">TZS {{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td class="label">Subtotal:</td>
            <td class="value">TZS {{ number_format($invoice->subtotal, 2) }}</td>
        </tr>

        @if ($invoice->discount_amount > 0)
            <tr>
                <td class="label">
                    Discount
                    @if ($invoice->discount_type === 'percentage')
                        ({{ rtrim(rtrim(number_format($invoice->discount_value, 2), '0'), '.') }}%)
                    @endif
                    :
                </td>
                <td class="value">&minus; TZS {{ number_format($invoice->discount_amount, 2) }}</td>
            </tr>
        @endif

        <tr>
            <td class="label">Tax ({{ rtrim(rtrim(number_format($invoice->tax_percentage, 2), '0'), '.') }}%):</td>
            <td class="value">TZS {{ number_format($invoice->tax_amount, 2) }}</td>
        </tr>
        <tr class="grand-total">
            <td>Total:</td>
            <td class="value">TZS {{ number_format($invoice->total_amount, 2) }}</td>
        </tr>
    </table>

    @if ($invoice->notes)
        <div class="section-title">Notes</div>
        <div class="notes-list">{!! nl2br(e($invoice->notes)) !!}</div>
    @endif

    {{-- Hardcoded payment terms / payment instructions block --}}
    <div class="section-title">Terms</div>
    <div class="terms-block">
        <div style="font-weight:bold;">Payment Modes.</div>

        <div class="payment-mode">1. Bank</div>
        Bank Name: Stanbic Bank<br>
        Account Number (TZS): 9120003763372<br>
        Account Number (USD): 9120003764085<br>
        Account Name: Swahili Units

        <div class="payment-mode">2. Mobile Money</div>
        Phone Numbers: +255762156762<br>
        Lipa Number (Voda): 5573030

        <div class="payment-mode">3. Cash</div>
    </div>

</body>
</html>
