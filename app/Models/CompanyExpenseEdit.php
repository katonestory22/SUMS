<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyExpenseEdit extends Model
{
    protected $fillable = [
        'company_expense_id',
        'edited_by',
        'field_changed',
        'old_value',
        'new_value',
        'reason',
    ];

    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function expense()
    {
        return $this->belongsTo(CompanyExpense::class, 'company_expense_id');
    }
}
