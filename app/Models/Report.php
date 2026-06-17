<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'project_id',
        'uploaded_by',
        'title',
        'type',
        'source',
        'file_path',
        'notes',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class)->withDefault([
            'project_name' => 'Company',
        ]);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
