@extends('layouts.app')

@section('title', 'Project Workspace')
@section('page-title')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('technical.dashboard') }}">
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

        .project-wrap {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* HEADER */

        .workspace-header {
            background: white;
            padding: 25px;
            border-radius: 14px;
            margin-bottom: 25px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        }

        .workspace-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .project-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }

        .project-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }

        .btn {
            padding: 11px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: .2s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        /* PHASE */

        .phase-card {
            background: white;
            border-radius: 14px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        }

        .phase-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .phase-name {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .phase-meta {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }

        /* ACTIVITY GRID */

        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
        }

        .activity-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            transition: .2s;
            background: #fff;
        }

        .activity-card:hover {
            border-color: #2563eb;
            transform: translateY(-2px);
        }

        .activity-top {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
            gap: 10px;
        }

        .activity-name {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            line-height: 1.4;
        }

        .badge {
            background: #eff6ff;
            color: #2563eb;
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        /* PROGRESS */

        .progress-wrap {
            margin-bottom: 18px;
        }

        .progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 30px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .progress-fill {
            height: 100%;
            background: #2563eb;
        }

        .progress-text {
            font-size: 13px;
            color: #6b7280;
        }

        /* FORM */

        .form-group {
            margin-bottom: 14px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        input,
        textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
        }

        textarea {
            resize: none;
        }

        /* EVIDENCE */

        .evidence-preview {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .evidence-preview img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        /* ACTIONS */

        .activity-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .history-link {
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .history-link:hover {
            text-decoration: underline;
        }

        .submit-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .submit-btn:hover {
            background: #1d4ed8;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }

        @media(max-width:768px) {

            .workspace-top,
            .phase-header {
                flex-direction: column;
                align-items: flex-start;
            }

        }
    </style>

    <div class="project-wrap">

        {{-- HEADER --}}
        <div class="workspace-header">

            <div class="workspace-top">

                <div>
                    <div class="project-title">
                        {{ $project->project_name }}
                    </div>

                    <div class="project-subtitle">
                        Technical execution workspace for site operations and progress tracking.
                    </div>
                </div>

                <a href="{{ route('phases.create', $project->id) }}" class="btn btn-primary">
                    + Add Phase
                </a>

            </div>

        </div>

        {{-- EMPTY --}}
        @if ($project->phases->isEmpty())
            <div class="phase-card empty-state">

                <h3>No phases added yet</h3>

                <p style="margin:10px 0 20px;">
                    Start structuring the project into measurable construction phases.
                </p>

                <a href="{{ route('phases.create', $project->id) }}" class="btn btn-primary">
                    + Create First Phase
                </a>

            </div>
        @endif

        {{-- PHASES --}}
        @foreach ($project->phases as $phase)
            <div class="phase-card">

                <div class="phase-header">

                    <div>
                        <div class="phase-name">
                            {{ $phase->name }}
                        </div>

                        <div class="phase-meta">
                            Weight: {{ $phase->weight_percentage }}%
                        </div>
                    </div>

                    <a href="{{ route('activities.create', $phase->id) }}" class="btn btn-primary">
                        + Add Activity
                    </a>

                </div>

                {{-- ACTIVITIES --}}
                <div class="activity-grid">

                    @foreach ($phase->activities as $activity)
                        <div class="activity-card">

                            <div class="activity-top">

                                <div class="activity-name">
                                    {{ $activity->name }}
                                </div>

                                <div class="badge">
                                    {{ $activity->weight_percentage }}%
                                </div>

                            </div>

                            {{-- PROGRESS --}}
                            <div class="progress-wrap">

                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $activity->current_progress }}%">
                                    </div>
                                </div>

                                <div class="progress-text">
                                    {{ $activity->current_progress }}% completed
                                </div>

                            </div>

                            {{-- UPDATE FORM --}}
                            <form method="POST" action="{{ route('activities.progress.store', $activity->id) }}"
                                enctype="multipart/form-data">

                                @csrf

                                <div class="form-group">

                                    <label>Update Progress</label>

                                    <input type="number" name="new_percentage" min="0" max="100"
                                        placeholder="Enter new progress %" required>

                                </div>

                                <div class="form-group">

                                    <label>Site Notes</label>

                                    <textarea name="comment" rows="3" placeholder="Describe site progress, delays, inspections or observations..."></textarea>

                                </div>

                                <div class="form-group">

                                    <label>Upload Evidence</label>

                                    <input type="file" name="evidences[]" multiple accept=".jpg,.jpeg,.png,.pdf">

                                </div>

                                {{-- PREVIEW --}}
                                @if ($activity->evidences->count())
                                    <div class="evidence-preview">

                                        @foreach ($activity->evidences->take(4) as $evidence)
                                            <img src="{{ asset('storage/' . $evidence->file_path) }}">
                                        @endforeach

                                    </div>
                                @endif

                                <div class="activity-actions">

                                    <a
                                        href="{{ route('activities.progress.history', $activity->id) }}?project={{ $project->id }}">
                                        View History
                                    </a>

                                    <button class="submit-btn">
                                        Save Update
                                    </button>

                                </div>

                            </form>

                        </div>
                    @endforeach

                </div>

            </div>
        @endforeach

    </div>

@endsection
