<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Medicine extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    /**
     * Get all stock entries for this medicine
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'medicineId', 'id');
    }
    
    /**
     * Get only non-expired stock entries
     */
    public function validStocks()
    {
        return $this->hasMany(Stock::class, 'medicineId', 'id')
            ->where('expire_date', '>', now()->toDateString());
    }
    
    /**
     * Get total available stock (non-expired only)
     */
    public function getAvailableStockAttribute()
    {
        return $this->stocks()
            ->where('expire_date', '>', now()->toDateString())
            ->sum('qty');
    }
    
    /**
     * Get stock grouped by expiry date (non-expired only)
     */
    public function getStockByExpiryAttribute()
    {
        return $this->stocks()
            ->where('expire_date', '>', now()->toDateString())
            ->select('expire_date', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('expire_date')
            ->havingRaw('SUM(qty) > 0')
            ->orderBy('expire_date', 'asc')
            ->get();
    }
    
    /**
     * Check if medicine has available stock
     */
    public function hasAvailableStock()
    {
        return $this->available_stock > 0;
    }
    
    /**
     * Get stock status for display
     */
    public function getStockStatusAttribute()
    {
        $stock = $this->available_stock;
        
        if ($stock > 50) {
            return [
                'class' => 'stock-high',
                'text' => 'In Stock',
                'color' => 'success'
            ];
        } elseif ($stock > 10) {
            return [
                'class' => 'stock-medium',
                'text' => 'Low Stock',
                'color' => 'warning'
            ];
        } elseif ($stock > 0) {
            return [
                'class' => 'stock-low',
                'text' => 'Very Low',
                'color' => 'danger'
            ];
        } else {
            return [
                'class' => 'stock-out',
                'text' => 'Out of Stock',
                'color' => 'secondary'
            ];
        }
    }
    
    /**
     * Scope to get only medicines with available stock
     */
    public function scopeWithAvailableStock($query)
    {
        return $query->whereHas('stocks', function($q) {
            $q->where('expire_date', '>', now()->toDateString())
              ->groupBy('medicineId')
              ->havingRaw('SUM(qty) > 0');
        });
    }
    
    /**
     * Scope to filter by expiry date range
     */
    public function scopeExpiringBetween($query, $startDate, $endDate)
    {
        return $query->whereHas('stocks', function($q) use ($startDate, $endDate) {
            $q->whereBetween('expire_date', [$startDate, $endDate])
              ->havingRaw('SUM(qty) > 0');
        });
    }
    
    /**
     * Scope to get medicines expiring soon (within days)
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        $endDate = now()->addDays($days)->toDateString();
        return $query->whereHas('stocks', function($q) use ($endDate) {
            $q->where('expire_date', '<=', $endDate)
              ->where('expire_date', '>', now()->toDateString())
              ->havingRaw('SUM(qty) > 0');
        });
    }
    
    // Existing relationships
    public function leaf()
    {
        return $this->belongsTo(Leaf::class);
    }
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplierId', 'id');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId', 'id');
    }
    
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendorId', 'id');
    }
    
    public function type()
    {
        return $this->belongsTo(Type::class, 'typeId', 'id');
    }
    
    /**
     * Get sale details for this medicine
     */
    public function saleDetails()
    {
        return $this->hasMany(SaleDetails::class, 'medicineId', 'id');
    }
    
    /**
     * Get purchase details for this medicine
     */
    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseInvoiceDetails::class, 'medicineId', 'id');
    }
}