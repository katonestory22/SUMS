@php
    $index = $expenses->firstItem(); // Laravel pagination offset start
@endphp

@foreach ($expenses as $expense)
    <div class="expense-card">

        <div class="meta fw-bold text-secondary">
            #{{ $index++ }} • {{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}
        </div>

        <div class="amount">
            TSh {{ number_format($expense->amount, 2) }}
        </div>

        <div class="meta">
            {{ $expense->user->name ?? 'System' }}
        </div>

        <div class="desc">
            {{ $expense->description }}
        </div>

        <div class="meta">
            {{ $expense->allocation->category ?? 'Uncategorized' }}
        </div>

        <div class="receipt">
            @if ($expense->receipt)
                <a href="{{ asset($expense->receipt) }}" target="_blank">
                    View Receipt
                </a>
            @else
                <span class="no-receipt">No receipt uploaded</span>
            @endif
        </div>
        <div style="margin-top: 12px;">
            <a href="{{ route('expenses.edit', $expense) }}"
                style="display:inline-block; padding:6px 14px; border-radius:7px;
              background:#eff6ff; color:#1d4ed8; font-size:12px;
              font-weight:600; text-decoration:none; transition:background 0.2s;">
                ✏️ Edit
            </a>
        </div>
    </div>
@endforeach
