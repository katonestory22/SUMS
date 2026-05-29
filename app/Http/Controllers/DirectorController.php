<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\User;
use App\Models\Expense;


class DirectorController extends Controller
{
    public function index()
    {
        $projects = Project::with(['client', 'activities', 'allocations.expenses'])->get();

        $totalContract = $projects->sum('contract_amount');
        $totalAllocated = $projects->sum(fn($p) => $p->totalAllocated());
        $totalSpent = $projects->sum(fn($p) => $p->totalExpenses());
        // Chart data (ALL projects combined)
        $labels = $projects->pluck('project_name');

        $allocatedData = $projects->map(function ($project) {
            return method_exists($project, 'totalAllocated')
                ? $project->totalAllocated()
                : 0;
        });

        $expenseData = $projects->map(function ($project) {
            return method_exists($project, 'totalExpenses')
                ? $project->totalExpenses()
                : 0;
        });

        $categories = [
            'Labour',
            'Equipment',
            'Travel',
            'Operations',
            'Consulting',
            'Miscellaneous'
        ];

        $raw = Expense::selectRaw('category, SUM(amount) as total')
            ->whereNotNull('category')
            ->groupBy('category')
            ->pluck('total', 'category');

        $expenseByCategory = collect($categories)->mapWithKeys(function ($cat) use ($raw) {
            return [$cat => $raw[$cat] ?? 0];
        });

        $monthlyAllocations = DB::table('allocations')
            ->selectRaw("DATE_FORMAT(allocation_date, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyExpenses = DB::table('expenses')
            ->join('allocations', 'expenses.allocation_id', '=', 'allocations.id')
            ->selectRaw("DATE_FORMAT(expenses.date, '%Y-%m') as month, SUM(expenses.amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('director.dashboard', compact(
            'projects',
            'totalContract',
            'totalAllocated',
            'totalSpent',
            'labels',
            'allocatedData',
            'expenseData',
            'expenseByCategory',
            'monthlyExpenses',
            'monthlyAllocations'
        ));
    }

    public function users()
    {
        $users = User::latest()->get();

        return view('director.users.index', compact('users'));
    }

}
