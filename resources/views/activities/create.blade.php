@extends('layouts.app')

@section('title', 'Add Activity')
@section('page-title', '')

@section('sub-nav')
    @php
        $dashboardRoute = match (auth()->user()->role) {
            'admin' => route('admin.dashboard'),
            'director' => route('director.dashboard'),
            'finance' => route('finance.dashboard'),
            'technical' => route('technical.dashboard'),
            default => route('dashboard'),
        };
    @endphp

    <a href="{{ $dashboardRoute }}">
        Dashboard
    </a>
    <a href="{{ url()->previous() }}">
        Back
    </a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .form-card {
            max-width: 760px;
            margin: 0 auto;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: .2s ease;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
        }

        textarea {
            resize: vertical;
            min-height: 90px;
        }

        .file-box {
            border: 2px dashed #cbd5e1;
            padding: 25px;
            border-radius: 10px;
            background: #f8fafc;
            transition: .2s;
        }

        .file-box:hover {
            border-color: #2563eb;
            background: #f9fbff;
        }

        .file-note {
            margin-top: 10px;
            font-size: 12px;
            color: #6b7280;
            line-height: 1.5;
        }

        .btn-submit {
            background: #2563eb;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .btn-submit:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .tips-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e3a8a;
            padding: 16px;
            border-radius: 10px;
            margin-top: 30px;
        }

        .tips-box h4 {
            margin-bottom: 8px;
            font-size: 14px;
        }

        .tips-box ul {
            padding-left: 18px;
            font-size: 13px;
            line-height: 1.7;
        }
    </style>

    <div class="form-card">

        <div class="title">Add Activity</div>

        <div class="subtitle">
            Create a measurable activity under this phase and attach site evidence if available.
        </div>

        {{-- ERRORS --}}
        @if ($errors->any())
            <div class="error-box">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- IMPORTANT --}}
        <form method="POST" action="{{ route('activities.store') }}" enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="phase_id" value="{{ $phase->id }}">

            <div class="form-group">
                <label>Activity Name</label>

                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g Foundation Excavation"
                    required>
            </div>

            <div class="form-group">
                <label>Weight Percentage (%)</label>

                <input type="number" name="weight_percentage" min="1" max="100"
                    value="{{ old('weight_percentage') }}" required>
            </div>

            <div class="form-group">
                <label>Site Evidence</label>

                <div class="file-box">

                    <input type="file" name="evidences[]" multiple accept=".jpg,.jpeg,.png,.pdf">

                    <div class="file-note">
                        Upload photos, inspection documents, progress snapshots or reports from site visits.
                        JPG, PNG and PDF supported.
                    </div>

                </div>
            </div>

            <button type="submit" class="btn-submit">
                Save Activity
            </button>

        </form>

        {{-- TIPS --}}
        <div class="tips-box">

            <h4>Field Suggestions</h4>

            <ul>
                <li>Activities should be measurable and trackable.</li>
                <li>Avoid vague names like “Site Work”.</li>
                <li>Evidence uploads improve accountability and reporting accuracy.</li>
                <li>Total activity weight inside a phase should equal 100%.</li>
            </ul>

        </div>

    </div>

@endsection
