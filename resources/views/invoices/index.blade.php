@extends('layouts.app')

@section('title', 'Invoices')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('invoices.create') }}">+ New Invoice</a>
@endsection

@section('content')

    <style>
        body { font-family: 'Inter', sans-serif; background: #f4f6f9; }
        .wrapper { max-width: 1100px; margin: 0 auto; padding: 20px; }
        .card { background: #fff; border-radius: 14px; padding: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); }

        .page-header { margin-bottom: 20px; }

        .invoices-heading {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 18px 8px 14px;
            border-radius: 999px;
            background: linear-gradient(135deg, #1f3a5f, #16283f);
            box-shadow: 0 6px 16px rgba(31, 58, 95, 0.25);
        }

        .invoices-heading svg {
            width: 20px;
            height: 20px;
            color: #C9A84C;
            flex-shrink: 0;
        }

        .invoices-heading-text {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.6px;
            background: linear-gradient(90deg, #ffffff, #e9d9a8);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .page-subline {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
            font-size: 13px;
            color: #6b7280;
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

        h2 { font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 16px; }
        .toolbar { display: flex; gap: 10px; margin-bottom: 18px; }
        .toolbar input { flex: 1; padding: 10px 12px; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 14px; }
        .toolbar button { background: #1f3a5f; color: #fff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; padding: 10px 12px; border-bottom: 2px solid #e5e7eb; }
        td { padding: 12px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151; }
        tr:hover td { background: #f9fafb; }
        a.row-link { color: #1f3a5f; font-weight: 600; text-decoration: none; }
        a.row-link:hover { text-decoration: underline; }
        .badge { padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .badge-paid { background: #dcfce7; color: #166534; }
        .badge-unpaid { background: #f3f4f6; color: #4b5563; }
        .badge-partially_paid { background: #fef9c3; color: #854d0e; }
        .badge-overdue { background: #fee2e2; color: #991b1b; }
        .badge-cancelled { background: #e5e7eb; color: #6b7280; }
        .empty { text-align: center; padding: 40px; color: #9ca3af; }
        .pagination-wrap { margin-top: 16px; }
    </style>

    <div class="wrapper">
        <div class="card">
            <div class="page-header">
                <div class="invoices-heading">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 4.5h.008v.008h-.008V13.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <span class="invoices-heading-text">Invoices</span>
                </div>
                <div class="page-subline">
                    <span class="live-tag"><span class="live-dot"></span> {{ $invoices->total() }} total</span>
                    <span class="subline-divider">•</span>
                    <span>Billing and payment tracking for standalone invoices</span>
                </div>
            </div>

            @if (session('success'))
                <div style="background:#dcfce7;color:#166534;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div style="background:#fee2e2;color:#991b1b;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;">{{ session('error') }}</div>
            @endif

            <form method="GET" class="toolbar">
                <input type="text" name="search" placeholder="Search invoice #, client, title..." value="{{ request('search') }}">
                <button type="submit">Search</button>
            </form>

            @if ($invoices->isEmpty())
                <div class="empty">No invoices yet. <a href="{{ route('invoices.create') }}">Create your first invoice</a>.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Bill To</th>
                            <th>Issue Date</th>
                            <th>Total</th>
                            <th>Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            @php
                                $paid = $invoice->payments_sum_amount ?? 0;
                                $balance = $invoice->total_amount - $paid;
                            @endphp
                            <tr>
                                <td><a class="row-link" href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a></td>
                                <td>{{ $invoice->bill_to_name }}</td>
                                <td>{{ $invoice->issue_date->format('M j, Y') }}</td>
                                <td>TZS {{ number_format($invoice->total_amount, 2) }}</td>
                                <td>TZS {{ number_format($balance, 2) }}</td>
                                <td><span class="badge badge-{{ $invoice->payment_status }}">{{ str_replace('_', ' ', $invoice->payment_status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination-wrap">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection
