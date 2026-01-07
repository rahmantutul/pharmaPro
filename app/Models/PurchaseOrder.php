<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $guarded =[];
    public function purchase_details(){
        return $this->hasMany(PurchaseOrderDetail::class,'order_id','id');
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplierId','id');
    }
}
