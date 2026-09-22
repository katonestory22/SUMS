@extends('layouts.app')

@section('title', 'Project Expenses')

@section('page-title')
    <span class="contract-heading">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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

        .status-active { background: #dcfce7; color: #166534; }
        .status-completed { background: #dbeafe; color: #1e40af; }
        .status-on_hold, .status-paused { background: #fef9c3; color: #854d0e; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .status-default { background: #f3f4f6; color: #4b5563; }

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
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.55); }
            70% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        /* ---------- Stat cards ---------- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        @media (max-width: 800px) {
            .stats-grid { grid-template-columns: 1fr; }
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

        .stat-card.allocated .stat-value { color: #1f3a5f; }
        .stat-card.spent .stat-value { color: #b45309; }
        .stat-card.balance .stat-value { color: #166534; }
        .stat-card.balance.low .stat-value { color: #b91c1c; }

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

        /* ---------- Expense feed ---------- */
        .feed-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding: 0 2px;
        }

        .feed-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .expense-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            padding: 0 0 24px;
        }

        @media (max-width: 768px) {
            .expense-grid {
                grid-template-columns: 1fr;
            }
        }

        .expense-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 18px 18px 16px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            transition: all .18s ease;
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        .expense-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.08);
        }

        .amount {
            font-size: 18px;
            font-weight: 700;
            color: #2563eb;
            letter-spacing: 0.2px;
        }

        .meta {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .desc {
            margin-top: 10px;
            font-size: 14px;
            color: #111827;
            line-height: 1.5;
        }

        .receipt {
            margin-top: 12px;
            font-size: 12px;
        }

        .receipt a {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 8px;
            background: #eef2ff;
            color: #3730a3;
            text-decoration: none;
            font-weight: 600;
            transition: 0.15s ease;
        }

        .receipt a:hover {
            background: #e0e7ff;
        }

        .empty-receipt {
            color: #9ca3af;
            font-size: 12px;
            padding: 6px 0;
        }

        .loader {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 13px;
        }

        .expense-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .expense-index {
            font-size: 12px;
            font-weight: 700;
            color: #9ca3af;
            letter-spacing: 0.5px;
        }
    </style>

    <div class="page-wrap">

        {{-- Overview: contract, client, status --}}
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
                $isLow = $allocated > 0 && $balance <= ($allocated * 0.1);
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

        {{-- Expense feed --}}
        <div class="feed-header">
            <span class="feed-title">Expenses</span>
        </div>

        <div class="expense-grid" id="expenseGrid">
            @include('projects.partials.expense_cards')
        </div>

        <div class="loader" id="loader">Scroll to load more…</div>
    </div>

    <script>
        // ---------- Infinite scroll (unchanged behaviour) ----------
        let page = 2;
        let loading = false;
        let hasMore = true;

        window.addEventListener('scroll', async () => {
            if (loading || !hasMore) return;

            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 200) {
                loading = true;

                const res = await fetch(`?page=${page}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const html = await res.text();

                if (!html.trim()) {
                    hasMore = false;
                    document.getElementById('loader').innerText = "No more expenses";
                    return;
                }

                document.getElementById('expenseGrid')
                    .insertAdjacentHTML('beforeend', html);

                page++;
                loading = false;
            }
        });

        // ---------- Live-refreshing financial stats ----------
        const allocatedEl = document.getElementById('stat-allocated');
        const expensesEl = document.getElementById('stat-expenses');
        const balanceEl = document.getElementById('stat-balance');
        const balanceCard = document.getElementById('balance-card');
        const progressFill = document.getElementById('progress-fill');
        const progressCaption = document.getElementById('progress-caption');

        function formatMoney(n) {
            return Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function flash(el) {
            el.classList.add('flash');
            setTimeout(() => el.classList.remove('flash'), 900);
        }

        async function refreshStats() {
            try {
                const res = await fetch(`{{ route('projects.expenses', $project) }}?stats=1`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
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
