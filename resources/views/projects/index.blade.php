@extends('layouts.app')

@section('title', 'Projects')
@section('page-title', '')
@section('card-class', 'projects-wide')

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
            max-width: 1400px;
            margin: 0 auto;
            background: #fff;
            padding: 35px 40px;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .table-scroll {
            width: 100%;
            overflow-x: auto;
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
            font-size: 14px;
            white-space: nowrap;
            transition: background-color 0.2s ease;
        }

        .new-btn:hover {
            background-color: #1f3d5a;
        }

        .projects-table {
            width: 100%;
            min-width: 1100px;
            border-collapse: collapse;
            font-size: 13px;
            table-layout: fixed;
        }

        .projects-table thead {
            background-color: #2c5282;
            color: #fff;
        }

        .projects-table th {
            padding: 12px 14px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            letter-spacing: 0.3px;
        }

        .projects-table td {
            padding: 13px 14px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .projects-table tbody tr:last-child td {
            border-bottom: none;
        }

        .projects-table tbody tr:hover td {
            background: #f9fafb;
        }

        /* Column widths */
        .col-client {
            width: 180px;
        }

        .col-project {
            width: 200px;
        }

        .col-location {
            width: 120px;
        }

        .col-money {
            width: 125px;
        }

        .col-progress {
            width: 140px;
        }

        .col-actions {
            width: 110px;
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
            background: #2c5282;
            color: #fff;
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
            background: #e6f1fb;
            color: #0c447c;
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

        .money-cell {
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .col-contract {
            color: #2c5282;
        }

        .col-income {
            color: #374151;
        }

        .col-spent {
            color: #c53030;
        }

        .col-balance {
            color: #2f855a;
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
            background: #2f855a;
        }

        .progress-orange {
            background: #dd6b20;
        }

        .progress-red {
            background: #c53030;
        }

        .actions {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            padding: 5px 11px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .btn-view {
            background: #e6f1fb;
            color: #185fa5;
        }

        .btn-view:hover {
            background: #b5d4f4;
        }

        .btn-delete {
            background: #fef2f2;
            color: #dc2626;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .btn-delete:hover {
            background: #fee2e2;
        }

        .empty-state {
            text-align: center;
            padding: 45px;
            color: #9ca3af;
            font-size: 14px;
        }

        /* Delete Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-box {
            width: 380px;
            background: #fff;
            border-radius: 12px;
            padding: 28px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        }

        .modal-icon {
            width: 48px;
            height: 48px;
            background: #fef2f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 22px;
        }

        .modal-box h2 {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 8px;
        }

        .modal-box p {
            font-size: 14px;
            color: #6b7280;
            margin: 0 0 24px;
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
        }

        .modal-cancel {
            flex: 1;
            padding: 11px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            color: #374151;
            font-family: 'Inter', sans-serif;
            transition: background 0.2s;
        }

        .modal-cancel:hover {
            background: #f3f4f6;
        }

        .modal-confirm {
            flex: 1;
            padding: 11px;
            border-radius: 8px;
            border: none;
            background: #dc2626;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.2s;
        }

        .modal-confirm:hover {
            background: #b91c1c;
        }
    </style>

    <div class="card">

        <div class="page-header">
            <div>
                <h3>Projects</h3>
                <p>Financial overview of all registered projects</p>
            </div>
            <a href="{{ route('projects.create') }}" class="new-btn">+ New Project</a>
        </div>

        <div class="table-scroll">
            <table class="projects-table">
                <thead>
                    <tr>
                        <th class="col-client">Client</th>
                        <th class="col-project">Project</th>
                        <th class="col-location">Location</th>
                        <th class="col-money">Contract</th>
                        <th class="col-money">Income</th>
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
                                $project->contract_amount > 0
                                    ? min(($spent / $project->contract_amount) * 100, 100)
                                    : 0;

                            $barColor =
                                $progress > 90
                                    ? 'progress-red'
                                    : ($progress > 70
                                        ? 'progress-orange'
                                        : 'progress-green');

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

                            <td class="col-money money-cell col-contract">
                                TSh {{ number_format($project->contract_amount, 0) }}
                            </td>

                            <td class="col-money money-cell col-income">
                                TSh {{ number_format($allocated, 0) }}
                            </td>

                            <td class="col-money money-cell col-spent">
                                TSh {{ number_format($spent, 0) }}
                            </td>

                            <td class="col-money money-cell col-balance">
                                TSh {{ number_format($balance, 0) }}
                            </td>

                            <td class="col-progress">
                                <div class="progress-wrap">
                                    <span class="progress-label">{{ number_format($progress, 1) }}%</span>
                                    <div class="progress-bar">
                                        <div class="progress-fill {{ $barColor }}"
                                            style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </td>

                            <td class="col-actions">
                                <div class="actions">
                                    <a href="{{ route('projects.expenses', $project) }}" class="action-btn btn-view">
                                        View
                                    </a>
                                    <button type="button" class="action-btn btn-delete"
                                        onclick="openDeleteModal({{ $project->id }}, '{{ addslashes($project->project_name) }}')">
                                        Delete
                                    </button>
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

        @if (method_exists($projects, 'links'))
            <div style="margin-top: 20px;">{{ $projects->links() }}</div>
        @endif

    </div>

    {{-- DELETE CONFIRMATION MODAL --}}
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="modal-icon">&#x26A0;</div>
            <h2>Delete Project</h2>
            <p>Are you sure you want to delete <strong id="projectName"></strong>? This action cannot be undone.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-actions">
                    <button type="button" class="modal-cancel" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="modal-confirm">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal(id, name) {
            document.getElementById('projectName').innerText = name;
            document.getElementById('deleteForm').action = `/projects/${id}`;
            document.getElementById('deleteModal').classList.add('open');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('open');
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('deleteModal');
            if (e.target === modal) closeDeleteModal();
        });
    </script>

@endsection
