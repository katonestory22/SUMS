@extends('layouts.app')

@section('title', 'Record Expense')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('allocations.index') }}">Back to Allocations</a>
@endsection

@section('content')

    <!-- Modern font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .expense-card {
            max-width: 720px;
            margin: 40px auto;
            background: #fff;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .section-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .allocation-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .balance-safe {
            color: #16a34a;
            font-weight: 500;
        }

        .balance-warning {
            color: #f59e0b;
            font-weight: 500;
        }

        .balance-danger {
            color: #dc2626;
            font-weight: 600;
        }

        .btn-primary-custom {
            background: #2c5282;
            border: none;
            padding: 10px 26px;
            border-radius: 8px;
            font-weight: 600;
            color: #fff;
            display: inline-block;
            transition: background 0.2s ease;
        }

        .btn-primary-custom:hover {
            background: #1f3d5a;
        }
    </style>

    <div class="expense-card">

        <div class="section-title">Record Expense</div>
        <div class="section-subtitle">Attach spending to an allocation with proof</div>

        {{-- Allocation Info --}}
        <div class="allocation-box">
            <div><strong>Project:</strong> {{ $allocation->project->project_name }}</div>
            <div><strong>Category:</strong> {{ $allocation->category }}</div>
            <div><strong>Allocated:</strong> TSh {{ number_format($allocation->amount, 2) }}</div>
            <div>
                <strong>Remaining:</strong>
                TSh <span id="remainingAmount">{{ number_format($remaining, 2) }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('expenses.store') }}" id="expenseForm" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="allocation_id" value="{{ $allocation->id }}">
            <input type="hidden" name="amount" id="amount_raw">

            {{-- AMOUNT --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Amount</label>
                <input type="text" id="amount_display" class="form-control" required>
                <small id="balancePreview" class="d-block mt-1"></small>
            </div>

            {{-- DESCRIPTION --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>

            {{-- DATE --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Date</label>
                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            {{-- RECEIPT --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Receipt (Optional)</label>
                <input type="file" name="receipt" class="form-control">
                <small class="text-muted">JPG, PNG, PDF (max 2MB)</small>
            </div>

            <button type="submit" class="btn btn-primary-custom">
                Record Expense
            </button>

        </form>
    </div>

    <script>
        const display = document.getElementById('amount_display');
        const raw = document.getElementById('amount_raw');
        const remaining = {{ $remaining }};
        const preview = document.getElementById('balancePreview');
        const form = document.getElementById('expenseForm');

        function updatePreview(value) {
            let newBalance = remaining - value;

            if (newBalance < 0) {
                preview.innerHTML = "❌ Exceeds allocation";
                preview.className = "balance-danger";
            } else {
                preview.innerHTML = "Balance: TSh " + newBalance.toLocaleString();

                preview.className =
                    newBalance <= remaining * 0.2 ? "balance-danger" :
                    newBalance <= remaining * 0.5 ? "balance-warning" :
                    "balance-safe";
            }
        }

        display.addEventListener('input', function() {
            let value = this.value.replace(/,/g, '');

            if (!isNaN(value) && value !== '') {
                raw.value = value;
                this.value = Number(value).toLocaleString('en');
                updatePreview(Number(value));
            } else {
                raw.value = '';
                preview.innerHTML = '';
            }
        });

        form.addEventListener('submit', function(e) {
            if (parseFloat(raw.value) > remaining) {
                e.preventDefault();
                preview.innerHTML = "❌ Cannot exceed allocation";
                preview.className = "balance-danger";
            }
        });
    </script>

@endsection
