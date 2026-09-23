@extends('layouts.app')

@section('title', 'Add Project')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">

        Dashboard
    </a>

    <span class="nav-separator">/</span>

    <a href="{{ route('projects.index') }}">
        Projects
    </a>
@endsection

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    * {
        box-sizing: border-box;
    }

    body {
        font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        background: #f4f6f9;
        margin: 0;
    }

    /* ================================================================
       PAGE WRAPPER
    ================================================================= */

    .project-page {
        width: 100%;
        max-width: 980px;
        margin: 0 auto;
        padding: 20px;
    }

    .nav-separator {
        color: #b8bec8;
        margin: 0 4px;
    }


    /* ================================================================
       MAIN CARD
    ================================================================= */

    .project-card {
        background: #ffffff;
        border: 1px solid #e7eaf0;
        border-radius: 14px;
        box-shadow: 0 6px 22px rgba(16, 24, 40, 0.055);
        overflow: hidden;
    }


    /* ================================================================
       HEADER
    ================================================================= */

    .project-card-header {
        position: relative;
        padding: 25px 28px;
        background: linear-gradient(135deg, #1f3a5f, #16283f);
        overflow: hidden;
    }

    .project-card-header::after {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        right: -90px;
        top: -150px;
        border-radius: 50%;
        background: rgba(201, 168, 76, 0.10);
        pointer-events: none;
    }

    .project-card-header::before {
        content: "";
        position: absolute;
        width: 120px;
        height: 120px;
        right: 95px;
        bottom: -85px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.035);
        pointer-events: none;
    }

    .project-heading {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .project-heading-icon {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        background: rgba(201, 168, 76, 0.15);
        border: 1px solid rgba(201, 168, 76, 0.38);
        color: #e3c66f;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .project-heading h1 {
        margin: 0;
        color: #ffffff;
        font-size: 22px;
        line-height: 1.2;
        font-weight: 800;
        letter-spacing: -0.3px;
    }

    .project-heading p {
        margin: 5px 0 0;
        color: rgba(255, 255, 255, 0.68);
        font-size: 12.5px;
        line-height: 1.5;
    }

    .heading-live {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 7px;
        color: #e3c66f;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.9px;
    }

    .heading-live-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #46c98a;
        box-shadow: 0 0 0 4px rgba(70, 201, 138, 0.09);
    }


    /* ================================================================
       FORM BODY
    ================================================================= */

    .project-card-body {
        padding: 28px;
    }


    /* ================================================================
       VALIDATION
    ================================================================= */

    .validation-errors {
        margin-bottom: 24px;
        padding: 14px 16px;
        border: 1px solid #fecdd3;
        border-radius: 10px;
        background: #fff1f2;
        color: #b4232f;
        font-size: 12.5px;
    }

    .validation-errors-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 7px;
        font-weight: 800;
    }

    .validation-errors ul {
        margin: 0;
        padding-left: 25px;
    }

    .validation-errors li {
        margin: 3px 0;
    }


    /* ================================================================
       FORM SECTIONS
    ================================================================= */

    .form-section {
        margin-bottom: 30px;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        padding-bottom: 11px;
        border-bottom: 1px solid #eaecf0;
    }

    .section-number {
        width: 27px;
        height: 27px;
        border-radius: 8px;
        background: #eef2f7;
        color: #1f3a5f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
    }

    .section-heading h2 {
        margin: 0;
        color: #1f2937;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.65px;
    }

    .section-heading p {
        margin: 2px 0 0;
        color: #98a2b3;
        font-size: 11px;
    }


    /* ================================================================
       FORM GRID
    ================================================================= */

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 19px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }


    /* ================================================================
       LABELS
    ================================================================= */

    .form-group label {
        margin-bottom: 7px;
        color: #344054;
        font-size: 12px;
        font-weight: 700;
    }

    .required {
        color: #c53545;
        margin-left: 2px;
    }

    .optional {
        color: #98a2b3;
        font-size: 10.5px;
        font-weight: 500;
        margin-left: 3px;
    }


    /* ================================================================
       INPUTS / SELECTS
    ================================================================= */

    .form-group input[type="text"],
    .form-group input[type="date"],
    .form-group select {
        width: 100%;
        height: 42px;
        padding: 0 13px;
        border: 1px solid #dfe3ea;
        border-radius: 9px;
        outline: none;
        background: #ffffff;
        color: #172033;
        font-family: inherit;
        font-size: 13px;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease;
    }

    .form-group input[type="text"]:hover,
    .form-group input[type="date"]:hover,
    .form-group select:hover {
        border-color: #c8ced8;
    }

    .form-group input[type="text"]:focus,
    .form-group input[type="date"]:focus,
    .form-group select:focus {
        border-color: #1f3a5f;
        box-shadow: 0 0 0 3px rgba(31, 58, 95, 0.08);
        background: #ffffff;
    }

    .form-group input::placeholder {
        color: #b1b8c4;
    }

    .form-group select {
        cursor: pointer;
    }


    /* ================================================================
       CURRENCY INPUT
    ================================================================= */

    .amount-wrap {
        position: relative;
        width: 100%;
    }

    .amount-wrap input {
        padding-right: 58px !important;
    }

    .currency-tag {
        position: absolute;
        right: 1px;
        top: 1px;
        bottom: 1px;
        width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border-left: 1px solid #e1e5eb;
        border-radius: 0 8px 8px 0;
        color: #667085;
        font-size: 11px;
        font-weight: 800;
        pointer-events: none;
    }


    /* ================================================================
       FIELD ERRORS
    ================================================================= */

    .field-error {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 5px;
        color: #c53545;
        font-size: 11px;
        font-weight: 600;
    }


    /* ================================================================
       FORM DIVIDER
    ================================================================= */

    .form-divider {
        height: 1px;
        margin: 5px 0 28px;
        background: #eaecf0;
    }


    /* ================================================================
       ACTIONS
    ================================================================= */

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding-top: 5px;
    }

    .action-note {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #98a2b3;
        font-size: 11px;
    }

    .action-note svg {
        color: #1f3a5f;
        flex-shrink: 0;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .btn-cancel,
    .btn-submit {
        min-height: 41px;
        padding: 0 17px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-family: inherit;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition:
            transform 0.18s ease,
            background 0.18s ease,
            box-shadow 0.18s ease,
            border-color 0.18s ease;
    }

    .btn-cancel {
        border: 1px solid #dfe3ea;
        background: #ffffff;
        color: #475467;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        border-color: #cfd5de;
    }

    .btn-submit {
        border: 1px solid #C9A84C;
        background: #C9A84C;
        color: #16283f;
        box-shadow: 0 6px 15px rgba(201, 168, 76, 0.20);
    }

    .btn-submit:hover {
        background: #d8b95e;
        border-color: #d8b95e;
        transform: translateY(-1px);
        box-shadow: 0 9px 20px rgba(201, 168, 76, 0.27);
    }

    .btn-submit:active {
        transform: translateY(0);
    }


    /* ================================================================
       RESPONSIVE
    ================================================================= */

    @media (max-width: 700px) {

        .project-page {
            padding: 15px;
        }

        .project-card-header {
            padding: 21px;
        }

        .project-card-body {
            padding: 21px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }

        .form-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .action-note {
            justify-content: center;
        }

        .action-buttons {
            width: 100%;
        }

        .btn-cancel,
        .btn-submit {
            flex: 1;
        }
    }

    @media (max-width: 480px) {

        .project-heading {
            gap: 12px;
        }

        .project-heading-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
        }

        .project-heading h1 {
            font-size: 19px;
        }

        .project-heading p {
            font-size: 11.5px;
        }

        .project-card-body {
            padding: 17px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-cancel,
        .btn-submit {
            width: 100%;
        }
    }
</style>


<div class="project-page">

    <div class="project-card">

        {{-- ============================================================
             HEADER
        ============================================================= --}}
        <div class="project-card-header">

            <div class="project-heading">

                <div class="project-heading-icon">

                    <svg width="26" height="26"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <rect x="3" y="4" width="18" height="16" rx="2"/>
                        <path d="M8 2v4"/>
                        <path d="M16 2v4"/>
                        <path d="M3 10h18"/>
                        <path d="M8 14h3"/>
                        <path d="M8 17h6"/>

                    </svg>

                </div>

                <div>

                    <h1>Add New Project</h1>

                    <p>
                        Enter the project details and contract information.
                    </p>

                    <div class="heading-live">
                        <span class="heading-live-dot"></span>
                        Project Management
                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             BODY
        ============================================================= --}}
        <div class="project-card-body">

            {{-- Validation Errors --}}
            @if ($errors->any())

                <div class="validation-errors">

                    <div class="validation-errors-title">

                        <svg width="17" height="17"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 8v4"/>
                            <path d="M12 16h.01"/>

                        </svg>

                        Please correct the following errors

                    </div>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif


            <form method="POST" action="{{ route('projects.store') }}">
                @csrf


                {{-- ====================================================
                     CLIENT & PROJECT
                ===================================================== --}}
                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-number">
                            01
                        </div>

                        <div>
                            <h2>Client & Project</h2>

                            <p>
                                Basic project identification
                            </p>
                        </div>

                    </div>


                    <div class="form-grid">

                        {{-- Client --}}
                        <div class="form-group full">

                            <label>
                                Client
                                <span class="required">*</span>
                            </label>

                            <select name="client_id" required>

                                <option value="" disabled
                                    {{ old('client_id') ? '' : 'selected' }}>
                                    Select client
                                </option>

                                @foreach ($clients as $client)

                                    <option value="{{ $client->id }}"
                                        {{ old('client_id') == $client->id ? 'selected' : '' }}>

                                        {{ $client->first_name }}
                                        {{ $client->last_name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('client_id')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Project Name --}}
                        <div class="form-group">

                            <label>
                                Project Name
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="project_name"
                                value="{{ old('project_name') }}"
                                placeholder="e.g. Road Rehabilitation Phase 2"
                                required
                            >

                            @error('project_name')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Project Type --}}
                        <div class="form-group">

                            <label>
                                Project Type
                                <span class="required">*</span>
                            </label>

                            <select name="project_type_id" required>

                                <option value="" disabled
                                    {{ old('project_type_id') ? '' : 'selected' }}>
                                    Select type
                                </option>

                                @foreach ($projectTypes as $type)

                                    <option value="{{ $type->id }}"
                                        {{ old('project_type_id') == $type->id ? 'selected' : '' }}>

                                        {{ ucfirst($type->name) }}

                                    </option>

                                @endforeach

                            </select>

                            @error('project_type_id')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Location --}}
                        <div class="form-group full">

                            <label>
                                Location
                                <span class="optional">(Optional)</span>
                            </label>

                            <select name="location">

                                <option value="" disabled
                                    {{ old('location') ? '' : 'selected' }}>
                                    Select region
                                </option>

                                @php
                                    $regions = [
                                        'Arusha',
                                        'Dar es Salaam',
                                        'Dodoma',
                                        'Geita',
                                        'Iringa',
                                        'Kagera',
                                        'Katavi',
                                        'Kigoma',
                                        'Kilimanjaro',
                                        'Lindi',
                                        'Manyara',
                                        'Mara',
                                        'Mbeya',
                                        'Morogoro',
                                        'Mtwara',
                                        'Mwanza',
                                        'Njombe',
                                        'Pemba North',
                                        'Pemba South',
                                        'Pwani',
                                        'Rukwa',
                                        'Ruvuma',
                                        'Shinyanga',
                                        'Simiyu',
                                        'Singida',
                                        'Songwe',
                                        'Tabora',
                                        'Tanga',
                                        'Unguja North',
                                        'Unguja South',
                                        'Zanzibar West',
                                    ];
                                @endphp

                                @foreach ($regions as $region)

                                    <option value="{{ $region }}"
                                        {{ old('location') == $region ? 'selected' : '' }}>

                                        {{ $region }}

                                    </option>

                                @endforeach

                            </select>

                            @error('location')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                <div class="form-divider"></div>


                {{-- ====================================================
                     CONTRACT DETAILS
                ===================================================== --}}
                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-number">
                            02
                        </div>

                        <div>
                            <h2>Contract Details</h2>

                            <p>
                                Contract value and project timeline
                            </p>
                        </div>

                    </div>


                    <div class="form-grid">

                        {{-- Contract Number --}}
                        <div class="form-group">

                            <label>
                                Contract Number
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="contract_number"
                                value="{{ old('contract_number') }}"
                                placeholder="e.g. TANROADS/2025/001"
                                required
                            >

                            @error('contract_number')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Contract Amount --}}
                        <div class="form-group">

                            <label>
                                Contract Amount
                                <span class="required">*</span>
                            </label>

                            <div class="amount-wrap">

                                <input
                                    type="text"
                                    id="contract_amount"
                                    name="contract_amount"
                                    value="{{ old('contract_amount') }}"
                                    placeholder="0"
                                    inputmode="decimal"
                                    required
                                >

                                <span class="currency-tag">
                                    TSh
                                </span>

                            </div>

                            @error('contract_amount')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Start Date --}}
                        <div class="form-group">

                            <label>
                                Start Date
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                name="start_date"
                                value="{{ old('start_date') }}"
                                required
                            >

                            @error('start_date')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- End Date --}}
                        <div class="form-group">

                            <label>
                                End Date
                                <span class="optional">(Optional)</span>
                            </label>

                            <input
                                type="date"
                                name="end_date"
                                value="{{ old('end_date') }}"
                            >

                            @error('end_date')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                <div class="form-divider"></div>


                {{-- ====================================================
                     ACTIONS
                ===================================================== --}}
                <div class="form-actions">

                    <div class="action-note">

                        <svg width="15" height="15"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 8v4"/>
                            <path d="M12 16h.01"/>

                        </svg>

                        Fields marked with <strong>*</strong> are required.

                    </div>


                    <div class="action-buttons">

                        <a href="{{ route('projects.index') }}"
                           class="btn-cancel">

                            <svg width="15" height="15"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path d="M19 12H5"/>
                                <path d="M12 19l-7-7 7-7"/>

                            </svg>

                            Cancel

                        </a>


                        <button type="submit" class="btn-submit">

                            <svg width="15" height="15"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path d="M12 5v14"/>
                                <path d="M5 12h14"/>

                            </svg>

                            Save Project

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>


<script>
    const amountInput = document.getElementById('contract_amount');

    if (amountInput) {

        amountInput.addEventListener('input', function () {

            let value = this.value.replace(/,/g, '');

            if (value === '') {
                return;
            }

            if (isNaN(value)) {
                return;
            }

            this.value = Number(value).toLocaleString('en-US');
        });

    }
</script>

@endsection
