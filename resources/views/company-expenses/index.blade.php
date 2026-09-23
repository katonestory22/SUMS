@extends('layouts.app')

@section('title', 'Company Expenses')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('finance.dashboard') }}">Dashboard</a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .expense-page {
            max-width: 1250px;
            margin: 0 auto;
            padding: 20px;
        }

        /* =========================================================
           SUMMARY CARDS
        ========================================================= */

        .top-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #e7ebf0;
            border-radius: 14px;
            padding: 20px 22px;
            box-shadow: 0 6px 18px rgba(31, 58, 95, 0.055);
        }

        .stat-card::after {
            content: "";
            position: absolute;
            width: 80px;
            height: 80px;
            right: -25px;
            top: -25px;
            border-radius: 50%;
            background: rgba(201, 168, 76, 0.06);
        }

        .stat-card.month {
            border-top: 3px solid #C9A84C;
        }

        .stat-card.year {
            border-top: 3px solid #1f3a5f;
        }

        .stat-card.all-time {
            border-top: 3px solid #3f8f70;
        }

        .stat-label {
            position: relative;
            z-index: 1;
            color: #6b7280;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 7px;
        }

        .stat-value {
            position: relative;
            z-index: 1;
            color: #1f3a5f;
            font-size: 21px;
            font-weight: 700;
            letter-spacing: -.3px;
        }

        .stat-note {
            position: relative;
            z-index: 1;
            margin-top: 5px;
            color: #9ca3af;
            font-size: 11px;
        }

        /* =========================================================
           COMMON CARD
        ========================================================= */

        .page-card {
            background: #ffffff;
            border: 1px solid #e7ebf0;
            border-radius: 14px;
            padding: 25px 26px;
            box-shadow: 0 6px 18px rgba(31, 58, 95, 0.055);
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .card-heading {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .card-heading-icon {
            width: 35px;
            height: 35px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef3f8;
            color: #1f3a5f;
            flex-shrink: 0;
        }

        .card-heading-icon svg {
            width: 18px;
            height: 18px;
        }

        .card-title {
            color: #1f3a5f;
            font-size: 17px;
            font-weight: 700;
        }

        .card-subtitle {
            color: #9ca3af;
            font-size: 11px;
            margin-top: 2px;
        }

        /* =========================================================
           NEW EXPENSE BUTTON
        ========================================================= */

        .add-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 15px;
            border-radius: 8px;
            background: #C9A84C;
            color: #16283f;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(201, 168, 76, 0.20);
            transition:
                background .2s ease,
                transform .2s ease,
                box-shadow .2s ease;
            white-space: nowrap;
        }

        .add-btn svg {
            width: 15px;
            height: 15px;
        }

        .add-btn:hover {
            background: #b8953d;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(201, 168, 76, 0.28);
        }

        /* =========================================================
           FILTER BAR
        ========================================================= */

        .filter-bar {
            display: grid;
            grid-template-columns: minmax(180px, 1fr) 180px 145px 145px auto;
            gap: 10px;
            margin-bottom: 18px;
            align-items: end;
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #edf0f3;
            border-radius: 10px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-label {
            color: #6b7280;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .filter-input {
            width: 100%;
            box-sizing: border-box;
            padding: 9px 11px;
            border: 1px solid #d9dee5;
            border-radius: 7px;
            background: #ffffff;
            color: #374151;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .filter-input:focus {
            outline: none;
            border-color: #1f3a5f;
            box-shadow: 0 0 0 3px rgba(31, 58, 95, 0.08);
        }

        .filter-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 14px;
            border-radius: 7px;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-search {
            background: #1f3a5f;
            color: #ffffff;
        }

        .btn-search:hover {
            background: #16283f;
        }

        .btn-clear {
            background: #ffffff;
            color: #6b7280;
            border: 1px solid #d9dee5;
        }

        .btn-clear:hover {
            background: #f3f4f6;
            color: #374151;
        }

        /* =========================================================
           RESULTS META
        ========================================================= */

        .results-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
            color: #9ca3af;
            font-size: 11px;
        }

        .results-meta a {
            color: #1f3a5f;
            font-weight: 600;
            text-decoration: none;
        }

        /* =========================================================
           TABLE
        ========================================================= */

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #edf0f3;
            border-radius: 10px;
        }

        table {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
            font-size: 12px;
        }

        thead {
            background: #1f3a5f;
            color: #ffffff;
        }

        th {
            padding: 12px 13px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            white-space: nowrap;
        }

        td {
            padding: 13px;
            border-bottom: 1px solid #f0f2f4;
            vertical-align: middle;
        }

        tbody tr {
            transition: background .15s ease;
        }

        tbody tr:hover {
            background: #fafbfc;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .expense-title {
            color: #111827;
            font-weight: 650;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 9px;
            border-radius: 20px;
            background: #f1f4f7;
            color: #1f3a5f;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .expense-amount {
            color: #b42318;
            font-weight: 700;
            white-space: nowrap;
        }

        .muted {
            color: #6b7280;
        }

        /* =========================================================
           ACTION BUTTONS
        ========================================================= */

        .action-group {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 9px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            text-decoration: none;
            transition: background .2s ease, color .2s ease;
            white-space: nowrap;
        }

        .action-btn svg {
            width: 13px;
            height: 13px;
        }

        .btn-preview {
            background: #eef3f8;
            color: #1f3a5f;
        }

        .btn-preview:hover {
            background: #e0e8f0;
        }

        .btn-download {
            background: #1f3a5f;
            color: #ffffff;
        }

        .btn-download:hover {
            background: #16283f;
        }

        .edit-btn {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 6px;
            background: #f5f6f8;
            color: #374151;
            font-size: 10px;
            font-weight: 700;
            text-decoration: none;
            transition: background .2s ease;
        }

        .edit-btn:hover {
            background: #e8ebef;
        }

        .no-receipt {
            color: #c5c9cf;
            font-size: 11px;
        }

        /* =========================================================
           EMPTY STATE
        ========================================================= */

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #9ca3af;
            font-size: 13px;
        }

        .empty-icon {
            width: 45px;
            height: 45px;
            margin: 0 auto 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f5f7;
            color: #9ca3af;
        }

        .empty-icon svg {
            width: 21px;
            height: 21px;
        }

        /* =========================================================
           REPORTS
        ========================================================= */

        .reports-table table {
            min-width: 650px;
        }

        .report-count {
            color: #9ca3af;
            font-size: 11px;
        }

        /* =========================================================
           REPORT GENERATOR
        ========================================================= */

        .report-card {
            background: #ffffff;
            border: 1px solid #e7ebf0;
            border-radius: 14px;
            padding: 25px 26px;
            box-shadow: 0 6px 18px rgba(31, 58, 95, 0.055);
            margin-bottom: 20px;
        }

        .report-intro {
            margin: 4px 0 20px;
            color: #6b7280;
            font-size: 12px;
        }

        .report-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 17px;
            padding: 18px;
            background: #f8fafc;
            border: 1px solid #edf0f3;
            border-radius: 10px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group.full {
            grid-column: span 2;
        }

        .form-group label {
            color: #374151;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .35px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            box-sizing: border-box;
            padding: 9px 11px;
            border: 1px solid #d9dee5;
            border-radius: 7px;
            background: #ffffff;
            color: #374151;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #1f3a5f;
            box-shadow: 0 0 0 3px rgba(31, 58, 95, 0.08);
        }

        .gen-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #C9A84C;
            color: #16283f;
            border: none;
            padding: 11px 19px;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(201, 168, 76, 0.18);
            transition:
                background .2s ease,
                transform .2s ease,
                box-shadow .2s ease;
        }

        .gen-btn:hover {
            background: #b8953d;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(201, 168, 76, 0.25);
        }

        /* =========================================================
           PAGINATION
        ========================================================= */

        .pagination-wrap {
            margin-top: 18px;
        }

        /* =========================================================
           PREVIEW MODAL
        ========================================================= */

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.68);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-box {
            background: #ffffff;
            width: 92%;
            max-width: 900px;
            height: 85vh;
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.25);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 14px 20px;
            background: #1f3a5f;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-title {
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
        }

        .modal-close {
            width: 31px;
            height: 31px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 7px;
            color: #ffffff;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
            transition: background .2s ease;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.17);
        }

        .modal-body {
            flex: 1;
            overflow: hidden;
            background: #f4f6f9;
        }

        .modal-body iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .image-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 8px;
            object-fit: contain;
            box-shadow: 0 5px 25px rgba(0, 0, 0, .12);
        }

        .file-notice {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            flex-direction: column;
            gap: 10px;
            color: #6b7280;
            font-size: 13px;
        }

        .file-notice-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #ffffff;
            color: #1f3a5f;
            box-shadow: 0 5px 20px rgba(31, 58, 95, 0.08);
        }

        .file-notice-icon svg {
            width: 25px;
            height: 25px;
        }

        .file-notice a {
            color: #1f3a5f;
            font-weight: 700;
            text-decoration: none;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1050px) {
            .filter-bar {
                grid-template-columns: 1fr 1fr;
            }

            .filter-actions {
                grid-column: span 2;
            }
        }

        @media (max-width: 800px) {
            .top-stats {
                grid-template-columns: 1fr;
            }

            .report-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: span 1;
            }

            .page-card,
            .report-card {
                padding: 20px;
            }
        }

        @media (max-width: 600px) {

            .expense-page {
                padding: 14px;
            }

            .card-header {
                align-items: flex-start;
            }

            .add-btn {
                padding: 9px 11px;
                font-size: 11px;
            }

            .card-subtitle {
                display: none;
            }

            .filter-bar {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                grid-column: span 1;
            }

            .filter-actions .filter-btn {
                flex: 1;
            }

            .results-meta {
                align-items: flex-start;
                flex-direction: column;
            }

            .modal-overlay {
                padding: 10px;
            }

            .modal-box {
                width: 100%;
                height: 90vh;
            }
        }
    </style>

    <div class="expense-page">

        {{-- =====================================================
         SUMMARY
    ====================================================== --}}

        <div class="top-stats">

            <div class="stat-card month">
                <div class="stat-label">This Month</div>
                <div class="stat-value">
                    TSh {{ number_format($totalThisMonth, 0) }}
                </div>
                <div class="stat-note">
                    Company expenses recorded this month
                </div>
            </div>

            <div class="stat-card year">
                <div class="stat-label">This Year</div>
                <div class="stat-value">
                    TSh {{ number_format($totalThisYear, 0) }}
                </div>
                <div class="stat-note">
                    Total expenses recorded this year
                </div>
            </div>

            <div class="stat-card all-time">
                <div class="stat-label">All Time</div>
                <div class="stat-value">
                    TSh {{ number_format($totalAll, 0) }}
                </div>
                <div class="stat-note">
                    Cumulative company expenses
                </div>
            </div>

        </div>


        {{-- =====================================================
         EXPENSE REGISTER
    ====================================================== --}}

        <div class="page-card">

            <div class="card-header">

                <div class="card-heading">

                    <div class="card-heading-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <path d="M3 10h18"></path>
                            <path d="M7 15h3"></path>
                            <path d="M16 15h1"></path>
                        </svg>
                    </div>

                    <div>
                        <div class="card-title">Company Expenses</div>
                        <div class="card-subtitle">
                            Operational expense register
                        </div>
                    </div>

                </div>

                <a href="{{ route('company-expenses.create') }}" class="add-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg>

                    New Expense
                </a>

            </div>


            {{-- FILTERS --}}
            <form method="GET" action="{{ route('company-expenses.index') }}">

                <div class="filter-bar">

                    <div class="filter-group">
                        <span class="filter-label">Search</span>

                        <input type="text" name="search" class="filter-input"
                            placeholder="Search by title or description..." value="{{ request('search') }}">
                    </div>


                    <div class="filter-group">

                        <span class="filter-label">Category</span>

                        <select name="category" class="filter-input">

                            <option value="">
                                All Categories
                            </option>

                            @foreach (['Salaries', 'Office Operation Cost', 'Transport', 'Medical Insurance', 'Taxes and Fines', 'Miscellaneous'] as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="filter-group">

                        <span class="filter-label">From</span>

                        <input type="date" name="date_from" class="filter-input" value="{{ request('date_from') }}">

                    </div>


                    <div class="filter-group">

                        <span class="filter-label">To</span>

                        <input type="date" name="date_to" class="filter-input" value="{{ request('date_to') }}">

                    </div>


                    <div class="filter-actions">

                        <button type="submit" class="filter-btn btn-search">
                            Filter
                        </button>

                        <a href="{{ route('company-expenses.index') }}" class="filter-btn btn-clear">
                            Clear
                        </a>

                    </div>

                </div>

            </form>


            {{-- RESULTS META --}}
            <div class="results-meta">

                <span>
                    Showing
                    {{ $expenses->firstItem() ?? 0 }}
                    –
                    {{ $expenses->lastItem() ?? 0 }}
                    of
                    {{ $expenses->total() }}
                    expenses
                </span>

                @if (request('search') || request('category') || request('date_from') || request('date_to'))
                    <a href="{{ route('company-expenses.index') }}">
                        Clear filters
                    </a>
                @endif

            </div>


            {{-- EXPENSE TABLE --}}
            <div class="table-wrap">

                <table>

                    <thead>

                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Recorded By</th>
                            <th>Receipt</th>
                            <th>Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($expenses as $expense)
                            <tr>

                                <td>
                                    <div class="expense-title">
                                        {{ $expense->title }}
                                    </div>
                                </td>


                                <td>

                                    <span class="category-badge">
                                        {{ $expense->category }}
                                    </span>

                                </td>


                                <td>

                                    <span class="expense-amount">
                                        TSh {{ number_format($expense->amount, 0) }}
                                    </span>

                                </td>


                                <td class="muted">
                                    {{ $expense->date->format('d M Y') }}
                                </td>


                                <td class="muted">
                                    {{ $expense->recorder->name ?? '—' }}
                                </td>


                                <td>

                                    @if ($expense->receipt)
                                        @php
                                            $ext = strtolower(pathinfo($expense->receipt, PATHINFO_EXTENSION));
                                        @endphp

                                        <div class="action-group">

                                            <a href="#" class="action-btn btn-preview"
                                                onclick="openPreview(
                                                '{{ asset('storage/' . $expense->receipt) }}',
                                                '{{ addslashes($expense->title) }}',
                                                '{{ $ext }}'
                                            ); return false;">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"></path>
                                                    <circle cx="12" cy="12" r="2.5"></circle>
                                                </svg>

                                                Preview
                                            </a>


                                            <a href="{{ asset('storage/' . $expense->receipt) }}" download
                                                class="action-btn btn-download">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 3v12"></path>
                                                    <path d="m7 10 5 5 5-5"></path>
                                                    <path d="M5 21h14"></path>
                                                </svg>

                                                Download
                                            </a>

                                        </div>
                                    @else
                                        <span class="no-receipt">
                                            None
                                        </span>
                                    @endif

                                </td>


                                <td>

                                    <a href="{{ route('company-expenses.edit', $expense) }}" class="edit-btn">
                                        Edit
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7">

                                    <div class="empty-state">

                                        <div class="empty-icon">

                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                                                <path d="M8 8h8"></path>
                                                <path d="M8 12h8"></path>
                                                <path d="M8 16h4"></path>
                                            </svg>

                                        </div>

                                        @if (request('search') || request('category') || request('date_from') || request('date_to'))
                                            No expenses match your filters.
                                        @else
                                            No company expenses recorded yet.
                                        @endif

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            <div class="pagination-wrap">
                {{ $expenses->appends(request()->query())->links() }}
            </div>

        </div>


        {{-- =====================================================
         GENERATED REPORTS
    ====================================================== --}}

        <div class="page-card reports-table">

            <div class="card-header">

                <div class="card-heading">

                    <div class="card-heading-icon">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2h9l4 4v16H6z"></path>
                            <path d="M14 2v5h5"></path>
                            <path d="M9 12h6"></path>
                            <path d="M9 16h6"></path>
                        </svg>

                    </div>

                    <div>
                        <div class="card-title">Generated Reports</div>

                        <div class="card-subtitle">
                            Previously generated expense reports
                        </div>
                    </div>

                </div>

                <span class="report-count">
                    {{ $reports->count() }} report(s)
                </span>

            </div>


            @if ($reports->count())

                <div class="table-wrap">

                    <table>

                        <thead>

                            <tr>
                                <th>Title</th>
                                <th>Generated By</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($reports as $report)
                                @php
                                    $ext = strtolower(pathinfo($report->file_path ?? '', PATHINFO_EXTENSION));
                                @endphp

                                <tr>

                                    <td>
                                        <div class="expense-title">
                                            {{ $report->title }}
                                        </div>
                                    </td>

                                    <td class="muted">
                                        {{ $report->uploader->name ?? '—' }}
                                    </td>

                                    <td class="muted">
                                        {{ $report->created_at->format('d M Y') }}
                                    </td>

                                    <td>

                                        @if ($report->file_path)
                                            <div class="action-group">

                                                <a href="#" class="action-btn btn-preview"
                                                    onclick="openPreview(
                                                    '{{ route('reports.preview', $report) }}',
                                                    '{{ addslashes($report->title) }}',
                                                    '{{ $ext }}'
                                                ); return false;">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"></path>
                                                        <circle cx="12" cy="12" r="2.5"></circle>
                                                    </svg>

                                                    Preview
                                                </a>

                                                <a href="{{ route('reports.download', $report) }}"
                                                    class="action-btn btn-download">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 3v12"></path>
                                                        <path d="m7 10 5 5 5-5"></path>
                                                        <path d="M5 21h14"></path>
                                                    </svg>

                                                    Download
                                                </a>

                                            </div>
                                        @endif

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>
            @else
                <div class="empty-state">

                    <div class="empty-icon">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2h9l4 4v16H6z"></path>
                            <path d="M14 2v5h5"></path>
                            <path d="M9 12h6"></path>
                            <path d="M9 16h6"></path>
                        </svg>

                    </div>

                    No reports generated yet.

                </div>

            @endif

        </div>


        {{-- =====================================================
         REPORT GENERATOR
    ====================================================== --}}

        <div class="report-card">

            <div class="card-heading">

                <div class="card-heading-icon">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19V5"></path>
                        <path d="M4 19h16"></path>
                        <path d="m7 15 3-4 3 2 4-6"></path>
                    </svg>

                </div>

                <div>
                    <div class="card-title">
                        Generate Company Expense Report
                    </div>

                    <div class="card-subtitle">
                        Create a PDF report for a selected month or date range
                    </div>
                </div>

            </div>


            <p class="report-intro">
                Select the reporting period and generate a formal company expense report.
            </p>


            <form method="POST" action="{{ route('company-expenses.report') }}">
                @csrf

                <div class="report-grid">

                    {{-- REPORT TYPE --}}
                    <div class="form-group">

                        <label>
                            Report Type
                        </label>

                        <select name="report_type" id="reportType" onchange="toggleReportType()">
                            <option value="month">
                                Specific Month
                            </option>

                            <option value="range">
                                Date Range
                            </option>
                        </select>

                    </div>


                    {{-- MONTH --}}
                    <div class="form-group" id="monthField">

                        <label>
                            Month
                        </label>

                        <input type="month" name="month" value="{{ now()->format('Y-m') }}">

                    </div>


                    {{-- FROM --}}
                    <div class="form-group" id="fromField" style="display:none;">

                        <label>
                            From
                        </label>

                        <input type="date" name="date_from">

                    </div>


                    {{-- TO --}}
                    <div class="form-group" id="toField" style="display:none;">

                        <label>
                            To
                        </label>

                        <input type="date" name="date_to">

                    </div>


                    {{-- BUTTON --}}
                    <div class="form-group full">

                        <button type="submit" class="gen-btn">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" width="15" height="15">
                                <path d="M12 3v18"></path>
                                <path d="M5 12h14"></path>
                            </svg>

                            Generate Report

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
     PREVIEW MODAL
