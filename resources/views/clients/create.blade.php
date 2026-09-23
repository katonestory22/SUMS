@extends('layouts.app')

@section('title', 'Add Client')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">

        Dashboard
    </a>

    <span class="nav-separator">/</span>

    <a href="{{ route('clients.index') }}">
        Clients
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
    }

    /* ================================================================
       PAGE WRAPPER
    ================================================================= */

    .client-page {
        width: 100%;
        max-width: 980px;
        margin: 0 auto;
        padding: 20px;
    }


    /* ================================================================
       SUB NAV
    ================================================================= */

    .nav-separator {
        color: #b8bec8;
        margin: 0 4px;
    }

    /* ================================================================
       MAIN CARD
    ================================================================= */

    .client-card {
        background: #ffffff;
        border: 1px solid #e7eaf0;
        border-radius: 14px;
        box-shadow: 0 6px 22px rgba(16, 24, 40, 0.055);
        overflow: hidden;
    }


    /* ================================================================
       CARD HEADER
    ================================================================= */

    .client-card-header {
        position: relative;
        padding: 25px 28px;
        background: linear-gradient(135deg, #1f3a5f, #16283f);
        overflow: hidden;
    }

    .client-card-header::after {
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

    .client-card-header::before {
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

    .client-heading {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .client-avatar {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        background: rgba(201, 168, 76, 0.15);
        border: 1px solid rgba(201, 168, 76, 0.38);
        color: #e3c66f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        font-weight: 800;
        letter-spacing: 0.5px;
        flex-shrink: 0;
    }

    .client-heading h1 {
        margin: 0;
        color: #ffffff;
        font-size: 22px;
        line-height: 1.2;
        font-weight: 800;
        letter-spacing: -0.3px;
    }

    .client-heading p {
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

    .client-card-body {
        padding: 28px;
    }


    /* ================================================================
       VALIDATION SUMMARY
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
       INPUTS
    ================================================================= */

    .form-group input {
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

    .form-group input:hover {
        border-color: #c8ced8;
    }

    .form-group input:focus {
        border-color: #1f3a5f;
        box-shadow: 0 0 0 3px rgba(31, 58, 95, 0.08);
        background: #ffffff;
    }

    .form-group input::placeholder {
        color: #b1b8c4;
    }


    /* ================================================================
       FIELD HINTS
    ================================================================= */

    .field-hint {
        margin-top: 6px;
        color: #98a2b3;
        font-size: 11px;
        line-height: 1.45;
    }


    /* ================================================================
       ERRORS
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
       DIVIDER
    ================================================================= */

    .form-divider {
        height: 1px;
        margin: 5px 0 28px;
        background: #eaecf0;
    }


    /* ================================================================
       FORM ACTIONS
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
    .submit-btn {
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

    .submit-btn {
        border: 1px solid #C9A84C;
        background: #C9A84C;
        color: #16283f;
        box-shadow: 0 6px 15px rgba(201, 168, 76, 0.20);
    }

    .submit-btn:hover {
        background: #d8b95e;
        border-color: #d8b95e;
        transform: translateY(-1px);
        box-shadow: 0 9px 20px rgba(201, 168, 76, 0.27);
    }

    .submit-btn:active {
        transform: translateY(0);
    }


    /* ================================================================
       RESPONSIVE
    ================================================================= */

    @media (max-width: 700px) {

        .client-page {
            padding: 15px;
        }

        .client-card-header {
            padding: 21px;
        }

        .client-card-body {
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
        .submit-btn {
            flex: 1;
        }
    }


    @media (max-width: 480px) {

        .client-heading {
            gap: 12px;
        }

        .client-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            font-size: 15px;
        }

        .client-heading h1 {
            font-size: 19px;
        }

        .client-heading p {
            font-size: 11.5px;
        }

        .client-card-body {
            padding: 17px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-cancel,
        .submit-btn {
            width: 100%;
        }
    }
</style>


<div class="client-page">

    {{-- ================================================================
         MAIN CARD
    ================================================================= --}}
    <div class="client-card">

        {{-- ============================================================
             HEADER
        ============================================================= --}}
        <div class="client-card-header">

            <div class="client-heading">

                <div class="client-avatar" id="avatarInitials">
                    CL
                </div>

                <div>
                    <h1>New Client</h1>

                    <p>
                        Add a client and keep their contact information
                        organized in one place.
                    </p>

                    <div class="heading-live">
                        <span class="heading-live-dot"></span>
                        Client Management
                    </div>
                </div>

            </div>

        </div>


        {{-- ============================================================
             BODY
        ============================================================= --}}
        <div class="client-card-body">

            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="validation-errors">

                    <div class="validation-errors-title">

                        <svg width="17" height="17" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 8v4"/>
                            <path d="M12 16h.01"/>
                        </svg>

                        Please correct the following errors
                    </div>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif


            <form method="POST" action="{{ route('clients.store') }}">
                @csrf


                {{-- ====================================================
                     CLIENT INFORMATION
                ===================================================== --}}
                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-number">
                            01
                        </div>

                        <div>
                            <h2>Client Information</h2>

                            <p>
                                Basic identification details
                            </p>
                        </div>

                    </div>


                    <div class="form-grid">

                        {{-- First Name --}}
                        <div class="form-group">

                            <label>
                                First Name
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                id="firstName"
                                value="{{ old('first_name') }}"
                                placeholder="Enter first name"
                                autocomplete="given-name"
                                required
                            >

                            @error('first_name')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Middle Name --}}
                        <div class="form-group">

                            <label>
                                Middle Name
                                <span class="optional">(Optional)</span>
                            </label>

                            <input
                                type="text"
                                name="middle_name"
                                value="{{ old('middle_name') }}"
                                placeholder="Enter middle name"
                                autocomplete="additional-name"
                            >

                        </div>


                        {{-- Last Name --}}
                        <div class="form-group">

                            <label>
                                Last Name
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="last_name"
                                id="lastName"
                                value="{{ old('last_name') }}"
                                placeholder="Enter last name"
                                autocomplete="family-name"
                                required
                            >

                            @error('last_name')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Address --}}
                        <div class="form-group full">

                            <label>
                                Address
                                <span class="optional">(Optional)</span>
                            </label>

                            <input
                                type="text"
                                name="address"
                                value="{{ old('address') }}"
                                placeholder="Enter physical or mailing address"
                                autocomplete="street-address"
                            >

                        </div>

                    </div>

                </div>


                <div class="form-divider"></div>


                {{-- ====================================================
                     CONTACT INFORMATION
                ===================================================== --}}
                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-number">
                            02
                        </div>

                        <div>
                            <h2>Contact Information</h2>

                            <p>
                                How the client can be reached
                            </p>
                        </div>

                    </div>


                    <div class="form-grid">

                        {{-- Email --}}
                        <div class="form-group">

                            <label>
                                Email Address
                                <span class="optional">(Optional)</span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="client@example.com"
                                autocomplete="email"
                            >

                            <span class="field-hint">
                                Used for communication and notifications.
                            </span>

                            @error('email')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Phone --}}
                        <div class="form-group">

                            <label>
                                Phone Number
                                <span class="optional">(Optional)</span>
                            </label>

                            <input
                                type="text"
                                name="phone_number"
                                value="{{ old('phone_number') }}"
                                placeholder="+255 7XX XXX XXX"
                                autocomplete="tel"
                            >

                            <span class="field-hint">
                                Include country code if applicable.
                            </span>

                            @error('phone_number')
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

                        <svg width="15" height="15" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 8v4"/>
                            <path d="M12 16h.01"/>
                        </svg>

                        Fields marked with <strong>*</strong> are required.

                    </div>


                    <div class="action-buttons">

                        <a href="{{ route('clients.index') }}"
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


                        <button type="submit" class="submit-btn">

                            <svg width="15" height="15"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path d="M12 5v14"/>
                                <path d="M5 12h14"/>

                            </svg>

                            Save Client

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>


<script>
    const firstName = document.getElementById('firstName');
    const lastName = document.getElementById('lastName');
    const avatar = document.getElementById('avatarInitials');

    function updateAvatar() {
        const f = firstName.value.trim().charAt(0) || '';
        const l = lastName.value.trim().charAt(0) || '';

        avatar.textContent = (f + l).toUpperCase() || 'CL';
    }

    firstName.addEventListener('input', updateAvatar);
    lastName.addEventListener('input', updateAvatar);

    updateAvatar();
</script>

@endsection
