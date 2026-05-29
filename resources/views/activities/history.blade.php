@extends('layouts.app')

@section('title', 'Progress History')

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
    <a href="
{{ auth()->user()->role === 'admin'
    ? route('admin.dashboard')
    : (request()->has('project')
        ? route('projects.show', request('project'))
        : route('technical.dashboard')) }}
"
        class="back-btn">
        Back
    </a>
@endsection

@section('content')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .history-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }

        .history-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #111827;
        }

        .history-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 16px;
        }

        .history-card {
            background: #fff;
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            transition: 0.2s ease;
        }

        .history-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.08);
        }

        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
        }

        .progress {
            color: #2563eb;
            font-weight: 700;
        }

        .date {
            font-size: 12px;
            color: #6b7280;
        }

        .meta {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
        }

        .comment {
            margin-top: 10px;
            font-size: 14px;
            color: #111827;
            line-height: 1.4;
        }

        .evidence {
            margin-top: 12px;
        }

        .evidence img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .no-evidence {
            font-size: 12px;
            color: #9ca3af;
            padding: 10px 0;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
    </style>

    <div class="history-wrapper">

        <div class="history-title">
            Progress History
        </div>

        <div class="history-grid">

            @forelse($histories as $history)
                <div class="history-card">

                    <div class="top-row">
                        <div>
                            <span>{{ $history->old_percentage }}%</span>
                            →
                            <span class="progress">{{ $history->new_percentage }}%</span>
                        </div>

                        <div class="date">
                            {{ $history->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <div class="meta">
                        {{ $history->activity->name ?? 'Activity' }}
                        • {{ $history->activity->phase->project->project_name ?? 'Project' }}
                    </div>

                    <div class="comment">
                        {{ $history->comment ?? 'No comment provided' }}
                    </div>

                    <div class="evidence">
                        @if ($history->evidence_path)
                            <img src="{{ asset('storage/' . $history->evidence_path) }}" alt="Evidence">
                        @else
                            <div class="no-evidence">No photos uploaded</div>
                        @endif
                    </div>

                </div>

            @empty

                <div>No history yet</div>
            @endforelse

        </div>

        <div class="pagination">
            {{ $histories->appends(request()->query())->onEachSide(1)->links() }}
        </div>

    </div>

@endsection
