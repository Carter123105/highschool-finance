<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'student_id',        // ✅ ADDED (nullable for class-based invoices)
        'class_id',
        'section_id',
        'academic_year_id',
        'student_type',      // ✅ ADDED (Old or New)
        'total_amount',
        'paid_amount',       // ✅ ADDED
        'balance',           // ✅ ADDED
        'status',
        'created_by',
        'due_date',
    ];

    /*
    |--------------------------------------------------------------------------
    | STUDENT (nullable - for individual student invoices)
    |--------------------------------------------------------------------------
    */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS
    |--------------------------------------------------------------------------
    */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SECTION
    |--------------------------------------------------------------------------
    */
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR
    |--------------------------------------------------------------------------
    */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENTS
    |--------------------------------------------------------------------------
    */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | INVOICE ITEMS
    |--------------------------------------------------------------------------
    */
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ALIAS
    |--------------------------------------------------------------------------
    */
    public function items()
    {
        return $this->invoiceItems();
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL PAID (DYNAMIC)
    |--------------------------------------------------------------------------
    */
    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount_paid');
    }

    /*
    |--------------------------------------------------------------------------
    | REMAINING BALANCE
    |--------------------------------------------------------------------------
    */
    public function getRemainingAttribute()
    {
        return max(0, $this->total_amount - $this->total_paid);
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT STATUS
    |--------------------------------------------------------------------------
    */
    public function getPaymentStatusAttribute()
    {
        return match (true) {
            $this->total_paid <= 0 => 'Unpaid',
            $this->total_paid < $this->total_amount => 'Partial',
            default => 'Paid',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | INVOICE NUMBER GENERATOR
    |--------------------------------------------------------------------------
    */
    public static function generateInvoiceNumber()
    {
        $lastId = self::max('id') + 1;

        return 'INV-' . date('Y') . '-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);
    }
}