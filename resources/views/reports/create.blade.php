@extends('layouts.app')

@section('title', 'Upload Report')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('reports.create') }}">Upload Report</a>
@endsection

@section('content')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .report-card {
            max-width: 720px;
            margin: 0 auto;
            background: white;
            border-radius: 14px;
            padding: 35px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .section-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 28px;
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

        input,
        select,
        textarea {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            background: white;
            transition: .2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 28px 0;
        }

        .generate-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .generate-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            border: none;
            padding: 11px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-generate {
            background: #111827;
            color: white;
            border: none;
            padding: 11px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-generate:hover {
            background: #1f2937;
        }
    </style>

    <div class="report-card">

        <div class="section-title">Upload Report</div>
        <div class="section-subtitle">Manually upload a PDF or Excel report for a project</div>

        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">

                <div class="form-group full">
                    <label>Project *</label>
                    <select name="project_id" required>
                        <option value="" disabled selected>Select project…</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->project_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full">
                    <label>Report Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Q2 Financial Summary"
                        required>
                </div>

                <div class="form-group">
                    <label>Report Type *</label>
                    <select name="type" required>
                        <option value="" disabled selected>Select type…</option>
                        @foreach ($types as $type)
                            <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>File (PDF or Excel) *</label>
                    <input type="file" name="file" accept=".pdf,.xlsx,.xls" required>
                    <small style="color:#6b7280; font-size:12px; margin-top:4px;">Max 10MB</small>
                </div>

                <div class="form-group full">
                    <label>Notes (Optional)</label>
                    <textarea name="notes" rows="3" placeholder="Any additional context…">{{ old('notes') }}</textarea>
                </div>

                <div class="form-group full">
                    <button type="submit" class="btn-primary">Upload Report</button>
                </div>

            </div>
        </form>

        <hr class="divider">

        <div class="generate-title">Generate Report Automatically</div>
        <div class="generate-subtitle">Pull live data from the system and generate a formatted PDF report instantly</div>

        <form method="POST" action="{{ route('reports.generate') }}">
            @csrf
            <div class="form-grid">

                <div class="form-group">
                    <label>Project *</label>
                    <select name="project_id" required>
                        <option value="" disabled selected>Select project…</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Report Type *</label>
                    <select name="type" required>
                        <option value="" disabled selected>Select type…</option>
                        @foreach ($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full">
                    <button type="submit" class="btn-generate">⚡ Generate from System Data</button>
                </div>

            </div>
        </form>

    </div>
@endsection
