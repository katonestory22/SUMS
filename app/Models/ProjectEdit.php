<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProjectEdit extends Model
{
    protected $fillable = ['project_id', 'edited_by', 'field_changed', 'old_value', 'new_value', 'reason'];
    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
