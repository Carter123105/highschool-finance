<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Invoice;
use App\Models\Student;
use App\Models\User;
use App\Models\PaymentAllocation;
use App\Models\SchoolClass;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_no',
        'invoice_id',
        'student_id',
        'amount_paid',
        'payment_method',
        'payment_date',
        'received_by',
        'reference_number',
        'notes',
    ];

    /*
    |--------------------------------------------------------------------------
    | INVOICE RELATION
    |--------------------------------------------------------------------------
    */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT RELATION
    |--------------------------------------------------------------------------
    */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RECEIVER (USER)
    |--------------------------------------------------------------------------
    */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT ALLOCATIONS
    |--------------------------------------------------------------------------
    */
    public function allocations()
    {
        return $this->hasMany(PaymentAllocation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCHOOL CLASS (FIXED + SIMPLIFIED)
    |--------------------------------------------------------------------------
    | ❌ Your previous hasOneThrough was incorrect
    | ✔ Proper way: go through invoice relation
    |--------------------------------------------------------------------------
    */
    public function schoolClass()
    {
        return $this->hasOneThrough(
            SchoolClass::class,
            Invoice::class,
            'id',        // invoices.id
            'id',        // school_classes.id
            'invoice_id',// payments.invoice_id
            'class_id'   // invoices.class_id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR: SAFE TOTAL PAID FOR THIS PAYMENT'S INVOICE
    |--------------------------------------------------------------------------
    */
    public function getTotalPaidOnInvoiceAttribute()
    {
        return Payment::where('invoice_id', $this->invoice_id)->sum('amount_paid');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR: INVOICE BALANCE
    |--------------------------------------------------------------------------
    */
    public function getInvoiceBalanceAttribute()
    {
        if (!$this->invoice) return 0;

        $paid = Payment::where('invoice_id', $this->invoice_id)->sum('amount_paid');

        return max(0, $this->invoice->total_amount - $paid);
    }
}