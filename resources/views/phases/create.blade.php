@extends('layouts.app')

@section('title', 'Add Phase')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('projects.show', $project) }}">Back to Project</a>
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
            max-width: 640px;
            margin: 0 auto;
            background: #ffffff;
            padding: 35px 40px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #111827;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 25px;
        }

        .project-tag {
            font-size: 13px;
            color: #374151;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 18px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #374151;
        }

        input {
            padding: 10px 12px;
            border: 1px solid #dcdfe6;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s ease;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
        }

        .btn {
            margin-top: 10px;
            padding: 10px 18px;
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn:hover {
            background-color: #1e40af;
        }

        .alert-error {
            background: #fff5f5;
            color: #c53030;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>


    <div class="card">

        <div class="title">Create Phase</div>

        <div class="project-tag">
            Project: {{ $project->project_name }}
        </div>

        <div class="subtitle">
            Define a stage within the project lifecycle
        </div>

        {{-- ERRORS --}}
        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('phases.store', $project->id) }}">
            @csrf

            {{-- IMPORTANT: pass project_id manually --}}
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div class="form-group">
                <label>Phase Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label>Weight (%)</label>
                <input type="number" name="weight_percentage" value="{{ old('weight_percentage') }}" min="1"
                    max="100" required>
            </div>

            <button class="btn">Save Phase</button>

        </form>

    </div>

@endsection
