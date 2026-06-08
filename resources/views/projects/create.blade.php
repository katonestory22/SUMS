@extends('layouts.app')

@section('title', 'Add Project')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('projects.index') }}">Back to Projects</a>
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
            max-width: 820px;
            margin: 0 auto;
            background: #fff;
            padding: 35px 40px;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .page-header {
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .page-header h3 {
            font-size: 22px;
            font-weight: 600;
            margin: 0;
            color: #222;
        }

        .page-header p {
            font-size: 14px;
            color: #666;
            margin: 4px 0 0 0;
        }

        .error-box {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 18px;
        }

        .section-label {
            font-size: 11px;
            font-weight: 600;
            color: #2c5282;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin: 24px 0 14px;
        }

        .section-label:first-of-type {
            margin-top: 0;
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

        label .required {
            color: #c53030;
            margin-left: 2px;
        }

        input[type="text"],
        input[type="date"],
        select {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #111827;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            appearance: none;
            -webkit-appearance: none;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        select:focus {
            outline: none;
            border-color: #2c5282;
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.1);
        }

        .amount-wrap {
            position: relative;
        }

        .amount-wrap input {
            padding-right: 46px;
            width: 100%;
            box-sizing: border-box;
        }

        .currency-tag {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-left: none;
            border-radius: 0 6px 6px 0;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            pointer-events: none;
        }

        .form-actions {
            grid-column: span 2;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 8px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .btn-submit {
            background: #2c5282;
            color: #fff;
            padding: 11px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.2s ease;
        }

        .btn-submit:hover {
            background: #1f3d5a;
        }

        .btn-cancel {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            padding: 11px 16px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .btn-cancel:hover {
            background: #f3f4f6;
            color: #374151;
        }

        @media (max-width: 700px) {
            .card {
                padding: 25px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full,
            .form-actions {
                grid-column: span 1;
            }
        }
    </style>

    <div class="card">

        <div class="page-header">
            <h3>Add New Project</h3>
            <p>Enter the project details and contract information</p>
        </div>

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

            <div class="section-label">Client & Project</div>

            <div class="form-grid">

                <div class="form-group full">
                    <label>Client <span class="required">*</span></label>
                    <select name="client_id" required>
                        <option value="" disabled selected>Select client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->first_name }} {{ $client->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Project Name <span class="required">*</span></label>
                    <input type="text" name="project_name" value="{{ old('project_name') }}"
                        placeholder="e.g. Road Rehabilitation Phase 2" required>
                </div>

                <div class="form-group">
                    <label>Project Type <span class="required">*</span></label>
                    <select name="project_type_id" required>
                        <option value="" disabled selected>Select type</option>
                        @foreach ($projectTypes as $type)
                            <option value="{{ $type->id }}" {{ old('project_type_id') == $type->id ? 'selected' : '' }}>
                                {{ ucfirst($type->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full">
                    <label>Location</label>
                    <select name="location">
                        <option value="" disabled selected>Select region</option>
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

            </div>

            <div class="section-label">Contract Details</div>

            <div class="form-grid">

                <div class="form-group">
                    <label>Contract Number <span class="required">*</span></label>
                    <input type="text" name="contract_number" value="{{ old('contract_number') }}"
                        placeholder="e.g. TANROADS/2025/001" required>
                </div>

                <div class="form-group">
                    <label>Contract Amount <span class="required">*</span></label>
                    <div class="amount-wrap">
                        <input type="text" id="contract_amount" name="contract_amount"
                            value="{{ old('contract_amount') }}" placeholder="0" required>
                        <span class="currency-tag">TSh</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Start Date <span class="required">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required>
                </div>

                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Save Project</button>
                    <a href="{{ route('projects.index') }}" class="btn-cancel">Cancel</a>
                </div>

            </div>

        </form>

    </div>

    <script>
        const amountInput = document.getElementById('contract_amount');

        amountInput.addEventListener('input', function() {
            let value = this.value.replace(/,/g, '');
            if (value === '' || isNaN(value)) return;
            this.value = Number(value).toLocaleString('en');
        });
    </script>

@endsection
