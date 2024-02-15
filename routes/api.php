<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PreLoginController;
use App\Http\Controllers\API\PostLoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'auth:api'
  ], function() {
	Route::post("change-password","API\PostLoginController@changePassword");
    Route::get("user-profile","API\PostLoginController@userProfile");
	Route::post("add-address","API\PostLoginController@addAddress");
	Route::post("update-profile","API\PostLoginController@updateProfile");
	Route::post("delete-address","API\PostLoginController@deleteAddress");
	Route::get("get-address-by-id","API\PostLoginController@getAddressById");
	Route::get("address-list","API\PostLoginController@addressList");
	Route::post("add-cart","API\PostLoginController@addToCart");
	Route::post("delete-cart","API\PostLoginController@deleteCart");
	Route::get("delete-complete-cart","API\PostLoginController@deleteCompleteCart");
	Route::get("cart-list","API\PostLoginController@cartList");
	Route::post("checkout","API\PostLoginController@checkout");
	Route::get("order","API\PostLoginController@order");
	Route::post("wishlist-product","API\PostLoginController@wishlistProduct");
	Route::get("wishlist-product-list","API\PostLoginController@wishlistProductList");
	Route::post("delete-wishlist-product","API\PostLoginController@deleteWishlistProduct");
	Route::post("check-coupon-code","API\PostLoginController@checkCouponCode");
	Route::get("logout","API\PostLoginController@logout");
});

Route::post('contact-us', [PreloginController::class, 'contactUs']);
Route::get('slider-list',[PreLoginController::class, 'sliderList']);
Route::get('category-list',[PreLoginController::class, 'categoryList']);
Route::get('topselling-product',[PreLoginController::class, 'topSellingProduct']);
Route::get('product-list',[PreLoginController::class, 'productList']);

