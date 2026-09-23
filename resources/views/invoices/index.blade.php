@extends('layouts.app')

@section('title', 'Invoices')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">

        Dashboard
    </a>
@endsection

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Safe Summary Calculations
    |--------------------------------------------------------------------------
    | Controller values are used when available. The fallbacks keep the
    | page functional even if those values were not supplied.
    */

    $fallbackBilled = $invoices->sum(function ($invoice) {
        return (float) $invoice->total_amount;
    });

    $fallbackPaid = $invoices->sum(function ($invoice) {
        return (float) ($invoice->payments_sum_amount ?? 0);
    });

    $fallbackOutstanding = max($fallbackBilled - $fallbackPaid, 0);

    $displayBilled = $totalBilled ?? $fallbackBilled;
    $displayPaid = $totalPaid ?? $fallbackPaid;
    $displayOutstanding = $totalOutstanding ?? $fallbackOutstanding;
@endphp

<div class="invoice-page">

    {{-- ================================================================
         PAGE HEADER
    ================================================================= --}}
    <div class="invoices-heading">

        <div class="heading-icon">
            <svg width="25" height="25" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.8">
                <path d="M7 3h10a2 2 0 0 1 2 2v16l-3-2-3 2-3-2-3 2V5a2 2 0 0 1 2-2z"/>
                <path d="M9 8h6"/>
                <path d="M9 12h6"/>
                <path d="M9 16h3"/>
            </svg>
        </div>

        <div class="heading-content">
            <h1>Invoices</h1>

            <p>
                <span class="live-dot"></span>
                Invoice management and payment tracking
                <span class="heading-separator">•</span>
                {{ number_format($invoices->total()) }} invoice{{ $invoices->total() == 1 ? '' : 's' }}
            </p>
        </div>

    </div>


    {{-- ================================================================
         ALERTS
    ================================================================= --}}
    @if(session('success'))
        <div class="alert alert-success">
            <div class="alert-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </div>

            <div>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <div class="alert-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M12 9v4"/>
                    <path d="M12 17h.01"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
            </div>

            <div>
                {{ session('error') }}
            </div>
        </div>
    @endif


    {{-- ================================================================
         SUMMARY CARDS
    ================================================================= --}}
    <div class="summary-grid">

        {{-- Total Invoices --}}
        <div class="summary-card">
            <div class="summary-card-top">

                <div>
                    <div class="summary-label">Total Invoices</div>

                    <div class="summary-value">
                        {{ number_format($invoices->total()) }}
                    </div>
                </div>

                <div class="summary-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <path d="M7 3h10a2 2 0 0 1 2 2v16l-3-2-3 2-3-2-3 2V5a2 2 0 0 1 2-2z"/>
                        <path d="M9 8h6"/>
                        <path d="M9 12h6"/>
                        <path d="M9 16h3"/>
                    </svg>
                </div>

            </div>

            <div class="summary-accent"></div>
        </div>


        {{-- Total Billed --}}
        <div class="summary-card">
            <div class="summary-card-top">

                <div>
                    <div class="summary-label">Total Billed</div>

                    <div class="summary-value money">
                        TZS {{ number_format($displayBilled, 2) }}
                    </div>
                </div>

                <div class="summary-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <path d="M12 1v22"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H7"/>
                    </svg>
                </div>

            </div>

            <div class="summary-accent"></div>
        </div>


        {{-- Total Paid --}}
        <div class="summary-card">
            <div class="summary-card-top">

                <div>
                    <div class="summary-label">Total Paid</div>

                    <div class="summary-value money">
                        TZS {{ number_format($displayPaid, 2) }}
                    </div>
                </div>

                <div class="summary-icon paid">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                </div>

            </div>

            <div class="summary-accent"></div>
        </div>


        {{-- Outstanding --}}
        <div class="summary-card">
            <div class="summary-card-top">

                <div>
                    <div class="summary-label">Outstanding</div>

                    <div class="summary-value money outstanding-value">
                        TZS {{ number_format($displayOutstanding, 2) }}
                    </div>
                </div>

                <div class="summary-icon outstanding">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 8v4"/>
                        <path d="M12 16h.01"/>
                    </svg>
                </div>

            </div>

            <div class="summary-accent"></div>
        </div>

    </div>


    {{-- ================================================================
         GENERATE NEW INVOICE CTA
    ================================================================= --}}
    <div class="create-invoice-cta">

        <div class="cta-glow"></div>

        <div class="cta-content">

            <div class="cta-icon">
                <svg width="27" height="27" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8">
                    <path d="M7 3h10a2 2 0 0 1 2 2v16l-3-2-3 2-3-2-3 2V5a2 2 0 0 1 2-2z"/>
                    <path d="M12 8v6"/>
                    <path d="M9 11h6"/>
                </svg>
            </div>

            <div class="cta-text">
                <div class="cta-kicker">
                    INVOICE MANAGEMENT
                </div>

                <h2>
                    Generate a New Invoice
                </h2>

                <p>
                    Create a professional invoice, add your line items,
                    apply discounts or tax, and issue it to your client.
                </p>
            </div>

        </div>

        <a href="{{ route('invoices.create') }}" class="create-invoice-btn">

            <span>Create Invoice</span>

            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2">
                <path d="M5 12h14"/>
                <path d="M13 6l6 6-6 6"/>
            </svg>

        </a>

    </div>


    {{-- ================================================================
         INVOICE REGISTER
    ================================================================= --}}
    <div class="card">

        <div class="card-header">

            <div>
                <h2>Invoice Register</h2>

                <p>
                    Select an invoice to open its full workspace,
                    payment history and actions.
                </p>
            </div>

        </div>


        {{-- ============================================================
             FILTER TOOLBAR
        ============================================================= --}}
        <form method="GET"
              action="{{ route('invoices.index') }}"
              class="invoice-toolbar">

            <div class="search-box">

                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-3.5-3.5"/>
                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search invoice #, client or title..."
                >

            </div>


            <select name="status" class="status-select">

                <option value="">All Statuses</option>

                <option value="unpaid"
                    {{ request('status') === 'unpaid' ? 'selected' : '' }}>
                    Unpaid
                </option>

                <option value="partially_paid"
                    {{ request('status') === 'partially_paid' ? 'selected' : '' }}>
                    Partially Paid
                </option>

                <option value="paid"
                    {{ request('status') === 'paid' ? 'selected' : '' }}>
                    Paid
                </option>

                <option value="overdue"
                    {{ request('status') === 'overdue' ? 'selected' : '' }}>
                    Overdue
                </option>

                <option value="cancelled"
                    {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>

            </select>


            <button type="submit" class="search-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-3.5-3.5"/>
                </svg>

                Search
            </button>


            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('invoices.index') }}" class="clear-btn">
                    Clear
                </a>
            @endif

        </form>


        {{-- ============================================================
             EMPTY STATE
        ============================================================= --}}
        @if($invoices->count() === 0)

            <div class="empty-state">

                <div class="empty-icon">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <path d="M7 3h10a2 2 0 0 1 2 2v16l-3-2-3 2-3-2-3 2V5a2 2 0 0 1 2-2z"/>
                        <path d="M9 9h6"/>
                        <path d="M9 13h6"/>
                    </svg>
                </div>

                <h3>
                    {{ request()->filled('search') || request()->filled('status')
                        ? 'No invoices found'
                        : 'No invoices yet' }}
                </h3>

                <p>
                    {{ request()->filled('search') || request()->filled('status')
                        ? 'Try adjusting your search or status filter.'
                        : 'Create your first invoice to start tracking billing and payments.' }}
                </p>

                @if(request()->filled('search') || request()->filled('status'))

                    <a href="{{ route('invoices.index') }}" class="empty-action">
                        Clear Filters
                    </a>

                @else

                    <a href="{{ route('invoices.create') }}" class="empty-action">
                        + Create Invoice
                    </a>

                @endif

            </div>

        @else

            {{-- ========================================================
                 INVOICE TABLE
            ========================================================= --}}
            <div class="table-wrapper">

                <table class="invoice-table">

                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Bill To</th>
                            <th>Issue Date</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($invoices as $invoice)

                            @php
                                $paid = (float) ($invoice->payments_sum_amount ?? 0);
                                $total = (float) $invoice->total_amount;
                                $balance = max($total - $paid, 0);
                            @endphp

                            <tr>

                                {{-- Invoice --}}
                                <td>
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                       class="invoice-number">
                                        {{ $invoice->invoice_number }}
                                    </a>

                                    @if($invoice->title)
                                        <div class="invoice-title">
                                            {{ $invoice->title }}
                                        </div>
                                    @endif
                                </td>


                                {{-- Bill To --}}
                                <td>
                                    <div class="client-name">
                                        {{ $invoice->bill_to }}
                                    </div>

                                    @if($invoice->bill_to_address)
                                        <div class="client-address">
                                            {{ $invoice->bill_to_address }}
                                        </div>
                                    @endif
                                </td>


                                {{-- Issue Date --}}
                                <td>
                                    <span class="date-text">
                                        {{ $invoice->issue_date->format('M j, Y') }}
                                    </span>
                                </td>


                                {{-- Total --}}
                                <td>
                                    <span class="amount">
                                        TZS {{ number_format($total, 2) }}
                                    </span>
                                </td>


                                {{-- Paid --}}
                                <td>
                                    <span class="amount paid-amount">
                                        TZS {{ number_format($paid, 2) }}
                                    </span>
                                </td>


                                {{-- Balance --}}
                                <td>
                                    @if($balance <= 0)

                                        <span class="amount balance-paid">
                                            TZS 0.00
                                        </span>

                                    @else

                                        <span class="amount balance-due">
                                            TZS {{ number_format($balance, 2) }}
                                        </span>

                                    @endif
                                </td>


                                {{-- Status --}}
                                <td>

                                    <span class="status-badge badge-{{ $invoice->payment_status }}">
                                        {{ str_replace('_', ' ', $invoice->payment_status) }}
                                    </span>

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="actions">

                                        <a href="{{ route('invoices.show', $invoice) }}"
                                           class="action-btn action-view"
                                           title="View Invoice">

                                            <svg width="15" height="15"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2">

                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                                <circle cx="12" cy="12" r="3"/>

                                            </svg>

                                            View

                                        </a>


                                        <a href="{{ route('invoices.download', $invoice) }}"
                                           class="action-btn action-pdf"
                                           title="Download PDF">

                                            <svg width="15" height="15"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2">

                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                <path d="M14 2v6h6"/>
                                                <path d="M8 13h8"/>
                                                <path d="M8 17h6"/>

                                            </svg>

                                            PDF

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- ========================================================
                 PAGINATION
            ========================================================= --}}
            <div class="pagination-wrapper">
                {{ $invoices->appends(request()->query())->links() }}
            </div>

        @endif

    </div>

