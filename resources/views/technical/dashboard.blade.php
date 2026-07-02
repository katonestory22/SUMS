@extends('layouts.app')

@section('title', 'Technical Dashboard')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('technical.dashboard') }}">Home</a>
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

        /* ── Summary cards ───────────────────────────────────── */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .summary-card {
            background: #fff;
            padding: 22px 24px;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
            border-left: 4px solid #e5e7eb;
        }

        .summary-card.blue {
            border-left-color: #2563eb;
        }

        .summary-card.green {
            border-left-color: #16a34a;
        }

        .summary-card.amber {
            border-left-color: #f59e0b;
        }

        .summary-card.purple {
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
            color: #111827;
        }

        /* ── Wrapper cards ───────────────────────────────────── */
        .table-card,
        .chart-card {
            background: white;
            padding: 28px 30px;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
            margin-bottom: 24px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-title,
        .chart-title {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
        }

        /* ── Project grid ────────────────────────────────────── */
        .project-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        /* THE CARD IS THE MAIN CHARACTER */
        .project-card {
            background: #fff;
            border: 0.5px solid #e5e7eb;
            border-radius: 12px;
            padding: 22px 24px;
            transition: border-color .15s, box-shadow .15s;
        }

        .project-card:hover {
            border-color: #d1d5db;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .06);
        }

        .project-name {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 3px;
        }

        .project-client {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 18px;
        }

        /* ── Status badge — soft tints, no loud fills ────────── */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            margin-bottom: 18px;
        }

        .badge-healthy {
            background: #f0fdf4;
            color: #15803d;
        }

        .badge-warning {
            background: #fffbeb;
            color: #b45309;
        }

        .badge-danger {
            background: #fef2f2;
            color: #b91c1c;
        }

        .badge-new {
            background: #eff6ff;
            color: #1d4ed8;
        }

        /* ── Progress bar — thin and quiet ──────────────────── */
        .progress-wrap {
            margin-bottom: 5px;
            background: #f3f4f6;
            border-radius: 20px;
            height: 5px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 20px;
            transition: width .4s ease;
        }

        .fill-healthy {
            background: #86efac;
        }

        .fill-warning {
            background: #fcd34d;
        }

        .fill-danger {
            background: #fca5a5;
        }

        .fill-new {
            background: #bfdbfe;
        }

        .progress-label {
            font-size: 11px;
            color: #9ca3af;
            margin-bottom: 20px;
        }

        /* ── View button — ghost, quiet ──────────────────────── */
        .view-btn {
            display: inline-block;
            padding: 7px 14px;
            border-radius: 7px;
            border: 0.5px solid #d1d5db;
            background: transparent;
            color: #6b7280;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: background .12s, color .12s, border-color .12s;
        }

        .view-btn:hover {
            background: #f9fafb;
            color: #111827;
            border-color: #9ca3af;
        }

        /* ── Empty state ─────────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: #9ca3af;
            font-size: 14px;
        }

        /* ── Chart ───────────────────────────────────────────── */
        .chart-colors {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 8px;
            margin-bottom: 20px;
        }

        .chart-legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #6b7280;
        }

        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        @media (max-width: 900px) {
            .dashboard-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 560px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- ── SUMMARY CARDS ─────────────────────────────────────── --}}

    <div class="dashboard-grid">

        <div class="summary-card blue">
            <div class="summary-title">Projects</div>
            <div class="summary-number">{{ $projectsCount }}</div>
        </div>

        <div class="summary-card green">
            <div class="summary-title">Active Phases</div>
            <div class="summary-number">{{ $phasesCount }}</div>
        </div>

        <div class="summary-card amber">
            <div class="summary-title">Activities</div>
            <div class="summary-number">{{ $activitiesCount }}</div>
        </div>

        <div class="summary-card purple">
            <div class="summary-title">Avg Progress</div>
            <div class="summary-number">{{ round($averageProgress) }}%</div>
        </div>

    </div>

    {{-- ── PROJECT CARDS ─────────────────────────────────────── --}}

    <div class="table-card">

        <div class="table-header">
            <div class="table-title">Project Technical Overview</div>
        </div>

        @if ($projects->isEmpty())
            <div class="empty-state">No projects found.</div>
        @else
            <div class="project-grid">

                @foreach ($projects as $project)
                    @php
                        /*
                         * SMARTER STATUS LOGIC
                         * ─────────────────────
                         * Instead of judging progress in isolation (which always
                         * flags new projects as "Critical"), we compare what's
 * actually done against what *should* be done by now,
 * based on how far through the project timeline we are.
 *
 * expected_progress = elapsed days / total duration × 100
 * gap              = actual − expected  (negative = behind)
 *
 * Thresholds:
 *   gap ≥ −10  → On track   (within 10% of schedule)
 *   gap ≥ −25  → Delayed    (10–25% behind)
 *   gap <  −25 → Critical   (>25% behind)
 *
 * Edge cases:
 *   • No start/end date set        → "Not started"
 *   • Project not started yet      → "Not started"
 *   • No activities recorded yet   → "Not started"
 *   • Project past end date        → compare against 100%
 */

