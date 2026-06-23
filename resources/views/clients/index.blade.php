@extends('layouts.app')

@section('title', 'Clients')
@section('page-title', '')

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
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 35px 40px;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
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
            transition: background-color 0.2s ease;
            white-space: nowrap;
        }

        .new-btn:hover {
            background-color: #1f3d5a;
        }

        /* ── FILTER BAR ── */
        .filter-bar {
            display: grid;
            grid-template-columns: 1fr 180px auto;
            gap: 10px;
            margin-bottom: 14px;
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
        table {
            width: 100%;
            border-collapse: collapse;
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
            border-bottom: 1px solid #eee;
            transition: background 0.2s;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: #f9fafb;
        }

        .client-name {
            font-weight: 600;
            color: #111827;
        }

        .client-email {
            color: #2c5282;
            font-size: 13px;
        }

        .project-badge {
            background: #e6f1fb;
            color: #0c447c;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .project-badge.none {
            background: #f3f4f6;
            color: #9ca3af;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s ease;
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

        /* ── DELETE MODAL ── */
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
            transition: background 0.2s;
            font-family: 'Inter', sans-serif;
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
            transition: background 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .modal-confirm:hover {
            background: #b91c1c;
        }

        @media (max-width: 900px) {
            .card {
                padding: 25px;
            }

            .filter-bar {
                grid-template-columns: 1fr;
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            tbody tr {
                margin-bottom: 15px;
            }

            td {
                padding-left: 50%;
                position: relative;
            }

            td::before {
                position: absolute;
                left: 14px;
                width: 45%;
                white-space: nowrap;
                font-weight: 600;
            }

            td:nth-of-type(1)::before {
                content: "Client";
            }

            td:nth-of-type(2)::before {
                content: "Address";
            }

            td:nth-of-type(3)::before {
                content: "Email";
            }

            td:nth-of-type(4)::before {
                content: "Phone";
            }

            td:nth-of-type(5)::before {
                content: "Projects";
            }

            td:nth-of-type(6)::before {
                content: "Actions";
            }
        }
    </style>

    <div class="card">

        <div class="page-header">
            <div>
                <h3>Registered Clients</h3>
                <p>Manage project owners, stakeholders and client records</p>
            </div>
            <a href="{{ route('clients.create') }}" class="new-btn">+ Add Client</a>
        </div>

        {{-- FILTER BAR --}}
        <form method="GET" action="{{ route('clients.index') }}">
            <div class="filter-bar">

                <div class="filter-group">
                    <span class="filter-label">Search</span>
                    <input type="text" name="search" class="filter-input" placeholder="Name, email or phone…"
                        value="{{ request('search') }}">
                </div>

                <div class="filter-group">
                    <span class="filter-label">Has Projects</span>
                    <select name="has_projects" class="filter-input">
                        <option value="">All Clients</option>
                        <option value="yes" {{ request('has_projects') === 'yes' ? 'selected' : '' }}>
                            With Projects
                        </option>
                        <option value="no" {{ request('has_projects') === 'no' ? 'selected' : '' }}>
                            No Projects
                        </option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="filter-btn btn-search">Filter</button>
                    <a href="{{ route('clients.index') }}" class="filter-btn btn-clear">Clear</a>
                </div>

            </div>
        </form>

        {{-- Results meta --}}
        <div class="results-meta">
            Showing {{ $clients->firstItem() ?? 0 }}–{{ $clients->lastItem() ?? 0 }}
            of {{ $clients->total() }} clients
            @if (request('search') || request('has_projects'))
                — <a href="{{ route('clients.index') }}" style="color:#2563eb;">clear filters</a>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Projects</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clients as $client)
                    <tr>
                        <td class="client-name">{{ $client->full_name }}</td>
                        <td style="color:#6b7280;">{{ $client->address ?? '—' }}</td>
                        <td><span class="client-email">{{ $client->email }}</span></td>
                        <td style="color:#374151;">{{ $client->phone_number ?? '—' }}</td>
                        <td>
                            <span class="project-badge {{ $client->projects_count === 0 ? 'none' : '' }}">
                                {{ $client->projects_count }}
                                {{ Str::plural('Project', $client->projects_count) }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('clients.edit', $client->id) }}" class="action-btn btn-edit">Edit</a>
                                <form id="deleteForm-{{ $client->id }}"
                                    action="{{ route('clients.destroy', $client->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="action-btn btn-delete"
                                        onclick="openDeleteModal({{ $client->id }}, '{{ addslashes($client->full_name) }}')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            @if (request('search') || request('has_projects'))
                                No clients match your filters
                            @else
                                No clients registered yet
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:20px;">
            {{ $clients->appends(request()->query())->links() }}
        </div>

    </div>

    {{-- DELETE MODAL --}}
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="modal-icon">&#x26A0;</div>
            <h2>Delete Client</h2>
            <p>Are you sure you want to delete <strong id="clientName"></strong>? This action cannot be undone.</p>
            <div class="modal-actions">
                <button class="modal-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button class="modal-confirm" onclick="submitDelete()">Delete</button>
            </div>
        </div>
    </div>

    <script>
        let selectedClientId = null;

        function openDeleteModal(id, name) {
            selectedClientId = id;
            document.getElementById('clientName').innerText = name;
            document.getElementById('deleteModal').classList.add('open');
        }

        function closeDeleteModal() {
            selectedClientId = null;
            document.getElementById('deleteModal').classList.remove('open');
        }

        function submitDelete() {
            if (!selectedClientId) return;
            document.getElementById(`deleteForm-${selectedClientId}`).submit();
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('deleteModal');
            if (e.target === modal) closeDeleteModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>

@endsection
