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
            margin-bottom: 20px;
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

        .report-card {
            background: white;
            border-radius: 12px;
            padding: 28px 30px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
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
        }

        .gen-btn:hover {
            background: #1f2937;
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            color: #9ca3af;
            font-size: 14px;
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
        <div class="page-subtitle">
            Total Reports: {{ $reports->total() }}
        </div>
    </div>

    {{-- TABLE --}}
    <div class="page-card">
        <div class="card-header">
            <div class="card-title">Company Expenses</div>
            <a href="{{ route('company-expenses.create') }}" class="add-btn">+ New Expense</a>
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
                                    $file = basename($expense->receipt);
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
                        <td colspan="7" class="empty-state">No company expenses recorded yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:20px;">
            {{ $reports->links() }}
        </div>
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

    <script>
        function toggleReportType() {
            const type = document.getElementById('reportType').value;
            document.getElementById('monthField').style.display = type === 'month' ? 'flex' : 'none';
            document.getElementById('fromField').style.display = type === 'range' ? 'flex' : 'none';
            document.getElementById('toField').style.display = type === 'range' ? 'flex' : 'none';
        }
    </script>
@endsection
