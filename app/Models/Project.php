<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'project_name',
        'project_type_id',
        'location',
        'start_date',
        'end_date',
        'contract_amount',
        'contract_number',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function phases()
    {
        return $this->hasMany(Phase::class);
    }

    public function activities()
    {
        return $this->hasManyThrough(Activity::class, Phase::class);
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function type()
    {
        return $this->belongsTo(ProjectType::class, 'project_type_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------
    */

    public function setContractAmountAttribute($value)
    {
        $this->attributes['contract_amount'] = str_replace(',', '', $value);
    }

    /*
    |--------------------------------------------------------------------------
    | Financial Logic
    |--------------------------------------------------------------------------
    */

    public function totalAllocated()
    {
        return $this->allocations()->sum('amount');
    }

    public function totalExpenses()
    {
        return $this->allocations()
            ->with('expenses')
            ->get()
            ->pluck('expenses')
            ->flatten()
            ->sum('amount');
    }

    public function remainingBalance()
    {
        return $this->totalAllocated() - $this->totalExpenses();
    }

    /*
    |--------------------------------------------------------------------------
    | Progress Logic (Single Source of Truth)
    |--------------------------------------------------------------------------
    */

    public function getProgressAttribute()
    {
        $phases = $this->phases()->with('activities')->get();

        if ($phases->isEmpty())
            return 0;

        $totalPhaseWeight = $phases->sum('weight_percentage');

        if ($totalPhaseWeight == 0)
            return 0;

        $weighted = $phases->sum(function ($phase) {
            return $phase->weight_percentage * $phase->progress();
        });

        return round($weighted / $totalPhaseWeight, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Optional: Phase-Based Progress (Advanced)
    |--------------------------------------------------------------------------
    */

    public function getPhaseProgressAttribute()
    {
        $phases = $this->phases;

        if ($phases->isEmpty()) {
            return 0;
        }

        $totalWeight = $phases->sum('weight_percentage');

        if ($totalWeight == 0) {
            return 0;
        }

        $weighted = $phases->sum(function ($phase) {
            return $phase->progress() * $phase->weight_percentage;
        });

        return round($weighted / $totalWeight, 2);
    }
}
