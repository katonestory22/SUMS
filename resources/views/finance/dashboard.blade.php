@extends('layouts.app')

@section('title', 'Finance Dashboard')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('clients.index') }}">Clients</a>
    <a href="{{ route('projects.index') }}">Projects</a>
    <a href="{{ route('allocations.index') }}">Income</a>
    <a href="{{ route('company-expenses.index') }}">Company Expenses</a>
    <a href="{{ route('reports.create') }}">Upload Report</a>
    <a href="{{ route('reports.my') }}">My Reports</a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        /* ── SUMMARY GRID ── */
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
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #e5e7eb;
            transition: transform .18s ease;
        }

        .summary-card:hover {
            transform: translateY(-2px);
        }

        .summary-card.blue {
            border-left-color: #2563eb;
        }

        .summary-card.green {
            border-left-color: #16a34a;
        }

        .summary-card.red {
            border-left-color: #dc2626;
        }

        .summary-card.amber {
            border-left-color: #f59e0b;
        }

        .summary-card.navy {
            border-left-color: #1f3a5f;
        }

        .summary-card.purple {
            border-left-color: #7c3aed;
        }

        .summary-title {
            font-size: 11px;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        /* ── TABLE CARD ── */
        .table-card {
            background: white;
            padding: 28px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .table-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .view-all-btn {
            font-size: 13px;
            font-weight: 600;
            color: #2563eb;
            text-decoration: none;
            padding: 6px 14px;
            background: #eff6ff;
            border-radius: 7px;
            transition: background 0.2s;
        }

        .view-all-btn:hover {
            background: #dbeafe;
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
            padding: 11px 14px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        td {
            padding: 13px 14px;
            border-bottom: 1px solid #f0f0f0;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .client-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .client-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .client-name {
            font-size: 13px;
            color: #374151;
        }

        .amount {
            font-weight: 600;
            color: #2563eb;
        }

        .proj-name {
            font-weight: 600;
            color: #111827;
        }

        /* ── CHARTS ── */
        .chart-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .chart-card {
            background: white;
            padding: 28px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }

        .chart-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
        }

        /* ── PIE LEGEND ── */
        .pie-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .pie-canvas-wrap {
            flex: 1;
            max-width: 200px;
            height: 200px;
        }

        .pie-legend {
            flex: 1;
            display: grid;
            gap: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #374151;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 3px;
            display: inline-block;
            flex-shrink: 0;
        }

        .legend-amount {
            margin-left: auto;
            font-weight: 600;
            font-size: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
            font-size: 14px;
        }

        @media(max-width: 900px) {
            .dashboard-grid {
                grid-template-columns: 1fr 1fr;
            }

            .chart-grid {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width: 600px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- ── SUMMARY STATS ── --}}
    <div class="dashboard-grid">
        <div class="summary-card blue">
            <div class="summary-title">Total Clients</div>
            <div class="summary-number">{{ $clientsCount }}</div>
        </div>
        <div class="summary-card navy">
            <div class="summary-title">Total Projects</div>
            <div class="summary-number">{{ $projectsCount }}</div>
        </div>
        <div class="summary-card purple">
            <div class="summary-title">Total Contract Value</div>
            <div class="summary-money">TSh {{ number_format($totalContractValue, 0) }}</div>
        </div>
        <div class="summary-card amber">
            <div class="summary-title">Total Income Allocated</div>
            <div class="summary-money">TSh {{ number_format($totalAllocated, 0) }}</div>
        </div>
        <div class="summary-card red">
            <div class="summary-title">Total Spent</div>
            <div class="summary-money">TSh {{ number_format($totalSpent, 0) }}</div>
        </div>
        <div class="summary-card green">
            <div class="summary-title">Remaining Budget</div>
            <div class="summary-money">TSh {{ number_format($remainingBudget, 0) }}</div>
        </div>
    </div>

    {{-- ── RECENT PROJECTS ── --}}
    <div class="table-card">
        <div class="table-header">
            <div class="table-title">Recent Projects</div>
            <a href="{{ route('projects.index') }}" class="view-all-btn">View All →</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Project</th>
                    <th>Contract No.</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    @php
                        $initials =
                            substr($project->client->first_name, 0, 1) . substr($project->client->last_name, 0, 1);
                    @endphp
                    <tr>
                        <td>
                            <div class="client-cell">
                                <div class="client-avatar">{{ strtoupper($initials) }}</div>
                                <div class="client-name">
                                    {{ $project->client->first_name }}
                                    {{ $project->client->last_name }}
                                </div>
                            </div>
                        </td>
                        <td><span class="proj-name">{{ $project->project_name }}</span></td>
                        <td style="color:#6b7280; font-size:12px;">{{ $project->contract_number }}</td>
                        <td class="amount">TSh {{ number_format($project->contract_amount, 0) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-state">No projects registered yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── CHARTS ── --}}
    <div class="chart-grid">

        {{-- Cashflow Bar --}}
        <div class="chart-card">
            <div class="chart-title">Cashflow Overview</div>
            <div style="height:260px;">
                <canvas id="cashflowChart"></canvas>
            </div>
        </div>

        {{-- Expense Pie --}}
        <div class="chart-card">
            <div class="chart-title">Expense Breakdown by Category</div>
            <div class="pie-wrap">
                <div class="pie-canvas-wrap">
                    <canvas id="expenseChart"></canvas>
                </div>
                <div class="pie-legend">
                    @php
                        $pieColors = ['#2563eb', '#dc2626', '#f59e0b', '#16a34a', '#8b5cf6', '#6b7280'];
                    @endphp
                    @foreach ($expenseByCategory as $item)
                        <div class="legend-item">
                            <span class="legend-dot" style="background:{{ $pieColors[$loop->index] ?? '#6b7280' }}"></span>
                            <span>{{ $item['category'] }}</span>
                            <span class="legend-amount">TSh {{ number_format($item['total'], 0) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Cashflow bar
        new Chart(document.getElementById('cashflowChart'), {
            type: 'bar',
            data: {
                labels: ['Allocated', 'Spent', 'Remaining'],
                datasets: [{
                    data: [
                        {{ $cashflow['allocated'] }},
                        {{ $cashflow['spent'] }},
                        {{ $cashflow['remaining'] }}
                    ],
                    backgroundColor: ['#2563eb', '#dc2626', '#16a34a'],
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        }
                    }
                }
            }
        });

        // Expense pie
        new Chart(document.getElementById('expenseChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($expenseByCategory->pluck('category')) !!},
                datasets: [{
                    data: {!! json_encode($expenseByCategory->pluck('total')) !!},
                    backgroundColor: ['#2563eb', '#dc2626', '#f59e0b', '#16a34a', '#8b5cf6', '#6b7280'],
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '65%',
            }
        });
    </script>

@endsection
