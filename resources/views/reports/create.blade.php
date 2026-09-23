@extends('layouts.app')

@section('title', 'Upload Report')

@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">
        Dashboard
    </a>
    <a href="{{ route('reports.my') }}">
        Back to My Reports
    </a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        :root {
            --navy: #1f3a5f;
            --navy-dark: #16283f;
            --navy-soft: #edf2f7;
            --gold: #C9A84C;
            --gold-dark: #b18f36;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #e5e7eb;
            --background: #f4f6f9;
            --white: #ffffff;
            --danger: #b91c1c;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            margin: 0;
            color: var(--text);
        }

        .report-page {
            max-width: 980px;
            margin: 0 auto;
            padding: 20px;
        }

        /* =========================================
           MAIN CARD
        ========================================= */

        .report-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        }

        /* =========================================
           HEADER
        ========================================= */

        .report-header {
            position: relative;
            padding: 28px 32px;
            background: linear-gradient(135deg,
                    var(--navy),
                    var(--navy-dark));
            color: #fff;
            overflow: hidden;
        }

        .report-header::after {
            content: '';
            position: absolute;
            width: 220px;
            height: 220px;
            right: -70px;
            top: -100px;
            border-radius: 50%;
            border: 1px solid rgba(201, 168, 76, 0.18);
            box-shadow:
                0 0 0 25px rgba(201, 168, 76, 0.04),
                0 0 0 50px rgba(201, 168, 76, 0.025);
        }

        .header-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-icon {
            width: 50px;
            height: 50px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(201, 168, 76, 0.14);
            border: 1px solid rgba(201, 168, 76, 0.35);
            color: var(--gold);
        }

        .header-icon svg {
            width: 25px;
            height: 25px;
        }

        .header-text h2 {
            margin: 0;
            font-size: 21px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .header-text p {
            margin: 5px 0 0;
            color: rgba(255, 255, 255, 0.72);
            font-size: 13px;
        }

        .header-tag {
            margin-left: auto;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.82);
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .header-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #52c878;
            box-shadow: 0 0 0 3px rgba(82, 200, 120, 0.12);
        }

        /* =========================================
           CARD BODY
        ========================================= */

        .report-body {
            padding: 32px;
        }

        /* =========================================
           VALIDATION
        ========================================= */

        .error-box {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 13px 15px;
            border-radius: 9px;
            margin-bottom: 28px;
            font-size: 13px;
        }

        .error-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 17px;
        }

        .error-box li+li {
            margin-top: 4px;
        }

        /* =========================================
           SECTION HEADERS
        ========================================= */

        .section-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .section-number {
            width: 29px;
            height: 29px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: var(--navy);
            color: var(--gold);
            font-size: 11px;
            font-weight: 700;
        }

        .section-heading-text h3 {
            margin: 0;
            color: var(--navy);
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.55px;
        }

        .section-heading-text p {
            margin: 3px 0 0;
            color: #9ca3af;
            font-size: 12px;
        }

        .section-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 32px 0;
        }

        /* =========================================
           FORM
        ========================================= */

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 19px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .form-group.full {
            grid-column: span 2;
        }

        label {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 7px;
        }

        label .required {
            color: #b91c1c;
            margin-left: 2px;
        }

        input[type="text"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            color: #111827;
            background: #fff;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        input[type="text"]:hover,
        input[type="file"]:hover,
        select:hover,
        textarea:hover {
            border-color: #b8bec7;
        }

        input[type="text"]:focus,
        input[type="file"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.12);
        }

        select {
            appearance: none;
            -webkit-appearance: none;
            padding-right: 38px;
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
            min-height: 90px;
            line-height: 1.55;
        }

        input[type="file"] {
            padding: 8px 10px;
            color: #6b7280;
            cursor: pointer;
            background: #fafafa;
        }

        input[type="file"]::file-selector-button {
            margin-right: 10px;
            padding: 7px 11px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #fff;
            color: var(--navy);
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .file-hint {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 5px;
        }

        /* =========================================
           UPLOAD ACTIONS
        ========================================= */

        .form-actions {
            grid-column: span 2;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 5px;
            padding-top: 22px;
            border-top: 1px solid var(--border);
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--gold);
            color: #1f2937;
            padding: 11px 20px;
            border: 1px solid var(--gold);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition:
                background 0.2s ease,
                border-color 0.2s ease,
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .btn-submit:hover {
            background: var(--gold-dark);
            border-color: var(--gold-dark);
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(201, 168, 76, 0.22);
        }

        .btn-submit svg {
            width: 16px;
            height: 16px;
        }

        .btn-cancel {
            color: #6b7280;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            padding: 11px 15px;
            border-radius: 8px;
            transition:
                background 0.2s ease,
                color 0.2s ease;
        }

        .btn-cancel:hover {
            background: #f3f4f6;
            color: #374151;
        }

        /* =========================================
           GENERATE SECTION
        ========================================= */

        .generate-block {
            position: relative;
            background: linear-gradient(135deg,
                    #f8fafc,
                    #f5f7fa);
            border: 1px solid #dfe5ec;
            border-radius: 12px;
            padding: 24px;
            overflow: hidden;
        }

        .generate-block::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--gold);
        }

        .generate-heading {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            margin-bottom: 20px;
        }

        .generate-icon {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: rgba(201, 168, 76, 0.13);
            color: var(--gold-dark);
            border: 1px solid rgba(201, 168, 76, 0.25);
        }

        .generate-icon svg {
            width: 19px;
            height: 19px;
        }

        .generate-heading h3 {
            margin: 0;
            color: var(--navy);
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .generate-desc {
            font-size: 12px;
            color: #6b7280;
            margin: 4px 0 0;
            line-height: 1.5;
        }

        .generate-block .form-grid {
            gap: 17px;
        }

        .btn-generate {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--navy);
            color: #fff;
            padding: 11px 18px;
            border: 1px solid var(--navy);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition:
                background 0.2s ease,
                border-color 0.2s ease,
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .btn-generate:hover {
            background: var(--navy-dark);
            border-color: var(--navy-dark);
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(31, 58, 95, 0.18);
        }

        .btn-generate svg {
            width: 16px;
            height: 16px;
            color: var(--gold);
        }

        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 700px) {
            .report-page {
                padding: 12px;
            }

            .report-header {
                padding: 23px 20px;
            }

            .header-content {
                align-items: flex-start;
            }

            .header-tag {
                display: none;
            }

            .report-body {
                padding: 22px 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full,
            .form-actions {
                grid-column: span 1;
            }

            .generate-block {
                padding: 20px;
            }

            .form-actions {
                flex-wrap: wrap;
            }

            .btn-submit,
            .btn-generate {
                width: 100%;
            }

            .btn-cancel {
                text-align: center;
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>

    <div class="report-page">

        <div class="report-card">

            {{-- HEADER --}}
            <div class="report-header">
                <div class="header-content">

                    <div class="header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="M12 18v-6" />
                            <path d="M9 15l3-3 3 3" />
                        </svg>
                    </div>

                    <div class="header-text">
                        <h2>Upload Report</h2>
                        <p>Upload an existing report or generate one directly from system data.</p>
                    </div>

                    <div class="header-tag">
                        <span class="header-dot"></span>
                        Report Management
                    </div>

                </div>
            </div>

            <div class="report-body">

                {{-- VALIDATION ERRORS --}}
                @if ($errors->any())
                    <div class="error-box">

                        <svg class="error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif

                {{-- =========================================
                 UPLOAD SECTION
            ========================================== --}}

                <div class="section-heading">

                    <div class="section-number">
                        01
                    </div>

                    <div class="section-heading-text">
                        <h3>Upload Report</h3>
                        <p>Provide the project report and supporting information</p>
                    </div>

                </div>

                <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">

                    @csrf

                    <div class="form-grid">

                        {{-- PROJECT --}}
                        <div class="form-group full">

                            <label>
                                Project
                                <span class="required">*</span>
                            </label>

                            <select name="project_id" required>
                                <option value="" disabled selected>
                                    Select project
                                </option>

                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->project_name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        {{-- TITLE --}}
                        <div class="form-group full">

                            <label>
                                Report Title
                                <span class="required">*</span>
                            </label>

                            <input type="text" name="title" value="{{ old('title') }}"
                                placeholder="e.g. Q2 Financial Summary" required>

                        </div>

                        {{-- TYPE --}}
                        <div class="form-group">

                            <label>
                                Report Type
                                <span class="required">*</span>
                            </label>

                            <select name="type" required>

                                <option value="" disabled selected>
                                    Select type
                                </option>

                                @foreach ($types as $type)
                                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                        {{-- FILE --}}
                        <div class="form-group">

                            <label>
                                File
                                <span class="required">*</span>
                            </label>

                            <input type="file" name="file" accept=".pdf,.xlsx,.xls" required>

                            <span class="file-hint">
                                PDF or Excel · maximum 10MB
                            </span>

                        </div>

                        {{-- NOTES --}}
                        <div class="form-group full">

                            <label>
                                Notes
                                <span style="font-weight:400; color:#9ca3af;">
                                    (optional)
                                </span>
                            </label>

                            <textarea name="notes" rows="3" placeholder="Any additional context about this report…">{{ old('notes') }}</textarea>

                        </div>

                        {{-- ACTIONS --}}
                        <div class="form-actions">

                            <button type="submit" class="btn-submit">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="17 8 12 3 7 8" />
                                    <line x1="12" y1="3" x2="12" y2="15" />
                                </svg>

                                Upload Report

                            </button>

                            <a href="{{ route('reports.my') }}" class="btn-cancel">
                                Cancel
                            </a>

                        </div>

                    </div>

                </form>

                <hr class="section-divider">

                {{-- =========================================
                 GENERATE SECTION
            ========================================== --}}

                <div class="generate-block">

                    <div class="generate-heading">

                        <div class="generate-icon">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                            </svg>

                        </div>

                        <div>
                            <h3>Generate Automatically</h3>

                            <p class="generate-desc">
                                Pull live data from the system and generate a formatted PDF report instantly.
                            </p>
                        </div>

                    </div>

                    <form method="POST" action="{{ route('reports.generate') }}">

                        @csrf

                        <div class="form-grid">

                            {{-- PROJECT --}}
                            <div class="form-group">

                                <label>
                                    Project
                                    <span class="required">*</span>
                                </label>

                                <select name="project_id" required>

                                    <option value="" disabled selected>
                                        Select project
                                    </option>

                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">
                                            {{ $project->project_name }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            {{-- REPORT TYPE --}}
                            <div class="form-group">

                                <label>
                                    Report Type
                                    <span class="required">*</span>
                                </label>

                                <select name="type" required>

                                    <option value="" disabled selected>
                                        Select type
                                    </option>

                                    @foreach ($types as $type)
                                        <option value="{{ $type }}">
                                            {{ $type }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            {{-- GENERATE BUTTON --}}
                            <div class="form-group full">

                                <button type="submit" class="btn-generate">

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                    </svg>

                                    Generate from System Data

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection
