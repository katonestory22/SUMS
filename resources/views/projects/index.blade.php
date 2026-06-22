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
            margin-bottom: 20px;
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

        /* ── FILTER BAR ── */
        .filter-bar {
            display: grid;
            grid-template-columns: 1fr 160px 160px auto;
            gap: 10px;
            margin-bottom: 16px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-label {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .filter-input {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 13px;
            background: white;
            font-family: 'Inter', sans-serif;
        }

        .filter-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 6px;
            align-items: flex-end;
        }

        .filter-btn {
            padding: 8px 16px;
            border-radius: 7px;
            border: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            white-space: nowrap;
        }

        .btn-search {
            background: #2c5282;
            color: white;
        }

        .btn-search:hover {
            background: #1f3d5a;
        }

        .btn-clear {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            text-decoration: none;
            display: inline-block;
            padding: 8px 14px;
        }

        .btn-clear:hover {
            background: #e5e7eb;
        }

        .results-meta {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 14px;
        }

        /* ── TABLE ── */
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

        .col-client {
            width: 180px;
        }

        .col-project {
            width: 190px;
        }

        .col-location {
            width: 110px;
        }

        .col-money {
            width: 118px;
        }

        .col-progress {
            width: 130px;
        }

        .col-actions {
            width: 120px;
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

        .project-type {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
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

        .col-spent {
            color: #c53030;
        }

        .col-balance {
            color: #2f855a;
        }

        .progress-wrap {
            display: flex;
            flex-direction: column;
            gap: 4px;
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
            height: 7px;
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
            gap: 5px;
            align-items: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s ease;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .btn-view {
            background: #e6f1fb;
            color: #185fa5;
        }

        .btn-view:hover {
            background: #b5d4f4;
        }

        .btn-edit {
            background: #f0fdf4;
            color: #15803d;
        }

        .btn-edit:hover {
            background: #dcfce7;
        }

        .btn-delete {
            background: #fef2f2;
            color: #dc2626;
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

        /* ── EDIT MODAL ── */
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

        .edit-modal-box {
            background: #fff;
            width: 94%;
            max-width: 680px;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.18);
            display: flex;
            flex-direction: column;
            max-height: 90vh;
        }

        .edit-modal-header {
            background: #2c5282;
            color: white;
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .edit-modal-title {
            font-size: 16px;
            font-weight: 700;
        }

        .edit-modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 4px;
            line-height: 1;
            transition: background 0.2s;
        }

        .edit-modal-close:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .edit-modal-body {
            padding: 24px;
            overflow-y: auto;
            flex: 1;
        }

        .project-info-strip {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #4b5563;
        }

        .audit-notice {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12px;
            color: #92400e;
            margin-bottom: 20px;
        }

        .edit-sections {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .edit-section {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .edit-section-header {
            background: #f9fafb;
            padding: 10px 16px;
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            user-select: none;
        }

        .edit-section-header:hover {
            background: #f3f4f6;
        }

        .edit-section-body {
            padding: 16px;
            display: none;
        }

        .edit-section-body.open {
            display: block;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        label {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
        }

        input,
        select,
        textarea {
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 13px;
            background: white;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
            width: 100%;
            box-sizing: border-box;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .reason-group {
            margin-top: 4px;
        }

        .reason-group textarea {
            border-color: #f59e0b;
            resize: vertical;
        }

        .reason-group textarea:focus {
            border-color: #d97706;
            box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.1);
        }

        .edit-modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            flex-shrink: 0;
            background: #fff;
        }

        .btn-cancel-modal {
            padding: 9px 20px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            color: #374151;
            font-family: 'Inter', sans-serif;
            transition: background 0.2s;
        }

        .btn-cancel-modal:hover {
            background: #f3f4f6;
        }

        .btn-save-modal {
            padding: 9px 24px;
            border-radius: 8px;
            border: none;
            background: #2c5282;
            color: white;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.2s;
        }

        .btn-save-modal:hover {
            background: #1f3d5a;
        }

        /* DELETE MODAL */
        .delete-modal-box {
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

        .delete-modal-box h2 {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 8px;
        }

        .delete-modal-box p {
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

        {{-- FILTER BAR --}}
        <form method="GET" action="{{ route('projects.index') }}">
            <div class="filter-bar">

                <div class="filter-group">
                    <span class="filter-label">Search</span>
                    <input type="text" name="search" class="filter-input" placeholder="Project name or contract number…"
                        value="{{ request('search') }}">
                </div>

                <div class="filter-group">
                    <span class="filter-label">Location</span>
                    <select name="location" class="filter-input">
                        <option value="">All Regions</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region }}" {{ request('location') == $region ? 'selected' : '' }}>
                                {{ $region }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <span class="filter-label">Project Type</span>
                    <select name="type" class="filter-input">
                        <option value="">All Types</option>
                        @foreach ($projectTypes as $type)
                            <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                {{ ucfirst($type->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="filter-btn btn-search">Filter</button>
                    <a href="{{ route('projects.index') }}" class="filter-btn btn-clear">Clear</a>
                </div>

            </div>
        </form>

        {{-- Results meta --}}
        <div class="results-meta">
            Showing {{ $projects->firstItem() ?? 0 }}–{{ $projects->lastItem() ?? 0 }}
            of {{ $projects->total() }} projects
            @if (request('search') || request('location') || request('type'))
                — <a href="{{ route('projects.index') }}" style="color:#2563eb;">clear filters</a>
            @endif
        </div>

        <div class="table-scroll">
            <table class="projects-table">
                <thead>
                    <tr>
                        <th class="col-client">Client</th>
                        <th class="col-project">Project</th>
                        <th class="col-location">Location</th>
                        <th class="col-money">Contract</th>
                        <th class="col-money">Spent</th>
                        <th class="col-money">Balance</th>
                        <th class="col-progress">Progress</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        @php
                            $spent = $project->allocations->sum(function ($a) {
                                return $a->expenses->sum('amount');
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
                                <div class="project-type">{{ $project->type->name ?? '—' }}</div>
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
                                        <div class="progress-fill {{ $barColor }}" style="width:{{ $progress }}%">
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="col-actions">
                                <div class="actions">
                                    <a href="{{ route('projects.expenses', $project) }}"
                                        class="action-btn btn-view">View</a>

                                    <button type="button" class="action-btn btn-edit"
                                        onclick="openEditModal(
                                        {{ $project->id }},
                                        '{{ addslashes($project->project_name) }}',
                                        {{ $project->client_id }},
                                        {{ $project->project_type_id }},
                                        '{{ addslashes($project->location ?? '') }}',
                                        '{{ addslashes($project->contract_number) }}',
                                        '{{ $project->contract_amount }}',
                                        '{{ $project->start_date }}',
                                        '{{ $project->end_date ?? '' }}'
                                    )">
                                        Edit
                                    </button>

                                    <button type="button" class="action-btn btn-delete"
                                        onclick="openDeleteModal({{ $project->id }}, '{{ addslashes($project->project_name) }}')">
                                        Del
                                    </button>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                @if (request('search') || request('location') || request('type'))
                                    No projects match your filters
                                @else
                                    No projects registered yet
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">
            {{ $projects->links() }}
        </div>

    </div>

    {{-- ── EDIT MODAL ── --}}
    <div class="modal-overlay" id="editModal">
        <div class="edit-modal-box">

            <div class="edit-modal-header">
                <div class="edit-modal-title" id="editModalTitle">Edit Project</div>
                <button class="edit-modal-close" onclick="closeEditModal()">&#x2715;</button>
            </div>

            <div class="edit-modal-body">

                <div class="project-info-strip" id="editProjectInfo"></div>

                <div class="audit-notice">
                    ⚠️ All changes are logged and visible to the director, including what changed and why.
                </div>

                <form id="editProjectForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="edit-sections">

                        {{-- Basic Info --}}
                        <div class="edit-section">
                            <div class="edit-section-header" onclick="toggleSection(this)">
                                Basic Information <span>&#9660;</span>
                            </div>
                            <div class="edit-section-body">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Project Name</label>
                                        <input type="text" name="project_name" id="edit_project_name">
                                    </div>
                                    <div class="form-group">
                                        <label>Location</label>
                                        <select name="location" id="edit_location">
                                            <option value="">Not set</option>
                                            @foreach ($regions as $region)
                                                <option value="{{ $region }}">{{ $region }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Client & Type --}}
                        <div class="edit-section">
                            <div class="edit-section-header" onclick="toggleSection(this)">
                                Client & Project Type <span>&#9660;</span>
                            </div>
                            <div class="edit-section-body">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Client</label>
                                        <select name="client_id" id="edit_client_id">
                                            @foreach ($projects->pluck('client')->unique('id') as $client)
                                                <option value="{{ $client->id }}">
                                                    {{ $client->first_name }} {{ $client->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Project Type</label>
                                        <select name="project_type_id" id="edit_project_type_id">
                                            @foreach ($projectTypes as $type)
                                                <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contract --}}
                        <div class="edit-section">
                            <div class="edit-section-header" onclick="toggleSection(this)">
                                Contract Details <span>&#9660;</span>
                            </div>
                            <div class="edit-section-body">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Contract Number</label>
                                        <input type="text" name="contract_number" id="edit_contract_number">
                                    </div>
                                    <div class="form-group">
                                        <label>Contract Amount (TSh)</label>
                                        <input type="text" name="contract_amount" id="edit_contract_amount">
                                    </div>
                                </div>
                                <div class="form-row" style="margin-top:14px;">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="date" name="start_date" id="edit_start_date">
                                    </div>
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" name="end_date" id="edit_end_date">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Reason --}}
                        <div class="reason-group">
                            <label style="font-size:13px; font-weight:600; color:#374151;">
                                Reason for Edit *
                            </label>
                            <textarea name="reason" rows="2" placeholder="Explain why you are making this change…" required
                                style="margin-top:6px;"></textarea>
                        </div>

                    </div>
                </form>
            </div>

            <div class="edit-modal-footer">
                <button class="btn-cancel-modal" onclick="closeEditModal()">Cancel</button>
                <button class="btn-save-modal" onclick="submitEditForm()">Save Changes</button>
            </div>

        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div class="modal-overlay" id="deleteModal">
        <div class="delete-modal-box">
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
        function toggleSection(header) {
            const body = header.nextElementSibling;
            const icon = header.querySelector('span');
            const isOpen = body.classList.contains('open');
            body.classList.toggle('open', !isOpen);
            icon.innerHTML = isOpen ? '&#9660;' : '&#9650;';
        }

        function openEditModal(id, name, clientId, typeId, location,
            contractNumber, contractAmount, startDate, endDate) {
            document.getElementById('editModalTitle').innerText = 'Edit — ' + name;
            document.getElementById('editProjectInfo').innerHTML = '<strong>Project:</strong> ' + name;
            document.getElementById('editProjectForm').action = '/projects/' + id;

            document.getElementById('edit_project_name').value = name;
            document.getElementById('edit_location').value = location;
            document.getElementById('edit_client_id').value = clientId;
            document.getElementById('edit_project_type_id').value = typeId;
            document.getElementById('edit_contract_number').value = contractNumber;
            document.getElementById('edit_contract_amount').value = Number(contractAmount).toLocaleString('en');
            document.getElementById('edit_start_date').value = startDate;
            document.getElementById('edit_end_date').value = endDate;

            document.getElementById('editModal').classList.add('open');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('open');
        }

        function submitEditForm() {
            const amtField = document.getElementById('edit_contract_amount');
            amtField.value = amtField.value.replace(/,/g, '');
            document.getElementById('editProjectForm').submit();
        }

        function openDeleteModal(id, name) {
            document.getElementById('projectName').innerText = name;
            document.getElementById('deleteForm').action = '/projects/' + id;
            document.getElementById('deleteModal').classList.add('open');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('open');
        }

        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('editModal')) closeEditModal();
            if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
                closeDeleteModal();
            }
        });
    </script>

@endsection
