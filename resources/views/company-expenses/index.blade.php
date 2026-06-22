@extends('layouts.app')
@section('title', 'Company Expenses')
@section('sub-nav')
    <a href="{{ route('finance.dashboard') }}">Dashboard</a>
    <a href="{{ route('company-expenses.index') }}">Company Expenses</a>
    <a href="{{ route('company-expenses.create') }}">+ New Expense</a>
@endsection

@section('content')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .top-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px 24px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #2563eb;
        }

        .stat-card.red {
            border-left-color: #dc2626;
        }

        .stat-card.green {
            border-left-color: #16a34a;
        }

        .stat-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        .page-card {
            background: white;
            border-radius: 12px;
            padding: 28px 30px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .add-btn {
            background: #2563eb;
            color: white;
            padding: 9px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
        }

        .add-btn:hover {
            background: #1d4ed8;
        }

        /* ── SEARCH & FILTER BAR ── */
        .filter-bar {
            display: grid;
            grid-template-columns: 1fr 180px 160px 160px auto;
            gap: 10px;
            margin-bottom: 20px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-label {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .filter-input {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 13px;
            background: white;
            font-family: 'Inter', sans-serif;
        }

        .filter-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .filter-btn {
            padding: 8px 16px;
            border-radius: 7px;
            border: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .btn-search {
            background: #2563eb;
            color: white;
        }

        .btn-search:hover {
            background: #1d4ed8;
        }

        .btn-clear {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            margin-left: 4px;
        }

        .btn-clear:hover {
            background: #e5e7eb;
        }

        /* ── TABLE ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead {
            background: #1f3a5f;
            color: white;
        }

        th {
            padding: 12px 14px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        td {
            padding: 13px 14px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .cat-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .edit-btn {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            background: #f3f4f6;
            color: #374151;
        }

        .edit-btn:hover {
            background: #e5e7eb;
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            color: #9ca3af;
            font-size: 14px;
        }

        /* ── REPORT SECTION ── */
        .report-card {
            background: white;
            border-radius: 12px;
            padding: 28px 30px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        .report-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group.full {
            grid-column: span 2;
        }

        label {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
        }

        input,
        select {
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 13px;
            background: white;
            font-family: 'Inter', sans-serif;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .gen-btn {
            background: #111827;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .gen-btn:hover {
            background: #1f2937;
        }

        /* ── GENERATED REPORTS TABLE ── */
        .reports-table table thead {
            background: #374151;
        }

        .badge-company {
            background: #fef3c7;
            color: #92400e;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            margin-right: 4px;
            transition: background 0.2s ease;
        }

        .btn-preview {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .btn-preview:hover {
            background: #dbeafe;
        }

        .btn-download {
            background: #2563eb;
            color: white;
        }

        .btn-download:hover {
            background: #1d4ed8;
        }

        /* ── MODAL ── */
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
        }

        .modal-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .modal-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            font-size: 20px;
            line-height: 1;
            padding: 4px 6px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .modal-close:hover {
            background: #f3f4f6;
            color: #111827;
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

        .excel-notice {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            flex-direction: column;
            gap: 12px;
            color: #6b7280;
            font-size: 14px;
        }

        .results-meta {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 12px;
        }
    </style>

    {{-- STATS --}}
    <div class="top-stats">
        <div class="stat-card">
            <div class="stat-label">This Month</div>
            <div class="stat-value">TSh {{ number_format($totalThisMonth, 0) }}</div>
        </div>
        <div class="stat-card red">
            <div class="stat-label">This Year</div>
            <div class="stat-value">TSh {{ number_format($totalThisYear, 0) }}</div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">All Time</div>
            <div class="stat-value">TSh {{ number_format($totalAll, 0) }}</div>
        </div>
    </div>

    {{-- EXPENSES TABLE --}}
    <div class="page-card">
        <div class="card-header">
            <div class="card-title">Company Expenses</div>
            <a href="{{ route('company-expenses.create') }}" class="add-btn">+ New Expense</a>
        </div>

        {{-- SEARCH & FILTER --}}
        <form method="GET" action="{{ route('company-expenses.index') }}">
            <div class="filter-bar">

                <div class="filter-group">
                    <span class="filter-label">Search</span>
                    <input type="text" name="search" class="filter-input" placeholder="Search by title or description…"
                        value="{{ request('search') }}">
                </div>

                <div class="filter-group">
                    <span class="filter-label">Category</span>
                    <select name="category" class="filter-input">
                        <option value="">All Categories</option>
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

                <div style="display:flex; gap:6px; align-items:flex-end;">
                    <button type="submit" class="filter-btn btn-search">Filter</button>
                    <a href="{{ route('company-expenses.index') }}" class="filter-btn btn-clear"
                        style="text-decoration:none; display:inline-block; padding:8px 14px;">
                        Clear
                    </a>
                </div>

            </div>
        </form>

        {{-- Results meta --}}
        <div class="results-meta">
            Showing {{ $expenses->firstItem() ?? 0 }}–{{ $expenses->lastItem() ?? 0 }}
            of {{ $expenses->total() }} expenses
            @if (request('search') || request('category') || request('date_from'))
                — <a href="{{ route('company-expenses.index') }}" style="color:#2563eb;">clear filters</a>
            @endif
        </div>

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
                        <td style="font-weight:600; color:#111827;">{{ $expense->title }}</td>
                        <td><span class="cat-badge">{{ $expense->category }}</span></td>
                        <td style="font-weight:700; color:#dc2626;">TSh {{ number_format($expense->amount, 0) }}</td>
                        <td style="color:#6b7280;">{{ $expense->date->format('d M Y') }}</td>
                        <td style="color:#6b7280;">{{ $expense->recorder->name ?? '—' }}</td>
                        <td>
                            @if ($expense->receipt)
                                @php
                                    $ext = strtolower(pathinfo($expense->receipt, PATHINFO_EXTENSION));
                                @endphp
                                @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                    <img src="{{ asset('storage/' . $expense->receipt) }}"
                                        style="width:40px;height:40px;object-fit:cover;border-radius:6px;
                                            border:1px solid #e5e7eb; cursor:pointer;"
                                        onclick="window.open('{{ asset('storage/' . $expense->receipt) }}','_blank')" />
                                @else
                                    <a href="{{ asset('storage/' . $expense->receipt) }}" target="_blank"
                                        style="font-size:12px;color:#2563eb;">📄 View</a>
                                @endif
                            @else
                                <span style="font-size:12px;color:#d1d5db;">None</span>
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
                        <td colspan="7" class="empty-state">
                            @if (request('search') || request('category') || request('date_from'))
                                No expenses match your filters
                            @else
                                No company expenses recorded yet
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:20px;">
            {{ $expenses->appends(request()->query())->links() }}
        </div>
    </div>

    {{-- GENERATED REPORTS --}}
    <div class="page-card reports-table">
        <div class="card-header">
            <div class="card-title">Generated Reports</div>
            <span style="font-size:13px; color:#6b7280;">{{ $reports->count() }} report(s)</span>
        </div>

        @if ($reports->count())
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
                        @php $ext = strtolower(pathinfo($report->file_path ?? '', PATHINFO_EXTENSION)); @endphp
                        <tr>
                            <td style="font-weight:600; color:#111827;">{{ $report->title }}</td>
                            <td style="color:#6b7280;">{{ $report->uploader->name ?? '—' }}</td>
                            <td style="color:#6b7280;">{{ $report->created_at->format('d M Y') }}</td>
                            <td>
                                @if ($report->file_path)
                                    <a href="#" class="action-btn btn-preview"
                                        onclick="openPreview('{{ route('reports.preview', $report) }}','{{ addslashes($report->title) }}','{{ $ext }}')">
                                        👁 Preview
                                    </a>
                                    <a href="{{ route('reports.download', $report) }}" class="action-btn btn-download">
                                        ↓ Download
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">No reports generated yet</div>
        @endif
    </div>

    {{-- GENERATE REPORT --}}
    <div class="report-card">
        <div class="card-title">Generate Company Expense Report</div>
        <p style="font-size:13px; color:#6b7280; margin-top:4px;">
            Generate a PDF report for a specific month or date range.
        </p>

        <form method="POST" action="{{ route('company-expenses.report') }}">
            @csrf
            <div class="report-grid">

                <div class="form-group">
                    <label>Report Type</label>
                    <select name="report_type" id="reportType" onchange="toggleReportType()">
                        <option value="month">Specific Month</option>
                        <option value="range">Date Range</option>
                    </select>
                </div>

                <div class="form-group" id="monthField">
                    <label>Month</label>
                    <input type="month" name="month" value="{{ now()->format('Y-m') }}">
                </div>

                <div class="form-group" id="fromField" style="display:none;">
                    <label>From</label>
                    <input type="date" name="date_from">
                </div>

                <div class="form-group" id="toField" style="display:none;">
                    <label>To</label>
                    <input type="date" name="date_to">
                </div>

                <div class="form-group full">
                    <button type="submit" class="gen-btn">⚡ Generate Report</button>
                </div>

            </div>
        </form>
    </div>

    {{-- PREVIEW MODAL --}}
    <div class="modal-overlay" id="previewModal">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title" id="modalTitle"></div>
                <button class="modal-close" onclick="closePreview()">&#x2715;</button>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <script>
        function toggleReportType() {
            const type = document.getElementById('reportType').value;
            document.getElementById('monthField').style.display = type === 'month' ? 'flex' : 'none';
            document.getElementById('fromField').style.display = type === 'range' ? 'flex' : 'none';
            document.getElementById('toField').style.display = type === 'range' ? 'flex' : 'none';
        }

        function openPreview(url, title, ext) {
            document.getElementById('modalTitle').innerText = title;
            const body = document.getElementById('modalBody');
            if (ext === 'pdf') {
                body.innerHTML = `<iframe src="${url}"></iframe>`;
            } else {
                body.innerHTML = `
                <div class="excel-notice">
                    <div style="font-size:42px;">📊</div>
                    <div>This file cannot be previewed inline.</div>
                    <a href="${url}" style="color:#2563eb; font-weight:600;">Download to view</a>
                </div>`;
            }
            document.getElementById('previewModal').classList.add('open');
        }

        function closePreview() {
            document.getElementById('previewModal').classList.remove('open');
            document.getElementById('modalBody').innerHTML = '';
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('previewModal');
            if (e.target === modal) closePreview();
        });
    </script>

@endsection
