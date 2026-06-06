<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/*
|--------------------------------------------------------------------------
| RELATED MODELS
|--------------------------------------------------------------------------
*/
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\AcademicYear;
use App\Models\Invoice;
use App\Models\Payment;

class Student extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */
    protected $table = 'students';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'gender',
        'phone',
        'guardian_name',
        'guardian_phone',
        'address',
        'photo',
        'class_id',
        'section_id',
        'academic_year_id',
        'student_type',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | APPENDED ATTRIBUTES
    |--------------------------------------------------------------------------
    */
    protected $appends = [
        'full_name',
        'total_paid',
    ];

    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTE CASTING
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'class_id' => 'integer',
        'section_id' => 'integer',
        'academic_year_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | CLASS RELATION
    |--------------------------------------------------------------------------
    */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SECTION RELATION
    |--------------------------------------------------------------------------
    */
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR RELATION
    |--------------------------------------------------------------------------
    */
    public function academicYear()
    {
        return $this->belongsTo(
            AcademicYear::class,
            'academic_year_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INVOICES RELATION
    |--------------------------------------------------------------------------
    */
    public function invoices()
    {
        return $this->hasMany(
            Invoice::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENTS RELATION
    |--------------------------------------------------------------------------
    */
    public function payments()
    {
        return $this->hasMany(
            Payment::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FULL NAME ACCESSOR
    |--------------------------------------------------------------------------
    | FIXED:
    | Your blade uses:
    | $student->full_name
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute()
    {
        return trim(
            ($this->first_name ?? '') . ' ' .
            ($this->last_name ?? '')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | OPTIONAL SHORT NAME ACCESSOR
    |--------------------------------------------------------------------------
    */
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL PAID ACCESSOR
    |--------------------------------------------------------------------------
    */
    public function getTotalPaidAttribute()
    {
        return (float) $this->payments()
            ->sum('amount_paid');
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS ACCESSOR
    |--------------------------------------------------------------------------
    */
    public function getStatusAttribute($value)
    {
        return $value ?: 'Active';
    }

    /*
    |--------------------------------------------------------------------------
    | PHOTO URL ACCESSOR
    |--------------------------------------------------------------------------
    */
    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return asset('images/default-avatar.png');
        }

        return asset('storage/' . $this->photo);
    }
}