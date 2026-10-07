@extends('layouts.app')

@section('title', 'Income Details')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('allocations.index') }}">Back to Income</a>
@endsection

@section('content')

    @php
        $pctLeft = $allocation->amount > 0 ? max(min(($remaining / $allocation->amount) * 100, 100), 0) : 0;
        $pctSpent = 100 - $pctLeft;

        if ($pctLeft > 50) {
            $statusClass = 'status-green';
            $statusText = 'Healthy';
        } elseif ($pctLeft > 20) {
            $statusClass = 'status-orange';
            $statusText = 'Warning';
        } else {
            $statusClass = 'status-red';
            $statusText = 'Critical';
        }
    @endphp

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .page {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        /* ---------- Alerts ---------- */
        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 16px;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        /* ---------- Hero ---------- */
        .hero {
            background: linear-gradient(135deg, #1f3a5f, #16283f);
            border-radius: 14px;
            padding: 28px 32px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(31, 58, 95, 0.18);
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 240px;
            height: 240px;
            right: -80px;
            top: -110px;
            border-radius: 50%;
            background: rgba(201, 168, 76, 0.09);
        }

        .hero-top {
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
            z-index: 1;
        }

        .hero-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: rgba(201, 168, 76, 0.14);
            border: 1px solid rgba(201, 168, 76, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #C9A84C;
            flex-shrink: 0;
        }

        .hero-icon svg {
            width: 25px;
            height: 25px;
        }

        .hero-text h1 {
            margin: 0;
            color: #fff;
            font-size: 21px;
            font-weight: 700;
            letter-spacing: -0.2px;
            text-transform: uppercase;
        }

        .hero-text p {
            margin: 5px 0 0;
            color: rgba(255, 255, 255, 0.68);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .hero-actions {
            margin-left: auto;
            display: flex;
            gap: 8px;
        }

        .hero-btn {
            padding: 9px 15px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: background .2s ease;
        }

        .hero-btn-gold {
            background: #C9A84C;
            color: #16283f;
        }

        .hero-btn-gold:hover {
            background: #b8953d;
        }

        .hero-btn-ghost {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .hero-btn-ghost:hover {
            background: rgba(255, 255, 255, 0.18);
        }

        /* ---------- Summary ---------- */
        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin: 18px 0;
        }

        .stat {
            background: #fff;
            border: 1px solid #e7ebf0;
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
        }

        .stat::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
        }

        .stat.s-blue::before {
            background: #2c5282;
        }

        .stat.s-red::before {
            background: #dc2626;
        }

        .stat.s-green::before {
            background: #16a34a;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 700;
            margin-top: 6px;
            letter-spacing: -0.3px;
        }

        .c-blue {
            color: #2c5282;
        }

        .c-red {
            color: #dc2626;
        }

        .c-green {
            color: #16a34a;
        }

        /* ---------- Progress ---------- */
        .progress-card {
            background: #fff;
            border: 1px solid #e7ebf0;
            border-radius: 12px;
            padding: 18px 22px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
            margin-bottom: 18px;
        }

        .progress-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
        }

        .progress-bar {
            height: 10px;
            background: #eef0f3;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #dc2626, #f59e0b);
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

        /* ---------- Sections ---------- */
        .section {
            background: #fff;
            border: 1px solid #e7ebf0;
            border-radius: 14px;
            padding: 24px 26px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
            margin-bottom: 18px;
        }

        .section-head {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #edf0f3;
        }

        .section-number {
            width: 27px;
            height: 27px;
            border-radius: 8px;
            background: #eef3f8;
            color: #1f3a5f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
        }

        .section-title {
            color: #1f3a5f;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .65px;
        }

        .section-count {
            margin-left: auto;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            background: #f3f4f6;
            padding: 3px 10px;
            border-radius: 999px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead th {
            background: #f8fafc;
            color: #6b7280;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 11px 14px;
            text-align: left;
            border-bottom: 1px solid #e7ebf0;
            white-space: nowrap;
        }

        thead th:first-child {
            border-radius: 8px 0 0 0;
        }

        thead th:last-child {
            border-radius: 0 8px 0 0;
        }

        tbody td {
            padding: 13px 14px;
            border-bottom: 1px solid #f0f2f5;
            color: #374151;
            vertical-align: middle;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #fafbfc;
        }

        .date-cell {
            font-size: 12px;
            color: #6b7280;
            white-space: nowrap;
        }

        .pill {
            display: inline-block;
            padding: 4px 11px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .pill-blue {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .pill-red {
            background: #fef2f2;
            color: #dc2626;
        }

        .tag {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 6px;
            background: #f3f4f6;
            color: #374151;
            font-size: 11px;
            font-weight: 600;
        }

        .by-cell {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #1f3a5f;
            color: #C9A84C;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .muted {
            color: #9ca3af;
        }

        .link-btn {
            font-size: 12px;
            font-weight: 600;
            color: #1d4ed8;
            text-decoration: none;
        }

        .link-btn:hover {
            text-decoration: underline;
        }

        .btn-delete {
            background: #fef2f2;
            color: #dc2626;
            border: none;
            padding: 6px 11px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background .2s ease;
        }

        .btn-delete:hover {
            background: #fee2e2;
        }

        .empty {
            text-align: center;
            padding: 34px 10px;
            color: #9ca3af;
            font-size: 13px;
        }

        /* ---------- Modal ---------- */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            padding: 16px;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-box {
            width: 380px;
            max-width: 100%;
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
            line-height: 1.5;
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

        /* ---------- Responsive ---------- */
        @media (max-width: 760px) {
            .page {
                padding: 14px;
            }

            .hero {
                padding: 22px 20px;
            }

            .hero-top {
                flex-wrap: wrap;
            }

            .hero-actions {
                margin-left: 0;
                width: 100%;
            }

            .hero-btn {
                flex: 1;
                text-align: center;
            }

            .hero-text h1 {
                font-size: 18px;
            }

            .summary {
                grid-template-columns: 1fr;
            }

            .section {
                padding: 20px 16px;
            }
        }
    </style>

    <div class="page">

        @if (session('success'))
            <div class="alert alert-success">&#10003; {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">&#9888; {{ session('error') }}</div>
        @endif

        {{-- HERO --}}
        <div class="hero">
            <div class="hero-top">

                <div class="hero-text">
                    <h1>{{ $allocation->project->project_name }}</h1>
                    <p>{{ $allocation->project->client->first_name }} {{ $allocation->project->client->last_name }}</p>
                </div>

                <div class="hero-actions">
                    <a href="{{ route('expenses.create', $allocation->id) }}" class="hero-btn hero-btn-gold">+ Expense</a>
                    <a href="{{ route('allocations.index') }}" class="hero-btn hero-btn-ghost">Back</a>
                </div>
            </div>
        </div>

        {{-- SUMMARY --}}
        <div class="summary">
            <div class="stat s-blue">
                <div class="stat-label">Total Allocated</div>
                <div class="stat-value c-blue">
                    TSh {{ number_format($totalAllocated, 0) }}
                </div>
            </div>

            <div class="stat s-red">
                <div class="stat-label">Spent</div>
                <div class="stat-value c-red">
                    TSh {{ number_format($totalExpenses, 0) }}
                </div>
            </div>

            <div class="stat s-green">
                <div class="stat-label">Remaining</div>
                <div class="stat-value c-green">
                    TSh {{ number_format($remaining, 0) }}
                </div>
            </div>
        </div>

        {{-- PROGRESS --}}
        <div class="progress-card">
            <div class="progress-head">
                <span>Budget used: {{ number_format($pctSpent, 0) }}%</span>
                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $pctSpent }}%;"></div>
            </div>
        </div>

        {{-- INCOME HISTORY --}}
        <div class="section">
            <div class="section-head">
                <span class="section-number">01</span>
                <span class="section-title">Income History</span>
                <span class="section-count">{{ $allocation->topups->count() }}
                    {{ $allocation->topups->count() === 1 ? 'entry' : 'entries' }}</span>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Notes</th>
                            <th>Recorded by</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($allocation->topups as $topup)
                            <tr>
                                <td class="date-cell">
                                    {{ \Carbon\Carbon::parse($topup->received_date)->format('d M Y') }}</td>
                                <td>
                                    <span class="pill {{ $topup->amount < 0 ? 'pill-red' : 'pill-blue' }}">
                                        {{ $topup->amount < 0 ? '−' : '+' }} TSh
                                        {{ number_format(abs($topup->amount), 0) }}
                                    </span>
                                </td>
                                <td>{{ $topup->notes ?: '—' }}</td>
                                <td>
                                    @if ($topup->user)
                                        <div class="by-cell">
                                            <span class="avatar">{{ strtoupper(substr($topup->user->name, 0, 1)) }}</span>
                                            {{ $topup->user->name }}
                                        </div>
                                    @else
                                        <span class="muted">—</span>
                                    @endif
                                </td>
                                <td style="text-align:right;">
                                    <button type="button" class="btn-delete"
                                        onclick="openTopupModal('{{ route('allocations.topups.destroy', $topup) }}', '{{ number_format($topup->amount, 0) }}')">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty">No income history yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- EXPENSES --}}
        <div class="section">
            <div class="section-head">
                <span class="section-number">02</span>
                <span class="section-title">Expenses</span>
                <span class="section-count">{{ $allocation->expenses->count() }}
                    {{ $allocation->expenses->count() === 1 ? 'entry' : 'entries' }}</span>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($allocation->expenses as $expense)
                            <tr>
                                <td class="date-cell">{{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</td>
                                <td><span class="tag">{{ $expense->category }}</span></td>
                                <td>{{ $expense->description }}</td>
                                <td><span class="pill pill-red">TSh {{ number_format($expense->amount, 0) }}</span></td>
                                <td>
                                    @if ($expense->receipt)
                                        <a class="link-btn"
                                            href="{{ route('file.download', ['type' => 'receipts', 'file' => basename($expense->receipt)]) }}">Download</a>
                                    @else
                                        <span class="muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty">No expenses recorded yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- DELETE INCOME MODAL --}}
    <div class="modal-overlay" id="topupModal">
        <div class="modal-box">
            <div class="modal-icon">&#x26A0;</div>
            <h2>Delete Income Entry</h2>
            <p>This will reduce the total income by <strong>TSh <span id="topupAmount"></span></strong>.
                This cannot be undone.</p>
            <form id="topupForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-actions">
                    <button type="button" class="modal-cancel" onclick="closeTopupModal()">Cancel</button>
                    <button type="submit" class="modal-confirm">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openTopupModal(url, amount) {
            document.getElementById('topupForm').action = url;
            document.getElementById('topupAmount').innerText = amount;
            document.getElementById('topupModal').classList.add('open');
        }

        function closeTopupModal() {
            document.getElementById('topupModal').classList.remove('open');
        }

        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('topupModal')) closeTopupModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeTopupModal();
        });
    </script>

@endsection
