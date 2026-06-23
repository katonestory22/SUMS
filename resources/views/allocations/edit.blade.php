@extends('layouts.app')

@section('title', 'Edit Allocation')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('allocations.index') }}">Income</a>
@endsection

@section('content')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .form-card {
            max-width: 620px;
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
            margin-bottom: 24px;
        }

        .info-strip {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #4b5563;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .info-strip strong {
            color: #111827;
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
        textarea {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 14px;
            background: white;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .balance-preview {
            font-size: 12px;
            margin-top: 4px;
        }

        .balance-safe {
            color: #16a34a;
        }

        .balance-warning {
            color: #f59e0b;
        }

        .balance-danger {
            color: #dc2626;
            font-weight: 600;
        }

        .btn {
            background: #2c5282;
            color: white;
            border: none;
            padding: 11px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            font-family: 'Inter', sans-serif;
        }

        .btn:hover {
            background: #1f3d5a;
        }
    </style>

    <div class="form-card">

        <div class="form-title">Edit Allocation</div>
        <div class="form-subtitle">Update the income received for this project</div>

        {{-- Context info --}}
        <div class="info-strip">
            <div>
                <strong>Project:</strong>
                {{ $allocation->project->project_name }}
            </div>
            <div>
                <strong>Client:</strong>
                {{ $allocation->project->client->first_name }}
                {{ $allocation->project->client->last_name }}
            </div>
            <div>
                <strong>Already Spent:</strong>
                TSh {{ number_format($spent, 0) }}
            </div>
            <div>
                <strong>Contract Balance Available:</strong>
                TSh
                {{ number_format($allocation->project->contract_amount - $allocation->project->allocations()->where('id', '!=', $allocation->id)->sum('amount'), 0) }}
            </div>
        </div>

        <form method="POST" action="{{ route('allocations.update', $allocation) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label>Amount (TSh) *</label>
                    <input type="text" name="amount" id="amountDisplay"
                        value="{{ old('amount', number_format($allocation->amount, 0, '.', '')) }}" required>
                    <div class="balance-preview" id="balancePreview"></div>
                    @error('amount')
                        <small style="color:#dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Allocation Date *</label>
                    <input type="date" name="allocation_date"
                        value="{{ old('allocation_date', $allocation->allocation_date) }}" required>
                </div>

                <div class="form-group full">
                    <label>Notes (Optional)</label>
                    <textarea name="notes" rows="3">{{ old('notes', $allocation->notes) }}</textarea>
                </div>

                <div class="form-group full">
                    <button type="submit" class="btn">Save Changes</button>
                </div>

            </div>
        </form>
    </div>

    <script>
        const spent = {{ $spent }};
        const display = document.getElementById('amountDisplay');
        const preview = document.getElementById('balancePreview');

        display.addEventListener('input', function() {
            let v = this.value.replace(/,/g, '');
            if (!isNaN(v) && v !== '') {
                this.value = Number(v).toLocaleString('en');
                const newRemaining = Number(v) - spent;
                if (newRemaining < 0) {
                    preview.innerHTML = '❌ Less than already spent (TSh ' + spent.toLocaleString('en') + ')';
                    preview.className = 'balance-preview balance-danger';
                } else {
                    preview.innerHTML = 'Remaining after spent: TSh ' + newRemaining.toLocaleString('en');
                    preview.className = 'balance-preview ' + (
                        newRemaining <= Number(v) * 0.2 ? 'balance-danger' :
                        newRemaining <= Number(v) * 0.5 ? 'balance-warning' : 'balance-safe'
                    );
                }
            }
        });

        display.closest('form').addEventListener('submit', function() {
            display.value = display.value.replace(/,/g, '');
        });
    </script>

@endsection