</div>


{{-- ====================================================================
     STYLES
===================================================================== --}}
<style>

    * {
        box-sizing: border-box;
    }

    body {
        background: #f4f6f9;
        font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .invoice-page {
        width: 100%;
        max-width: 1250px;
        margin: 0 auto;
        padding: 20px;
    }


    /* ================================================================
       PAGE HEADER
    ================================================================= */

    .invoices-heading {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .heading-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        background: linear-gradient(135deg, #1f3a5f, #16283f);
        color: #C9A84C;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 22px rgba(31, 58, 95, 0.18);
        flex-shrink: 0;
    }

    .heading-content h1 {
        margin: 0;
        font-size: 27px;
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, #1f3a5f, #C9A84C);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .heading-content p {
        margin: 5px 0 0;
        color: #667085;
        font-size: 13px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
    }

    .live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #22a06b;
        box-shadow: 0 0 0 4px rgba(34, 160, 107, 0.10);
    }

    .heading-separator {
        color: #b8bec8;
    }


    /* ================================================================
       ALERTS
    ================================================================= */

    .alert {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px 15px;
        border-radius: 10px;
        margin-bottom: 18px;
        font-size: 13px;
        font-weight: 600;
    }

    .alert-success {
        background: #ecfdf3;
        border: 1px solid #b7ebcd;
        color: #176b45;
    }

    .alert-error {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        color: #b4232f;
    }

    .alert-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }


    /* ================================================================
       SUMMARY CARDS
    ================================================================= */

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 18px;
    }

    .summary-card {
        position: relative;
        background: #ffffff;
        border: 1px solid #e7eaf0;
        border-radius: 14px;
        padding: 18px;
        overflow: hidden;
        box-shadow: 0 5px 18px rgba(16, 24, 40, 0.045);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(16, 24, 40, 0.08);
    }

    .summary-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
    }

    .summary-label {
        color: #667085;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .summary-value {
        margin-top: 7px;
        color: #172033;
        font-size: 22px;
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -0.4px;
    }

    .summary-value.money {
        font-size: 18px;
        letter-spacing: -0.2px;
    }

    .outstanding-value {
        color: #b4232f;
    }

    .summary-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: #eef2f7;
        color: #1f3a5f;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .summary-icon.paid {
        background: #ecfdf3;
        color: #18875b;
    }

    .summary-icon.outstanding {
        background: #fff1f2;
        color: #c53545;
    }

    .summary-accent {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #1f3a5f, #C9A84C);
        opacity: 0.8;
    }


    /* ================================================================
       CREATE INVOICE CTA
    ================================================================= */

    .create-invoice-cta {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        min-height: 122px;
        margin-bottom: 20px;
        padding: 23px 25px;
        overflow: hidden;
        border-radius: 16px;
        background: linear-gradient(135deg, #1f3a5f 0%, #16283f 72%, #102238 100%);
        border: 1px solid rgba(201, 168, 76, 0.28);
        box-shadow: 0 12px 30px rgba(22, 40, 63, 0.16);
    }

    .cta-glow {
        position: absolute;
        width: 210px;
        height: 210px;
        right: 135px;
        top: -115px;
        border-radius: 50%;
        background: rgba(201, 168, 76, 0.10);
        pointer-events: none;
    }

    .cta-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .cta-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        background: rgba(201, 168, 76, 0.14);
        border: 1px solid rgba(201, 168, 76, 0.38);
        color: #e3c66f;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .cta-kicker {
        margin-bottom: 4px;
        color: #e3c66f;
        font-size: 10px;
        line-height: 1;
        font-weight: 800;
        letter-spacing: 1.25px;
    }

    .cta-text h2 {
        margin: 0;
        color: #ffffff;
        font-size: 21px;
        line-height: 1.2;
        font-weight: 800;
        letter-spacing: -0.3px;
    }

    .cta-text p {
        margin: 5px 0 0;
        color: rgba(255, 255, 255, 0.68);
        font-size: 12.5px;
        line-height: 1.5;
    }

    .create-invoice-btn {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-width: 168px;
        padding: 13px 19px;
        border-radius: 10px;
        background: #C9A84C;
        color: #16283f;
        text-decoration: none;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
        box-shadow: 0 7px 18px rgba(0, 0, 0, 0.18);
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .create-invoice-btn:hover {
        background: #d8b95e;
        color: #122238;
        transform: translateY(-2px);
        box-shadow: 0 10px 23px rgba(0, 0, 0, 0.23);
    }

    .create-invoice-btn:active {
        transform: translateY(0);
    }


    /* ================================================================
       MAIN CARD
    ================================================================= */

    .card {
        background: #ffffff;
        border: 1px solid #e7eaf0;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 5px 18px rgba(16, 24, 40, 0.045);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 20px;
    }

    .card-header h2 {
        margin: 0;
        color: #172033;
        font-size: 18px;
        font-weight: 800;
        letter-spacing: -0.2px;
    }

    .card-header p {
        margin: 5px 0 0;
        color: #7a8495;
        font-size: 12.5px;
        line-height: 1.5;
    }


    /* ================================================================
       TOOLBAR
    ================================================================= */

    .invoice-toolbar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .search-box svg {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #98a2b3;
        pointer-events: none;
    }

    .search-box input {
        width: 100%;
        height: 42px;
        padding: 0 13px 0 39px;
        border: 1px solid #dfe3ea;
        border-radius: 9px;
        outline: none;
        color: #172033;
        background: #ffffff;
        font-family: inherit;
        font-size: 13px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .search-box input:focus {
        border-color: #1f3a5f;
        box-shadow: 0 0 0 3px rgba(31, 58, 95, 0.08);
    }

    .status-select {
        height: 42px;
        min-width: 165px;
        padding: 0 35px 0 12px;
        border: 1px solid #dfe3ea;
        border-radius: 9px;
        outline: none;
        color: #344054;
        background: #ffffff;
        font-family: inherit;
        font-size: 13px;
        cursor: pointer;
    }

    .search-btn,
    .clear-btn {
        height: 42px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 0 15px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .search-btn {
        border: 1px solid #1f3a5f;
        background: #1f3a5f;
        color: #ffffff;
    }

    .search-btn:hover {
        background: #16283f;
    }

    .clear-btn {
        border: 1px solid #dfe3ea;
        background: #ffffff;
        color: #475467;
    }

    .clear-btn:hover {
        background: #f8fafc;
    }


    /* ================================================================
       TABLE
    ================================================================= */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #eaecf0;
        border-radius: 11px;
    }

    .invoice-table {
        width: 100%;
        min-width: 980px;
        border-collapse: collapse;
    }

    .invoice-table th {
        padding: 12px 13px;
        background: #f8fafc;
        border-bottom: 1px solid #eaecf0;
        color: #667085;
        font-size: 10.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.55px;
        text-align: left;
        white-space: nowrap;
    }

    .invoice-table td {
        padding: 14px 13px;
        border-bottom: 1px solid #eef0f3;
        color: #344054;
        font-size: 12.5px;
        vertical-align: middle;
    }

    .invoice-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .invoice-table tbody tr {
        transition: background 0.15s ease;
    }

    .invoice-table tbody tr:hover {
        background: #fafbfd;
    }


    /* ================================================================
       TABLE CONTENT
    ================================================================= */

    .invoice-number {
        color: #1f3a5f;
        font-weight: 800;
        text-decoration: none;
        font-size: 13px;
    }

    .invoice-number:hover {
        color: #C9A84C;
    }

    .invoice-title {
        max-width: 180px;
        margin-top: 3px;
        color: #8a93a3;
        font-size: 11px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .client-name {
        color: #273246;
        font-weight: 700;
    }

    .client-address {
        max-width: 190px;
        margin-top: 3px;
        color: #98a2b3;
        font-size: 11px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .date-text {
        color: #667085;
        white-space: nowrap;
    }

    .amount {
        color: #344054;
        font-weight: 700;
        white-space: nowrap;
    }

    .paid-amount {
        color: #18875b;
    }

    .balance-paid {
        color: #18875b;
    }

    .balance-due {
        color: #c53545;
    }


    /* ================================================================
       STATUS BADGES
    ================================================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 10.5px;
        line-height: 1;
        font-weight: 800;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .badge-paid {
        background: #ecfdf3;
        color: #18794e;
    }

    .badge-unpaid {
        background: #fff7e6;
        color: #a66a00;
    }

    .badge-partially_paid {
        background: #eef4ff;
        color: #315b9b;
    }

    .badge-overdue {
        background: #fff1f2;
        color: #b4232f;
    }

    .badge-cancelled {
        background: #f2f4f7;
        color: #667085;
    }


    /* ================================================================
       ACTIONS
    ================================================================= */

    .actions {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        min-height: 32px;
        padding: 0 9px;
        border-radius: 7px;
        border: 1px solid transparent;
        text-decoration: none;
        font-size: 11px;
        font-weight: 700;
        transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }

    .action-view {
        background: #eef4ff;
        color: #315b9b;
        border-color: #dbe7ff;
    }

    .action-view:hover {
        background: #e3edff;
    }

    .action-pdf {
        background: #f2f4f7;
        color: #475467;
        border-color: #e4e7ec;
    }

    .action-pdf:hover {
        background: #e7e9ed;
    }


    /* ================================================================
       EMPTY STATE
    ================================================================= */

    .empty-state {
        padding: 55px 20px;
        text-align: center;
        border: 1px dashed #d9dee7;
        border-radius: 12px;
        background: #fbfcfd;
    }

    .empty-icon {
        width: 65px;
        height: 65px;
        margin: 0 auto 15px;
        border-radius: 17px;
        background: #eef2f7;
        color: #1f3a5f;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state h3 {
        margin: 0;
        color: #273246;
        font-size: 17px;
        font-weight: 800;
    }

    .empty-state p {
        max-width: 430px;
        margin: 7px auto 17px;
        color: #7a8495;
        font-size: 12.5px;
        line-height: 1.5;
    }

    .empty-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 15px;
        border-radius: 8px;
        background: #1f3a5f;
        color: #ffffff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
    }

    .empty-action:hover {
        background: #16283f;
    }


    /* ================================================================
       PAGINATION
    ================================================================= */

    .pagination-wrapper {
        margin-top: 18px;
    }


    /* ================================================================
       RESPONSIVE
    ================================================================= */

    @media (max-width: 1000px) {

        .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .create-invoice-cta {
            align-items: flex-start;
        }

    }


    @media (max-width: 760px) {

        .invoice-page {
            padding: 15px;
        }

        .create-invoice-cta {
            flex-direction: column;
            align-items: stretch;
            gap: 17px;
            padding: 20px;
        }

        .cta-content {
            align-items: flex-start;
        }

        .create-invoice-btn {
            width: 100%;
        }

        .invoice-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box,
        .status-select,
        .search-btn,
        .clear-btn {
            width: 100%;
        }

    }


    @media (max-width: 600px) {

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .invoices-heading {
            align-items: flex-start;
        }

        .heading-icon {
            width: 47px;
            height: 47px;
            border-radius: 12px;
        }

        .heading-content h1 {
            font-size: 23px;
        }

        .heading-content p {
            font-size: 12px;
        }

        .card {
            padding: 15px;
        }

        .card-header {
            margin-bottom: 15px;
        }

        .cta-icon {
            width: 48px;
            height: 48px;
        }

        .cta-text h2 {
            font-size: 18px;
        }

        .cta-text p {
            font-size: 12px;
        }

    }

</style>

@endsection
