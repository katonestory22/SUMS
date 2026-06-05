@extends('layouts.app')

@section('title', $project->project_name . ' — Overview')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a>
@endsection

@section('content')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .ov-wrap {
            max-width: 1100px;
            margin: auto;
        }

        /* ── CARDS ── */
        .ov-card {
            background: #fff;
            border-radius: 14px;
            padding: 28px 30px;
            margin-bottom: 22px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0f0f0;
        }

        /* ── SECTION TITLE ── */
        .sec-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #6b7280;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sec-title::before {
            content: '';
            display: inline-block;
            width: 3px;
            height: 14px;
            background: #2563eb;
            border-radius: 2px;
        }

        /* ── PROJECT HEADER ── */
        .proj-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .proj-name {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 4px;
        }

        .proj-client {
            font-size: 14px;
            color: #6b7280;
        }

        .proj-progress-pill {
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 13px;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 20px;
            white-space: nowrap;
        }

        /* ── FINANCE STATS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .stat-box {
            background: #f9fafb;
            border-radius: 10px;
            padding: 16px 18px;
            border-left: 3px solid #e5e7eb;
            transition: transform .15s ease;
        }

        .stat-box:hover {
            transform: translateY(-2px);
        }

        .stat-box.blue {
            border-left-color: #2563eb;
        }

        .stat-box.green {
            border-left-color: #16a34a;
        }

        .stat-box.red {
            border-left-color: #dc2626;
        }

        .stat-box.amber {
            border-left-color: #f59e0b;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9ca3af;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 19px;
            font-weight: 700;
            color: #111827;
            line-height: 1.2;
        }

        /* ── LIST ITEMS ── */
        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 14px 0;
            border-bottom: 1px solid #f3f4f6;
            gap: 16px;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item-left {
            flex: 1;
            min-width: 0;
        }

        .list-item-title {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 3px;
        }

        .list-item-meta {
            font-size: 12px;
            color: #9ca3af;
        }

        .list-item-amount {
            font-size: 14px;
            font-weight: 700;
            color: #2563eb;
            white-space: nowrap;
        }

        /* ── RECEIPT ── */
        .receipt-block {
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 14px;
            width: fit-content;
        }

        .receipt-thumb {
            width: 52px;
            height: 52px;
            border-radius: 7px;
            object-fit: cover;
            border: 1px solid #e5e7eb;
            cursor: pointer;
            transition: opacity .15s ease;
        }

        .receipt-thumb:hover {
            opacity: 0.85;
        }

        .receipt-info {
            flex: 1;
        }

        .receipt-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9ca3af;
            margin-bottom: 4px;
        }

        .receipt-download {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 600;
            color: #2563eb;
            text-decoration: none;
            padding: 5px 10px;
            background: #eff6ff;
            border-radius: 6px;
            transition: background .15s ease;
        }

        .receipt-download:hover {
            background: #dbeafe;
        }

        .no-receipt {
            font-size: 12px;
            color: #d1d5db;
            font-style: italic;
            margin-top: 8px;
        }

        /* ── PROGRESS SECTION ── */
        .phase-block {
            padding: 16px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .phase-block:last-child {
            border-bottom: none;
        }

        .phase-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .phase-name {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }

        .phase-pct {
            font-size: 12px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            color: white;
        }

        .pct-green {
            background: #16a34a;
        }

        .pct-amber {
            background: #f59e0b;
        }

        .pct-red {
            background: #dc2626;
        }

        .pct-gray {
            background: #9ca3af;
        }

        .phase-bar-track {
            width: 100%;
            height: 6px;
            background: #f3f4f6;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 14px;
        }

        .phase-bar-fill {
            height: 100%;
            border-radius: 6px;
            transition: width .4s ease;
        }

        /* ── ACTIVITIES ── */
        .activity-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-left: 16px;
            margin-bottom: 10px;
        }

        .activity-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #d1d5db;
            flex-shrink: 0;
        }

        .activity-body {
            flex: 1;
            min-width: 0;
        }

        .activity-name-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .activity-name {
            font-size: 13px;
            color: #374151;
            font-weight: 500;
        }

        .activity-pct {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            white-space: nowrap;
        }

        .activity-bar-track {
            width: 100%;
            height: 4px;
            background: #f3f4f6;
            border-radius: 4px;
            overflow: hidden;
        }

        .activity-bar-fill {
            height: 100%;
            border-radius: 4px;
            background: #2563eb;
        }

        /* ── EVIDENCE GRID ── */
        .evidence-grid {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
            margin-left: 16px;
        }

        .evidence-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            width: 88px;
        }

        .evidence-thumb-wrap {
            width: 88px;
            height: 88px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            cursor: pointer;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .evidence-thumb-wrap:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .evidence-thumb-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .evidence-dl {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 10px;
            font-weight: 600;
            color: #2563eb;
            text-decoration: none;
            padding: 3px 8px;
            background: #eff6ff;
            border-radius: 5px;
            transition: background .15s ease;
            white-space: nowrap;
        }

        .evidence-dl:hover {
            background: #dbeafe;
        }

        .evidence-more {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 88px;
            height: 88px;
            border-radius: 10px;
            background: #f3f4f6;
            border: 1px dashed #d1d5db;
            font-size: 12px;
            font-weight: 600;
            color: #9ca3af;
        }

        .no-evidence {
            font-size: 12px;
            color: #d1d5db;
            font-style: italic;
            margin-left: 16px;
            margin-top: 6px;
        }

        /* ── LIGHTBOX ── */
        .lightbox-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .lightbox-overlay.active {
            display: flex;
        }

        .lightbox-inner {
            position: relative;
            max-width: 90vw;
            max-height: 90vh;
        }

        .lightbox-inner img {
            max-width: 100%;
            max-height: 85vh;
            border-radius: 10px;
            display: block;
        }

        .lightbox-close {
            position: absolute;
            top: -14px;
            right: -14px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: white;
            border: none;
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #111827;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .proj-header {
                flex-direction: column;
                gap: 12px;
            }
        }
    </style>

    <div class="ov-wrap">

        {{-- HEADER --}}
        <div class="ov-card">
            <div class="proj-header">
                <div>
                    <div class="proj-name">{{ $project->project_name }}</div>
                    <div class="proj-client">
                        {{ $project->client->first_name }} {{ $project->client->last_name }}
                        @if ($project->location)
                            &nbsp;·&nbsp; {{ $project->location }}
                        @endif
                    </div>
                </div>
                <div class="proj-progress-pill">
                    {{ $project->progress }}% Complete
                </div>
            </div>
        </div>

        {{-- FINANCE --}}
        <div class="ov-card">
            <div class="sec-title">Finance Overview</div>
            <div class="stats-grid">
                <div class="stat-box blue">
                    <div class="stat-label">Contract</div>
                    <div class="stat-value">TSh {{ number_format($project->contract_amount, 0) }}</div>
                </div>
                <div class="stat-box amber">
                    <div class="stat-label">Allocated</div>
                    <div class="stat-value">TSh {{ number_format($project->totalAllocated(), 0) }}</div>
                </div>
                <div class="stat-box red">
                    <div class="stat-label">Spent</div>
                    <div class="stat-value">TSh {{ number_format($project->totalExpenses(), 0) }}</div>
                </div>
                <div class="stat-box green">
                    <div class="stat-label">Balance</div>
                    <div class="stat-value">TSh {{ number_format($project->remainingBalance(), 0) }}</div>
                </div>
            </div>
        </div>

        {{-- ALLOCATIONS --}}
        <div class="ov-card">
            <div class="sec-title">Allocations</div>
            @forelse ($project->allocations as $allocation)
                <div class="list-item">
                    <div class="list-item-left">
                        <div class="list-item-title">{{ $allocation->category }}</div>
                        <div class="list-item-meta">
                            {{ \Carbon\Carbon::parse($allocation->allocation_date)->format('d M Y') }}</div>
                    </div>
                    <div class="list-item-amount">TSh {{ number_format($allocation->amount, 0) }}</div>
                </div>
            @empty
                <p style="font-size:13px; color:#9ca3af; font-style:italic;">No allocations recorded.</p>
            @endforelse
        </div>

        {{-- EXPENSES --}}
        <div class="ov-card">
            <div class="sec-title">Expense History</div>
            @forelse ($project->allocations as $allocation)
                @foreach ($allocation->expenses as $expense)
                    <div class="list-item" style="flex-direction:column; align-items:stretch;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                            <div class="list-item-left">
                                <div class="list-item-title">{{ $expense->description }}</div>
                                <div class="list-item-meta">
                                    {{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}
                                    @if ($expense->category)
                                        &nbsp;·&nbsp;
                                        <span
                                            style="background:#f3f4f6; padding:1px 7px; border-radius:10px; font-size:11px;">
                                            {{ $expense->category }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="list-item-amount">TSh {{ number_format($expense->amount, 0) }}</div>
                        </div>

                        @if ($expense->receipt)
                            @php
                                $receiptFile = basename($expense->receipt);
                                $receiptExt = strtolower(pathinfo($receiptFile, PATHINFO_EXTENSION));
                                $receiptUrl = asset('storage/receipts/' . $receiptFile);
                                $downloadUrl = route('file.download', ['type' => 'receipts', 'file' => $receiptFile]);
                            @endphp

                            <div class="receipt-block">

                                {{-- IMAGE receipt --}}
                                @if (in_array($receiptExt, ['jpg', 'jpeg', 'png']))
                                    <img class="receipt-thumb" src="{{ $receiptUrl }}"
                                        onerror="this.style.display='none'" onclick="openLightbox('{{ $receiptUrl }}')"
                                        title="Click to enlarge" />
                                    <div class="receipt-info">
                                        <div class="receipt-label">Receipt</div>
                                        <a class="receipt-download" href="{{ $downloadUrl }}">↓ Download</a>
                                    </div>

                                    {{-- PDF receipt --}}
                                @elseif ($receiptExt === 'pdf')
                                    <div
                                        style="width:52px; height:52px; border-radius:7px; background:#fef2f2;
                        border:1px solid #fecaca; display:flex; align-items:center;
                        justify-content:center; font-size:20px; flex-shrink:0;">
                                        📄
                                    </div>
                                    <div class="receipt-info">
                                        <div class="receipt-label">PDF Receipt</div>
                                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                            <a class="receipt-download" href="{{ $receiptUrl }}" target="_blank"
                                                style="background:#fef2f2; color:#dc2626;">
                                                👁 Preview
                                            </a>
                                            <a class="receipt-download" href="{{ $downloadUrl }}">↓ Download</a>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @else
                            <div class="no-receipt">No receipt uploaded</div>
                        @endif
                    </div>
                @endforeach
            @empty
                <p style="font-size:13px; color:#9ca3af; font-style:italic;">No expenses recorded.</p>
            @endforelse
        </div>

        {{-- PROGRESS --}}
        <div class="ov-card">
            <div class="sec-title">Project Progress</div>

            @forelse ($project->phases as $phase)
                @php
                    $phasePct = round($phase->progress());
                    $phaseColor = $phasePct >= 70 ? 'pct-green' : ($phasePct >= 30 ? 'pct-amber' : 'pct-gray');
                    $barColor = $phasePct >= 70 ? '#16a34a' : ($phasePct >= 30 ? '#f59e0b' : '#9ca3af');
                @endphp

                <div class="phase-block">

                    {{-- Phase header --}}
                    <div class="phase-header">
                        <div class="phase-name">{{ $phase->name }}</div>
                        <span class="phase-pct {{ $phaseColor }}">{{ $phasePct }}%</span>
                    </div>

                    {{-- Phase progress bar --}}
                    <div class="phase-bar-track">
                        <div class="phase-bar-fill" style="width:{{ $phasePct }}%; background:{{ $barColor }};">
                        </div>
                    </div>

                    {{-- Activities --}}
                    @foreach ($phase->activities as $activity)
                        <div class="activity-row">
                            <div class="activity-dot"></div>
                            <div class="activity-body">
                                <div class="activity-name-row">
                                    <span class="activity-name">{{ $activity->name }}</span>
                                    <span class="activity-pct">{{ $activity->current_progress }}%</span>
                                </div>
                                <div class="activity-bar-track">
                                    <div class="activity-bar-fill" style="width:{{ $activity->current_progress }}%;"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Evidence --}}
                        @if ($activity->evidences->count())
                            <div class="evidence-grid">
                                @foreach ($activity->evidences->take(3) as $evidence)
                                    @php $evidenceFile = basename($evidence->file_path); @endphp
                                    <div class="evidence-card">
                                        <div class="evidence-thumb-wrap"
                                            onclick="openLightbox('{{ asset('storage/activity-evidence/' . $evidenceFile) }}')">
                                            <img src="{{ asset('storage/activity-evidence/' . $evidenceFile) }}"
                                                onerror="this.parentElement.style.background='#f3f4f6'" />
                                        </div>
                                        <a class="evidence-dl"
                                            href="{{ route('file.download', ['type' => 'activity-evidence', 'file' => $evidenceFile]) }}">
                                            ↓ Download
                                        </a>
                                    </div>
                                @endforeach

                                @if ($activity->evidences->count() > 3)
                                    <div class="evidence-more">
                                        +{{ $activity->evidences->count() - 3 }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="no-evidence">No evidence uploaded</div>
                        @endif
                    @endforeach

                </div>
            @empty
                <p style="font-size:13px; color:#9ca3af; font-style:italic;">No phases added yet.</p>
            @endforelse
        </div>

    </div>

    {{-- LIGHTBOX --}}
    <div class="lightbox-overlay" id="lightbox" onclick="closeLightbox(event)">
        <div class="lightbox-inner">
            <button class="lightbox-close" onclick="closeLightbox()">✕</button>
            <img id="lightboxImg" src="" alt="Preview" />
        </div>
    </div>

    <script>
        function openLightbox(src) {
            document.getElementById('lightboxImg').src = src;
            document.getElementById('lightbox').classList.add('active');
        }

        function closeLightbox(e) {
            if (!e || e.target === document.getElementById('lightbox') || e.target.classList.contains('lightbox-close')) {
                document.getElementById('lightbox').classList.remove('active');
                document.getElementById('lightboxImg').src = '';
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLightbox();
        });
    </script>

@endsection
