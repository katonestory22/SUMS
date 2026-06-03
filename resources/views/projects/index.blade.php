@extends('layouts.app')

@section('title', 'Projects')
@section('page-title', '')

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
            max-width: 1150px;
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
            font-size: 14px;
            color: #6b7280;
        }

        .add-btn {
            background: #2563eb;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .add-btn:hover {
            background: #1d4ed8;
        }

        .projects-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .projects-table thead {
            background: #1f3a5f;
            color: white;
        }

        .projects-table th,
        .projects-table td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .projects-table tbody tr:hover {
            background: #f9fafb;
        }

        .client-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .client-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
        }

        .project-name {
            font-weight: 600;
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

        .progress-bar {
            width: 100%;
            background: #e5e7eb;
            border-radius: 6px;
            height: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
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

        .actions a {
            margin-right: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #2563eb;
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .delete-link {
            color: #dc2626 !important;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }
    </style>

    <div class="page-card">

        <div class="page-header">
            <div>
                <div class="page-title">Projects</div>
                <div class="page-subtitle">Financial overview of all projects</div>
            </div>

            <a href="{{ route('projects.create') }}" class="add-btn">
                + New Project
            </a>
        </div>

        <table class="projects-table">

            <thead>
                <tr>
                    <th>Client</th>
                    <th>Project</th>
                    <th>Location</th>
                    <th>Contract</th>
                    <th>Allocated</th>
                    <th>Spent</th>
                    <th>Balance</th>
                    <th>Progress</th>
                    <th>Actions</th>
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
                        <td>
                            <div class="client-cell">
                                <div class="client-avatar">
                                    {{ strtoupper($initials) }}
                                </div>
                                <div>
                                    {{ $project->client->first_name }} {{ $project->client->last_name }}
                                </div>
                            </div>
                        </td>

                        <td class="project-name">{{ $project->project_name }}</td>
                        <td style="font-size:13px; color:#6b7280;">
                            {{ $project->location ?? 'No location set' }}
                        </td>
                        <td class="amount">TSh {{ number_format($project->contract_amount, 2) }}</td>
                        <td>TSh {{ number_format($allocated, 2) }}</td>
                        <td class="spent">TSh {{ number_format($spent, 2) }}</td>
                        <td class="balance">TSh {{ number_format($balance, 2) }}</td>

                        <td style="width:180px;">
                            {{ number_format($progress, 1) }}%
                            <div class="progress-bar">
                                <div class="progress-fill {{ $barColor }}" style="width: {{ $progress }}%"></div>
                            </div>
                        </td>

                        <td class="actions">
                            <a href="{{ route('projects.expenses', $project) }}">View</a>

                            <a href="#" class="delete-link"
                                onclick="openDeleteModal({{ $project->id }}, '{{ addslashes($project->project_name) }}')">
                                Delete
                            </a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="9" class="empty-state">
                            No projects registered yet
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>
    </div>

    <!-- DELETE MODAL -->
    <div id="deleteModal"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
            justify-content:center; align-items:center; z-index:9999;">

        <div style="background:white; width:380px; border-radius:12px; padding:22px; text-align:center;">

            <h3 style="font-size:18px; font-weight:700; margin-bottom:10px;">
                Delete Project
            </h3>

            <p style="font-size:14px; color:#6b7280; margin-bottom:20px;">
                Are you sure you want to delete <span id="projectName"></span>?
            </p>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <div style="display:flex; gap:10px; justify-content:center;">
                    <button type="button" onclick="closeDeleteModal()"
                        style="padding:8px 14px; border-radius:8px; border:1px solid #d1d5db; background:white;">
                        No
                    </button>

                    <button type="submit"
                        style="padding:8px 14px; border-radius:8px; border:none; background:#dc2626; color:white;">
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
