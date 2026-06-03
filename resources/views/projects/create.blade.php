@extends('layouts.app')

@section('title', 'Add Project')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a> |
    <a href="{{ route('projects.index') }}">Projects</a>
@endsection

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .page-card {
            max-width: 820px;
            margin: 0 auto;
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .page-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 25px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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
        select {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: .2s;
            background: white;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .amount-input {
            position: relative;
        }

        .currency-label {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            color: #6b7280;
            pointer-events: none;
        }

        .form-actions {
            grid-column: span 2;
            margin-top: 10px;
        }

        button {
            padding: 11px 20px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 18px;
        }

        @media(max-width:700px) {

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full,
            .form-actions {
                grid-column: span 1;
            }

        }
    </style>


    <div class="page-card">

        <div class="page-title">Project Information</div>
        <div class="page-subtitle">Enter the project details and contract information</div>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST" action="{{ route('projects.store') }}">
            @csrf

            <div class="form-grid">

                <div class="form-group full">

                    <label>Client *</label>

                    <select name="client_id" required>

                        <option value="" disabled selected>Select Client</option>

                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>

                                {{ $client->first_name }} {{ $client->last_name }}

                            </option>
                        @endforeach

                    </select>

                </div>


                <div class="form-group">

                    <label>Project Name *</label>

                    <input type="text" name="project_name" value="{{ old('project_name') }}" required>

                </div>


                <div class="form-group">

                    <label>Project Type *</label>

                    <select name="project_type_id" required>

                        <option value="" disabled selected>Select Project Type</option>

                        @foreach ($projectTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ old('project_type_id') == $type->id ? 'selected' : '' }}>

                                {{ ucfirst($type->name) }}

                            </option>
                        @endforeach

                    </select>

                </div>
                <div class="form-group">
                    <label>Location</label>
                    <select name="location">
                        <option value="" disabled selected>Select Region</option>
                        @php
                            $regions = [
                                'Arusha',
                                'Dar es Salaam',
                                'Dodoma',
                                'Geita',
                                'Iringa',
                                'Kagera',
                                'Katavi',
                                'Kigoma',
                                'Kilimanjaro',
                                'Lindi',
                                'Manyara',
                                'Mara',
                                'Mbeya',
                                'Morogoro',
                                'Mtwara',
                                'Mwanza',
                                'Njombe',
                                'Pemba North',
                                'Pemba South',
                                'Pwani',
                                'Rukwa',
                                'Ruvuma',
                                'Shinyanga',
                                'Simiyu',
                                'Singida',
                                'Songwe',
                                'Tabora',
                                'Tanga',
                                'Unguja North',
                                'Unguja South',
                                'Zanzibar West',
                            ];
                        @endphp
                        @foreach ($regions as $region)
                            <option value="{{ $region }}" {{ old('location') == $region ? 'selected' : '' }}>
                                {{ $region }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">

                    <label>Contract Number *</label>

                    <input type="text" name="contract_number" value="{{ old('contract_number') }}" required>

                </div>


                <div class="form-group amount-input">

                    <label>Contract Amount *</label>

                    <input type="text" id="contract_amount" name="contract_amount" value="{{ old('contract_amount') }}"
                        required>

                    <span class="currency-label">TSh</span>

                </div>


                <div class="form-group">

                    <label>Start Date *</label>

                    <input type="date" name="start_date" value="{{ old('start_date') }}" required>

                </div>


                <div class="form-group">

                    <label>End Date</label>

                    <input type="date" name="end_date" value="{{ old('end_date') }}">

                </div>


                <div class="form-actions">

                    <button type="submit">
                        Save Project
                    </button>

                </div>

            </div>

        </form>

    </div>


    <script>
        const amountInput = document.getElementById('contract_amount');

        amountInput.addEventListener('input', function() {

            let value = this.value.replace(/,/g, '');

            if (value === '') return;

            if (!isNaN(value)) {

                this.value = Number(value).toLocaleString('en');

            }

        });
    </script>

@endsection
