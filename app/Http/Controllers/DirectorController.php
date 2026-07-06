<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
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
        $activeTab = request('tab', 'all');
        $perPage = 5;
        $page = request('page', 1);
        $offset = ($page - 1) * $perPage;

        $typeFilter = $activeTab === 'all' ? null : $activeTab;

        // --- COUNT QUERY (for pagination total & tab badges) ---
        $countUnion = DB::table('project_edits')->selectRaw("COUNT(*) as cnt, 'Project' as audit_type")
            ->unionAll(DB::table('expense_edits')->selectRaw("COUNT(*) as cnt, 'Expense' as audit_type"))
            ->unionAll(DB::table('phase_edits')->selectRaw("COUNT(*) as cnt, 'Phase' as audit_type"))
            ->unionAll(DB::table('activity_edits')->selectRaw("COUNT(*) as cnt, 'Activity' as audit_type"))
            ->unionAll(DB::table('company_expense_edits')->selectRaw("COUNT(*) as cnt, 'Company Expense' as audit_type"));

        $counts = DB::table(DB::raw("({$countUnion->toSql()}) as counts"))
            ->mergeBindings($countUnion)
            ->get()
            ->keyBy('audit_type');

        $allCount = $counts->sum('cnt');
        $tabCounts = [
            'all' => $allCount,
            'Project' => $counts['Project']->cnt ?? 0,
            'Expense' => $counts['Expense']->cnt ?? 0,
            'Phase' => $counts['Phase']->cnt ?? 0,
            'Activity' => $counts['Activity']->cnt ?? 0,
            'Company Expense' => $counts['Company Expense']->cnt ?? 0,
        ];

        $total = $typeFilter ? ($tabCounts[$typeFilter] ?? 0) : $allCount;

        // --- DATA QUERY (only current page rows) ---
        $projectQ = DB::table('project_edits as e')
            ->join('projects as p', 'p.id', '=', 'e.project_id')
            ->join('users as u', 'u.id', '=', 'e.edited_by')
            ->selectRaw("'Project' as audit_type, p.project_name as subject, u.name as editor_name,
                      e.field_changed as field, e.old_value as old, e.new_value as new,
                      e.reason, e.created_at as audit_date");

        $expenseQ = DB::table('expense_edits as e')
            ->join('expenses as p', 'p.id', '=', 'e.expense_id')
            ->join('users as u', 'u.id', '=', 'e.edited_by')
            ->selectRaw("'Expense' as audit_type, p.description as subject, u.name as editor_name,
                      e.field_changed as field, e.old_value as old, e.new_value as new,
                      e.reason, e.created_at as audit_date");

        $phaseQ = DB::table('phase_edits as e')
            ->join('phases as p', 'p.id', '=', 'e.phase_id')
            ->join('users as u', 'u.id', '=', 'e.edited_by')
            ->selectRaw("'Phase' as audit_type, p.name as subject, u.name as editor_name,
                      e.field_changed as field, e.old_value as old, e.new_value as new,
                      e.reason, e.created_at as audit_date");

        $activityQ = DB::table('activity_edits as e')
            ->join('activities as p', 'p.id', '=', 'e.activity_id')
            ->join('users as u', 'u.id', '=', 'e.edited_by')
            ->selectRaw("'Activity' as audit_type, p.name as subject, u.name as editor_name,
                      e.field_changed as field, e.old_value as old, e.new_value as new,
                      e.reason, e.created_at as audit_date");

        $companyQ = DB::table('company_expense_edits as e')
            ->join('company_expenses as p', 'p.id', '=', 'e.company_expense_id')
            ->join('users as u', 'u.id', '=', 'e.edited_by')
            ->selectRaw("'Company Expense' as audit_type, p.title as subject, u.name as editor_name,
                      e.field_changed as field, e.old_value as old, e.new_value as new,
                      e.reason, e.created_at as audit_date");

        // Apply tab filter
        $queries = collect([$projectQ, $expenseQ, $phaseQ, $activityQ, $companyQ]);

        if ($typeFilter) {
            $typeMap = [
                'Project' => $projectQ,
                'Expense' => $expenseQ,
                'Phase' => $phaseQ,
                'Activity' => $activityQ,
                'Company Expense' => $companyQ,
            ];
            $baseQuery = $typeMap[$typeFilter];
        } else {
            $baseQuery = $projectQ
                ->unionAll($expenseQ)
                ->unionAll($phaseQ)
                ->unionAll($activityQ)
                ->unionAll($companyQ);
        }

        $rows = DB::table(DB::raw("({$baseQuery->toSql()}) as audit_rows"))
            ->mergeBindings($baseQuery)
            ->orderByDesc('audit_date')
            ->offset($offset)
            ->limit($perPage)
            ->get()
            ->map(fn($r) => (array) $r);

        $paginator = new LengthAwarePaginator(
            $rows,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('director.audit', [
            'rows' => $paginator,
            'tabCounts' => $tabCounts,
            'activeTab' => $activeTab,
        ]);
    }
}
