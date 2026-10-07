@extends('layouts.app')

@section('title', 'Project Expenses')

@section('page-title')
    <span class="contract-heading">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="contract-heading-text">{{ $project->contract_number }}</span>
    </span>
@endsection

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a> |
    <a href="{{ route('projects.index') }}">Projects</a>
@endsection

@section('content')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        /* ---------- Beautified page-title (contract number) ---------- */
        .contract-heading {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 18px 8px 14px;
            border-radius: 999px;
            background: linear-gradient(135deg, #1f3a5f, #16283f);
            box-shadow: 0 6px 16px rgba(31, 58, 95, 0.25);
        }

        .contract-heading svg {
            width: 20px;
            height: 20px;
            color: #C9A84C;
            flex-shrink: 0;
        }

        .contract-heading-text {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.6px;
            background: linear-gradient(90deg, #ffffff, #e9d9a8);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .page-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px 24px 0;
        }

        /* ---------- Overview card ---------- */
        .overview-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 24px 28px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            margin-bottom: 20px;
        }

        .overview-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 12px;
        }

        .project-name {
            font-size: 17px;
            font-weight: 700;
            color: #111827;
        }

        .status-badge {
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            white-space: nowrap;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-completed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-on_hold,
        .status-paused {
            background: #fef9c3;
            color: #854d0e;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-default {
            background: #f3f4f6;
            color: #4b5563;
        }

        .client-row {
            display: flex;
            flex-wrap: wrap;
            gap: 22px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #f0f0f0;
        }

        .client-item {
            font-size: 13px;
            color: #4b5563;
        }

        .client-item strong {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9ca3af;
            font-weight: 700;
            margin-bottom: 2px;
        }

        /* ---------- Live indicator ---------- */
        .live-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            color: #16a34a;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.6);
            animation: pulse-dot 1.8s infinite;
        }

        @keyframes pulse-dot {
            0% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.55);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        /* ---------- Stat cards ---------- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        @media (max-width: 800px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 18px 20px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            transition: background-color 0.4s ease;
        }

        .stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9ca3af;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 800;
            color: #111827;
            transition: color 0.3s ease;
        }

        .stat-value.flash {
            color: #16a34a;
        }

        .stat-value.tzs::before {
            content: "TZS ";
            font-size: 12px;
            font-weight: 700;
            color: #9ca3af;
        }

        .stat-card.allocated .stat-value {
            color: #1f3a5f;
        }

        .stat-card.spent .stat-value {
            color: #b45309;
        }

        .stat-card.balance .stat-value {
            color: #166534;
        }

        .stat-card.balance.low .stat-value {
            color: #b91c1c;
        }

        .progress-track {
            margin-top: 12px;
            height: 6px;
            border-radius: 999px;
            background: #f1f1f4;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #1f3a5f, #C9A84C);
            transition: width 0.6s ease;
        }

        .progress-caption {
            margin-top: 6px;
            font-size: 11px;
            color: #9ca3af;
        }

        /* ---------- Flash messages ---------- */
        .alert {
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ---------- Expense feed header + add-expense control ---------- */
        .feed-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 12px;
            padding: 0 2px;
        }

        .feed-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .add-expense-bar {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .add-expense-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #1f3a5f;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .add-expense-btn:hover {
            background: #16283f;
        }

        .add-expense-btn.disabled {
            background: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
            pointer-events: none;
        }

        .allocation-picker {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .allocation-picker select {
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            max-width: 260px;
        }

        .no-allocation-note {
            font-size: 12px;
            color: #9ca3af;
        }

        .no-allocation-note a {
            color: #1f3a5f;
            font-weight: 600;
        }

        /* ---------- Expense table ---------- */
        .table-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-bottom: 24px;
        }

        table.expense-table {
            width: 100%;
            border-collapse: collapse;
        }

        .expense-table thead {
            background: #1f3a5f;
        }

        .expense-table th {
            color: #C9A84C;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            text-align: left;
            font-weight: 700;
        }

        .expense-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
            color: #374151;
            vertical-align: middle;
        }

        .expense-table tbody tr:last-child td {
            border-bottom: none;
        }

        .expense-table tbody tr:hover td {
            background: #f9fafb;
        }

        .category-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            background: #eef2ff;
            color: #3730a3;
        }

        .amount-cell {
            font-weight: 700;
            color: #b45309;
            white-space: nowrap;
        }

        .receipt-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 6px;
            background: #eef2ff;
            color: #3730a3;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }

        .receipt-link:hover {
            background: #e0e7ff;
        }

        .no-receipt {
            font-size: 12px;
            color: #9ca3af;
        }

        .recorded-by {
            font-size: 12px;
            color: #6b7280;
        }

        .delete-btn {
            background: #fff;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .delete-btn:hover {
            background: #fef2f2;
        }

        .empty-row {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
            font-size: 13px;
        }

        .pagination-wrap {
            margin-bottom: 24px;
        }

        /* ---------- Delete confirmation modal ---------- */
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
        }

        .modal-confirm:hover {
            background: #b91c1c;
        }

        @media (max-width: 760px) {
            .table-card {
                overflow-x: auto;
            }

            .expense-table {
                min-width: 760px;
            }
        }
    </style>

    <div class="page-wrap">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        {{-- Overview: project name, status, client --}}
        <div class="overview-card">
            <div class="overview-top">
                <div>
                    <div class="project-name">{{ $project->project_name }}</div>
                </div>
                <span class="status-badge status-{{ $project->status ?? 'default' }}">
                    {{ str_replace('_', ' ', $project->status ?? 'active') }}
                </span>
            </div>

            <div class="client-row">
                <div class="client-item">
                    <strong>Client</strong>
                    {{ $project->client->full_name ?? '—' }}
                </div>
                <div class="client-item">
                    <strong>Phone</strong>
                    {{ $project->client->phone_number ?? '—' }}
                </div>
                <div class="client-item">
                    <strong>Email</strong>
                    {{ $project->client->email ?? '—' }}
                </div>
                <div class="client-item">
                    <strong>Location</strong>
                    {{ $project->location ?? '—' }}
                </div>
                <div class="client-item">
                    <strong>Contract Amount</strong>
                    TZS {{ number_format($project->contract_amount, 2) }}
                </div>
            </div>
        </div>

        {{-- Live financial stats --}}
        <div class="feed-header" style="margin-bottom:12px;">
            <span class="live-tag"><span class="live-dot"></span> Live figures</span>
        </div>

        <div class="stats-grid">
            <div class="stat-card allocated">
                <div class="stat-label">Total Allocated</div>
                <div class="stat-value tzs" id="stat-allocated">{{ number_format($project->totalAllocated(), 2) }}</div>
            </div>

            <div class="stat-card spent">
                <div class="stat-label">Total Expensed</div>
                <div class="stat-value tzs" id="stat-expenses">{{ number_format($project->totalExpenses(), 2) }}</div>
            </div>

            @php
                $allocated = $project->totalAllocated();
                $spent = $project->totalExpenses();
                $balance = $project->remainingBalance();
                $pct = $allocated > 0 ? min(100, round(($spent / $allocated) * 100, 1)) : 0;
                $isLow = $allocated > 0 && $balance <= $allocated * 0.1;
            @endphp

            <div class="stat-card balance {{ $isLow ? 'low' : '' }}" id="balance-card">
                <div class="stat-label">Remaining Balance</div>
                <div class="stat-value tzs" id="stat-balance">{{ number_format($balance, 2) }}</div>
                <div class="progress-track">
                    <div class="progress-fill" id="progress-fill" style="width: {{ $pct }}%;"></div>
                </div>
                <div class="progress-caption" id="progress-caption">{{ $pct }}% of allocation used</div>
            </div>
        </div>

        {{-- Expense feed: header + add-expense control --}}
        <div class="feed-header">
            <span class="feed-title">Expenses</span>

            @php
                $allocations = $project->allocations;
                $canDelete = in_array(auth()->user()->role, ['finance', 'director', 'admin'], true);
            @endphp

            <div class="add-expense-bar">
                @if ($allocations->isEmpty())
                    <span class="no-allocation-note">
                        No income allocated yet — <a href="{{ route('allocations.create') }}">add an allocation first</a>
                    </span>
                @elseif ($allocations->count() === 1)
                    <a href="{{ route('expenses.create', $allocations->first()->id) }}" class="add-expense-btn">
                        + Add Expense
                    </a>
                @else
                    <div class="allocation-picker">
                        <select id="allocation-select">
                            <option value="">Charge to allocation…</option>
                            @foreach ($allocations as $alloc)
                                @php
                                    $allocRemaining = $alloc->amount - $alloc->expenses->sum('amount');
                                @endphp
                                <option value="{{ route('expenses.create', $alloc->id) }}">
                                    {{ \Carbon\Carbon::parse($alloc->allocation_date)->format('M j, Y') }}
                                    — TZS {{ number_format($allocRemaining, 0) }} remaining
                                </option>
                            @endforeach
                        </select>
                        <button type="button" class="add-expense-btn" id="allocation-go-btn">+ Add Expense</button>
                    </div>
                @endif
            </div>
        </div>

        <div class="table-card">
            <table class="expense-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Receipt</th>
                        <th>Recorded By</th>
                        @if ($canDelete)
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($expense->date)->format('M j, Y') }}</td>
                            <td><span class="category-badge">{{ $expense->category ?? 'Uncategorized' }}</span></td>
                            <td>{{ $expense->description }}</td>
                            <td class="amount-cell">TZS {{ number_format($expense->amount, 2) }}</td>
                            <td>
                                @if ($expense->receipt)
                                    <a href="{{ asset('storage/' . $expense->receipt) }}" target="_blank"
                                        class="receipt-link">
                                        &#128206; View
                                    </a>
                                @else
                                    <span class="no-receipt">No receipt</span>
                                @endif
                            </td>
                            <td class="recorded-by">{{ $expense->user->name ?? '—' }}</td>
                            @if ($canDelete)
                                <td>
                                    <form method="POST" action="{{ route('expenses.destroy', $expense) }}"
                                        class="delete-expense-form" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-btn"
                                            onclick="openDeleteModal('{{ $expense->id }}', '{{ addslashes($expense->description) }}')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $canDelete ? 7 : 6 }}" class="empty-row">No expenses recorded for this project
                                yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $expenses->links() }}
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="modal-icon">&#x26A0;</div>
            <h2>Delete Expense</h2>
            <p>Are you sure you want to delete <strong id="expenseDescription"></strong>? This action cannot be undone.</p>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button type="button" class="modal-confirm" onclick="submitDelete()">Yes, Delete</button>
            </div>
        </div>
    </div>

    <script>
        // ---------- Delete modal ----------
        let activeDeleteForm = null;

        function openDeleteModal(id, description) {
            const forms = document.querySelectorAll('.delete-expense-form');
            activeDeleteForm = Array.from(forms).find(f => f.action.endsWith('/' + id));
            document.getElementById('expenseDescription').innerText = description || 'this expense';
            document.getElementById('deleteModal').classList.add('open');
        }

        function closeDeleteModal() {
            activeDeleteForm = null;
            document.getElementById('deleteModal').classList.remove('open');
        }

        function submitDelete() {
            if (activeDeleteForm) activeDeleteForm.submit();
        }

        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDeleteModal();
        });

        // ---------- Add Expense allocation picker ----------
        const allocationSelect = document.getElementById('allocation-select');
        const allocationGoBtn = document.getElementById('allocation-go-btn');

        if (allocationGoBtn) {
            allocationGoBtn.addEventListener('click', function() {
                if (allocationSelect.value) {
                    window.location.href = allocationSelect.value;
                }
            });
        }

        // ---------- Live-refreshing financial stats ----------
        const allocatedEl = document.getElementById('stat-allocated');
        const expensesEl = document.getElementById('stat-expenses');
        const balanceEl = document.getElementById('stat-balance');
        const balanceCard = document.getElementById('balance-card');
        const progressFill = document.getElementById('progress-fill');
        const progressCaption = document.getElementById('progress-caption');

        function formatMoney(n) {
            return Number(n).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function flash(el) {
            el.classList.add('flash');
            setTimeout(() => el.classList.remove('flash'), 900);
        }

        async function refreshStats() {
            try {
                const res = await fetch(`{{ route('projects.expenses', $project) }}?stats=1`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!res.ok) return;

                const data = await res.json();

                const newAllocated = formatMoney(data.total_allocated);
                const newExpenses = formatMoney(data.total_expenses);
                const newBalance = formatMoney(data.remaining_balance);

                if (allocatedEl.textContent !== newAllocated) {
                    allocatedEl.textContent = newAllocated;
                    flash(allocatedEl);
                }
                if (expensesEl.textContent !== newExpenses) {
                    expensesEl.textContent = newExpenses;
                    flash(expensesEl);
                }
                if (balanceEl.textContent !== newBalance) {
                    balanceEl.textContent = newBalance;
                    flash(balanceEl);
                }

                if (data.total_allocated > 0) {
                    const pct = Math.min(100, Math.round((data.total_expenses / data.total_allocated) * 1000) / 10);
                    progressFill.style.width = pct + '%';
                    progressCaption.textContent = pct + '% of allocation used';

                    if (data.remaining_balance <= data.total_allocated * 0.1) {
                        balanceCard.classList.add('low');
                    } else {
                        balanceCard.classList.remove('low');
                    }
                }
            } catch (e) {
                // Silently ignore — figures simply won't refresh this cycle.
            }
        }

        setInterval(refreshStats, 20000);
    </script>

@endsection
