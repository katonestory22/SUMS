<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyExpense extends Model
{
    protected $fillable = [
        'recorded_by',
        'title',
        'category',
        'amount',
        'date',
        'description',
        'receipt',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function edits()
    {
        return $this->hasMany(CompanyExpenseEdit::class)->latest();
    }
}
