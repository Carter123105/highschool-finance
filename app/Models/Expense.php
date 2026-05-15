<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [

        // BASIC INFO
        'title',
        'amount',
        'description',

        // CLASSIFICATION
        'category',
        'expense_date',

        // RELATIONSHIPS
        'user_id',
        'academic_year_id',
    ];

    /*
    |---------------------------------------------
    | CASTING (IMPORTANT FOR CLEAN OUTPUT)
    |---------------------------------------------
    */
    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    /*
    |---------------------------------------------
    | USER WHO CREATED EXPENSE
    |---------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /*
    |---------------------------------------------
    | ACADEMIC YEAR (OPTIONAL BUT USEFUL)
    |---------------------------------------------
    */
    public function academicYear()
    {
        return $this->belongsTo(\App\Models\AcademicYear::class);
    }
}