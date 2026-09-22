<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'bill_to_name',
        'bill_to_address',
        'title',
        'issue_date',
        'due_date',
        'status',
        'subtotal',
        'tax_percentage',
        'tax_amount',
        'total_amount',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function items()
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Numbering
    |--------------------------------------------------------------------------
    */

    public static function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');

        return DB::transaction(function () use ($year) {
            $lastNumber = static::where('invoice_number', 'like', "INV-{$year}-%")
                ->lockForUpdate()
                ->orderByDesc('id')
                ->value('invoice_number');

            $nextSeq = 1;

            if ($lastNumber) {
                $parts = explode('-', $lastNumber);
                $nextSeq = ((int) end($parts)) + 1;
            }

            return sprintf('INV-%s-%04d', $year, $nextSeq);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Financial Logic (mirrors Project::remainingBalance() pattern)
    |--------------------------------------------------------------------------
    */

    public function totalPaid()
    {
        return $this->payments()->sum('amount');
    }

    public function balance()
    {
        return $this->total_amount - $this->totalPaid();
    }

    public function getPaymentStatusAttribute()
    {
        if ($this->status === 'cancelled') {
            return 'cancelled';
        }

        $balance = $this->balance();
        $paid = $this->totalPaid();

        if ($balance <= 0 && $this->total_amount > 0) {
            return 'paid';
        }

        if ($paid > 0) {
            return 'partially_paid';
        }

        if ($this->due_date && $this->due_date->isPast()) {
            return 'overdue';
        }

        return 'unpaid';
    }

    /**
     * Recalculate subtotal/tax/total from current line items.
     * Call this after items are created/updated/deleted.
     */
    public function recalculateTotals(): void
    {
        $subtotal = $this->items()->sum('amount');
        $taxAmount = round($subtotal * ($this->tax_percentage / 100), 2);

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $subtotal + $taxAmount,
        ]);
    }
}
