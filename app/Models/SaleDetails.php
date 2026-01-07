<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetails extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function medicine(){
        return $this->belongsTo(Medicine::class,'medicineId','id');
    }
}
