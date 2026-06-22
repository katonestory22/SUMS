<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\User;
use App\Models\Expense;
use App\Models\ProjectEdit;
use App\Models\ExpenseEdit;
use App\Models\PhaseEdit;
use App\Models\ActivityEdit;
use App\Models\CompanyExpenseEdit;
use App\Models\CompanyExpense;


class DirectorController extends Controller
{
    public function index()
    { /*
   |--------------------------------------------------------------------------
   | Load Projects With Relationships
   |--------------------------------------------------------------------------
   */
        $projects = Project::with([
            'client',
            'activities',
            'allocations.expenses'
        ])->get();

        /*
        |--------------------------------------------------------------------------
        | Dashboard Summary Statistics
        |--------------------------------------------------------------------------
        */
        $totalProjects = $projects->count();

        $totalContract = $projects->sum('contract_amount');

        $totalAllocated = $projects->sum(function ($project) {
            return $project->totalAllocated();
        });

        $totalSpent = $projects->sum(function ($project) {
            return $project->totalExpenses();
        });

        $remainingBudget = $totalAllocated - $totalSpent;

        $totalCompanyExpenses = CompanyExpense::sum('amount');

        $overBudgetProjects = $projects->filter(function ($project) {
            return $project->totalExpenses() >
                $project->totalAllocated();
        })->count();

        /*
        |--------------------------------------------------------------------------
        | Project Chart Data
        |--------------------------------------------------------------------------
        */
        $labels = $projects->pluck('project_name');

        $allocatedData = $projects->map(function ($project) {
            return $project->totalAllocated();
        });

        $expenseData = $projects->map(function ($project) {
            return $project->totalExpenses();
        });

        /*
        |--------------------------------------------------------------------------
        | Expense Categories
        |--------------------------------------------------------------------------
        */
        $categories = [
            'Labour',
            'Equipment',
            'Travel',
            'Operations',
            'Consulting',
            'Miscellaneous'
        ];

        $rawExpenses = Expense::selectRaw(
            'category, SUM(amount) as total'
        )
            ->whereNotNull('category')
            ->groupBy('category')
            ->pluck('total', 'category');

        $expenseByCategory = collect($categories)
            ->mapWithKeys(function ($category) use ($rawExpenses) {
                return [
                    $category => $rawExpenses[$category] ?? 0
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | Monthly Allocation Trend
        |--------------------------------------------------------------------------
        */
        $monthlyAllocations = DB::table('allocations')
            ->selectRaw("
            DATE_FORMAT(allocation_date, '%Y-%m') as month,
            SUM(amount) as total
        ")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        /*
        |--------------------------------------------------------------------------
        | Monthly Expense Trend
        |--------------------------------------------------------------------------
        */
        $monthlyExpenses = DB::table('expenses')
            ->join(
                'allocations',
                'expenses.allocation_id',
                '=',
                'allocations.id'
            )
            ->selectRaw("
            DATE_FORMAT(expenses.date, '%Y-%m') as month,
            SUM(expenses.amount) as total
        ")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */
        return view('director.dashboard', compact(
            'projects',
            'totalProjects',
            'totalContract',
            'totalAllocated',
            'totalSpent',
            'remainingBudget',
            'totalCompanyExpenses',
            'overBudgetProjects',
            'labels',
            'allocatedData',
            'expenseData',
            'expenseByCategory',
            'monthlyAllocations',
            'monthlyExpenses'
        ));
    }

    public function users()
    {
        $users = User::latest()->get();

        return view('director.users.index', compact('users'));
    }
    public function audit()
    {
        $projectEdits = ProjectEdit::with('editor', 'project')
            ->latest()->get()->map(fn($e) => array_merge($e->toArray(), [
                'audit_type' => 'Project',
                'subject' => $e->project->project_name ?? '—',
                'editor_name' => $e->editor->name ?? '—',
                'audit_date' => $e->created_at,
                'reason' => $e->reason,
                'field' => $e->field_changed,
                'old' => $e->old_value,
                'new' => $e->new_value,
            ]));

        $expenseEdits = ExpenseEdit::with('editor', 'expense.allocation.project')
            ->latest()->get()->map(fn($e) => array_merge($e->toArray(), [
                'audit_type' => 'Expense',
                'subject' => $e->expense->description ?? '—',
                'editor_name' => $e->editor->name ?? '—',
                'audit_date' => $e->created_at,
                'reason' => $e->reason,
                'field' => $e->field_changed,
                'old' => $e->old_value,
                'new' => $e->new_value,
            ]));

        $phaseEdits = PhaseEdit::with('editor', 'phase.project')
            ->latest()->get()->map(fn($e) => array_merge($e->toArray(), [
                'audit_type' => 'Phase',
                'subject' => $e->phase->name ?? '—',
                'editor_name' => $e->editor->name ?? '—',
                'audit_date' => $e->created_at,
                'reason' => $e->reason,
                'field' => $e->field_changed,
                'old' => $e->old_value,
                'new' => $e->new_value,
            ]));

        $activityEdits = ActivityEdit::with('editor', 'activity.phase.project')
            ->latest()->get()->map(fn($e) => array_merge($e->toArray(), [
                'audit_type' => 'Activity',
                'subject' => $e->activity->name ?? '—',
                'editor_name' => $e->editor->name ?? '—',
                'audit_date' => $e->created_at,
                'reason' => $e->reason,
                'field' => $e->field_changed,
                'old' => $e->old_value,
                'new' => $e->new_value,
            ]));

        $companyEdits = CompanyExpenseEdit::with('editor', 'expense')
            ->latest()->get()->map(fn($e) => array_merge($e->toArray(), [
                'audit_type' => 'Company Expense',
                'subject' => $e->expense->title ?? '—',
                'editor_name' => $e->editor->name ?? '—',
                'audit_date' => $e->created_at,
                'reason' => $e->reason,
                'field' => $e->field_changed,
                'old' => $e->old_value,
                'new' => $e->new_value,
            ]));

        $allEdits = $projectEdits
            ->concat($expenseEdits)
            ->concat($phaseEdits)
            ->concat($activityEdits)
            ->concat($companyEdits)
            ->sortByDesc('audit_date')
            ->values();

        return view('company-expenses.audit', [
            'edits' => $allEdits
        ]);
    }
}
