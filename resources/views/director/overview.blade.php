@extends('layouts.app')

@section('title', $project->project_name . ' — Overview')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a>
@endsection

@section('content')

    <style>
        body {
            font-family: Inter, sans-serif;
            background: #f4f6f9;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .stat {
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .stat h4 {
            font-size: 12px;
            color: #6b7280;
        }

        .stat p {
            font-size: 18px;
            font-weight: 700;
        }

        .section-title {
            margin-bottom: 10px;
            font-weight: 700;
        }

        .item {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 12px;
            color: white;
            background: #2563eb;
        }
    </style>

    <div class="container">

        {{-- HEADER --}}
        <div class="card">
            <h2>{{ $project->project_name }}</h2>
            <p>{{ $project->client->first_name }} {{ $project->client->last_name }}</p>
        </div>

        {{-- FINANCE --}}
        <div class="card">
            <div class="section-title">Finance Overview</div>
            <div class="grid">
                <div class="stat">
                    <h4>Contract</h4>
                    <p>TSh {{ number_format($project->contract_amount, 2) }}</p>
                </div>
                <div class="stat">
                    <h4>Allocated</h4>
                    <p>TSh {{ number_format($project->totalAllocated(), 2) }}</p>
                </div>
                <div class="stat">
                    <h4>Spent</h4>
                    <p>TSh {{ number_format($project->totalExpenses(), 2) }}</p>
                </div>
                <div class="stat">
                    <h4>Balance</h4>
                    <p>TSh {{ number_format($project->remainingBalance(), 2) }}</p>
                </div>
            </div>
        </div>

        {{-- ALLOCATIONS --}}
        <div class="card">
            <div class="section-title">Allocations</div>
            @foreach ($project->allocations as $allocation)
                <div class="item">
                    <strong>{{ $allocation->category }}</strong>
                    <span style="float:right;">TSh {{ number_format($allocation->amount, 2) }}</span>
                    <div style="font-size:12px;color:#6b7280;">{{ $allocation->allocation_date }}</div>
                </div>
            @endforeach
        </div>

        {{-- EXPENSES --}}
        <div class="card">
            <div class="section-title">Expense History</div>
            @foreach ($project->allocations as $allocation)
                @foreach ($allocation->expenses as $expense)
                    <div class="item">
                        <strong>{{ $expense->description }}</strong>
                        <span style="float:right;">TSh {{ number_format($expense->amount, 2) }}</span>
                        <div style="font-size:12px;color:#6b7280;">{{ $expense->date }}</div>

                        @if ($expense->receipt)
                            @php $receiptFile = basename($expense->receipt); @endphp
                            <div style="margin-top:10px; display:flex; gap:10px; align-items:center;">

                                <img src="{{ asset('storage/receipts/' . $receiptFile) }}"
                                    style="width:60px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #e5e7eb;"
                                    onerror="this.style.display='none'" />

                                <a href="{{ route('file.download', ['type' => 'receipts', 'file' => $receiptFile]) }}"
                                    style="font-size:12px; color:#2563eb; text-decoration:none;">
                                    ⬇ Download Receipt
                                </a>
                            </div>
                        @else
                            <div style="font-size:12px;color:#9ca3af;margin-top:6px;">No receipt uploaded</div>
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>

        {{-- PROGRESS --}}
        <div class="card">
            <div class="section-title">Project Progress</div>
            <p><strong>{{ $project->progress }}%</strong></p>

            @foreach ($project->phases as $phase)
                <div class="item">
                    <strong>{{ $phase->name }}</strong>
                    <span class="badge">{{ round($phase->progress()) }}%</span>

                    @foreach ($phase->activities as $activity)
                        <div style="margin-left:15px; font-size:13px; margin-top:8px;">
                            • {{ $activity->name }} ({{ $activity->current_progress }}%)

                            @if ($activity->evidences->count())
                                <div style="margin-top:8px; display:flex; gap:8px; flex-wrap:wrap;">

                                    @foreach ($activity->evidences->take(3) as $evidence)
                                        @php $evidenceFile = basename($evidence->file_path); @endphp
                                        <div style="display:flex; flex-direction:column; align-items:center; gap:6px;">

                                            <div
                                                style="width:90px; height:90px; border-radius:8px; overflow:hidden; border:1px solid #e5e7eb; background:#f9fafb;">
                                                <img src="{{ asset('storage/activity-evidence/' . $evidenceFile) }}"
                                                    style="width:100%; height:100%; object-fit:cover;"
                                                    onerror="this.style.display='none'" />
                                            </div>

                                            <a href="{{ route('file.download', ['type' => 'activity-evidence', 'file' => $evidenceFile]) }}"
                                                style="font-size:11px; color:#2563eb; text-decoration:none;">
                                                ⬇ Download
                                            </a>

                                        </div>
                                    @endforeach

                                    @if ($activity->evidences->count() > 3)
                                        <div style="display:flex; align-items:center; font-size:12px; color:#6b7280;">
                                            +{{ $activity->evidences->count() - 3 }} more
                                        </div>
                                    @endif

                                </div>
                            @else
                                <div style="font-size:12px;color:#9ca3af;margin-top:6px;">No evidence uploaded</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

    </div>

@endsection
