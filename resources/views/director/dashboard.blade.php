@extends('layouts.app')

@section('title', 'Director Dashboard')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Home</a>
    <a href="{{ route('director.users') }}">Users</a>
    <a href="{{ route('reports.index') }}">Reports</a>
    <a href="{{ route('director.audit') }}">Audit Log</a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .summary-card {
            background: white;
            padding: 22px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .05);
            border-left: 4px solid #e5e7eb;
            transition: .2s;
        }

        .summary-card:hover {
            transform: translateY(-2px);
        }

        .blue {
            border-left-color: #2563eb;
        }

        .green {
            border-left-color: #16a34a;
        }

        .red {
            border-left-color: #dc2626;
        }

        .amber {
            border-left-color: #f59e0b;
        }

        .navy {
            border-left-color: #1f3a5f;
        }

        .purple {
            border-left-color: #7c3aed;
        }

        .summary-title {
            font-size: 11px;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 8px;
        }

        .summary-number {
            font-size: 28px;
            font-weight: 700;
            color: #2563eb;
        }

        .summary-money {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .table-card,
        .chart-card {
            background: white;
            padding: 28px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .05);
            margin-bottom: 24px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .table-title,
        .chart-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
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
            padding: 12px;
            text-align: left;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: .5px;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #f0f0f0;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .status {
            padding: 6px 10px;
            border-radius: 6px;
            color: white;
            font-size: 11px;
            font-weight: 600;
        }

        .good {
            background: #16a34a;
        }

        .warning {
            background: #f59e0b;
        }

        .danger {
            background: #dc2626;
        }

        .view-btn {
            padding: 7px 14px;
            border-radius: 7px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }

        .view-btn:hover {
            background: #1d4ed8;
            color: white;
        }

        .chart-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .pie-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .pie-canvas-wrap {
            flex: 1;
            max-width: 220px;
            height: 220px;
        }

        .pie-legend {
            flex: 1;
            display: grid;
            gap: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 4px;
        }

        .legend-amount {
            margin-left: auto;
            font-weight: 600;
        }

        @media(max-width:900px) {

            .dashboard-grid,
            .chart-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- SUMMARY CARDS --}}

    <div class="dashboard-grid">

        <div class="summary-card blue">
            <div class="summary-title">Total Projects</div>
            <div class="summary-number">{{ $totalProjects }}</div>
        </div>

        <div class="summary-card navy">
            <div class="summary-title">Contract Value</div>
            <div class="summary-money">
                TSh {{ number_format($totalContract, 0) }}
            </div>
        </div>

        <div class="summary-card amber">
            <div class="summary-title">Allocated</div>
            <div class="summary-money">
                TSh {{ number_format($totalAllocated, 0) }}
            </div>
        </div>

        <div class="summary-card red">
            <div class="summary-title">Spent</div>
            <div class="summary-money">
                TSh {{ number_format($totalSpent, 0) }}
            </div>
        </div>

        <div class="summary-card green">
            <div class="summary-title">Remaining Budget</div>
            <div class="summary-money">
                TSh {{ number_format($remainingBudget, 0) }}
            </div>
        </div>

        <div class="summary-card purple">
            <div class="summary-title">Over Budget Projects</div>
            <div class="summary-number">
                {{ $overBudgetProjects }}
            </div>
        </div>

    </div>

    {{-- PROJECT TABLE --}}

    <div class="table-card">

        <div class="table-header">
            <div class="table-title">Project Health Overview</div>
        </div>

        <table>

            <thead>
                <tr>
                    <th>Project</th>
                    <th>Client</th>
                    <th>Progress</th>
                    <th>Allocated</th>
                    <th>Spent</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($projects as $project)
                    @php
                        $progress = $project->progress;
                        $allocated = $project->totalAllocated();
                        $spent = $project->totalExpenses();

                        $status = 'good';
                        $label = 'Healthy';

                        if ($spent > $allocated) {
                            $status = 'danger';
                            $label = 'Over Budget';
                        } elseif ($progress < 30) {
                            $status = 'warning';
                            $label = 'Slow';
                        }
                    @endphp

                    <tr>

                        <td>
                            <strong>{{ $project->project_name }}</strong>
                        </td>

                        <td>
                            {{ $project->client->first_name }}
                            {{ $project->client->last_name }}
                        </td>

                        <td>{{ round($progress) }}%</td>

                        <td>
                            TSh {{ number_format($allocated, 0) }}
                        </td>

                        <td>
                            TSh {{ number_format($spent, 0) }}
                        </td>

                        <td>
                            <span class="status {{ $status }}">
                                {{ $label }}
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('projects.overview', $project->id) }}" class="view-btn">
                                View
                            </a>
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>

    {{-- CHARTS --}}

    <div class="chart-grid">

        <div class="chart-card">

            <div class="chart-title">
                Allocation vs Expense
            </div>

            <div style="height:280px">
                <canvas id="allocationExpenseChart"></canvas>
            </div>

        </div>

        <div class="chart-card">

            <div class="chart-title">
                Expense Breakdown
            </div>

            <div class="pie-wrap">

                <div class="pie-canvas-wrap">
                    <canvas id="expensePieChart"></canvas>
                </div>

                <div class="pie-legend">

                    @php
                        $colors = ['#2563eb', '#dc2626', '#f59e0b', '#16a34a', '#8b5cf6', '#6b7280'];
                    @endphp

                    @foreach ($expenseByCategory as $cat => $value)
                        <div class="legend-item">
                            <span class="legend-dot" style="background:{{ $colors[$loop->index] }}">
                            </span>

                            <span>{{ $cat }}</span>

                            <span class="legend-amount">
                                TSh {{ number_format($value, 0) }}
                            </span>
                        </div>
                    @endforeach

                </div>

            </div>

        </div>

    </div>

    {{-- MONTHLY TREND --}}

    <div class="chart-card">

        <div class="chart-title">
            Monthly Financial Trend
        </div>

        <div style="height:350px;">
            <canvas id="monthlyTrendChart"></canvas>
        </div>

    </div>

    {{-- REPORT GENERATOR --}}

    <div class="table-card">

        <div class="table-header">
            <div class="table-title">
                Generate Company Expense Report
            </div>
        </div>

        <form method="POST" action="{{ route('company-expenses.report') }}"
            style="display:grid;
                 grid-template-columns:1fr 1fr 1fr auto;
                 gap:12px;
                 align-items:end;">

            @csrf

            <div>
                <label>Type</label>

                <select name="report_type" id="dirReportType" onchange="toggleDirType()" class="form-control">

                    <option value="month">
                        Specific Month
                    </option>

                    <option value="range">
                        Date Range
                    </option>

                </select>
            </div>

            <div id="dirMonth">
                <label>Month</label>

                <input type="month" name="month" value="{{ now()->format('Y-m') }}" class="form-control">
            </div>

            <div id="dirFrom" style="display:none;">
                <label>From</label>

                <input type="date" name="date_from" class="form-control">
            </div>

            <div id="dirTo" style="display:none;">
                <label>To</label>

                <input type="date" name="date_to" class="form-control">
            </div>

            <div>
                <button type="submit" class="btn btn-dark w-100">
                    Generate Report
                </button>
            </div>

        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function toggleDirType() {

            const type =
                document.getElementById('dirReportType').value;

            document.getElementById('dirMonth').style.display =
                type === 'month' ? 'block' : 'none';

            document.getElementById('dirFrom').style.display =
                type === 'range' ? 'block' : 'none';

            document.getElementById('dirTo').style.display =
                type === 'range' ? 'block' : 'none';
        }

        new Chart(document.getElementById('allocationExpenseChart'), {

            type: 'bar',

            data: {
                labels: @json($labels),
                datasets: [{
                        label: 'Allocated',
                        data: @json($allocatedData),
                        backgroundColor: '#2563eb'
                    },
                    {
                        label: 'Spent',
                        data: @json($expenseData),
                        backgroundColor: '#dc2626'
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false
            }

        });

        new Chart(document.getElementById('expensePieChart'), {

            type: 'doughnut',

            data: {

                labels: @json($expenseByCategory->keys()),

                datasets: [{
                    data: @json($expenseByCategory->values()),
                    backgroundColor: [
                        '#2563eb',
                        '#dc2626',
                        '#f59e0b',
                        '#16a34a',
                        '#8b5cf6',
                        '#6b7280'
                    ],
                    borderWidth: 0
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }

        });

        new Chart(document.getElementById('monthlyTrendChart'), {

            type: 'line',

            data: {

                labels: @json($monthlyAllocations->keys()),

                datasets: [{
                        label: 'Allocations',
                        data: @json($monthlyAllocations->values()),
                        borderColor: '#16a34a',
                        tension: 0.3
                    },
                    {
                        label: 'Expenses',
                        data: @json($monthlyExpenses->values()),
                        borderColor: '#dc2626',
                        tension: 0.3
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false
            }

        });
    </script>

@endsection
