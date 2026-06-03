<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #111827;
        }

        h1 {
            font-size: 20px;
            color: #1f3a5f;
            margin-bottom: 4px;
        }

        h2 {
            font-size: 15px;
            color: #1f3a5f;
            margin-top: 24px;
            margin-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 4px;
        }

        .meta {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #1f3a5f;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 12px;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
        }

        .total-row td {
            font-weight: bold;
            background: #f9fafb;
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>

    <h1>{{ $type }}</h1>
    <div class="meta">
        Project: <strong>{{ $project->project_name }}</strong> &nbsp;|&nbsp;
        Client: <strong>{{ $project->client->first_name }} {{ $project->client->last_name }}</strong> &nbsp;|&nbsp;
        Generated: <strong>{{ now()->format('d M Y') }}</strong>
    </div>

    @if ($type === 'Financial Report')

        <h2>Finance Summary</h2>
        <table>
            <tr>
                <th>Item</th>
                <th class="right">Amount (TSh)</th>
            </tr>
            <tr>
                <td>Contract Value</td>
                <td class="right">{{ number_format($project->contract_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Total Allocated</td>
                <td class="right">{{ number_format($project->totalAllocated(), 2) }}</td>
            </tr>
            <tr>
                <td>Total Spent</td>
                <td class="right">{{ number_format($project->totalExpenses(), 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Balance</td>
                <td class="right">{{ number_format($project->remainingBalance(), 2) }}</td>
            </tr>
        </table>

        <h2>Expense Breakdown by Category</h2>
        <table>
            <tr>
                <th>Category</th>
                <th class="right">Total Spent (TSh)</th>
            </tr>
            @foreach ($project->allocations as $allocation)
                @foreach ($allocation->expenses->groupBy('category') as $cat => $expenses)
                    <tr>
                        <td>{{ $cat }}</td>
                        <td class="right">{{ number_format($expenses->sum('amount'), 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    @else
        <h2>Progress Summary</h2>
        <table>
            <tr>
                <th>Phase</th>
                <th>Activity</th>
                <th class="right">Progress</th>
            </tr>
            @foreach ($project->phases as $phase)
                @foreach ($phase->activities as $activity)
                    <tr>
                        <td>{{ $phase->name }}</td>
                        <td>{{ $activity->name }}</td>
                        <td class="right">{{ $activity->current_progress }}%</td>
                    </tr>
                @endforeach
            @endforeach
        </table>

    @endif

</body>

</html>
