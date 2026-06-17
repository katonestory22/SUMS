<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ExpenseEdit extends Model
{
    protected $fillable = ['expense_id', 'edited_by', 'field_changed', 'old_value', 'new_value', 'reason'];
    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
