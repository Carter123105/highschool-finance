<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

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
    |---------------------------------------
    | CLASS RELATION
    |---------------------------------------
    */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /*
    |---------------------------------------
    | SECTION
    |---------------------------------------
    */
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /*
    |---------------------------------------
    | INVOICES
    |---------------------------------------
    */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }

    /*
    |---------------------------------------
    | PAYMENTS
    |---------------------------------------
    */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'student_id');
    }

    /*
    |---------------------------------------
    | FULL NAME (🔥 FIX FOR YOUR UI)
    |---------------------------------------
    */
    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}