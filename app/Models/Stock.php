<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stock extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    protected $casts = [
        'date' => 'date',
        'expire_date' => 'date',
        'qty' => 'decimal:2',
    ];
    
    /**
     * Get the medicine that owns this stock
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicineId', 'id');
    }
    
    /**
     * Get the user who updated this stock
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    
    /**
     * Scope to get only non-expired stock
     */
    public function scopeNonExpired($query)
    {
        return $query->where('expire_date', '>', now()->toDateString());
    }
    
    /**
     * Scope to get only expired stock
     */
    public function scopeExpired($query)
    {
        return $query->where('expire_date', '<=', now()->toDateString());
    }
    
    /**
     * Scope to get stock expiring soon
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        $endDate = now()->addDays($days)->toDateString();
        return $query->where('expire_date', '<=', $endDate)
                     ->where('expire_date', '>', now()->toDateString());
    }
    
    /**
     * Scope to filter by type (purchase, sales, return, etc.)
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
    
    /**
     * Scope to get available stock (positive quantities, non-expired)
     */
    public function scopeAvailable($query)
    {
        return $query->where('expire_date', '>', now()->toDateString())
                     ->havingRaw('SUM(qty) > 0');
    }
    
    /**
     * Get stock summary for a medicine
     */
    public static function getMedicineSummary($medicineId)
    {
        return self::where('medicineId', $medicineId)
            ->select(
                DB::raw('SUM(CASE WHEN expire_date > NOW() THEN qty ELSE 0 END) as available_stock'),
                DB::raw('SUM(CASE WHEN expire_date <= NOW() THEN qty ELSE 0 END) as expired_stock'),
                DB::raw('SUM(qty) as total_stock'),
                DB::raw('COUNT(DISTINCT expire_date) as batch_count')
            )
            ->first();
    }
    
    /**
     * Get stock grouped by expiry date for a medicine
     */
    public static function getStockByExpiry($medicineId, $onlyValid = true)
    {
        $query = self::where('medicineId', $medicineId)
            ->select('expire_date', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('expire_date')
            ->havingRaw('SUM(qty) > 0')
            ->orderBy('expire_date', 'asc');
        
        if ($onlyValid) {
            $query->where('expire_date', '>', now()->toDateString());
        }
        
        return $query->get();
    }
    
    /**
     * Check if stock entry is expired
     */
    public function isExpired()
    {
        return $this->expire_date <= now()->toDateString();
    }
    
    /**
     * Check if stock is expiring soon (within days)
     */
    public function isExpiringSoon($days = 30)
    {
        $endDate = now()->addDays($days)->toDateString();
        return $this->expire_date <= $endDate && $this->expire_date > now()->toDateString();
    }
    
    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiryAttribute()
    {
        return now()->diffInDays($this->expire_date, false);
    }
    
    /**
     * Get formatted expiry date
     */
    public function getFormattedExpiryDateAttribute()
    {
        return $this->expire_date->format('d/m/Y');
    }
    
    /**
     * Get expiry status with color coding
     */
    public function getExpiryStatusAttribute()
    {
        $days = $this->days_until_expiry;
        
        if ($days < 0) {
            return [
                'status' => 'Expired',
                'class' => 'badge-danger',
                'color' => 'danger'
            ];
        } elseif ($days <= 30) {
            return [
                'status' => 'Expiring Soon',
                'class' => 'badge-warning',
                'color' => 'warning'
            ];
        } elseif ($days <= 90) {
            return [
                'status' => 'Good',
                'class' => 'badge-info',
                'color' => 'info'
            ];
        } else {
            return [
                'status' => 'Fresh',
                'class' => 'badge-success',
                'color' => 'success'
            ];
        }
    }
}