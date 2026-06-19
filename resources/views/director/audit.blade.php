@extends('layouts.app')

@section('title', 'Audit Log')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Home</a>
    <a href="{{ route('director.users') }}">Users</a>
    <a href="{{ route('reports.index') }}">View Reports</a>
    <a href="{{ route('director.audit') }}">Audit Log</a>
@endsection

@section('content')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .page-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        .page-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* ── TABS ── */
        .tab-bar {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 24px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 0;
        }

        .tab-btn {
            padding: 8px 16px;
            border-radius: 8px 8px 0 0;
            border: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            background: #f9fafb;
            color: #6b7280;
            font-family: 'Inter', sans-serif;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: all 0.2s;
        }

        .tab-btn:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .tab-btn.active {
            background: white;
            color: #1f3a5f;
            border-color: #1f3a5f;
            border-bottom: 2px solid #1f3a5f;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* ── TABLE ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead {
            background: #1f3a5f;
            color: white;
        }

        th {
            padding: 11px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        td {
            padding: 12px 14px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .subject-name {
            font-weight: 600;
            color: #111827;
            font-size: 13px;
        }

        .subject-sub {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .field-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 700;
            background: #f3f4f6;
            color: #374151;
            text-transform: capitalize;
        }

        .old-val {
            color: #dc2626;
            font-size: 12px;
            text-decoration: line-through;
        }

        .new-val {
            color: #16a34a;
            font-size: 12px;
            font-weight: 600;
        }

        .reason-text {
            font-size: 12px;
            color: #6b7280;
            font-style: italic;
            max-width: 180px;
        }

        .editor-name {
            font-weight: 600;
            color: #111827;
            font-size: 13px;
        }

        .edit-date {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            color: #9ca3af;
            font-size: 14px;
        }

        .count-badge {
            display: inline-block;
            background: #1f3a5f;
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 10px;
            margin-left: 5px;
            vertical-align: middle;
        }
    </style>

    <div class="page-card">

        <div class="page-header">
            <div class="page-title">Audit Log</div>
            <div class="page-subtitle">All changes made across projects, expenses, phases, activities and company expenses
            </div>
        </div>

        {{-- TAB BAR --}}
        <div class="tab-bar">
            @php
                $tabs = [
                    'all' => 'All',
                    'Project' => 'Projects',
                    'Expense' => 'Expenses',
                    'Phase' => 'Phases',
                    'Activity' => 'Activities',
                    'Company Expense' => 'Company Expenses',
                ];
            @endphp

            @foreach ($tabs as $key => $label)
                @php
                    $count = $key === 'all' ? $allEdits->count() : $allEdits->where('audit_type', $key)->count();
                @endphp
                <button class="tab-btn {{ $key === 'all' ? 'active' : '' }}" onclick="switchTab('{{ $key }}')">
                    {{ $label }}
                    @if ($count > 0)
                        <span class="count-badge">{{ $count }}</span>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- ALL TAB --}}
        <div class="tab-content active" id="tab-all">
            @include('director.partials.audit-table', ['rows' => $allEdits, 'showType' => true])
        </div>

        {{-- PER TYPE TABS --}}
        @foreach (['Project', 'Expense', 'Phase', 'Activity', 'Company Expense'] as $type)
            <div class="tab-content" id="tab-{{ $type }}">
                @include('director.partials.audit-table', [
                    'rows' => $allEdits->where('audit_type', $type)->values(),
                    'showType' => false,
                ])
            </div>
        @endforeach

    </div>

    <script>
        function switchTab(key) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

            document.querySelector(`[onclick="switchTab('${key}')"]`).classList.add('active');
            document.getElementById('tab-' + key).classList.add('active');
        }
    </script>

@endsection
