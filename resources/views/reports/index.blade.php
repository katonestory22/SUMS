@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Home</a>


@endsection

@section('content')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
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

        .reports-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .reports-table thead {
            background: #1f3a5f;
            color: white;
        }

        .reports-table th {
            padding: 12px 14px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .reports-table td {
            padding: 13px 14px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .reports-table tbody tr:hover {
            background: #f9fafb;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
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

        .badge-manual {
            background: #f9fafb;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }

        .badge-generated {
            background: #111827;
            color: white;
        }

        .badge-company {
            background: #fef3c7;
            color: #92400e;
        }

        .action-btn {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            margin-right: 6px;
        }

        .btn-preview {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .btn-download {
            background: #2563eb;
            color: white;
        }

        .btn-preview:hover {
            background: #dbeafe;
        }

        .btn-download:hover {
            background: #1d4ed8;
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            color: #9ca3af;
            font-size: 14px;
        }

        /* PREVIEW MODAL */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .modal-box {
            background: white;
            width: 90%;
            max-width: 900px;
            height: 85vh;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
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
            font-size: 20px;
            cursor: pointer;
            color: #6b7280;
            line-height: 1;
        }

        .modal-close:hover {
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
    </style>

    <div class="page-header">
        <div>
            <div class="page-title">Reports</div>
            <div class="page-subtitle">All uploaded and generated project reports</div>
        </div>
    </div>

    <table class="reports-table">
        <thead>
            <tr>
                <th>Project</th>
                <th>Title</th>
                <th>Type</th>
                <th>Source</th>
                <th>Uploaded By</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $report)
                @php
                    $ext = $report->file_path ? pathinfo($report->file_path, PATHINFO_EXTENSION) : null;
                @endphp
                <tr>
                    <td>{{ $report->project->project_name ?? 'Company' }}</td>
                    <td style="font-weight:600; color:#111827;">{{ $report->title }}</td>
                    <td>
                        <span
                            class="badge {{ $report->type === 'Financial Report'
                                ? 'badge-financial'
                                : ($report->type === 'Progress Report'
                                    ? 'badge-progress'
                                    : 'badge-company') }}">
                            {{ $report->type }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $report->source === 'generated' ? 'badge-generated' : 'badge-manual' }}">
                            {{ ucfirst($report->source) }}
                        </span>
                    </td>
                    <td style="color:#6b7280;">
                        {{ $report->uploader->name ?? '—' }}
                    </td>
                    <td style="color:#6b7280;">
                        {{ $report->created_at->format('d M Y') }}
                    </td>
                    <td>
                        @if ($report->file_path)
                            @if ($ext === 'pdf')
                                <a href="#" class="action-btn btn-preview"
                                    onclick="openPreview('{{ route('reports.preview', $report) }}', '{{ addslashes($report->title) }}', 'pdf')">
                                    👁 Preview
                                </a>
                            @else
                                <a href="#" class="action-btn btn-preview"
                                    onclick="openPreview('{{ route('reports.download', $report) }}', '{{ addslashes($report->title) }}', 'excel')">
                                    👁 Preview
                                </a>
                            @endif
                            <a href="{{ route('reports.download', $report) }}" class="action-btn btn-download">
                                ⬇ Download
                            </a>
                        @else
                            <span style="font-size:12px; color:#9ca3af;">No file</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-state">No reports uploaded yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $reports->links() }}
    </div>

    <!-- PREVIEW MODAL -->
    <div class="modal-overlay" id="previewModal">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title" id="modalTitle"></div>
                <button class="modal-close" onclick="closePreview()">✕</button>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <script>
        function openPreview(url, title, type) {
            document.getElementById('modalTitle').innerText = title;
            const body = document.getElementById('modalBody');

            if (type === 'pdf') {
                body.innerHTML = `<iframe src="${url}"></iframe>`;
            } else {
                body.innerHTML = `
                <div class="excel-notice">
                    <div style="font-size:40px;">📊</div>
                    <div>Excel files cannot be previewed inline.</div>
                    <a href="${url}" style="color:#2563eb; font-weight:600;">Download to view</a>
                </div>`;
            }

            document.getElementById('previewModal').style.display = 'flex';
        }

        function closePreview() {
            document.getElementById('previewModal').style.display = 'none';
            document.getElementById('modalBody').innerHTML = '';
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('previewModal');
            if (e.target === modal) closePreview();
        });
    </script>
@endsection
