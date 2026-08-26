@extends('layouts.app')

@section('title', 'Invoices')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('finance.dashboard') }}">Dashboard</a>
    <a href="{{ route('invoices.create') }}">New Invoice</a>
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

        .new-invoice-btn {
            background: #2563eb;
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
        }

        .new-invoice-btn:hover {
            background: #1d4ed8;
        }

        .invoices-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }

        .invoices-table thead {
            background: #1f3a5f;
            color: #C9A84C;
        }

        .invoices-table th {
            padding: 12px 14px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .invoices-table td {
            padding: 13px 14px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .invoices-table tbody tr:hover {
            background: #f9fafb;
        }

        .invoice-number {
            font-weight: 700;
            color: #1f3a5f;
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
    </style>

    <div class="page-header">
        <div>
            <div class="page-title">Invoices</div>
            <div class="page-subtitle">All invoices created in SUMS</div>
        </div>
        <a href="{{ route('invoices.create') }}" class="new-invoice-btn">+ New Invoice</a>
    </div>

    <table class="invoices-table">
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Bill To</th>
                <th>Invoice Date</th>
                <th>Total</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $invoice)
                <tr>
                    <td class="invoice-number">{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->bill_to_name }}</td>
                    <td style="color:#6b7280;">{{ $invoice->invoice_date->format('d M Y') }}</td>
                    <td style="font-weight:600; color:#111827;">{{ number_format($invoice->total, 2) }}</td>
                    <td style="color:#6b7280;">{{ $invoice->creator->name ?? '—' }}</td>
                    <td>
                        @if ($invoice->file_path)
                            <a href="#" class="action-btn btn-preview"
                                onclick="openPreview('{{ route('invoices.preview', $invoice) }}', '{{ addslashes($invoice->invoice_number) }}'); return false;">
                                👁 Preview
                            </a>
                            <a href="{{ route('invoices.download', $invoice) }}" class="action-btn btn-download">
                                ⬇ Download
                            </a>
                        @else
                            <span style="font-size:12px; color:#9ca3af;">No file</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-state">No invoices created yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $invoices->links() }}
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
        function openPreview(url, title) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalBody').innerHTML = `<iframe src="${url}"></iframe>`;
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