$actualProgress = round($project->activities_avg_current_progress ?? 0);

$startDate = $project->start_date ? \Carbon\Carbon::parse($project->start_date) : null;
$endDate = $project->end_date ? \Carbon\Carbon::parse($project->end_date) : null;
$today = \Carbon\Carbon::today();

$hasActivities = $actualProgress > 0;
$hasTimeline = $startDate && $endDate && $endDate->gt($startDate);

if (!$hasTimeline || !$hasActivities || $today->lt($startDate)) {
    // Not enough info to judge — show neutral state
    $badgeClass = 'badge-new';
    $fillClass = 'fill-new';
    $label = 'Not started';
} else {
    $totalDays = $startDate->diffInDays($endDate);
    $elapsedDays = min($startDate->diffInDays($today), $totalDays);
    $expectedProgress = ($elapsedDays / $totalDays) * 100;
    $gap = $actualProgress - $expectedProgress;

    if ($gap >= -10) {
        $badgeClass = 'badge-healthy';
        $fillClass = 'fill-healthy';
        $label = 'On track';
    } elseif ($gap >= -25) {
        $badgeClass = 'badge-warning';
        $fillClass = 'fill-warning';
        $label = 'Delayed';
    } else {
        $badgeClass = 'badge-danger';
        $fillClass = 'fill-danger';
        $label = 'Critical';
                            }
                        }
                    @endphp

                    <div class="project-card">

                        <div class="project-name">{{ $project->project_name }}</div>

                        <div class="project-client">
                            {{ $project->client->first_name }} {{ $project->client->last_name }}
                        </div>

                        <span class="status-badge {{ $badgeClass }}">{{ $label }}</span>

                        <div class="progress-wrap">
                            <div class="progress-fill {{ $fillClass }}" style="width: {{ $actualProgress }}%"></div>
                        </div>

                        <div class="progress-label">{{ $actualProgress }}% complete</div>

                        <a href="{{ route('projects.show', $project->id) }}" class="view-btn">
                            View details
                        </a>

                    </div>
                @endforeach
            </div>
        @endif

    </div>

    {{-- ── ACTIVITY CHART ────────────────────────────────────── --}}

    <div class="chart-card">

        <div class="chart-title">Activity Progress Overview</div>

        <div style="height: 320px; margin-top: 20px;">
            <canvas id="activityChart"></canvas>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const CHART_COLORS = [
            '#93c5fd', // blue-300
            '#86efac', // green-300
            '#fcd34d', // amber-300
            '#f9a8d4', // pink-300
            '#c4b5fd', // violet-300
            '#67e8f9', // cyan-300
            '#fdba74', // orange-300
        ];

        const labels = {!! json_encode($activityProgress->keys()) !!};
        const values = {!! json_encode($activityProgress->values()) !!};

        // Cycle colors safely regardless of how many activities exist
        const bgColors = labels.map((_, i) => CHART_COLORS[i % CHART_COLORS.length]);

        new Chart(document.getElementById('activityChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: bgColors,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y}%`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: val => val + '%',
                            precision: 0,
                            color: '#9ca3af',
                        },
                        grid: {
                            color: '#f3f4f6'
                        },
                        border: {
                            display: false
                        }
                    },
                    x: {
                        ticks: {
                            color: '#9ca3af'
                        },
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>

@endsection
