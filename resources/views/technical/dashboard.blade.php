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

        .amber {
            border-left-color: #f59e0b;
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

        .project-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .project-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            transition: .2s;
            background: #fff;
        }

        .project-card:hover {
            transform: translateY(-2px);
            border-color: #2563eb;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
        }

        .project-name {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 6px;
        }

        .project-client {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 18px;
        }

        .progress-container {
            margin-bottom: 15px;
        }

        .progress-bar {
            height: 10px;
            background: #e5e7eb;
            border-radius: 50px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #2563eb;
            border-radius: 50px;
        }

        .progress-text {
            margin-top: 8px;
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            color: white;
            margin-bottom: 15px;
        }

        .healthy {
            background: #16a34a;
        }

        .warning {
            background: #f59e0b;
        }

        .danger {
            background: #dc2626;
        }

        .view-btn {
            display: inline-block;
            padding: 8px 14px;
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

        @media(max-width:900px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- SUMMARY CARDS --}}

    <div class="dashboard-grid">

        <div class="summary-card blue">
            <div class="summary-title">Projects</div>
            <div class="summary-number">
                {{ $projectsCount }}
            </div>
        </div>

        <div class="summary-card green">
            <div class="summary-title">Active Phases</div>
            <div class="summary-number">
                {{ $phasesCount }}
            </div>
        </div>

        <div class="summary-card amber">
            <div class="summary-title">Activities</div>
            <div class="summary-number">
                {{ $activitiesCount }}
            </div>
        </div>

        <div class="summary-card purple">
            <div class="summary-title">Average Progress</div>
            <div class="summary-number">
                {{ round($averageProgress) }}%
            </div>
        </div>

    </div>

    {{-- PROJECTS --}}

    <div class="table-card">

        <div class="table-header">
            <div class="table-title">
                Project Technical Overview
            </div>
        </div>

        <div class="project-grid">

            @foreach ($projects as $project)
                @php

                    $progress = $project->activities_avg_current_progress ?? 0;

                    $status = 'healthy';
                    $label = 'On Track';

                    if ($progress < 30) {
                        $status = 'danger';
                        $label = 'Critical';
                    } elseif ($progress < 60) {
                        $status = 'warning';
                        $label = 'Delayed';
                    }

                @endphp

                <div class="project-card">

                    <div class="project-name">
                        {{ $project->project_name }}
                    </div>

                    <div class="project-client">
                        {{ $project->client->first_name }}
                        {{ $project->client->last_name }}
                    </div>

                    <span class="status-badge {{ $status }}">
                        {{ $label }}
                    </span>

                    <div class="progress-container">

                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $progress }}%">
                            </div>
                        </div>

                        <div class="progress-text">
                            {{ round($progress) }}% Complete
                        </div>

                    </div>

                    <a href="{{ route('projects.show', $project->id) }}" class="view-btn">
                        View Details
                    </a>

                </div>
            @endforeach

        </div>

    </div>

    {{-- CHART --}}

    <div class="chart-card">

        <div class="chart-title">
            Activity Progress Overview
        </div>

        <div style="height:350px;">
            <canvas id="activityChart"></canvas>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('activityChart');

        new Chart(ctx, {

            type: 'bar',

            data: {

                labels: {!! json_encode($activityProgress->keys()) !!},

                datasets: [{

                    data: {!! json_encode($activityProgress->values()) !!},

                    backgroundColor: [
                        '#2563eb',
                        '#16a34a',
                        '#f59e0b',
                        '#dc2626',
                        '#7c3aed'
                    ],

                    borderRadius: 6

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
                        enabled: true
                    }

                },

                scales: {

                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }

                }

            }

        });
    </script>

@endsection
