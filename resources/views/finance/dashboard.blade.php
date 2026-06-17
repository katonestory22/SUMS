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

        /* SUMMARY GRID */

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            transition: .2s;
        }

        .summary-card:hover {
            transform: translateY(-2px);
        }

        .summary-title {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .summary-number {
            font-size: 26px;
            font-weight: 700;
            color: #2563eb;
        }

        .summary-money {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        /* TABLE */

        .table-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 18px;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead {
            background: #1f3a5f;
            color: white;
        }

        th {
            padding: 13px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 13px;
            border-bottom: 1px solid #e5e7eb;
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
        }

        .amount {
            font-weight: 600;
            color: #2563eb;
        }

        /* RESPONSIVE */

        @media(max-width:900px) {

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

        }
    </style>



    <div class="dashboard-grid">

        <div class="summary-card">
            <div class="summary-title">Total Clients</div>
            <div class="summary-number">{{ $clientsCount }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-title">Total Projects</div>
            <div class="summary-number">{{ $projectsCount }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-title">Total Contract Value</div>
            <div class="summary-money">
                TSh {{ number_format($totalContractValue, 2) }}
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-title">Total Income Funds</div>
            <div class="summary-money">
                TSh {{ number_format($totalAllocated, 2) }}
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-title">Total Spent</div>
            <div class="summary-money">
                TSh {{ number_format($totalSpent, 2) }}
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-title">Remaining Budget</div>
            <div class="summary-money">
                TSh {{ number_format($remainingBudget, 2) }}
            </div>
        </div>

    </div>



    <div class="table-card">

        <div class="table-title">
            Recent Projects
        </div>

        <table>

            <thead>
                <tr>
                    <th>Client</th>
                    <th>Project</th>
                    <th>Contract</th>
                    <th>Amount</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($projects as $project)
                    @php

                        $initials =
                            substr($project->client->first_name, 0, 1) . substr($project->client->last_name, 0, 1);

                    @endphp

                    <tr>

                        <td>

                            <div class="client-cell">

                                <div class="client-avatar">
                                    {{ strtoupper($initials) }}
                                </div>

                                <div>
                                    {{ $project->client->first_name }}
                                    {{ $project->client->last_name }}
                                </div>

                            </div>

                        </td>

                        <td>
                            {{ $project->project_name }}
                        </td>

                        <td>
                            {{ $project->contract_number }}
                        </td>

                        <td class="amount">
                            TSh {{ number_format($project->contract_amount, 2) }}
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="table-card" style="margin-top:20px;">
        <div class="table-title">Cashflow Overview</div>

        <div style="height:350px;">
            <canvas id="cashflowChart"></canvas>
        </div>
    </div>

    <script>
        const cashflowChart = document.getElementById('cashflowChart');

        new Chart(cashflowChart, {
            type: 'bar',
            data: {
                labels: ['Allocated', 'Spent', 'Remaining'],
                datasets: [{
                    data: [
                        {{ $cashflow['allocated'] }},
                        {{ $cashflow['spent'] }},
                        {{ $cashflow['remaining'] }}
                    ],
                    backgroundColor: ['#2563eb', '#dc2626', '#16a34a']
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
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <div class="table-card" style="margin-top:20px;">
        <div class="table-title">Expense Breakdown</div>

        <div
            style="display:flex; align-items:center; justify-content:space-between; gap:20px; max-width:900px; margin:auto;">

            <!-- LEFT PIE -->
            <div style="flex:1; max-width:450px; height:320px;">
                <canvas id="expenseChart"></canvas>
            </div>

            <!-- RIGHT LEGEND -->
            <div style="flex:1;">
                <div style="display:grid; gap:10px;">
                    @foreach ($expenseByCategory as $item)
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

                            <span>{{ $item['category'] }}</span>

                            <span style="margin-left:auto; font-weight:600;">
                                TSh {{ number_format($item['total'], 0) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script>
        const expenseChart = document.getElementById('expenseChart');

        new Chart(expenseChart, {
            type: 'pie',
            data: {
                labels: {!! json_encode($expenseByCategory->pluck('category')) !!},
                datasets: [{
                    data: {!! json_encode($expenseByCategory->pluck('total')) !!},
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
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>



@endsection
