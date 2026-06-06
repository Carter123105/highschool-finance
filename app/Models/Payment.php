<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_no',
        'invoice_id',
        'student_id',
        'amount_paid',
        'currency',
        'exchange_rate',
        'payment_method',
        'transaction_reference',
        'payment_date',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'amount_paid'          => 'decimal:2',
        'exchange_rate'        => 'decimal:4',
        'payment_date'         => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(PaymentAllocation::class);
    }

    public function schoolClass(): HasOneThrough
    {
        return $this->hasOneThrough(
            SchoolClass::class,
            Invoice::class,
            'id',
            'id',
            'invoice_id',
            'class_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeForInvoice($query, int $invoiceId)
    {
        return $query->where('invoice_id', $invoiceId);
    }

    public function scopeByStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeMobileMoney($query)
    {
        return $query->whereIn('payment_method', [
            'Mobile Money',
            'mobile_money',
            'momo',
            'mpesa',
            'mtn',
            'orange_money',
            'wave',
            'airtel_money',
        ]);
    }

    public function scopeWithReference($query)
    {
        return $query->whereNotNull('transaction_reference')
                     ->where('transaction_reference', '!=', '');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getTotalPaidOnInvoiceAttribute(): float
    {
        return (float) static::forInvoice($this->invoice_id)->sum('amount_paid');
    }

    public function getInvoiceBalanceAttribute(): float
    {
        if (!$this->invoice) {
            return 0.0;
        }

        return max(0.0, (float) $this->invoice->total_amount - $this->total_paid_on_invoice);
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount_paid, 2);
    }

    public function getDisplayPaymentMethodAttribute(): string
    {
        return match ($this->payment_method) {
            'Mobile Money', 'mobile_money', 'momo' => 'Mobile Money',
            'mpesa'                                  => 'M-Pesa',
            'mtn'                                    => 'MTN Mobile Money',
            'orange_money'                           => 'Orange Money',
            'wave'                                   => 'Wave',
            'airtel_money'                           => 'Airtel Money',
            'Cash', 'cash'                           => 'Cash',
            'Bank', 'bank_transfer'                  => 'Bank Transfer',
            'Cheque', 'cheque', 'check'              => 'Cheque',
            default                                  => ucwords(str_replace('_', ' ', $this->payment_method ?? 'Unknown')),
        };
    }

    public function getIsMobileMoneyAttribute(): bool
    {
        return in_array($this->payment_method, [
            'Mobile Money',
            'mobile_money',
            'momo',
            'mpesa',
            'mtn',
            'orange_money',
            'wave',
            'airtel_money',
        ]);
    }

    public function getHasTransactionReferenceAttribute(): bool
    {
        return !empty($this->transaction_reference);
    }

    public function getFormattedPaymentDateAttribute(): ?string
    {
        return $this->payment_date?->format('d M Y h:i A');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isFullyPaid(): bool
    {
        return $this->invoice_balance <= 0;
    }

    public function isPartiallyPaid(): bool
    {
        return $this->invoice_balance > 0 && $this->total_paid_on_invoice > 0;
    }

    public function isNotPaid(): bool
    {
        return $this->total_paid_on_invoice <= 0;
    }
}