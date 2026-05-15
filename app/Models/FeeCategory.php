<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeCategory extends Model
{
    use HasFactory;

    protected $table = 'fee_categories';

    protected $fillable = [
        'name',
        'description',
        'is_monthly',
        'is_active',
    ];

    protected $casts = [
        'is_monthly' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMonthly($query)
    {
        return $query->where('is_monthly', true);
    }

    public function scopeOneTime($query)
    {
        return $query->where('is_monthly', false);
    }

    // Relationships
    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}