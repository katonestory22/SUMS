<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ActivityEdit extends Model
{
    protected $fillable = ['activity_id', 'edited_by', 'field_changed', 'old_value', 'new_value', 'reason'];
    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
