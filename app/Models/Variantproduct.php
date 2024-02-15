<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variantproduct extends Model
{
    use HasFactory;

    public function proData()
    {
        return $this->hasOne(Product::class , 'id','product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cartItem()
    {
        return $this->belongsTo(Addtocart::class);
    }
}
