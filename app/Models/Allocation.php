<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    protected $fillable = [
        'project_id',
        'category',
        'amount',
        'allocation_date',
        'notes',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
