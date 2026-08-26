<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #2d2d2d;
            margin: 0;
            padding: 0;
        }

        /* ── HEADER BANNER ── */
        .report-header {
            background: #1f3a5f;
            padding: 20px 24px;
            margin-bottom: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-logo-cell {
            width: 64px;
            vertical-align: middle;
        }

        .header-brand-cell {
            vertical-align: middle;
            padding-left: 14px;
        }

        .brand-name {
            font-size: 15px;
            font-weight: bold;
            color: #C9A84C;
            letter-spacing: 1px;
        }

        .brand-sub {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 2px;
            letter-spacing: 0.5px;
        }

        .header-report-cell {
            text-align: right;
            vertical-align: middle;
        }

        .report-type-label {
            font-size: 18px;
            font-weight: bold;
            color: #C9A84C;
            letter-spacing: 0.5px;
        }

        .report-date-label {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* ── GOLD ACCENT BAR ── */
        .accent-bar {
            background: #C9A84C;
            height: 4px;
            width: 100%;
        }

        /* ── META INFO STRIP ── */
        .meta-strip {
            background: #f8f6f0;
            border: 1px solid #e8dfc8;
            border-radius: 6px;
            padding: 12px 16px;
            margin: 20px 0 24px;
        }

        .meta-strip table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .meta-strip td {
            padding: 3px 8px 3px 0;
            border: none;
            font-size: 12px;
            color: #4b5563;
        }

        .meta-label {
            font-weight: bold;
            color: #1f3a5f;
            width: 90px;
        }

        /* ── BILL TO ── */
        .bill-to {
            margin: 0 0 20px;
        }

        .bill-to-label {
            font-size: 11px;
            font-weight: bold;
            color: #1f3a5f;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 4px;
        }

        .bill-to-name {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
        }

        .bill-to-detail {
            font-size: 12px;
            color: #4b5563;
            margin-top: 2px;
        }

        /* ── SECTION HEADINGS ── */
        h2 {
            font-size: 13px;
            font-weight: bold;
            color: #1f3a5f;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 24px;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 2px solid #C9A84C;
        }

        /* ── TABLES ── */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        th {
            background: #1f3a5f;
            color: #C9A84C;
            padding: 9px 12px;
            text-align: left;
            font-size: 11px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        td {
            padding: 8px 12px;
            border-bottom: 1px solid #f0ebe0;
            font-size: 12px;
            color: #374151;
        }

        tbody tr:nth-child(even) td {
            background: #fdf9f2;
        }

        .total-row td {
            font-weight: bold;
            background: #f8f3e6;
            color: #1f3a5f;
            border-top: 2px solid #C9A84C;
            border-bottom: 2px solid #C9A84C;
            font-size: 14px;
        }

        .right {
            text-align: right;
        }

        .notes {
            margin-top: 22px;
            font-size: 12px;
            color: #4b5563;
        }

        .notes-label {
            font-weight: bold;
            color: #1f3a5f;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.8px;
            margin-bottom: 4px;
        }

        /* ── FOOTER ── */
        .report-footer {
            margin-top: 40px;
            border-top: 1px solid #e8dfc8;
            padding-top: 10px;
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            font-size: 10px;
            color: #9ca3af;
        }

        .footer-right {
            display: table-cell;
            text-align: right;
            font-size: 10px;
            color: #9ca3af;
        }

        .gold {
            color: #C9A84C;
        }
    </style>
</head>
@php
    $logo = public_path('images/swahililogo.png');
@endphp

<body>

    {{-- ── HEADER ── --}}
    <div class="report-header">
        <table class="header-table">
            <tr>
                <td class="header-logo-cell">
                    <img src="{{ $logo }}" width="54" height="54">
                </td>
                <td class="header-brand-cell">
                    <div class="brand-name">SUMS</div>
                    <div class="brand-sub">Swahili Units Management System</div>
                </td>
                <td class="header-report-cell">
                    <div class="report-type-label">INVOICE</div>
                    <div class="report-date-label">{{ $invoice->invoice_number }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="accent-bar"></div>

    {{-- ── INVOICE META ── --}}
    <div class="meta-strip">
        <table>
            <tr>
                <td class="meta-label">Invoice Date</td>
                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</td>
                <td class="meta-label">Due Date</td>
                <td>{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : 'On receipt' }}</td>
            </tr>
        </table>
    </div>

    {{-- ── BILL TO ── --}}
    <div class="bill-to">
        <div class="bill-to-label">Bill To</div>
        <div class="bill-to-name">{{ $invoice->bill_to_name }}</div>
        @if ($invoice->bill_to_address)
            <div class="bill-to-detail">{{ $invoice->bill_to_address }}</div>
        @endif
        @if ($invoice->bill_to_email)
            <div class="bill-to-detail">{{ $invoice->bill_to_email }}</div>
        @endif
        @if ($invoice->bill_to_phone)
            <div class="bill-to-detail">{{ $invoice->bill_to_phone }}</div>
        @endif
    </div>

    {{-- ── LINE ITEMS ── --}}
    <h2>Line Items</h2>
    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Qty</th>
                <th class="right">Rate</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item['description'] }}</td>
                    <td class="right">{{ rtrim(rtrim(number_format($item['quantity'], 2), '0'), '.') }}</td>
                    <td class="right">{{ number_format($item['rate'], 2) }}</td>
                    <td class="right">{{ number_format($item['amount'], 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="right">TOTAL</td>
                <td class="right">{{ number_format($invoice->total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if ($invoice->notes)
        <div class="notes">
            <div class="notes-label">Notes</div>
            {{ $invoice->notes }}
        </div>
    @endif

    {{-- ── FOOTER ── --}}
    <div class="report-footer">
        <div class="footer-left">Generated by <span class="gold">SUMS</span> — Swahili Units Management System</div>
        <div class="footer-right">{{ now()->format('d M Y, H:i') }}</div>
    </div>

</body>

</html>
