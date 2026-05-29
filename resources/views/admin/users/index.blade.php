@extends('layouts.app')

@section('title', 'Users')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Home</a>
    <a href="{{ route('users.create') }}">+ Add User</a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .card {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 5px;
        }

        .desc {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #1f3a5f;
        }

        th {
            text-align: left;
            font-size: 13px;
            color: white;
            padding: 14px;
            font-weight: 600;
        }

        td {
            padding: 14px;
            font-size: 14px;
            border-bottom: 1px solid #f1f1f1;
            vertical-align: middle;
            color: #374151;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            background: #e5e7eb;
            border: 2px solid #f3f4f6;
        }

        .avatar-placeholder {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #dbeafe;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }

        .user-name {
            font-weight: 600;
            color: #111827;
        }

        .user-email {
            font-size: 12px;
            color: #6b7280;
            margin-top: 3px;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .admin {
            background: #fee2e2;
            color: #991b1b;
        }

        .director {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .finance {
            background: #dcfce7;
            color: #166534;
        }

        .technical {
            background: #fef3c7;
            color: #92400e;
        }

        .status {
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .active {
            background: #dcfce7;
            color: #166534;
        }

        .inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-action {
            border: none;
            padding: 7px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: .2s;
        }

        .btn-edit {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-edit:hover {
            background: #bfdbfe;
        }

        .btn-delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-delete:hover {
            background: #fecaca;
        }

        .btn-toggle {
            background: #f3f4f6;
            color: #111827;
        }

        .btn-toggle:hover {
            background: #e5e7eb;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
            font-size: 14px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        @media(max-width: 768px) {

            .card {
                padding: 18px;
            }

            table {
                min-width: 900px;
            }

        }
    </style>

    <div class="card">

        <div class="title">Users Management</div>

        <div class="desc">
            Control access levels, passwords, profile records and staff permissions.
            Tiny digital kingdom management. Humanity really made bureaucracy scalable.
        </div>

        <div class="table-responsive">

            <table>

                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)
                        <tr>

                            {{-- PHOTO --}}
                            <td>

                                @if ($user->passport_photo)
                                    <img src="{{ asset($user->passport_photo) }}" class="avatar">
                                @else
                                    <div class="avatar-placeholder">
                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                    </div>
                                @endif

                            </td>

                            {{-- USER --}}
                            <td>

                                <div class="user-name">
                                    {{ $user->first_name }}
                                    {{ $user->middle_name }}
                                    {{ $user->last_name }}
                                </div>

                                <div class="user-email">
                                    {{ $user->email }}
                                </div>

                            </td>

                            {{-- ROLE --}}
                            <td>

                                <span class="badge {{ $user->role }}">
                                    {{ ucfirst($user->role) }}
                                </span>

                            </td>

                            {{-- PHONE --}}
                            <td>
                                {{ $user->phone ?? '—' }}
                            </td>

                            {{-- STATUS --}}
                            <td>

                                @if ($user->status === 'active')
                                    <span class="status active">
                                        Active
                                    </span>
                                @else
                                    <span class="status inactive">
                                        Inactive
                                    </span>
                                @endif

                            </td>

                            {{-- ACTIONS --}}
                            <td>

                                <div class="actions">

                                    {{-- EDIT --}}
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn-action btn-edit">
                                        Edit
                                    </a>

                                    {{-- ACTIVATE / DEACTIVATE --}}
                                    <form action="{{ route('users.toggle-status', $user->id) }}" method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn-action btn-toggle">

                                            @if ($user->status === 'active')
                                                Deactivate
                                            @else
                                                Activate
                                            @endif

                                        </button>

                                    </form>

                                    {{-- DELETE --}}
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button" class="btn-action btn-delete"
                                            onclick="openDeleteModal({{ $user->id }})">
                                            Delete
                                        </button>

                                        {{-- hidden submit trigger --}}
                                        <button type="submit" id="delete-submit-{{ $user->id }}"
                                            style="display:none;"></button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="empty">
                                No users found. Either the system is empty or everyone escaped accountability.
                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div style="margin-top:20px;">
            {{ $users->links() }}
        </div>

    </div>
    {{-- DELETE MODAL --}}
    <div id="deleteModal"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:9999;">
        <div style="background:white; padding:25px; border-radius:12px; width:380px; text-align:center;">

            <h3 style="margin-bottom:10px;">Confirm Deletion</h3>

            <p style="font-size:13px; color:#6b7280; margin-bottom:20px;">
                This action is permanent. The record will vanish from existence.
            </p>

            <div style="display:flex; gap:10px; justify-content:center;">
                <button type="button" onclick="closeDeleteModal()"
                    style="padding:10px 14px; border:1px solid #d1d5db; background:white; border-radius:8px; cursor:pointer;">
                    Cancel
                </button>

                <button type="button" onclick="confirmDelete()"
                    style="padding:10px 14px; background:#dc2626; color:white; border:none; border-radius:8px; cursor:pointer;">
                    Delete
                </button>
            </div>

        </div>
    </div>
    <script>
        let selectedUserId = null;

        function openDeleteModal(id) {
            selectedUserId = id;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            selectedUserId = null;
            document.getElementById('deleteModal').style.display = 'none';
        }

        function confirmDelete() {
            if (selectedUserId) {
                document.getElementById('delete-submit-' + selectedUserId).click();
            }
        }
    </script>

@endsection
