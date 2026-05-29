<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'allocation_id',
        'category',
        'amount',
        'description',
        'date',
        'recorded_by',
        'receipt',
    ];

    public function allocation()
    {
        return $this->belongsTo(Allocation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
