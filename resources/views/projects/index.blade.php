@extends('layouts.app')

@section('title', 'Projects')
@section('page-title', '')
@section('card-class', 'projects-wide')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a> |
    <a href="{{ route('clients.index') }}">Clients</a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .page-card {
            max-width: 1280px;
            margin: 0 auto;
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        .page-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-top: 2px;
        }

        .add-btn {
            background: #2563eb;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
        }

        .add-btn:hover {
            background: #1d4ed8;
        }

        .projects-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            table-layout: fixed;
        }

        .projects-table thead {
            background: #1f3a5f;
            color: white;
        }

        .projects-table th {
            padding: 12px 14px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .projects-table td {
            padding: 13px 14px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .projects-table tbody tr:hover {
            background: #f9fafb;
        }

        /* Column widths — fixed layout so nothing overflows */
        .col-client {
            width: 160px;
        }

        .col-project {
            width: 170px;
        }

        .col-location {
            width: 110px;
        }

        .col-money {
            width: 115px;
        }

        .col-progress {
            width: 130px;
        }

        .col-actions {
            width: 90px;
        }

        .client-cell {
            display: flex;
            align-items: center;
            gap: 9px;
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
            color: #111827;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .project-name {
            font-weight: 600;
            color: #111827;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .location-tag {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: 500;
            padding: 3px 8px;
            border-radius: 20px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .location-none {
            font-size: 11px;
            color: #9ca3af;
            font-style: italic;
        }

        .amount {
            font-weight: 600;
            color: #2563eb;
        }

        .spent {
            font-weight: 600;
            color: #dc2626;
        }

        .balance {
            font-weight: 600;
            color: #16a34a;
        }

        .money-cell {
            font-size: 12px;
            white-space: nowrap;
        }

        .progress-wrap {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .progress-label {
            font-size: 11px;
            font-weight: 600;
            color: #374151;
        }

        .progress-bar {
            width: 100%;
            background: #e5e7eb;
            border-radius: 6px;
            height: 8px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 6px;
        }

        .progress-green {
            background: #16a34a;
        }

        .progress-orange {
            background: #f59e0b;
        }

        .progress-red {
            background: #dc2626;
        }

        .actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .actions a {
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            color: #2563eb;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .delete-link {
            color: #dc2626 !important;
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            color: #9ca3af;
            font-size: 14px;
        }
    </style>

    <div class="page-card">

        <div class="page-header">
            <div>
                <div class="page-title">Projects</div>
                <div class="page-subtitle">Financial overview of all registered projects</div>
            </div>
            <a href="{{ route('projects.create') }}" class="add-btn">+ New Project</a>
        </div>

        <table class="projects-table">
            <thead>
                <tr>
                    <th class="col-client">Client</th>
                    <th class="col-project">Project</th>
                    <th class="col-location">Location</th>
                    <th class="col-money">Contract</th>
                    <th class="col-money">Allocated</th>
                    <th class="col-money">Spent</th>
                    <th class="col-money">Balance</th>
                    <th class="col-progress">Progress</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($projects as $project)
                    @php
                        $allocated = $project->allocations->sum('amount');

                        $spent = $project->allocations->sum(function ($allocation) {
                            return $allocation->expenses->sum('amount');
                        });

                        $balance = $project->contract_amount - $spent;

                        $progress =
                            $project->contract_amount > 0 ? min(($spent / $project->contract_amount) * 100, 100) : 0;

                        $barColor =
                            $progress > 90 ? 'progress-red' : ($progress > 70 ? 'progress-orange' : 'progress-green');

                        $initials =
                            substr($project->client->first_name, 0, 1) . substr($project->client->last_name, 0, 1);
                    @endphp

                    <tr>
                        <td class="col-client">
                            <div class="client-cell">
                                <div class="client-avatar">{{ strtoupper($initials) }}</div>
                                <div class="client-name">
                                    {{ $project->client->first_name }} {{ $project->client->last_name }}
                                </div>
                            </div>
                        </td>

                        <td class="col-project">
                            <div class="project-name">{{ $project->project_name }}</div>
                        </td>

                        <td class="col-location">
                            @if ($project->location)
                                <span class="location-tag">{{ $project->location }}</span>
                            @else
                                <span class="location-none">Not set</span>
                            @endif
                        </td>

                        <td class="col-money money-cell amount">
                            TSh {{ number_format($project->contract_amount, 0) }}
                        </td>

                        <td class="col-money money-cell">
                            TSh {{ number_format($allocated, 0) }}
                        </td>

                        <td class="col-money money-cell spent">
                            TSh {{ number_format($spent, 0) }}
                        </td>

                        <td class="col-money money-cell balance">
                            TSh {{ number_format($balance, 0) }}
                        </td>

                        <td class="col-progress">
                            <div class="progress-wrap">
                                <span class="progress-label">{{ number_format($progress, 1) }}%</span>
                                <div class="progress-bar">
                                    <div class="progress-fill {{ $barColor }}" style="width:{{ $progress }}%">
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="col-actions">
                            <div class="actions">
                                <a href="{{ route('projects.expenses', $project) }}">View</a>
                                <a href="#" class="delete-link"
                                    onclick="openDeleteModal({{ $project->id }}, '{{ addslashes($project->project_name) }}')">
                                    Delete
                                </a>
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="9" class="empty-state">No projects registered yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- DELETE MODAL -->
    <div id="deleteModal"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
            justify-content:center; align-items:center; z-index:9999;">

        <div
            style="background:white; width:380px; border-radius:12px; padding:28px; text-align:center;
                box-shadow: 0 20px 50px rgba(0,0,0,0.15);">

            <h3 style="font-size:18px; font-weight:700; margin-bottom:8px; color:#111827;">
                Delete Project
            </h3>

            <p style="font-size:14px; color:#6b7280; margin-bottom:24px; line-height:1.5;">
                Are you sure you want to delete <strong id="projectName"></strong>?
                This action cannot be undone.
            </p>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display:flex; gap:10px; justify-content:center;">
                    <button type="button" onclick="closeDeleteModal()"
                        style="padding:9px 20px; border-radius:8px; border:1px solid #d1d5db;
                           background:white; font-weight:600; cursor:pointer; font-size:14px;">
                        Cancel
                    </button>
                    <button type="submit"
                        style="padding:9px 20px; border-radius:8px; border:none;
                           background:#dc2626; color:white; font-weight:600;
                           cursor:pointer; font-size:14px;">
                        Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal(id, name) {
            document.getElementById('deleteModal').style.display = 'flex';
            document.getElementById('projectName').innerText = name;
            document.getElementById('deleteForm').action = `/projects/${id}`;
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('deleteModal');
            if (e.target === modal) closeDeleteModal();
        });
    </script>

@endsection
