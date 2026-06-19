@extends('layouts.app')

@section('title', 'Edit Expense')
@section('page-title', '')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('projects.expenses', $expense->allocation->project_id) }}">Back to Expenses</a>
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
            margin-bottom: 24px;
        }

        .info-strip {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #4b5563;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }

        .info-strip strong {
            color: #111827;
        }

        .audit-notice {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12px;
            color: #92400e;
            margin-bottom: 24px;
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
            transition: border-color 0.2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .reason-field {
            border-color: #f59e0b;
        }

        .reason-field:focus {
            border-color: #d97706;
            box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.1);
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
            font-family: 'Inter', sans-serif;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        /* Edit history */
        .history-section {
            margin-top: 32px;
        }

        .history-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #6b7280;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f0f0f0;
        }

        .history-item {
            padding: 12px 0;
            border-bottom: 1px solid #f9fafb;
            font-size: 13px;
        }

        .history-field {
            font-weight: 700;
            color: #111827;
            text-transform: capitalize;
        }

        .history-meta {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .old-val {
            color: #dc2626;
            text-decoration: line-through;
        }

        .new-val {
            color: #16a34a;
            font-weight: 600;
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
    </style>

    <div class="form-card">

        <div class="form-title">Edit Expense</div>
        <div class="form-subtitle">Correct a recorded project expense</div>

        {{-- Allocation context --}}
        <div class="info-strip">
            <div><strong>Project:</strong> {{ $expense->allocation->project->project_name }}</div>
            <div><strong>Allocation:</strong> TSh {{ number_format($expense->allocation->amount, 0) }}</div>
            <div><strong>Remaining (excl. this):</strong> TSh <span
                    id="remainingDisplay">{{ number_format($remaining, 0) }}</span></div>
            <div><strong>Current Amount:</strong> TSh {{ number_format($expense->amount, 0) }}</div>
        </div>

        <div class="audit-notice">
            ⚠️ All changes are logged with your reason and visible to the director.
        </div>

        <form method="POST" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label>Category *</label>
                    <select name="category" required>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}"
                                {{ old('category', $expense->category) == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Amount (TSh) *</label>
                    <input type="text" name="amount" id="amountDisplay"
                        value="{{ old('amount', number_format($expense->amount, 0, '.', '')) }}" required>
                    <input type="hidden" name="amount_raw" id="amountRaw" value="{{ $expense->amount }}">
                    <div class="balance-preview" id="balancePreview"></div>
                </div>

                <div class="form-group">
                    <label>Date *</label>
                    <input type="date" name="date" value="{{ old('date', $expense->date) }}" required>
                </div>

                <div class="form-group">
                    <label>Replace Receipt (Optional)</label>
                    <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf">
                    @if ($expense->receipt)
                        <small style="color:#6b7280; font-size:11px;">Current receipt on file</small>
                    @endif
                </div>

                <div class="form-group full">
                    <label>Description *</label>
                    <textarea name="description" rows="3" required>{{ old('description', $expense->description) }}</textarea>
                </div>

                <div class="form-group full">
                    <label>Reason for Edit *</label>
                    <textarea name="reason" rows="2" class="reason-field" placeholder="Explain why you are making this change…"
                        required>{{ old('reason') }}</textarea>
                </div>

                <div class="form-group full">
                    <button type="submit" class="btn">Save Changes</button>
                </div>

            </div>
        </form>

        {{-- Edit history --}}
        @if ($expense->edits && $expense->edits->count())
            <div class="history-section">
                <div class="history-title">Edit History</div>
                @foreach ($expense->edits as $edit)
                    <div class="history-item">
                        <span class="history-field">{{ $edit->field_changed }}</span>
                        changed from
                        <span class="old-val">{{ $edit->old_value ?: '—' }}</span>
                        to
                        <span class="new-val">{{ $edit->new_value ?: '—' }}</span>
                        <div class="history-meta">
                            By {{ $edit->editor->name ?? '—' }} ·
                            {{ $edit->created_at->format('d M Y, H:i') }} ·
                            Reason: {{ $edit->reason }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <script>
        const remaining = {{ $remaining }};
        const display = document.getElementById('amountDisplay');
        const preview = document.getElementById('balancePreview');

        display.addEventListener('input', function() {
            let v = this.value.replace(/,/g, '');
            if (!isNaN(v) && v !== '') {
                this.value = Number(v).toLocaleString('en');
                const newBalance = remaining - Number(v);
                if (newBalance < 0) {
                    preview.innerHTML = '❌ Exceeds allocation';
                    preview.className = 'balance-preview balance-danger';
                } else {
                    preview.innerHTML = 'New balance: TSh ' + newBalance.toLocaleString('en');
                    preview.className = 'balance-preview ' + (
                        newBalance <= remaining * 0.2 ? 'balance-danger' :
                        newBalance <= remaining * 0.5 ? 'balance-warning' : 'balance-safe'
                    );
                }
            }
        });

        // Strip commas before submit
        display.closest('form').addEventListener('submit', function() {
            display.value = display.value.replace(/,/g, '');
        });
    </script>

@endsection
