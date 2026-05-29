@extends('layouts.app')

@section('title', 'New Allocation')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('finance.dashboard') }}">Dashboard</a>
    <a href="{{ route('allocations.index') }}">Back to Allocations</a>
@endsection

@section('content')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .wrapper {
            max-width: 820px;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        h2 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 18px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .full {
            grid-column: span 2;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            display: block;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            background: #fff;
            transition: 0.2s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        textarea {
            resize: vertical;
        }

        .btn {
            background: #2563eb;
            color: white;
            padding: 11px 14px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: 0.2s;
            width: 100%;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .hint {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }

        @media (max-width: 700px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: span 1;
            }
        }
    </style>

    <div class="wrapper">

        <div class="card">

            <h2>Allocation Setup</h2>
            <div class="subtitle">Assign budget allocation to a project category</div>

            <form method="POST" action="{{ route('allocations.store') }}">
                @csrf

                <div class="form-grid">

                    {{-- PROJECT --}}
                    <div class="full">
                        <label>Project</label>
                        <select name="project_id" required>
                            <option value="">Select project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">
                                    {{ $project->client->first_name }}
                                    {{ $project->client->last_name }}
                                    — {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- CATEGORY --}}
                    <div>
                        <label>Category</label>
                        <select name="category" required>
                            <option value="">Select category</option>
                            <option value="Labour">Labour</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Travel">Travel</option>
                            <option value="Operations">Operations</option>
                            <option value="Consulting">Consulting</option>
                            <option value="Miscellaneous">Misc</option>
                        </select>
                    </div>

                    {{-- AMOUNT --}}
                    <div>
                        <label>Amount</label>
                        <input type="text" name="amount" id="amount" required>
                    </div>

                    {{-- DATE --}}
                    <div>
                        <label>Allocation Date</label>
                        <input type="date" name="allocation_date" required>
                    </div>

                    {{-- NOTES --}}
                    <div class="full">
                        <label>Notes (optional)</label>
                        <textarea name="notes" rows="3"></textarea>
                    </div>

                    {{-- BUTTON --}}
                    <div class="full">
                        <button class="btn" type="submit">
                            Save Allocation
                        </button>
                    </div>

                </div>
            </form>

        </div>

    </div>

    <script>
        const amountInput = document.getElementById('amount');

        amountInput.addEventListener('input', function() {
            let value = this.value.replace(/,/g, '');
            if (!isNaN(value) && value !== '') {
                this.value = Number(value).toLocaleString('en');
            }
        });
    </script>

@endsection
