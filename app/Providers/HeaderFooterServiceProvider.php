<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Notification;
class HeaderFooterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Share data with all views
        View::composer('*', function ($view) {
            // Get the authenticated user
            $user = auth()->user();

            // Define the data you want to pass to the header and footer sections
            if ($user) {
                $userCartData = $user->cartItems()->with('variantProduct')->limit(3)->get();
                $totalPrice = $userCartData->sum('totalPrice');

                $userNotificationData = Notification::where('notifiable_id', $user->id)
                ->get();
            } else {
                $userCartData = [];
                $totalPrice = 0;
                $userNotificationData  = [];
            }
            $view->with(compact('userCartData', 'totalPrice', 'userNotificationData'));
        });
    }
}
