<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllocationTopup extends Model
{
    protected $fillable = ['allocation_id', 'amount', 'received_date', 'notes', 'recorded_by'];

    public function allocation()
    {
        return $this->belongsTo(Allocation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
