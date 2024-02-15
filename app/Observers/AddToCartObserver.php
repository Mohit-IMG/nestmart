<?php

namespace App\Observers;

use App\Models\AddToCart;

class AddToCartObserver
{
    public function updated(AddToCart $addToCart)
    {
        // Check if the quantity column has changed
        if ($addToCart->isDirty('quantity')) {
            // Reset coupon status for the user
            $addToCart->user->update(['couponstatus' => 'active']);
        }
    }
}
