@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number)
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
            max-width: 820px;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 2px solid #C9A84C;
        }

        .invoice-number {
            font-size: 22px;
            font-weight: 800;
            color: #1f3a5f;
        }

        .invoice-date {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }

        .btn-download {
            background: #2563eb;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
        }

        .btn-download:hover {
            background: #1d4ed8;
        }

        .bill-to-label {
            font-size: 11px;
            font-weight: 700;
            color: #1f3a5f;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 4px;
        }

        .bill-to-name {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 13px;
        }

        .items-table th {
            background: #1f3a5f;
            color: #C9A84C;
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0ebe0;
        }

        .items-table .right {
            text-align: right;
        }

        .total-row td {
            font-weight: 700;
            background: #f8f3e6;
            color: #1f3a5f;
            border-top: 2px solid #C9A84C;
        }
    </style>

    <div class="wrapper">
        <div class="card">

            <div class="invoice-header">
                <div>
                    <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                    <div class="invoice-date">
                        Issued {{ $invoice->invoice_date->format('d M Y') }}
                        @if ($invoice->due_date)
                            &middot; Due {{ $invoice->due_date->format('d M Y') }}
                        @endif
                    </div>
                </div>
                @if ($invoice->file_path)
                    <a href="{{ route('invoices.download', $invoice) }}" class="btn-download">⬇ Download PDF</a>
                @endif
            </div>

            <div class="bill-to-label">Bill To</div>
            <div class="bill-to-name">{{ $invoice->bill_to_name }}</div>
            @if ($invoice->bill_to_address)
                <div style="color:#6b7280; font-size:13px; margin-top:2px;">{{ $invoice->bill_to_address }}</div>
            @endif

            <table class="items-table">
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
                            <td class="right">{{ $item['quantity'] }}</td>
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
                <div style="margin-top:20px; font-size:13px; color:#4b5563;">
                    <strong style="color:#1f3a5f;">Notes:</strong> {{ $invoice->notes }}
                </div>
            @endif

        </div>
    </div>
@endsection
