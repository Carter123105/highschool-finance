<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $fillable = [
        'class_id',
        'fee_category_id',
        'amount',
        'is_active',
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function feeCategory()
    {
        return $this->belongsTo(FeeCategory::class);
    }
}