<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'inv_no',
        'medicineId',
        'supplierId',
        'return_date',
        'qty',
        'price',
        'total',
    ];

    protected $casts = [
        'return_date' => 'date',
        'qty' => 'decimal:2',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Relationship with Medicine
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicineId');
    }

    /**
     * Relationship with Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplierId');
    }

    /**
     * Relationship with Stock
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'inv_no', 'inv_no');
    }

    /**
     * Relationship with Transaction
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'refId')
            ->where('type', 'purchase_return');
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('return_date', [$startDate, $endDate]);
    }

    /**
     * Scope for supplier
     */
    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplierId', $supplierId);
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotalAttribute()
    {
        return '৳' . number_format($this->total, 2);
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->return_date->format('d M Y');
    }
}