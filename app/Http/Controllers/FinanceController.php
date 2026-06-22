<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Client;
use App\Models\Expense;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        $projects = Project::with('client')
            ->latest()
            ->take(5)
            ->get();

        $clientsCount = Client::count();
        $projectsCount = Project::count();
        $totalContractValue = Project::sum('contract_amount');
        $totalAllocated = Allocation::sum('amount');
        $totalSpent = Expense::sum('amount');
        $remainingBudget = $totalAllocated - $totalSpent; // ← fixed

        $categories = [
            'Labour',
            'Equipment',
            'Travel',
            'Operations',
            'Consulting',
            'Miscellaneous'
        ];

        $cashflow = [
            'allocated' => $totalAllocated,
            'spent' => $totalSpent,
            'remaining' => $remainingBudget,
        ];

        $expenseByCategory = collect($categories)->map(function ($cat) {
            $total = DB::table('expenses')
                ->where('category', $cat)
                ->sum('amount');
            return ['category' => $cat, 'total' => $total];
        });

        return view('finance.dashboard', compact(
            'clientsCount',
            'projectsCount',
            'totalContractValue',
            'totalAllocated',
            'totalSpent',
            'remainingBudget',
            'projects',
            'expenseByCategory',
            'cashflow'
        ));
    }
}
