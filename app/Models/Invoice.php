<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'created_by',
        'bill_to_name',
        'bill_to_address',
        'bill_to_email',
        'bill_to_phone',
        'invoice_date',
        'due_date',
        'notes',
        'items',
        'total',
        'file_path',
    ];

    protected $casts = [
        'items' => 'array',
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate the next sequential invoice number, e.g. INV-2026-0001.
     */
    public static function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');

        $count = static::whereYear('created_at', $year)->count() + 1;

        return sprintf('INV-%s-%04d', $year, $count);
    }

    /**
     * Compute the total from the items array (source of truth is the items,
     * mirroring the rest of the app's pattern of computed financial fields).
     */
    public function calculateTotal(): float
    {
        return collect($this->items)->sum(function ($item) {
            return (float) ($item['quantity'] ?? 0) * (float) ($item['rate'] ?? 0);
        });
    }
}
