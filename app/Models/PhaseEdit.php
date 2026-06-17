<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PhaseEdit extends Model
{
    protected $fillable = ['phase_id', 'edited_by', 'field_changed', 'old_value', 'new_value', 'reason'];
    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }
}
