@extends('layouts.app')

@section('title', 'Record Expense')

@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('allocations.index') }}">Back to Allocations</a>
@endsection

@section('content')

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
            color: #172033;
        }

        .expense-page {
            max-width: 920px;
            margin: 38px auto 60px;
            padding: 0 18px;
        }

        /* Main card */
        .expense-card {
            background: #ffffff;
            border: 1px solid #e8ebf0;
            border-radius: 18px;
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.07);
            overflow: hidden;
        }

        /* Header */
        .expense-header {
            padding: 28px 32px 24px;
            border-bottom: 1px solid #edf0f4;
            background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .expense-icon {
            width: 48px;
            height: 48px;
            border-radius: 13px;
            background: #eef4fb;
            color: #1f3a5f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            font-weight: 700;
        }

        .section-title {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.4px;
            color: #172033;
        }

        .section-subtitle {
            margin: 5px 0 0;
            color: #718096;
            font-size: 13px;
            line-height: 1.5;
        }

        .secure-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #dcfce7;
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .secure-dot {
            width: 6px;
            height: 6px;
            background: #22c55e;
            border-radius: 50%;
        }

        /* Allocation summary */
        .summary-section {
            padding: 25px 32px 8px;
        }

        .summary-title {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #8490a3;
            margin-bottom: 13px;
        }

        .allocation-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1fr;
            gap: 12px;
        }

        .allocation-item {
            background: #f8fafc;
            border: 1px solid #e8edf3;
            border-radius: 12px;
            padding: 14px 15px;
            min-width: 0;
        }

        .allocation-label {
            display: block;
            font-size: 11px;
            color: #8792a4;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .allocation-value {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #263449;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .allocation-value.money {
            color: #1f3a5f;
        }

        .allocation-value.remaining {
            color: #15803d;
        }

        /* Form */
        .form-section {
            padding: 25px 32px 32px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #293548;
            font-size: 13px;
            font-weight: 700;
        }

        .required {
            color: #dc2626;
            margin-left: 2px;
        }

        .form-control,
        .form-select {
            width: 100%;
            min-height: 46px;
            border: 1px solid #d9dee7;
            border-radius: 10px;
            background: #fff;
            padding: 11px 13px;
            color: #1f2937;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .form-control:hover,
        .form-select:hover {
            border-color: #b9c3d1;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2c5282;
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.10);
        }

        textarea.form-control {
            min-height: 105px;
            resize: vertical;
            line-height: 1.55;
        }

        input[type="date"].form-control {
            color: #374151;
        }

        input[type="file"].form-control {
            padding: 8px;
            cursor: pointer;
        }

        input[type="file"]::file-selector-button {
            border: none;
            background: #eef2f7;
            color: #344054;
            padding: 7px 12px;
            border-radius: 7px;
            margin-right: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .input-prefix {
            position: relative;
        }

        .currency-prefix {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #7b8798;
            font-size: 12px;
            font-weight: 700;
            pointer-events: none;
        }

        .amount-input {
            padding-left: 52px !important;
            font-size: 15px;
            font-weight: 600;
        }

        .input-help {
            display: block;
            margin-top: 6px;
            font-size: 11px;
            color: #8993a3;
        }

        .field-error {
            display: block;
            margin-top: 6px;
            color: #dc2626;
            font-size: 11px;
            font-weight: 600;
        }

        /* Balance preview */
        .balance-preview {
            min-height: 21px;
            margin-top: 7px;
            font-size: 11px;
            font-weight: 700;
        }

        .balance-safe {
            color: #15803d;
        }

        .balance-warning {
            color: #d97706;
        }

        .balance-danger {
            color: #dc2626;
        }

        /* Divider */
        .form-divider {
            height: 1px;
            background: #edf0f4;
            margin: 4px 0 25px;
        }

        /* Footer */
        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding-top: 8px;
        }

        .footer-note {
            font-size: 11px;
            color: #8a94a5;
            line-height: 1.5;
        }

        .footer-note strong {
            color: #667085;
        }

        .btn-primary-custom {
            border: none;
            background: #1f3a5f;
            color: #fff;
            min-height: 46px;
            padding: 0 23px;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            box-shadow: 0 5px 12px rgba(31, 58, 95, 0.18);
            transition: background .2s ease, transform .15s ease, box-shadow .2s ease;
        }

        .btn-primary-custom:hover {
            background: #162d49;
            transform: translateY(-1px);
            box-shadow: 0 7px 16px rgba(31, 58, 95, 0.22);
        }

        .btn-primary-custom:active {
            transform: translateY(0);
        }

        .btn-icon {
            font-size: 15px;
        }

        /* Responsive */
        @media (max-width: 800px) {
            .allocation-grid {
                grid-template-columns: 1fr 1fr;
            }

            .header-content {
                align-items: flex-start;
            }

            .secure-label {
                display: none;
            }
        }

        @media (max-width: 600px) {
            .expense-page {
                margin-top: 20px;
                padding: 0 10px;
            }

            .expense-header,
            .summary-section,
            .form-section {
                padding-left: 20px;
                padding-right: 20px;
            }

            .allocation-grid,
            .form-row {
                grid-template-columns: 1fr;
            }

            .header-left {
                align-items: flex-start;
            }

            .expense-icon {
                width: 42px;
                height: 42px;
                font-size: 18px;
            }

            .section-title {
                font-size: 19px;
            }

            .form-footer {
                align-items: stretch;
                flex-direction: column;
            }

            .btn-primary-custom {
                width: 100%;
            }
        }
    </style>

    <div class="expense-page">

        <div class="expense-card">

            {{-- HEADER --}}
            <div class="expense-header">
                <div class="header-content">

                    <div class="header-left">


                        <div>
                            <h1 class="section-title">Record Expense</h1>
                            <p class="section-subtitle">
                                Record spending against the selected allocation with supporting details.
                            </p>
                        </div>
                    </div>

                    <div class="secure-label">
                        <span class="secure-dot"></span>
                        Finance Record
                    </div>

                </div>
            </div>

            {{-- ALLOCATION SUMMARY --}}
            <div class="summary-section">

                <div class="summary-title">
                    Allocation Summary
                </div>

                <div class="allocation-grid">

                    <div class="allocation-item">
                        <span class="allocation-label">Project</span>
                        <span class="allocation-value">
                            {{ $allocation->project->project_name }}
                        </span>
                    </div>

                    <div class="allocation-item">
                        <span class="allocation-label">Category</span>
                        <span class="allocation-value">
                            {{ $allocation->category }}
                        </span>
                    </div>

                    <div class="allocation-item">
                        <span class="allocation-label">Allocated</span>
                        <span class="allocation-value money">
                            TSh {{ number_format($allocation->amount, 2) }}
                        </span>
                    </div>

                    <div class="allocation-item">
                        <span class="allocation-label">Remaining</span>
                        <span class="allocation-value remaining" id="remainingAmount">
                            TSh {{ number_format($remaining, 2) }}
                        </span>
                    </div>

                </div>

            </div>

            {{-- FORM --}}
            <div class="form-section">

                <form method="POST" action="{{ route('expenses.store') }}" id="expenseForm" enctype="multipart/form-data">

                    @csrf

                    <input type="hidden" name="allocation_id" value="{{ $allocation->id }}">

                    <input type="hidden" name="amount" id="amount_raw">

                    {{-- CATEGORY + AMOUNT --}}
                    <div class="form-row">

                        <div class="form-group">
                            <label class="form-label">
                                Expense Category
                                <span class="required">*</span>
                            </label>

                            <select name="category" class="form-select" required>

                                <option value="" disabled selected>
                                    Select category...
                                </option>

                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach

                            </select>

                            @error('category')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Expense Amount
                                <span class="required">*</span>
                            </label>

                            <div class="input-prefix">
                                <span class="currency-prefix">TSh</span>

                                <input type="text" id="amount_display" class="form-control amount-input"
                                    inputmode="decimal" placeholder="0.00" autocomplete="off" required>
                            </div>

                            <div id="balancePreview" class="balance-preview"></div>

                        </div>

                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="form-group">

                        <label class="form-label">
                            Description
                            <span class=""></span>
                        </label>

                        <textarea name="description" class="form-control" rows="4" placeholder="Describe what this expense was for..."
                            required>{{ old('description') }}</textarea>

                        <small class="input-help">
                            Provide enough detail to make the expense easy to identify during review.
                        </small>

                        @error('description')
                            <span class="field-error">{{ $message }}</span>
                        @enderror

                    </div>

                    {{-- DATE + RECEIPT --}}
                    <div class="form-row">

                        <div class="form-group">

                            <label class="form-label">
                                Expense Date
                                <span class="required">*</span>
                            </label>

                            <input type="date" name="date" class="form-control"
                                value="{{ old('date', date('Y-m-d')) }}" required>

                            @error('date')
                                <span class="field-error">{{ $message }}</span>
                            @enderror

                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Receipt
                                <span style="font-weight:500;color:#98a2b3;">
                                    (Optional)
                                </span>
                            </label>

                            <input type="file" name="receipt" class="form-control" accept=".jpg,.jpeg,.png,.pdf">

                            <small class="input-help">
                                JPG, PNG or PDF. Maximum file size: 2MB.
                            </small>

                            @error('receipt')
                                <span class="field-error">{{ $message }}</span>
                            @enderror

                        </div>

                    </div>

                    <div class="form-divider"></div>

                    {{-- FOOTER --}}
                    <div class="form-footer">

                        <div class="footer-note">
                            <strong>Important:</strong>
                            The expense cannot exceed the remaining allocation balance.
                        </div>

                        <button type="submit" class="btn-primary-custom">

                            <span class="btn-icon">+</span>
                            Record Expense

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <script>
        const display = document.getElementById('amount_display');
        const raw = document.getElementById('amount_raw');
        const remaining = {{ $remaining }};
        const preview = document.getElementById('balancePreview');
        const form = document.getElementById('expenseForm');

        function formatMoney(value) {
            return Number(value).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function updatePreview(value) {

            if (!value || value <= 0) {
                preview.innerHTML = '';
                preview.className = 'balance-preview';
                return;
            }

            const newBalance = remaining - value;

            if (newBalance < 0) {

                preview.innerHTML =
                    'Cannot exceed allocation by TSh ' +
                    formatMoney(Math.abs(newBalance));

                preview.className =
                    'balance-preview balance-danger';

            } else {

                preview.innerHTML =
                    'Remaining after this expense: TSh ' +
                    formatMoney(newBalance);

                preview.className =
                    newBalance <= remaining * 0.2 ?
                    'balance-preview balance-danger' :
                    newBalance <= remaining * 0.5 ?
                    'balance-preview balance-warning' :
                    'balance-preview balance-safe';
            }
        }

        display.addEventListener('input', function() {

            let value = this.value
                .replace(/,/g, '')
                .replace(/[^\d.]/g, '');

            const parts = value.split('.');

            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }

            if (value !== '' && !isNaN(value)) {

                const numberValue = Number(value);

                raw.value = numberValue;

                this.value = numberValue.toLocaleString('en-US', {
                    maximumFractionDigits: 2
                });

                updatePreview(numberValue);

            } else {

                raw.value = '';
                preview.innerHTML = '';
                preview.className = 'balance-preview';
            }
        });

        form.addEventListener('submit', function(e) {

            const amount = parseFloat(raw.value);

            if (!amount || amount <= 0) {
                e.preventDefault();

                preview.innerHTML = 'Please enter a valid expense amount.';
                preview.className =
                    'balance-preview balance-danger';

                display.focus();
                return;
            }

            if (amount > remaining) {

                e.preventDefault();

                preview.innerHTML =
                    'Cannot exceed the remaining allocation of TSh ' +
                    formatMoney(remaining);

                preview.className =
                    'balance-preview balance-danger';

                display.focus();
            }
        });
    </script>

@endsection
