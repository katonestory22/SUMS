<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityEvidence extends Model
{
    protected $table = 'activity_evidences';

    protected $fillable = [
        'activity_id',
        'file_path',
        'caption',
        'uploaded_by',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
