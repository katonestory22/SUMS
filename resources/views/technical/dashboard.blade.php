@extends('layouts.app')

@section('title', 'Technical Dashboard')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('technical.dashboard') }}">Home</a>
    <a href="{{ route('reports.create') }}">Upload Report</a>
@endsection


@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        /* SUMMARY */

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .summary-title {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        /* PROJECTS */

        .projects-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .projects-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .projects-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }

        .project-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .project-card {
            border: 1px solid #e5e7eb;
            padding: 18px;
            border-radius: 8px;
            transition: .2s;
        }

        .project-card:hover {
            border-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .project-name {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 6px;
            color: #111827;
        }

        .project-client {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 12px;
        }

        /* PROGRESS BAR */

        .progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .progress-fill {
            height: 100%;
            background: #2563eb;
        }

        .progress-text {
            font-size: 12px;
            color: #6b7280;
        }

        /* BUTTON */

        .view-btn {
            display: inline-block;
            margin-top: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #2563eb;
            text-decoration: none;
        }

        .view-btn:hover {
            text-decoration: underline;
        }

        /* RESPONSIVE */

        @media(max-width:900px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>


    {{-- SUMMARY --}}

    <div class="summary-grid">

        <div class="summary-card">
            <div class="summary-title">Projects</div>
            <div class="summary-value">{{ $projectsCount }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-title">Active Phases</div>
            <div class="summary-value">{{ $phasesCount }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-title">Activities</div>
            <div class="summary-value">{{ $activitiesCount }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-title">Avg Progress</div>
            <div class="summary-value">{{ $averageProgress }}%</div>
        </div>

    </div>


    {{-- PROJECTS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="projects-card" style="background:#fff;">

        <div class="projects-header">
            <div class="projects-title">Projects</div>
        </div>


        <div class="project-grid">

            @foreach ($projects as $project)
                @php

                    $progress = $project->activities_avg_current_progress ?? 0;

                @endphp

                <div class="project-card">

                    <div class="project-name">
                        {{ $project->project_name }}
                    </div>

                    <div class="project-client">
                        {{ $project->client->first_name }} {{ $project->client->last_name }}
                    </div>

                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $progress }}%"></div>
                    </div>

                    <div class="progress-text">
                        {{ round($progress) }}% complete
                    </div>

                    <a href="{{ route('projects.show', $project->id) }}" class="view-btn">
                        View Details →
                    </a>

                </div>
            @endforeach

        </div>

    </div>

    <div class="table-card" style="margin-top:20px;">
        <div class="table-title">Activity Progress Overview</div>

        <div style="height:350px;">
            <canvas id="activityChart"></canvas>
        </div>
    </div>

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
                        '#f59e0b'
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