========================================================= --}}

    <div class="modal-overlay" id="previewModal">

        <div class="modal-box">

            <div class="modal-header">

                <div class="modal-title" id="modalTitle"></div>

                <button type="button" class="modal-close" onclick="closePreview()" aria-label="Close preview">
                    &#x2715;
                </button>

            </div>

            <div class="modal-body" id="modalBody"></div>

        </div>

    </div>


    <script>
        /* =========================================================
           REPORT TYPE
        ========================================================= */

        function toggleReportType() {

            const type = document.getElementById('reportType').value;

            document.getElementById('monthField').style.display =
                type === 'month' ? 'flex' : 'none';

            document.getElementById('fromField').style.display =
                type === 'range' ? 'flex' : 'none';

            document.getElementById('toField').style.display =
                type === 'range' ? 'flex' : 'none';
        }


        /* =========================================================
           PREVIEW
        ========================================================= */

        function openPreview(url, title, ext) {

            document.getElementById('modalTitle').innerText = title;

            const body = document.getElementById('modalBody');

            const imageExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (imageExtensions.includes(ext)) {

                body.innerHTML = `
                <div class="image-preview">
                    <img
                        src="${url}"
                        alt="${title}"
                    >
                </div>
            `;

            } else if (ext === 'pdf') {

                body.innerHTML = `
                <iframe
                    src="${url}"
                    title="${title}"
                ></iframe>
            `;

            } else {

                body.innerHTML = `
                <div class="file-notice">

                    <div class="file-notice-icon">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.7"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M6 2h9l4 4v16H6z"></path>
                            <path d="M14 2v5h5"></path>
                            <path d="M9 13h6"></path>
                            <path d="M9 17h4"></path>
                        </svg>

                    </div>

                    <div>
                        This file cannot be previewed inline.
                    </div>

                    <a
                        href="${url}"
                        download
                    >
                        Download to view
                    </a>

                </div>
            `;
            }

            document
                .getElementById('previewModal')
                .classList
                .add('open');
        }


        /* =========================================================
           CLOSE PREVIEW
        ========================================================= */

        function closePreview() {

            document
                .getElementById('previewModal')
                .classList
                .remove('open');

            document.getElementById('modalBody').innerHTML = '';
        }


        /* =========================================================
           CLOSE WHEN CLICKING OUTSIDE MODAL
        ========================================================= */

        window.addEventListener('click', function(event) {

            const modal = document.getElementById('previewModal');

            if (event.target === modal) {
                closePreview();
            }

        });


        /* =========================================================
           ESCAPE KEY
        ========================================================= */

        window.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {
                closePreview();
            }

        });
    </script>

@endsection
