@extends('layouts.app')

@section('title', 'My Reports')
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
            margin-bottom: 25px;
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
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background-color 0.2s ease;
        }

        .new-btn:hover {
            background-color: #1f3d5a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
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

        tbody tr:hover {
            background-color: #f9fafb;
        }

        .project-label {
            font-size: 12px;
            color: #888;
            margin-bottom: 2px;
        }

        .report-title {
            font-weight: 600;
            color: #111827;
        }

        .ext-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            color: #6b7280;
            border-radius: 4px;
            padding: 2px 7px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 4px;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-financial {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .badge-progress {
            background: #f0fdf4;
            color: #15803d;
        }

        .badge-generated {
            background: #2c5282;
            color: #fff;
        }

        .badge-manual {
            background: #f9fafb;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }

        .badge-company {
            background: #fef3c7;
            color: #92400e;
        }

        .date-cell {
            color: #6b7280;
            font-size: 13px;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            margin-right: 6px;
            transition: background 0.2s ease;
        }

        .btn-preview {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .btn-preview:hover {
            background: #dbeafe;
        }

        .btn-download {
            background: #2c5282;
            color: #fff;
        }

        .btn-download:hover {
            background: #1f3d5a;
        }

        .empty-state {
            text-align: center;
            padding: 45px;
            color: #9ca3af;
            font-size: 14px;
        }

        /* Modal */
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
            background: #fff;
            width: 92%;
            max-width: 900px;
            height: 85vh;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .modal-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            font-size: 20px;
            line-height: 1;
            padding: 4px 6px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .modal-close:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .modal-body {
            flex: 1;
            overflow: hidden;
        }

        .modal-body iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .excel-notice {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            flex-direction: column;
            gap: 12px;
            color: #6b7280;
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .card {
                padding: 25px;
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            tbody tr {
                margin-bottom: 15px;
            }

            td {
                padding-left: 50%;
                position: relative;
            }

            td::before {
                position: absolute;
                left: 14px;
                width: 45%;
                white-space: nowrap;
                font-weight: 600;
            }

            td:nth-of-type(1)::before {
                content: "Project / Title";
            }

            td:nth-of-type(2)::before {
                content: "Type";
            }

            td:nth-of-type(3)::before {
                content: "Source";
            }

            td:nth-of-type(4)::before {
                content: "Date";
            }

            td:nth-of-type(5)::before {
                content: "Actions";
            }
        }
    </style>

    <div class="card">

        <div class="page-header">
            <div>
                <h3>My Reports</h3>
                <p>Reports you have uploaded or generated</p>
            </div>
            <a href="{{ route('reports.create') }}" class="new-btn">+ New Report</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Project / Title</th>
                    <th>Type</th>
                    <th>Source</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reports as $report)
                    @php
                        $ext = $report->file_path ? strtolower(pathinfo($report->file_path, PATHINFO_EXTENSION)) : null;

                        $extLabel = $ext ? strtoupper($ext) : null;

                        $typeClass = match ($report->type) {
                            'Financial Report' => 'badge-financial',
                            'Progress Report' => 'badge-progress',
                            'Company Expense Report' => 'badge-company',
                            default => 'badge-manual',
                        };

                        $sourceClass = $report->source === 'generated' ? 'badge-generated' : 'badge-manual';
                    @endphp

                    <tr>
                        <td>
                            <div class="project-label">
                                {{ $report->project->project_name ?? 'Company' }}
                            </div>
                            <div class="report-title">{{ $report->title }}</div>
                            @if ($extLabel)
                                <span class="ext-pill">{{ $extLabel }}</span>
                            @endif
                        </td>

                        <td>
                            <span class="badge {{ $typeClass }}">{{ $report->type }}</span>
                        </td>

                        <td>
                            <span class="badge {{ $sourceClass }}">{{ ucfirst($report->source) }}</span>
                        </td>

                        <td class="date-cell">
                            {{ $report->created_at->format('d M Y') }}
                        </td>

                        <td>
                            @if ($report->file_path)
                                <a href="#" class="action-btn btn-preview"
                                    onclick="openPreview(
                                        '{{ route('reports.preview', $report) }}',
                                        '{{ addslashes($report->title) }}',
                                        '{{ $ext }}'
                                    )">
                                    &#128065; Preview
                                </a>
                                <a href="{{ route('reports.download', $report) }}" class="action-btn btn-download">
                                    &#8659; Download
                                </a>
                            @else
                                <span style="font-size:12px; color:#9ca3af;">No file</span>
                            @endif
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            No reports uploaded or generated yet
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $reports->links() }}
        </div>

    </div>

    {{-- PREVIEW MODAL --}}
    <div class="modal-overlay" id="previewModal">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title" id="modalTitle"></div>
                <button class="modal-close" onclick="closePreview()" aria-label="Close preview">
                    &#x2715;
                </button>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <script>
        function openPreview(url, title, ext) {
            document.getElementById('modalTitle').innerText = title;
            const body = document.getElementById('modalBody');

            if (ext === 'pdf') {
                body.innerHTML = `<iframe src="${url}"></iframe>`;
            } else {
                body.innerHTML = `
                    <div class="excel-notice">
                        <div style="font-size:42px;">📊</div>
                        <div>Excel files cannot be previewed inline.</div>
                        <a href="${url}" style="color:#2c5282; font-weight:600;">Download to view</a>
                    </div>`;
            }

            document.getElementById('previewModal').classList.add('open');
        }

        function closePreview() {
            document.getElementById('previewModal').classList.remove('open');
            document.getElementById('modalBody').innerHTML = '';
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('previewModal');
            if (e.target === modal) closePreview();
        });
    </script>

@endsection
