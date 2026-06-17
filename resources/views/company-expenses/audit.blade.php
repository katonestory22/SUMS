@extends('layouts.app')
@section('title', 'Expense Audit Log')
@section('sub-nav')
    <a href="{{ route('dashboard') }}">Home</a>
    <a href="{{ route('director.users') }}">Users</a>
    <a href="{{ route('reports.index') }}">View Reports</a>
    <a href="{{ route('company-expenses.audit') }}">Expense Audit</a>
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
            padding: 12px 14px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        td {
            padding: 13px 14px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        tbody tr:hover {
            background: #f9fafb;
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
        }

        .editor-name {
            font-weight: 600;
            color: #111827;
        }

        .edit-date {
            font-size: 11px;
            color: #9ca3af;
        }

        .expense-link {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
            font-size: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            color: #9ca3af;
        }
    </style>

    <div class="page-card">
        <div class="page-header">
            <div class="page-title">Expense Audit Log</div>
            <div class="page-subtitle">All changes made to company expenses by finance staff</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Expense</th>
                    <th>Field Changed</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                    <th>Reason</th>
                    <th>Edited By</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($edits as $edit)
                    <tr>
                        <td>
                            <div style="font-weight:600; color:#111827; font-size:13px;">
                                {{ $edit->expense->title ?? '—' }}
                            </div>
                            <div style="font-size:11px; color:#9ca3af;">
                                {{ $edit->expense->category ?? '' }}
                            </div>
                        </td>
                        <td><span class="field-badge">{{ $edit->field_changed }}</span></td>
                        <td><span class="old-val">{{ $edit->old_value ?: '—' }}</span></td>
                        <td><span class="new-val">{{ $edit->new_value ?: '—' }}</span></td>
                        <td><span class="reason-text">{{ $edit->reason }}</span></td>
                        <td>
                            <span class="editor-name">{{ $edit->editor->name ?? '—' }}</span>
                        </td>
                        <td>
                            <div class="edit-date">{{ $edit->created_at->format('d M Y') }}</div>
                            <div class="edit-date">{{ $edit->created_at->format('H:i') }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">No edits recorded yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:20px;">
            {{ $edits->links() }}
        </div>
    </div>
@endsection
