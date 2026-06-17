@extends('layouts.app')

@section('title', 'Audit Log')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Home</a>
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
            margin-top: 4px;
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
            letter-spacing: .4px;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .type-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: 700;
        }

        .field-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            background: #f3f4f6;
            color: #374151;
            font-size: 11px;
            font-weight: 700;
        }

        .old-val {
            color: #dc2626;
            font-size: 12px;
            word-break: break-word;
        }

        .new-val {
            color: #16a34a;
            font-size: 12px;
            font-weight: 600;
            word-break: break-word;
        }

        .reason-text {
            color: #6b7280;
            font-size: 12px;
            max-width: 250px;
        }

        .editor-name {
            font-weight: 600;
            color: #111827;
        }

        .edit-date {
            font-size: 12px;
            color: #6b7280;
        }

        .subject {
            font-weight: 600;
            color: #111827;
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            color: #9ca3af;
        }

        @media(max-width: 1000px) {
            .page-card {
                overflow-x: auto;
            }

            table {
                min-width: 1000px;
            }
        }
    </style>

    <div class="page-card">

        <div class="page-header">
            <div class="page-title">
                System Audit Log
            </div>

            <div class="page-subtitle">
                Complete history of changes made across projects, phases, activities and finances.
            </div>
        </div>

        <table>

            <thead>
                <tr>
                    <th>Type</th>
                    <th>Subject</th>
                    <th>Field</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                    <th>Reason</th>
                    <th>Edited By</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>

                @forelse($edits as $edit)
                    <tr>

                        <td>
                            <span class="type-badge">
                                {{ $edit['audit_type'] ?? 'Unknown' }}
                            </span>
                        </td>

                        <td>
                            <div class="subject">
                                {{ $edit['subject'] ?? '—' }}
                            </div>
                        </td>

                        <td>
                            <span class="field-badge">
                                {{ $edit['field'] ?? '—' }}
                            </span>
                        </td>

                        <td>
                            <span class="old-val">
                                {{ $edit['old'] ?: '—' }}
                            </span>
                        </td>

                        <td>
                            <span class="new-val">
                                {{ $edit['new'] ?: '—' }}
                            </span>
                        </td>

                        <td>
                            <div class="reason-text">
                                {{ $edit['reason'] ?: 'No reason provided' }}
                            </div>
                        </td>

                        <td>
                            <span class="editor-name">
                                {{ $edit['editor_name'] ?? '—' }}
                            </span>
                        </td>

                        <td>
                            <div class="edit-date">
                                {{ \Carbon\Carbon::parse($edit['audit_date'])->format('d M Y') }}
                            </div>

                            <div class="edit-date">
                                {{ \Carbon\Carbon::parse($edit['audit_date'])->format('H:i') }}
                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                No audit records found.
                            </div>
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

@endsection
