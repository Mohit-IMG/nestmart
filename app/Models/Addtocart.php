<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addtocart extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'product_id', 'qty'];


    public function variantProduct()
    {
        return $this->belongsTo(Variantproduct::class, 'product_id', 'id');
    }

    public function cartProduct()
    {
        return $this->hasMany(Variantproduct::class, 'id', 'product_id');
    }
    
    public function cartProductName()
    {
        return $this->hasMany(Product::class, 'id', 'product_id');
    }



}
