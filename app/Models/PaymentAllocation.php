<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAllocation extends Model
{
    protected $table = 'payment_allocations';

    protected $fillable = [
        'payment_id',
        'invoice_item_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /*
    |=========================================================
    | PAYMENT RELATION
    |=========================================================
    */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    /*
    |=========================================================
    | INVOICE ITEM RELATION (SAFE NULLABLE FIX)
    |=========================================================
    */
    public function invoiceItem(): BelongsTo
    {
        return $this->belongsTo(InvoiceItem::class, 'invoice_item_id');
    }

    /*
    |=========================================================
    | ACCESSOR: SAFE AMOUNT FORMAT
    |=========================================================
    */
    public function getFormattedAmountAttribute(): string
    {
        return number_format((float) $this->amount, 2);
    }
}