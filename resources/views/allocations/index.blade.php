@extends('layouts.app')

@section('title', 'Project Income')
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
            grid-template-columns: 1fr 140px 150px 150px auto;
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
            font-size: 13px;
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

        .proj-name {
            font-weight: 600;
            color: #111827;
        }

        .proj-client {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .amount-cell {
            font-weight: 600;
            color: #2c5282;
        }

        .spent-cell {
            font-weight: 600;
            color: #dc2626;
        }

        .remain-cell {
            font-weight: 600;
            color: #16a34a;
        }

        .status-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            display: inline-block;
        }

        .status-green {
            background: #16a34a;
        }

        .status-orange {
            background: #f59e0b;
        }

        .status-red {
            background: #dc2626;
        }

        .date-cell {
            font-size: 12px;
            color: #6b7280;
        }

        .actions {
            display: flex;
            gap: 6px;
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

        .btn-expense {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .btn-expense:hover {
            background: #dbeafe;
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

        @media(max-width: 900px) {
            .card {
                padding: 25px;
            }

            .filter-bar {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="card">

        <div class="page-header">
            <div>
                <h3>Income</h3>
                <p>Budget allocations recorded per project</p>
            </div>
            <a href="{{ route('allocations.create') }}" class="new-btn">+ New Income Received</a>
        </div>

        {{-- FILTER BAR --}}
        <form method="GET" action="{{ route('allocations.index') }}">
            <div class="filter-bar">

                <div class="filter-group">
                    <span class="filter-label">Search</span>
                    <input type="text" name="search" class="filter-input" placeholder="Project name or client…"
                        value="{{ request('search') }}">
                </div>

                <div class="filter-group">
                    <span class="filter-label">Status</span>
                    <select name="status" class="filter-input">
                        <option value="">All</option>
                        <option value="healthy" {{ request('status') === 'healthy' ? 'selected' : '' }}>Healthy</option>
                        <option value="warning" {{ request('status') === 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="critical" {{ request('status') === 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>

                <div class="filter-group">
                    <span class="filter-label">From</span>
                    <input type="date" name="date_from" class="filter-input" value="{{ request('date_from') }}">
                </div>

                <div class="filter-group">
                    <span class="filter-label">To</span>
                    <input type="date" name="date_to" class="filter-input" value="{{ request('date_to') }}">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="filter-btn btn-search">Filter</button>
                    <a href="{{ route('allocations.index') }}" class="filter-btn btn-clear">Clear</a>
                </div>

            </div>
        </form>

        {{-- Results meta --}}
        <div class="results-meta">
            Showing {{ $allocations->firstItem() ?? 0 }}–{{ $allocations->lastItem() ?? 0 }}
            of {{ $allocations->total() }} allocations
            @if (request('search') || request('status') || request('date_from'))
                — <a href="{{ route('allocations.index') }}" style="color:#2563eb;">clear filters</a>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Date</th>
                    <th>Income Received</th>
                    <th>Spent</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allocations as $allocation)
                    @php
                        $spent = $allocation->expenses_sum_amount ?? 0;
                        $remaining = $allocation->amount - $spent;
                        $pct = $allocation->amount > 0 ? ($remaining / $allocation->amount) * 100 : 0;

                        if ($pct > 50) {
                            $statusClass = 'status-green';
                            $statusText = 'Healthy';
                        } elseif ($pct > 20) {
                            $statusClass = 'status-orange';
                            $statusText = 'Warning';
                        } else {
                            $statusClass = 'status-red';
                            $statusText = 'Critical';
                        }
                    @endphp

                    <tr>
                        <td>
                            <div class="proj-name">{{ $allocation->project->project_name }}</div>
                            <div class="proj-client">
                                {{ $allocation->project->client->first_name }}
                                {{ $allocation->project->client->last_name }}
                            </div>
                        </td>

                        <td class="date-cell">
                            {{ \Carbon\Carbon::parse($allocation->allocation_date)->format('d M Y') }}
                        </td>

                        <td class="amount-cell">TSh {{ number_format($allocation->amount, 0) }}</td>
                        <td class="spent-cell">TSh {{ number_format($spent, 0) }}</td>
                        <td class="remain-cell">TSh {{ number_format($remaining, 0) }}</td>

                        <td>
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </td>

                        <td>
                            <div class="actions">
                                <a href="{{ route('expenses.create', $allocation->id) }}" class="action-btn btn-expense">+
                                    Expense</a>

                                <a href="{{ route('allocations.edit', $allocation) }}" class="action-btn btn-edit">Edit</a>

                                <button type="button" class="action-btn btn-delete"
                                    onclick="openDeleteModal(
                                    {{ $allocation->id }},
                                    '{{ addslashes($allocation->project->project_name) }}'
                                )">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            @if (request('search') || request('status') || request('date_from'))
                                No allocations match your filters
                            @else
                                No allocations recorded yet
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:20px;">
            {{ $allocations->appends(request()->query())->links() }}
        </div>

    </div>

    {{-- DELETE MODAL --}}
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="modal-icon">&#x26A0;</div>
            <h2>Delete Allocation</h2>
            <p>Are you sure you want to delete the allocation for
                <strong id="allocationProject"></strong>?
                This cannot be undone.
            </p>
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
        function openDeleteModal(id, project) {
            document.getElementById('allocationProject').innerText = project;
            document.getElementById('deleteForm').action = '/allocations/' + id;
            document.getElementById('deleteModal').classList.add('open');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('open');
        }

        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>

@endsection
