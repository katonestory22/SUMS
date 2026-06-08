<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #2d2d2d;
            margin: 0;
            padding: 0;
        }

        /* ── HEADER BANNER ── */
        .report-header {
            background: #1f3a5f;
            padding: 20px 24px;
            margin-bottom: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-logo-cell {
            width: 64px;
            vertical-align: middle;
        }

        .header-brand-cell {
            vertical-align: middle;
            padding-left: 14px;
        }

        .brand-name {
            font-size: 15px;
            font-weight: bold;
            color: #C9A84C;
            letter-spacing: 1px;
        }

        .brand-sub {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 2px;
            letter-spacing: 0.5px;
        }

        .header-report-cell {
            text-align: right;
            vertical-align: middle;
        }

        .report-type-label {
            font-size: 18px;
            font-weight: bold;
            color: #C9A84C;
            letter-spacing: 0.5px;
        }

        .report-date-label {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* ── GOLD ACCENT BAR ── */
        .accent-bar {
            background: #C9A84C;
            height: 4px;
            width: 100%;
        }

        /* ── META INFO STRIP ── */
        .meta-strip {
            background: #f8f6f0;
            border: 1px solid #e8dfc8;
            border-radius: 6px;
            padding: 12px 16px;
            margin: 20px 0 24px;
        }

        .meta-strip table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .meta-strip td {
            padding: 3px 8px 3px 0;
            border: none;
            font-size: 12px;
            color: #4b5563;
        }

        .meta-label {
            font-weight: bold;
            color: #1f3a5f;
            width: 90px;
        }

        /* ── SECTION HEADINGS ── */
        h2 {
            font-size: 13px;
            font-weight: bold;
            color: #1f3a5f;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 24px;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 2px solid #C9A84C;
        }

        /* ── TABLES ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        th {
            background: #1f3a5f;
            color: #C9A84C;
            padding: 9px 12px;
            text-align: left;
            font-size: 11px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        td {
            padding: 8px 12px;
            border-bottom: 1px solid #f0ebe0;
            font-size: 12px;
            color: #374151;
        }

        tbody tr:nth-child(even) td {
            background: #fdf9f2;
        }

        .total-row td {
            font-weight: bold;
            background: #f8f3e6;
            color: #1f3a5f;
            border-top: 2px solid #C9A84C;
            border-bottom: 2px solid #C9A84C;
        }

        .right {
            text-align: right;
        }

        /* ── FOOTER ── */
        .report-footer {
            margin-top: 40px;
            border-top: 1px solid #e8dfc8;
            padding-top: 10px;
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            font-size: 10px;
            color: #9ca3af;
        }

        .footer-right {
            display: table-cell;
            text-align: right;
            font-size: 10px;
            color: #9ca3af;
        }

        .gold {
            color: #C9A84C;
        }
    </style>
</head>
@php
    $logo = public_path('images/swahililogo.png');
@endphp

<body>

    {{-- ── HEADER ── --}}
    <div class="report-header">
        <table class="header-table">
            <tr>
                <td class="header-logo-cell">
                    <img src="{{ $logo }}" width="54" height="54">
                </td>
                <td class="header-brand-cell">
                    <div class="brand-name">SUMS</div>
                    <div class="brand-sub">Swahili Units Management System</div>
                </td>
                <td class="header-report-cell">
                    <div class="report-type-label">{{ strtoupper($type) }}</div>
                    <div class="report-date-label">Generated {{ now()->format('d M Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="accent-bar"></div>

    {{-- ── PROJECT META ── --}}
    <div class="meta-strip">
        <table>
            <tr>
                <td class="meta-label">Project</td>
                <td>{{ $project->project_name }}</td>
                <td class="meta-label">Client</td>
                <td>{{ $project->client->first_name }} {{ $project->client->last_name }}</td>
            </tr>
            <tr>
                <td class="meta-label">Location</td>
                <td>{{ $project->location ?? 'Not specified' }}</td>
                <td class="meta-label">Contract No.</td>
                <td>{{ $project->contract_number }}</td>
            </tr>
            <tr>
                <td class="meta-label">Start Date</td>
                <td>{{ \Carbon\Carbon::parse($project->start_date)->format('d M Y') }}</td>
                <td class="meta-label">End Date</td>
                <td>{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : 'Ongoing' }}
                </td>
            </tr>
        </table>
    </div>

    {{-- ── FINANCIAL REPORT ── --}}
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
                <td>Remaining Balance</td>
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

        {{-- ── PROGRESS REPORT ── --}}
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

        <h2>Overall Project Progress</h2>
        <table>
            <tr>
                <th>Metric</th>
                <th class="right">Value</th>
            </tr>
            <tr>
                <td>Total Phases</td>
                <td class="right">{{ $project->phases->count() }}</td>
            </tr>
            <tr>
                <td>Total Activities</td>
                <td class="right">{{ $project->phases->sum(fn($p) => $p->activities->count()) }}</td>
            </tr>
            <tr class="total-row">
                <td>Overall Progress</td>
                <td class="right">{{ $project->progress }}%</td>
            </tr>
        </table>

    @endif

    {{-- ── FOOTER ── --}}
    <div class="report-footer">
        <div class="footer-left">
            SUMS — Swahili Units Management System &nbsp;·&nbsp;
            <span class="gold">Confidential</span>
        </div>
        <div class="footer-right">
            Page 1 &nbsp;·&nbsp; {{ now()->format('d M Y, H:i') }}
        </div>
    </div>

</body>

</html>
