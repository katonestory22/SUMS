<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'phase_id',
        'name',
        'weight_percentage',
        'current_progress',
    ];

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    public function histories()
    {
        return $this->hasMany(ActivityProgressHistory::class);
    }
    public function evidences()
    {
        return $this->hasMany(ActivityEvidence::class);
    }
}
