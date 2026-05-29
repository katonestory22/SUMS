@extends('layouts.app')

@section('title', 'Edit Client')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('clients.index') }}">Clients</a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .form-card {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #111827;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 30px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
        }

        .submit-btn {
            margin-top: 25px;
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .submit-btn:hover {
            background: #1d4ed8;
        }

        .error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>

    <div class="form-card">

        <div class="title">
            Edit Client
        </div>

        <div class="subtitle">
            Update client information and contact details
        </div>

        <form action="{{ route('clients.update', $client->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="form-grid">

                <div>
                    <label>First Name</label>

                    <input type="text" name="first_name" value="{{ old('first_name', $client->first_name) }}">

                    @error('first_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label>Middle Name</label>

                    <input type="text" name="middle_name" value="{{ old('middle_name', $client->middle_name) }}">
                </div>

                <div>
                    <label>Last Name</label>

                    <input type="text" name="last_name" value="{{ old('last_name', $client->last_name) }}">

                    @error('last_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label>Phone Number</label>

                    <input type="text" name="phone_number" value="{{ old('phone_number', $client->phone_number) }}">
                </div>

                <div class="full-width">
                    <label>Email</label>

                    <input type="email" name="email" value="{{ old('email', $client->email) }}">
                </div>

                <div class="full-width">
                    <label>Address</label>

                    <input type="text" name="address" value="{{ old('address', $client->address) }}">
                </div>

            </div>

            <button type="submit" class="submit-btn">
                Update Client
            </button>

        </form>

    </div>

@endsection
