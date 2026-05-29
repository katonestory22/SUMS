@extends('layouts.app')

@section('title', 'Project Expenses')
@section('page-title', 'Expense Ledger')

@section('sub-nav')
    <a href="{{ route('dashboard') }}">Dashboard</a> |
    <a href="{{ route('projects.index') }}">Projects</a>
@endsection

@section('content')

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
        }

        .expense-grid {
            max-width: 1100px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            padding: 24px;
        }

        @media (max-width: 768px) {
            .expense-grid {
                grid-template-columns: 1fr;
            }
        }

        .expense-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 18px 18px 16px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            transition: all .18s ease;
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        .expense-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.08);
        }

        .amount {
            font-size: 18px;
            font-weight: 700;
            color: #2563eb;
            letter-spacing: 0.2px;
        }

        .meta {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .desc {
            margin-top: 10px;
            font-size: 14px;
            color: #111827;
            line-height: 1.5;
        }

        .receipt {
            margin-top: 12px;
            font-size: 12px;
        }

        .receipt a {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 8px;
            background: #eef2ff;
            color: #3730a3;
            text-decoration: none;
            font-weight: 600;
            transition: 0.15s ease;
        }

        .receipt a:hover {
            background: #e0e7ff;
        }

        .empty-receipt {
            color: #9ca3af;
            font-size: 12px;
            padding: 6px 0;
        }

        .loader {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 13px;
        }

        .expense-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .expense-index {
            font-size: 12px;
            font-weight: 700;
            color: #9ca3af;
            letter-spacing: 0.5px;
        }
    </style>

    <div class="expense-grid" id="expenseGrid">
        @include('projects.partials.expense_cards')
    </div>

    <div class="loader" id="loader">Scroll to load more…</div>

    <script>
        let page = 2;
        let loading = false;
        let hasMore = true;

        window.addEventListener('scroll', async () => {
            if (loading || !hasMore) return;

            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 200) {
                loading = true;

                const res = await fetch(`?page=${page}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const html = await res.text();

                if (!html.trim()) {
                    hasMore = false;
                    document.getElementById('loader').innerText = "No more expenses";
                    return;
                }

                document.getElementById('expenseGrid')
                    .insertAdjacentHTML('beforeend', html);

                page++;
                loading = false;
            }
        });
    </script>

@endsection
