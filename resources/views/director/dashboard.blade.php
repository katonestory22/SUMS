@extends('layouts.app')

@section('title', 'Director Dashboard')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Home</a>
    <a href="{{ route('director.users') }}">Users</a>
    <a href="{{ route('reports.index') }}">View Reports</a>
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
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            transition: transform .2s ease;
        }

        .summary-card:hover {
            transform: translateY(-2px);
        }

        .summary-title {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .summary-money {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        .table-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #1f3a5f;
            color: white;
        }

        th,
        td {
            padding: 14px;
            text-align: left;
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .status {
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            display: inline-block;
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
            padding: 6px 12px;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .view-btn:hover {
            background: #1d4ed8;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }
    </style>

    {{-- SUMMARY --}}
    <div class="dashboard-grid">

        <div class="summary-card">
            <div class="summary-title">Total Contract</div>
            <div class="summary-money">TSh {{ number_format($totalContract, 2) }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-title">Allocated</div>
            <div class="summary-money">TSh {{ number_format($totalAllocated, 2) }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-title">Spent</div>
            <div class="summary-money">TSh {{ number_format($totalSpent, 2) }}</div>
        </div>

    </div>

    {{-- PROJECT TABLE --}}
    <div class="table-card">

        <h3 style="margin-bottom:20px;">Project Health Overview</h3>

        <table>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Progress</th>
                    <th>Spent</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($projects as $project)
                    @php
                        $progress = $project->progress;
                        $spent = $project->totalExpenses();
                        $allocated = $project->totalAllocated();

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
                        <td>{{ $project->project_name }}</td>

                        <td>{{ round($progress) }}%</td>

                        <td>TSh {{ number_format($spent, 2) }}</td>

                        <td>
                            <span class="status {{ $status }}">
                                {{ $label }}
                            </span>
                        </td>

                        <td class="actions">
                            <a class="view-btn" href="{{ route('projects.overview', $project->id) }}">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="table-card" style="margin-top: 20px;">
        <h3 style="margin-bottom:15px;">Allocation vs Expense Overview</h3>

        <canvas id="allocationExpenseChart"></canvas>
    </div>

    <div class="table-card" style="margin-top:20px;">
        <h3 style="margin-bottom:15px;">Expense Breakdown by Category</h3>

        <div
            style="display:flex; align-items:center; justify-content:space-between; gap:20px; max-width:900px; margin:auto;">

            <!-- LEFT: PIE -->
            <div style="flex:1; max-width:450px; height:320px;">
                <canvas id="expensePieChart"></canvas>
            </div>

            <!-- RIGHT: LEGEND -->
            <div style="flex:1;">
                <div style="display:grid; gap:10px;">
                    @foreach ($expenseByCategory as $cat => $value)
                        <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#374151;">
                            <span
                                style="
                            width:12px;
                            height:12px;
                            border-radius:3px;
                            display:inline-block;
                            background:
                                @switch($loop->index)
                                    @case(0) #2563eb @break
                                    @case(1) #dc2626 @break
                                    @case(2) #f59e0b @break
                                    @case(3) #16a34a @break
                                    @case(4) #8b5cf6 @break
                                    @default #6b7280
                                @endswitch
                        "></span>

                            <span>{{ $cat }}</span>

                            <span style="margin-left:auto; font-weight:600;">
                                TSh {{ number_format($value, 0) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <div class="table-card" style="margin-top:30px;">
        <h3 style="margin-bottom:15px;">Monthly Financial Trend</h3>

        <div style="height:400px;">
            <canvas id="monthlyTrendChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const categoryLabels = @json($expenseByCategory->keys());
        const categoryData = @json($expenseByCategory->values());
    </script>
    <script>
        const labels = @json($labels);
        const allocated = @json($allocatedData);
        const spent = @json($expenseData);
    </script>
    <script>
        const pieCtx = document.getElementById('expensePieChart');

        new Chart(pieCtx, {
            type: 'pie',
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
                layout: {
                    padding: 10
                },
                plugins: {
                    legend: {
                        display: false // we are manually controlling legend
                    }
                }
            }
        });
    </script>
    <script>
        const ctx = document.getElementById('allocationExpenseChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Allocated',
                        data: allocated,
                        backgroundColor: '#2563eb'
                    },
                    {
                        label: 'Spent',
                        data: spent,
                        backgroundColor: '#dc2626'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Allocation vs Expense per Project'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const labels = @json($monthlyAllocations->keys());

            const allocations = @json($monthlyAllocations->values());
            const expenses = @json($monthlyExpenses->values());

            const ctx = document.getElementById('monthlyTrendChart');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Allocations',
                            data: allocations,
                            borderColor: '#16a34a',
                            backgroundColor: 'transparent',
                            tension: 0.3
                        },
                        {
                            label: 'Expenses',
                            data: expenses,
                            borderColor: '#dc2626',
                            backgroundColor: 'transparent',
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

        });
    </script>

    <div class="table-card" style="margin-top:24px;">
        <h3 style="margin-bottom:6px;">Generate Company Expense Report</h3>
        <p style="font-size:13px; color:#6b7280; margin-bottom:16px;">
            Generate a PDF report for company operational expenses.
        </p>
        <form method="POST" action="{{ route('company-expenses.report') }}"
            style="display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:12px; align-items:end;">
            @csrf
            <div>
                <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:5px;">
                    Type
                </label>
                <select name="report_type" id="dirReportType" onchange="toggleDirType()"
                    style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:7px; font-size:13px;">
                    <option value="month">Specific Month</option>
                    <option value="range">Date Range</option>
                </select>
            </div>
            <div id="dirMonth">
                <label
                    style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:5px;">Month</label>
                <input type="month" name="month" value="{{ now()->format('Y-m') }}"
                    style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:7px; font-size:13px;">
            </div>
            <div id="dirFrom" style="display:none;">
                <label
                    style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:5px;">From</label>
                <input type="date" name="date_from"
                    style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:7px; font-size:13px;">
            </div>
            <div id="dirTo" style="display:none;">
                <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:5px;">To</label>
                <input type="date" name="date_to"
                    style="width:100%; padding:9px 12px; border:1px solid #d1d5db; border-radius:7px; font-size:13px;">
            </div>
            <div>
                <button type="submit"
                    style="background:#111827; color:white; border:none; padding:10px 18px;
                           border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; width:100%;">
                    ⚡ Generate
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleDirType() {
            const t = document.getElementById('dirReportType').value;
            document.getElementById('dirMonth').style.display = t === 'month' ? 'block' : 'none';
            document.getElementById('dirFrom').style.display = t === 'range' ? 'block' : 'none';
            document.getElementById('dirTo').style.display = t === 'range' ? 'block' : 'none';
        }
    </script>
@endsection
