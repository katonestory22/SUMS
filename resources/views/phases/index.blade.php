@extends('layouts.app')

@section('title', 'Phases')
@section('page-title', 'Project Phases')

@section('sub-nav')
    <a href="{{ route('phases.create') }}">Add Phase</a>
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
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 35px 40px;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .card h2 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #222;
        }

        .card p {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .new-btn {
            display: inline-block;
            padding: 10px 16px;
            background-color: #2c5282;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .new-btn:hover {
            background-color: #1f3d5a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            border-bottom: 1px solid #e5e7eb;
        }

        tbody tr:hover {
            background-color: #f9fafb;
        }

        .status {
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 4px;
            color: #fff;
            font-size: 12px;
        }

        .status-green {
            background-color: #2f855a;
        }

        .status-orange {
            background-color: #dd6b20;
        }

        .status-red {
            background-color: #c53030;
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

        @media(max-width: 700px) {
            .card {
                padding: 25px;
            }

            table {
                font-size: 13px;
            }

            th,
            td {
                padding: 10px 12px;
            }
        }
    </style>

    <div class="card">

        <h2>Project Phases</h2>
        <p>View and manage the phases of your projects here.</p>

        {{-- Example table --}}
        <table>
            <thead>
                <tr>
                    <th>Phase Name</th>
                    <th>Project</th>
                    <th>Weight (%)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($phases as $phase)
                    <tr>
                        <td>{{ $phase->name }}</td>
                        <td>{{ $phase->project->project_name ?? '-' }}</td>
                        <td>{{ $phase->weight_percentage }}</td>
                        <td>
                            @php
                                $statusClass = 'status-green';
                                $statusText = 'On Track';
                                if ($phase->progress() < 50) {
                                    $statusClass = 'status-orange';
                                    $statusText = 'Behind';
                                }
                                if ($phase->progress() == 0) {
                                    $statusClass = 'status-red';
                                    $statusText = 'Not Started';
                                }
                            @endphp
                            <span class="status {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td>
                            <a href="{{ route('phases.edit', $phase->id) }}" class="action-btn">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:#666;padding:20px;">
                            No phases recorded yet
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination if needed --}}
        @if (method_exists($phases, 'links'))
            <div style="margin-top:20px;">
                {{ $phases->links() }}
            </div>
        @endif

    </div>

@endsection
