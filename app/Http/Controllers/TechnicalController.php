<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Phase;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class TechnicalController extends Controller
{
    public function index()
    {
        $projectsCount = Project::count();
        $phasesCount = Phase::count();
        $activitiesCount = Activity::count();
        $averageProgress = Activity::avg('current_progress') ?? 0;

        $projects = Project::with('client')
            ->withAvg('activities', 'current_progress')
            ->latest()
            ->get();

        $activityProgress = Activity::select('current_progress')
            ->get()
            ->groupBy(function ($item) {
                if ($item->current_progress >= 100)
                    return 'Completed';
                if ($item->current_progress >= 50)
                    return 'Mid Progress';
                return 'Early Stage';
            })
            ->map->count();


        return view('technical.dashboard', compact(
            'projectsCount',
            'phasesCount',
            'activitiesCount',
            'averageProgress',
            'projects',
            'activityProgress'
        ));
    }

}
