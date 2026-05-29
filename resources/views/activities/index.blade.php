@extends('layouts.app')

@section('title', 'Activities')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('activities.create') }}">Add Activity</a>
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

        .card h2 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #222;
        }

        p {
            font-size: 14px;
            color: #555;
            margin-bottom: 0;
        }

        @media(max-width: 600px) {
            .card {
                padding: 25px;
            }
        }
    </style>

    <div class="card">
        <h2>Activities</h2>
        <p>Track and manage all project activities here.</p>
    </div>

@endsection
