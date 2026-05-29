<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Client;
use App\Models\Allocation;
use App\Models\Expense;
use App\Models\Activity;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // USERS
        $usersCount = User::count();

        $adminsCount = User::where('role', 'admin')->count();
        $financeCount = User::where('role', 'finance')->count();
        $technicalCount = User::where('role', 'technical')->count();
        $directorCount = User::where('role', 'director')->count();

        // CORE DATA
        $projectsCount = Project::count();
        $clientsCount = Client::count();

        // FINANCIALS
        $totalAllocated = Allocation::sum('amount');

        $totalSpent = Expense::sum('amount');

        $remainingBudget = $totalAllocated - $totalSpent;

        // TECHNICAL
        $activitiesCount = Activity::count();
        $averageProgress = round(Activity::avg('current_progress') ?? 0, 2);

        // RECENT USERS
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'usersCount',
            'adminsCount',
            'financeCount',
            'technicalCount',
            'directorCount',
            'projectsCount',
            'clientsCount',
            'totalAllocated',
            'totalSpent',
            'remainingBudget',
            'activitiesCount',
            'averageProgress',
            'recentUsers'
        ));
    }
}
