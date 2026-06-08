@extends('layouts.app')

@section('title', 'Upload Report')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('reports.my') }}">Back to My Reports</a>
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
            max-width: 720px;
            margin: 0 auto;
            background: #fff;
            padding: 35px 40px;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .page-header {
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
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

        .section-label {
            font-size: 11px;
            font-weight: 600;
            color: #2c5282;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin: 0 0 14px;
        }

        .section-divider {
            border: none;
            border-top: 1px solid #eee;
            margin: 30px 0;
        }

        .generate-block {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 24px;
        }

        .generate-block .section-label {
            margin-bottom: 4px;
        }

        .generate-desc {
            font-size: 13px;
            color: #6b7280;
            margin: 0 0 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full {
            grid-column: span 2;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        label .required {
            color: #c53030;
            margin-left: 2px;
        }

        input[type="text"],
        input[type="file"],
        select,
        textarea {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #111827;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input[type="text"]:focus,
        input[type="file"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2c5282;
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.1);
        }

        input[type="file"] {
            padding: 8px 10px;
            color: #6b7280;
            cursor: pointer;
        }

        .file-hint {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }

        textarea {
            resize: vertical;
        }

        .form-actions {
            grid-column: span 2;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 4px;
        }

        .btn-submit {
            background: #2c5282;
            color: #fff;
            padding: 11px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.2s ease;
        }

        .btn-submit:hover {
            background: #1f3d5a;
        }

        .btn-cancel {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            padding: 11px 16px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .btn-cancel:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-generate {
            background: #1a202c;
            color: #fff;
            padding: 11px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .btn-generate:hover {
            background: #2d3748;
        }

        .error-box {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 18px;
        }

        @media (max-width: 700px) {
            .card {
                padding: 25px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full,
            .form-actions {
                grid-column: span 1;
            }
        }
    </style>

    <div class="card">

        {{-- UPLOAD SECTION --}}
        <div class="page-header">
            <h3>Upload Report</h3>
            <p>Manually upload a PDF or Excel report for a project</p>
        </div>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="section-label">Report Details</div>

        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">

                <div class="form-group full">
                    <label>Project <span class="required">*</span></label>
                    <select name="project_id" required>
                        <option value="" disabled selected>Select project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->project_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full">
                    <label>Report Title <span class="required">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           placeholder="e.g. Q2 Financial Summary" required>
                </div>

                <div class="form-group">
                    <label>Report Type <span class="required">*</span></label>
                    <select name="type" required>
                        <option value="" disabled selected>Select type</option>
                        @foreach ($types as $type)
                            <option value="{{ $type }}"
                                {{ old('type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>File <span class="required">*</span></label>
                    <input type="file" name="file" accept=".pdf,.xlsx,.xls" required>
                    <span class="file-hint">PDF or Excel &mdash; max 10MB</span>
                </div>

                <div class="form-group full">
                    <label>Notes <span style="font-weight:400; color:#9ca3af;">(optional)</span></label>
                    <textarea name="notes" rows="3"
                              placeholder="Any additional context about this report…">{{ old('notes') }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Upload Report</button>
                    <a href="{{ route('reports.my') }}" class="btn-cancel">Cancel</a>
                </div>

            </div>
        </form>

        <hr class="section-divider">

        {{-- GENERATE SECTION --}}
        <div class="generate-block">

            <div class="section-label">Generate Automatically</div>
            <p class="generate-desc">Pull live data from the system and generate a formatted PDF report instantly</p>

            <form method="POST" action="{{ route('reports.generate') }}">
                @csrf
                <div class="form-grid">

                    <div class="form-group">
                        <label>Project <span class="required">*</span></label>
                        <select name="project_id" required>
                            <option value="" disabled selected>Select project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Report Type <span class="required">*</span></label>
                        <select name="type" required>
                            <option value="" disabled selected>Select type</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group full">
                        <button type="submit" class="btn-generate">
                            &#9889; Generate from System Data
                        </button>
                    </div>

                </div>
            </form>

        </div>

    </div>

@endsection
