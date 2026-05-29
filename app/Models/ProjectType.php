<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{
    protected $fillable = ['name'];

    // If you want, you can add a reverse relation to projects:
    public function projects()
    {
        return $this->hasMany(Project::class, 'project_type_id');
    }
}
