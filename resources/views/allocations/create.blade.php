@extends('layouts.app')

@section('title', 'New Allocation')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('finance.dashboard') }}">Dashboard</a>
    <a href="{{ route('allocations.index') }}">Back to Allocations</a>
@endsection

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background: #f4f6f9;
        margin: 0;
    }

    .allocation-page {
        max-width: 980px;
        margin: 0 auto;
        padding: 20px;
    }

    /* =========================================
       MAIN CARD
    ========================================= */

    .allocation-card {
        background: #ffffff;
        border: 1px solid #e7ebf0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(31, 58, 95, 0.07);
    }

    /* =========================================
       HEADER
    ========================================= */

    .allocation-header {
        background: linear-gradient(135deg, #1f3a5f, #16283f);
        padding: 28px 32px;
        position: relative;
        overflow: hidden;
    }

    .allocation-header::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -80px;
        top: -100px;
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
       FORM AREA
    ========================================= */

    .allocation-body {
        padding: 32px;
    }

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

    .section-heading span:last-child {
        color: #1f3a5f;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .65px;
    }

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
        background-size: 5px 5px, 5px 5px;
        background-repeat: no-repeat;
    }

    textarea {
        resize: vertical;
        min-height: 90px;
        line-height: 1.5;
    }

    .hint {
        margin-top: 5px;
        color: #9ca3af;
        font-size: 11px;
    }

    /* =========================================
       AMOUNT FIELD
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
        transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
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
       RESPONSIVE
    ========================================= */

    @media (max-width: 700px) {

        .allocation-page {
            padding: 14px;
        }

        .allocation-header {
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

        .allocation-body {
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

        .btn-submit {
            width: 100%;
        }

        .btn-cancel {
            width: 100%;
        }
    }
</style>

<div class="allocation-page">

    <div class="allocation-card">

        {{-- HEADER --}}
        <div class="allocation-header">

            <div class="header-content">

                <div class="header-icon">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                        <path d="M8 8h8"></path>
                        <path d="M8 12h8"></path>
                        <path d="M8 16h4"></path>
                    </svg>
                </div>

                <div class="header-text">
                    <h2>New Budget Allocation</h2>
                    <p>Assign funds to a project and record the allocation details</p>
                </div>

                <div class="header-tag">
                    <span class="dot"></span>
                    Finance Management
                </div>

            </div>

        </div>

        {{-- BODY --}}
        <div class="allocation-body">

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

            <form method="POST" action="{{ route('allocations.store') }}">
                @csrf

                {{-- SECTION 01 --}}
                <div class="section-heading">
                    <span class="section-number">01</span>
                    <span>Allocation Details</span>
                </div>

                <div class="form-grid">

                    {{-- PROJECT --}}
                    <div class="form-group full">
                        <label>
                            Project
                            <span class="required">*</span>
                        </label>

                        <select name="project_id" required>
                            <option value="" disabled {{ old('project_id') ? '' : 'selected' }}>
                                Select project
                            </option>

                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->client->first_name }}
                                    {{ $project->client->last_name }}
                                    — {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>

                        <span class="hint">
                            Select the project receiving this budget allocation.
                        </span>
                    </div>

                    {{-- AMOUNT --}}
                    <div class="form-group">

                        <label>
                            Allocation Amount
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
                            Enter the amount being allocated.
                        </span>

                    </div>

                    {{-- DATE --}}
                    <div class="form-group">

                        <label>
                            Allocation Date
                            <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="allocation_date"
                            value="{{ old('allocation_date') }}"
                            required
                        >

                        <span class="hint">
                            Date when the allocation is recorded.
                        </span>

                    </div>

                    {{-- NOTES --}}
                    <div class="form-group full">

                        <label>
                            Notes
                            <span style="font-weight:400;color:#9ca3af;">(Optional)</span>
                        </label>

                        <textarea
                            name="notes"
                            rows="3"
                            placeholder="Add any additional information about this allocation..."
                        >{{ old('notes') }}</textarea>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="form-actions">

                        <button type="submit" class="btn-submit">

                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14"></path>
                                <path d="M5 12h14"></path>
                            </svg>

                            Save Allocation

                        </button>

                        <a href="{{ route('allocations.index') }}" class="btn-cancel">
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

            // Keep only numbers and decimal point
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
