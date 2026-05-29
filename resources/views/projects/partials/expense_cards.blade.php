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

    </div>
@endforeach
