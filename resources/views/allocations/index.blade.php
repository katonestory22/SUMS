@extends('layouts.app')

@section('title', 'Project Income')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Back to Dashboard</a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .card {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 35px 40px;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h3 {
            font-size: 22px;
            font-weight: 600;
            margin: 0;
            color: #222;
        }

        .page-header p {
            font-size: 14px;
            color: #666;
            margin: 4px 0 0 0;
        }

        .new-btn {
            background-color: #2c5282;
            color: #fff;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .new-btn:hover {
            background-color: #1f3d5a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead {
            background-color: #2c5282;
            color: #fff;
        }

        th,
        td {
            padding: 12px 14px;
            text-align: left;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
            transition: background 0.2s;
        }

        tbody tr:hover {
            background-color: #f9fafb;
        }

        .amount {
            font-weight: 600;
            color: #2c5282;
        }

        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            color: #fff;
        }

        .status-green {
            background: #2f855a;
        }

        .status-orange {
            background: #dd6b20;
        }

        .status-red {
            background: #c53030;
        }

        .action-btn {
            font-size: 13px;
            font-weight: 600;
            color: #2c5282;
            text-decoration: none;
        }

        .action-btn:hover {
            text-decoration: underline;
        }

        @media(max-width: 900px) {
            .card {
                padding: 25px;
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            tbody tr {
                margin-bottom: 15px;
            }

            td {
                padding-left: 50%;
                position: relative;
            }

            td::before {
                position: absolute;
                left: 14px;
                width: 45%;
                white-space: nowrap;
                font-weight: 600;
            }

            td:nth-of-type(1)::before {
                content: "Client";
            }

            td:nth-of-type(2)::before {
                content: "Project";
            }

            td:nth-of-type(3)::before {
                content: "Category";
            }

            td:nth-of-type(4)::before {
                content: "Allocated";
            }

            td:nth-of-type(5)::before {
                content: "Spent";
            }

            td:nth-of-type(6)::before {
                content: "Remaining";
            }

            td:nth-of-type(7)::before {
                content: "Status";
            }

            td:nth-of-type(8)::before {
                content: "Actions";
            }
        }
    </style>

    <div class="card">

        <div class="page-header">
            <div>
                <h3>Income</h3>
                <p>Budget allocations for projects</p>
            </div>

            <a href="{{ route('allocations.create') }}" class="new-btn">+ New Income Received</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Project</th>
                    <th>Category</th>
                    <th>Income Received</th>
                    <th>Spent</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allocations as $allocation)
                    @php
                        $spent = $allocation->expenses_sum_amount ?? 0;
                        $remaining = $allocation->amount - $spent;
                        $percentage = $allocation->amount > 0 ? ($remaining / $allocation->amount) * 100 : 0;

                        $statusClass = 'status-green';
                        $statusText = 'Healthy';

                        if ($percentage <= 50 && $percentage > 20) {
                            $statusClass = 'status-orange';
                            $statusText = 'Warning';
                        }
                        if ($percentage <= 20) {
                            $statusClass = 'status-red';
                            $statusText = 'Critical';
                        }
                    @endphp

                    <tr>
                        <td>{{ $allocation->project->client->first_name }} {{ $allocation->project->client->last_name }}
                        </td>
                        <td>{{ $allocation->project->project_name }}</td>
                        <td>{{ $allocation->category }}</td>
                        <td class="amount">TSh {{ number_format($allocation->amount, 2) }}</td>
                        <td>TSh {{ number_format($spent, 2) }}</td>
                        <td class="amount">TSh {{ number_format($remaining, 2) }}</td>
                        <td><span class="status {{ $statusClass }}">{{ $statusText }}</span></td>
                        <td><a href="{{ route('expenses.create', $allocation->id) }}" class="action-btn">Add Expense</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; color:#666; padding:25px;">
                            No allocations recorded yet
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if (method_exists($allocations, 'links'))
            <div style="margin-top:20px;">{{ $allocations->links() }}</div>
        @endif

    </div>

@endsection
