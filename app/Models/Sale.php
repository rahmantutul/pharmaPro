<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_date',
        'invoice_no',
        'customerId',
        'is_walking_customer',
        'grand_total',
        'invoice_discount',
        'discount_type',
        'payable_total',
        'paid_amount',
        'due_amount',
        'paymentId',
        'status',
        'created_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'grand_total' => 'decimal:2',
        'invoice_discount' => 'decimal:2',
        'payable_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'is_walking_customer' => 'boolean',
    ];

    /**
     * Relationship with Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerId');
    }

    /**
     * Relationship with Payment Method
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'paymentId');
    }

    /**
     * Relationship with Sale Details
     */
    public function details()
    {
        return $this->hasMany(SaleDetails::class, 'salesId');
    }

    /**
     * Relationship with Stock
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'ref_id')->where('type', 'sales');
    }

    /**
     * Relationship with Transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'refId')
            ->whereIn('type', ['sale', 'customer_due', 'sale_payment']);
    }

    /**
     * Relationship with Creator (User)
     */
    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Scope for paid sales
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for partial paid sales
     */
    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }

    /**
     * Scope for due sales
     */
    public function scopeDue($query)
    {
        return $query->where('status', 'due');
    }

    /**
     * Scope for walking customers
     */
    public function scopeWalkingCustomer($query)
    {
        return $query->where('is_walking_customer', 1);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('invoice_date', [$startDate, $endDate]);
    }

    /**
     * Get formatted grand total
     */
    public function getFormattedGrandTotalAttribute()
    {
        return '৳' . number_format($this->grand_total, 2);
    }

    /**
     * Get formatted paid amount
     */
    public function getFormattedPaidAmountAttribute()
    {
        return '৳' . number_format($this->paid_amount, 2);
    }

    /**
     * Get formatted due amount
     */
    public function getFormattedDueAmountAttribute()
    {
        return '৳' . number_format($this->due_amount, 2);
    }

    /**
     * Get formatted invoice date
     */
    public function getFormattedDateAttribute()
    {
        return $this->invoice_date->format('d M Y');
    }

    /**
     * Get payment status badge
     */
    public function getPaymentStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'paid':
                return '<span class="badge badge-success">Paid</span>';
            case 'partial':
                return '<span class="badge badge-warning">Partial</span>';
            case 'due':
                return '<span class="badge badge-danger">Due</span>';
            case 'cancelled':
                return '<span class="badge badge-secondary">Cancelled</span>';
            default:
                return '<span class="badge badge-default">Unknown</span>';
        }
    }

    /**
     * Check if sale is fully paid
     */
    public function isPaid()
    {
        return $this->status === 'paid' && $this->due_amount == 0;
    }

    /**
     * Check if sale has due
     */
    public function hasDue()
    {
        return $this->due_amount > 0;
    }

    /**
     * Get discount percentage (if discount_type is 1)
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->discount_type == 1 && $this->invoice_discount > 0) {
            return ($this->invoice_discount / $this->grand_total) * 100;
        }
        return 0;
    }
}