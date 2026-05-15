<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'school_email',
        'school_phone',
        'school_address',
        'currency',
        'logo',
        'receipt_prefix',
        'system_name',
    ];
}