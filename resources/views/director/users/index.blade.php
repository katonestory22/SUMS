@extends('layouts.app')

@section('title', 'Director Users')

@section('sub-nav')
    <a href="{{ route('director.dashboard') }}">Dashboard</a>
    <a href="{{ route('director.users') }}">Users</a>
@endsection

@section('content')

    <style>
        body {
            font-family: Inter, sans-serif;
            background: #f4f6f9;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 18px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            transition: transform .2s ease;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .avatar {
            width: 100%;
            height: 180px;
            border-radius: 10px;
            overflow: hidden;
            background: #f3f4f6;
            margin-bottom: 12px;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .name {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .role {
            display: inline-block;
            padding: 3px 8px;
            font-size: 11px;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            margin-top: 5px;
        }

        .info {
            margin-top: 10px;
            font-size: 13px;
            color: #4b5563;
            line-height: 1.5;
        }

        .label {
            font-weight: 600;
            color: #111827;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }
    </style>

    <div class="container">

        <h2 style="margin-bottom:20px;">System Users</h2>

        <div class="grid">

            @forelse($users as $user)
                <div class="card">

                    <div class="avatar">
                        <img src="{{ asset($user->passport_photo ?? 'images/default.png') }}" alt="user">
                    </div>

                    <div class="name">
                        {{ $user->first_name }} {{ $user->last_name }}
                    </div>

                    <div class="role">
                        {{ ucfirst($user->role) }}
                    </div>

                    <div class="info">
                        <div><span class="label">Email:</span> {{ $user->email }}</div>
                        <div><span class="label">Phone:</span> {{ $user->phone ?? '—' }}</div>
                        <div><span class="label">National ID:</span> {{ $user->national_id ?? '—' }}</div>
                        <div><span class="label">Address:</span> {{ $user->address ?? '—' }}</div>
                    </div>

                </div>

            @empty

                <div class="empty">
                    No users found
                </div>
            @endforelse

        </div>

    </div>

@endsection
