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
        return $this->activities()->avg('current_progress') ?? 0;
    }
}
