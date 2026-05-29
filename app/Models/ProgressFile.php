<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressFile extends Model
{
    protected $fillable = [
        'activity_progress_history_id',
        'file_path',
        'file_type',
    ];

    public function history()
    {
        return $this->belongsTo(ActivityProgressHistory::class);
    }
}
