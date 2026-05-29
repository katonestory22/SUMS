<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityProgressHistory extends Model
{
    protected $fillable = [
        'activity_id',
        'old_percentage',
        'new_percentage',
        'updated_by',
        'comment',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function files()
    {
        return $this->hasMany(ProgressFile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
