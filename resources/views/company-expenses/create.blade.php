@extends('layouts.app')
@section('title', 'Record Company Expense')
@section('sub-nav')
    <a href="{{ route('finance.dashboard') }}">Dashboard</a>
    <a href="{{ route('company-expenses.index') }}">Company Expenses</a>
@endsection

@section('content')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .form-card {
            max-width: 720px;
            margin: 0 auto;
            background: white;
            border-radius: 14px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .form-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .form-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 28px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group.full {
            grid-column: span 2;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        input,
        select,
        textarea {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 14px;
            background: white;
            font-family: 'Inter', sans-serif;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 11px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }

        .btn:hover {
            background: #1d4ed8;
        }
    </style>

    <div class="form-card">
        <div class="form-title">Record Company Expense</div>
        <div class="form-subtitle">Log an operational expense for the company</div>

        <form method="POST" action="{{ route('company-expenses.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">

                <div class="form-group full">
                    <label>Expense Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        placeholder="e.g. Office Rent June 2025" required>
                </div>

                <div class="form-group">
                    <label>Category *</label>
                    <select name="category" required>
                        <option value="" disabled selected>Select category…</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Amount (TSh) *</label>
                    <input type="text" name="amount" id="amount" value="{{ old('amount') }}" required>
                </div>

                <div class="form-group">
                    <label>Date *</label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                </div>

                <div class="form-group">
                    <label>Receipt / Invoice (Optional)</label>
                    <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf">
                    <small style="color:#9ca3af; font-size:11px;">JPG, PNG or PDF — max 5MB</small>
                </div>

                <div class="form-group full">
                    <label>Description (Optional)</label>
                    <textarea name="description" rows="3" placeholder="Any additional notes…">{{ old('description') }}</textarea>
                </div>

                <div class="form-group full">
                    <button type="submit" class="btn">Save Expense</button>
                </div>

            </div>
        </form>
    </div>

    <script>
        const amt = document.getElementById('amount');
        amt.addEventListener('input', function() {
            let v = this.value.replace(/,/g, '');
            if (!isNaN(v) && v !== '') this.value = Number(v).toLocaleString('en');
        });
    </script>
@endsection
