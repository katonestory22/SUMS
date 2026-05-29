@extends('layouts.app')

@section('title', 'Clients')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('projects.index') }}">Projects</a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .table-card {
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }

        .page-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }

        .add-btn {
            background: #2563eb;
            color: white;
            padding: 11px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: .2s;
        }

        .add-btn:hover {
            background: #1d4ed8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #1f3a5f;
            color: white;
        }

        th {
            padding: 14px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
        }

        td {
            padding: 15px 14px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            color: #374151;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .client-name {
            font-weight: 600;
            color: #111827;
        }

        .project-badge {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .action-btn {
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: .2s;
        }

        .view-btn {
            color: #2563eb;
        }

        .edit-btn {
            color: #059669;
        }

        .delete-btn {
            color: #dc2626;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            font-weight: 600;
            font-size: 13px;
        }

        .empty-state {
            padding: 50px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        @media(max-width: 900px) {

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            table {
                font-size: 13px;
            }

            .actions {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="table-card">

        <div class="page-header">

            <div>
                <div class="page-title">
                    Registered Clients
                </div>

                <div class="page-subtitle">
                    Manage project owners, stakeholders and client records
                </div>
            </div>

            <a href="{{ route('clients.create') }}" class="add-btn">
                + Add Client
            </a>

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

                        <td class="client-name">
                            {{ $client->full_name }}
                        </td>

                        <td>
                            {{ $client->address }}
                        </td>

                        <td>
                            {{ $client->email }}
                        </td>

                        <td>
                            {{ $client->phone_number }}
                        </td>

                        <td>
                            <span class="project-badge">
                                {{ $client->projects_count }} Projects
                            </span>
                        </td>

                        <td>

                            <div class="actions">



                                <a href="{{ route('clients.edit', $client->id) }}" class="action-btn edit-btn">
                                    Edit
                                </a>
                                <form id="deleteForm-{{ $client->id }}"
                                    action="{{ route('clients.destroy', $client->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="delete-btn" style="color:#dc2626;"
                                        onclick="openDeleteModal({{ $client->id }}, '{{ addslashes($client->full_name) }}')">
                                        Delete
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6">

                            <div class="empty-state">
                                No clients registered yet.
                            </div>

                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

        <div style="margin-top:20px;">
            {{ $clients->links() }}
        </div>

    </div>
    <div id="deleteModal"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); backdrop-filter: blur(4px); justify-content:center; align-items:center; z-index:9999;">

        <div
            style="width:380px; background:white; border-radius:14px; padding:25px; text-align:center; box-shadow:0 20px 50px rgba(0,0,0,0.25);">

            <h2 style="font-size:18px; font-weight:700; margin-bottom:10px; color:#111827;">
                Delete Client
            </h2>

            <p style="font-size:14px; color:#6b7280; margin-bottom:20px;">
                Are you sure you want to delete <strong id="clientName"></strong>?
            </p>

            <div style="display:flex; gap:12px; justify-content:center;">

                <button onclick="closeDeleteModal()"
                    style="flex:1; padding:12px; border-radius:10px; border:1px solid #d1d5db; background:#f9fafb; font-weight:600; cursor:pointer;">
                    Cancel
                </button>

                <button onclick="submitDelete()"
                    style="flex:1; padding:12px; border-radius:10px; border:none; background:#dc2626; color:white; font-weight:700; cursor:pointer;">
                    Delete
                </button>

            </div>

        </div>
    </div>
    <script>
        let selectedClientId = null;

        function openDeleteModal(id, name) {
            selectedClientId = id;
            document.getElementById('clientName').innerText = name;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            selectedClientId = null;
            document.getElementById('deleteModal').style.display = 'none';
        }

        function submitDelete() {
            if (!selectedClientId) return;

            document.getElementById(`deleteForm-${selectedClientId}`).submit();
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('deleteModal');
            if (e.target === modal) {
                closeDeleteModal();
            }
        });
    </script>
@endsection
