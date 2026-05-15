<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Invoice;
use App\Models\FeeCategory;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';

    /*
    |---------------------------------------
    | FILLABLE
    |---------------------------------------
    */
    protected $fillable = [
        'invoice_id',
        'fee_category_id',
        'amount',
        'discount',
        'subtotal',
        'slip_no',
        'paid_amount',
        'balance',
        'status',
    ];

    /*
    |---------------------------------------
    | CASTS (SAFE FINANCIAL HANDLING)
    |---------------------------------------
    */
    protected $casts = [
        'amount'      => 'float',
        'discount'    => 'float',
        'subtotal'    => 'float',
        'paid_amount' => 'float',
        'balance'     => 'float',
    ];

    /*
    |---------------------------------------
    | RELATION: INVOICE
    |---------------------------------------
    */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    /*
    |---------------------------------------
    | RELATION: FEE CATEGORY (FIXED)
    |---------------------------------------
    */
    public function feeCategory()
    {
        return $this->belongsTo(FeeCategory::class, 'fee_category_id');
    }

    /*
    |---------------------------------------
    | AUTO CALCULATION FIXED
    |---------------------------------------
    */
    protected static function booted()
    {
        static::saving(function ($item) {

            $amount   = (float) ($item->amount ?? 0);
            $discount = (float) ($item->discount ?? 0);
            $paid     = (float) ($item->paid_amount ?? 0);

            // subtotal calculation
            $item->subtotal = max(0, $amount - $discount);

            // balance calculation
            $item->balance = max(0, $item->subtotal - $paid);

            // status logic (FIXED)
            if ($paid <= 0) {
                $item->status = 'Unpaid';
            } elseif ($paid < $item->subtotal) {
                $item->status = 'Partial';
            } else {
                $item->status = 'Paid';
            }
        });
    }

    /*
    |---------------------------------------
    | ACCESSORS (SAFE DISPLAY)
    |---------------------------------------
    */

    public function getFormattedAmountAttribute()
    {
        return number_format((float) $this->amount, 2);
    }

    public function getFormattedDiscountAttribute()
    {
        return number_format((float) $this->discount, 2);
    }

    public function getFormattedSubtotalAttribute()
    {
        return number_format((float) $this->subtotal, 2);
    }

    public function getFormattedBalanceAttribute()
    {
        return number_format((float) $this->balance, 2);
    }
}