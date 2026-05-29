@extends('layouts.app')

@section('title', 'Admin Panel')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            transition: .2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .desc {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .link {
            font-size: 13px;
            font-weight: 600;
            color: #2563eb;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }
    </style>

    <div class="dashboard-grid">

        <div class="card">
            <div class="title">Users</div>
            <div class="desc">Manage roles and access</div>
            <a href="{{ route('users.index') }}" class="link">View users →</a>
            <a href="{{ route('users.create') }}" class="link">Add users →</a>
        </div>





        <div class="card">
            <div class="title">Activity Logs</div>
            <div class="desc">Track system activity</div>
            <a href="{{ route('activities.progress.history', 1) }}" class="link">View Logs →</a>
        </div>

    </div>

@endsection
