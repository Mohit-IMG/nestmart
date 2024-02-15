<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    public function productData()
    {
        return $this->hasMany(Variantproduct::class , 'product_id','id');
    }


    public function getProduct(){

        return $this->hasOne('\App\Models\Variantproduct', 'product_id');

    }

    public function UserData()
    {
        return $this->hasOne(User::class , 'id','store_id');
    }

    public function variantProducts()
    {
        return $this->hasMany(VariantProduct::class);
    }
// for search product
    public function variants()
    {
        return $this->hasMany(\App\Models\VariantProduct::class, 'product_id');
    }

    
}
