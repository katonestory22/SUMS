<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'description',
        'weight_percentage',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function progress()
    {
        $activities = $this->activities;

        if ($activities->isEmpty())
            return 0;

        $totalWeight = $activities->sum('weight_percentage');

        if ($totalWeight == 0)
            return 0;

        $weighted = $activities->sum(function ($activity) {
            return ($activity->weight_percentage * $activity->current_progress);
        });

        return round($weighted / $totalWeight, 2);
    }
}
