@extends('layouts.app')

@section('title', 'Record Company Expense')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('finance.dashboard') }}">Dashboard</a>
    <a href="{{ route('company-expenses.index') }}">Company Expenses</a>
@endsection

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background: #f4f6f9;
        margin: 0;
    }

    .expense-page {
        max-width: 980px;
        margin: 0 auto;
        padding: 20px;
    }

    /* =========================================
       MAIN CARD
    ========================================= */

    .expense-card {
        background: #ffffff;
        border: 1px solid #e7ebf0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(31, 58, 95, 0.07);
    }

    /* =========================================
       HEADER
    ========================================= */

    .expense-header {
        background: linear-gradient(135deg, #1f3a5f, #16283f);
        padding: 28px 32px;
        position: relative;
        overflow: hidden;
    }

    .expense-header::after {
        content: "";
        position: absolute;
        width: 240px;
        height: 240px;
        right: -90px;
        top: -110px;
        border-radius: 50%;
        background: rgba(201, 168, 76, 0.08);
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 16px;
        position: relative;
        z-index: 1;
    }

    .header-icon {
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

    .header-icon svg {
        width: 25px;
        height: 25px;
    }

    .header-text h2 {
        margin: 0;
        color: #ffffff;
        font-size: 21px;
        font-weight: 700;
        letter-spacing: -0.2px;
    }

    .header-text p {
        margin: 5px 0 0;
        color: rgba(255, 255, 255, 0.68);
        font-size: 13px;
    }

    .header-tag {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #d9e2ec;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .4px;
        text-transform: uppercase;
    }

    .header-tag .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #3fcf8e;
        box-shadow: 0 0 0 4px rgba(63, 207, 142, 0.10);
    }

    /* =========================================
       BODY
    ========================================= */

    .expense-body {
        padding: 32px;
    }

    /* =========================================
       VALIDATION
    ========================================= */

    .error-box {
        margin-bottom: 25px;
        padding: 13px 16px;
        border: 1px solid #fecaca;
        border-radius: 9px;
        background: #fef2f2;
        color: #991b1b;
        font-size: 12px;
    }

    .error-box strong {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
    }

    .error-box ul {
        margin: 0;
        padding-left: 18px;
    }

    .error-box li {
        margin-bottom: 3px;
    }

    /* =========================================
       SECTION
    ========================================= */

    .section-heading {
        display: flex;
        align-items: center;
        gap: 11px;
        margin: 0 0 18px;
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

    /* =========================================
       FORM
    ========================================= */

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full {
        grid-column: span 2;
    }

    label {
        display: block;
        margin-bottom: 7px;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
    }

    .required {
        color: #c53030;
        margin-left: 2px;
    }

    input,
    select,
    textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 11px 13px;
        border: 1px solid #d9dee5;
        border-radius: 8px;
        background: #ffffff;
        color: #111827;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    input::placeholder,
    textarea::placeholder {
        color: #a1a8b3;
    }

    input:hover,
    select:hover,
    textarea:hover {
        border-color: #c6ccd5;
    }

    input:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: #1f3a5f;
        box-shadow: 0 0 0 3px rgba(31, 58, 95, 0.10);
    }

    select {
        appearance: none;
        -webkit-appearance: none;
        padding-right: 40px;

        background-image:
            linear-gradient(45deg, transparent 50%, #6b7280 50%),
            linear-gradient(135deg, #6b7280 50%, transparent 50%);

        background-position:
            calc(100% - 17px) 50%,
            calc(100% - 12px) 50%;

        background-size:
            5px 5px,
            5px 5px;

        background-repeat: no-repeat;
    }

    textarea {
        resize: vertical;
        min-height: 95px;
        line-height: 1.5;
    }

    .hint {
        margin-top: 5px;
        color: #9ca3af;
        font-size: 11px;
    }

    /* =========================================
       AMOUNT
    ========================================= */

    .amount-wrapper {
        position: relative;
    }

    .amount-wrapper input {
        padding-right: 58px;
    }

    .currency-tag {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f6f8;
        border-left: 1px solid #d9dee5;
        border-radius: 0 8px 8px 0;
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
        pointer-events: none;
    }

    /* =========================================
       FILE UPLOAD
    ========================================= */

    .file-wrapper {
        position: relative;
    }

    input[type="file"] {
        padding: 9px 10px;
        cursor: pointer;
        color: #6b7280;
    }

    input[type="file"]::file-selector-button {
        margin-right: 10px;
        padding: 7px 12px;
        border: 1px solid #d9dee5;
        border-radius: 6px;
        background: #f5f6f8;
        color: #374151;
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s ease;
    }

    input[type="file"]::file-selector-button:hover {
        background: #e9edf2;
    }

    .file-hint {
        margin-top: 6px;
        color: #9ca3af;
        font-size: 11px;
    }

    /* =========================================
       ACTIONS
    ========================================= */

    .form-actions {
        grid-column: span 2;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 4px;
        padding-top: 22px;
        border-top: 1px solid #edf0f3;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-width: 170px;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        background: #C9A84C;
        color: #16283f;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(201, 168, 76, 0.20);
        transition:
            background .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .btn-submit svg {
        width: 16px;
        height: 16px;
    }

    .btn-submit:hover {
        background: #b8953d;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(201, 168, 76, 0.28);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 17px;
        border-radius: 8px;
        color: #6b7280;
        background: transparent;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: background .2s ease, color .2s ease;
    }

    .btn-cancel:hover {
        background: #f3f4f6;
        color: #374151;
    }

    /* =========================================
       RESPONSIVE
    ========================================= */

    @media (max-width: 700px) {

        .expense-page {
            padding: 14px;
        }

        .expense-header {
            padding: 23px 20px;
        }

        .header-content {
            align-items: flex-start;
        }

        .header-tag {
            display: none;
        }

        .header-icon {
            width: 44px;
            height: 44px;
        }

        .header-text h2 {
            font-size: 18px;
        }

        .expense-body {
            padding: 22px 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 17px;
        }

        .form-group.full,
        .form-actions {
            grid-column: span 1;
        }

        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-submit,
        .btn-cancel {
            width: 100%;
        }
    }
</style>

<div class="expense-page">

    <div class="expense-card">

        {{-- HEADER --}}
        <div class="expense-header">

            <div class="header-content">

                <div class="header-icon">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8"
                         stroke-linecap="round"
                         stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                        <path d="M3 10h18"></path>
                        <path d="M7 15h3"></path>
                        <path d="M16 15h1"></path>
                    </svg>
                </div>

                <div class="header-text">
                    <h2>Record Company Expense</h2>
                    <p>Log an operational expense and keep the company accounts up to date</p>
                </div>

                <div class="header-tag">
                    <span class="dot"></span>
                    Finance Management
                </div>

            </div>

        </div>

        {{-- BODY --}}
        <div class="expense-body">

            {{-- VALIDATION ERRORS --}}
            @if ($errors->any())
                <div class="error-box">
                    <strong>Please correct the following:</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('company-expenses.store') }}"
                enctype="multipart/form-data"
            >
                @csrf

                {{-- SECTION 01 --}}
                <div class="section-heading">
                    <span class="section-number">01</span>
                    <span class="section-title">Expense Details</span>
                </div>

                <div class="form-grid">

                    {{-- TITLE --}}
                    <div class="form-group full">

                        <label>
                            Expense Title
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            placeholder="e.g. Office Rent June 2025"
                            required
                        >

                        <span class="hint">
                            Give the expense a clear and recognizable title.
                        </span>

                    </div>

                    {{-- CATEGORY --}}
                    <div class="form-group">

                        <label>
                            Category
                            <span class="required">*</span>
                        </label>

                        <select name="category" required>

                            <option value="" disabled
                                {{ old('category') ? '' : 'selected' }}>
                                Select category
                            </option>

                            @foreach ($categories as $cat)
                                <option
                                    value="{{ $cat }}"
                                    {{ old('category') == $cat ? 'selected' : '' }}
                                >
                                    {{ $cat }}
                                </option>
                            @endforeach

                        </select>

                        <span class="hint">
                            Choose the category that best describes the expense.
                        </span>

                    </div>

                    {{-- AMOUNT --}}
                    <div class="form-group">

                        <label>
                            Amount
                            <span class="required">*</span>
                        </label>

                        <div class="amount-wrapper">

                            <input
                                type="text"
                                name="amount"
                                id="amount"
                                value="{{ old('amount') }}"
                                placeholder="0"
                                inputmode="numeric"
                                autocomplete="off"
                                required
                            >

                            <span class="currency-tag">TSh</span>

                        </div>

                        <span class="hint">
                            Enter the total expense amount.
                        </span>

                    </div>

                    {{-- DATE --}}
                    <div class="form-group">

                        <label>
                            Expense Date
                            <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="date"
                            value="{{ old('date', date('Y-m-d')) }}"
                            required
                        >

                        <span class="hint">
                            Date on which the expense was incurred.
                        </span>

                    </div>

                    {{-- RECEIPT --}}
                    <div class="form-group">

                        <label>
                            Receipt / Invoice
                            <span style="font-weight:400;color:#9ca3af;">
                                (Optional)
                            </span>
                        </label>

                        <div class="file-wrapper">

                            <input
                                type="file"
                                name="receipt"
                                accept=".jpg,.jpeg,.png,.pdf"
                            >

                        </div>

                        <span class="file-hint">
                            JPG, PNG or PDF — maximum 5MB.
                        </span>

                    </div>

                </div>

                {{-- SECTION 02 --}}
                <div class="section-heading" style="margin-top: 30px;">
                    <span class="section-number">02</span>
                    <span class="section-title">Additional Information</span>
                </div>

                <div class="form-grid">

                    {{-- DESCRIPTION --}}
                    <div class="form-group full">

                        <label>
                            Description
                            <span style="font-weight:400;color:#9ca3af;">
                                (Optional)
                            </span>
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            placeholder="Add any additional notes about this expense..."
                        >{{ old('description') }}</textarea>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="form-actions">

                        <button type="submit" class="btn-submit">

                            <svg viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M12 5v14"></path>
                                <path d="M5 12h14"></path>
                            </svg>

                            Save Expense

                        </button>

                        <a
                            href="{{ route('company-expenses.index') }}"
                            class="btn-cancel"
                        >
                            Cancel
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
    const amountInput = document.getElementById('amount');

    if (amountInput) {
        amountInput.addEventListener('input', function () {

            let value = this.value.replace(/,/g, '');

            // Keep numbers and decimal point only
            value = value.replace(/[^\d.]/g, '');

            // Allow only one decimal point
            const parts = value.split('.');

            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }

            if (value === '') {
                this.value = '';
                return;
            }

            const numericValue = Number(value);

            if (!isNaN(numericValue)) {
                this.value = numericValue.toLocaleString('en-US', {
                    maximumFractionDigits: 2
                });
            }
        });
    }
</script>

@endsection
