<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\VariantAttributeController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\RazorpayPaymentController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


	Route::get('/', "HomeController@index");

	Route::get('login', 'Auth\LoginController@showLoginForm');
	Route::post('login', 'Auth\LoginController@login')->name('admin.login'); 

	Route::match(['get','post'],'/change-password', 'Admin\AdminController@changePassword')->name('changepassword');
 
	/*--------------------------------ADMIN---------------------------------------------------------------*/

	Route::group(['prefix'=>'admin','as'=>'admin','middleware'=>['auth','checkadmin'],'as'=>'admin.'],function() {
		
		Route::match(['get','post'],'/logout','Auth\LoginController@logout')->name('logout');
		Route::match(['get','post'],'/dashboard', 'Admin\DashboardController@index');

		// Slider
		Route::group(['prefix'=>'slider'],function() {
			Route::match(['get','post'],'add','Admin\SliderController@add')->name('slider.add');
			Route::get('list', 'Admin\SliderController@sliderList');
			Route::post('change-status','Admin\SliderController@changeStatus')->name('slider.changestatus');
			Route::get('update/{id}','Admin\SliderController@updateSlider');
			Route::get('delete/{id}','Admin\SliderController@deleteSlider');
			Route::post('change-order','Admin\SliderController@changeOrder')->name('slider.change-order');
		});

	// sales list
	Route::group(['prefix'=>'sales'],function() {
		Route::get('list/{type}', 'Admin\SalesController@salesList');
		Route::post('getsaledetail','Admin\SalesController@getSalesDetail');
		Route::post('update-orderstatus','Admin\SalesController@updateOrderStatus');
		Route::get('download-packaging-slip/{no}','Admin\SalesController@downloadPackagingSlip');
		Route::get('order-invoice/{no}','Admin\SalesController@orderInvoice');
		Route::get('ondemand-enquiry','Admin\SalesController@ondemandEnquiry');
		Route::post('orderready','Admin\SalesController@orderReady')->name('sales.orderready');
		Route::post('return-approve','Admin\SalesController@returnApprove')->name('sales.returnapprove');
		Route::post('return-reject','Admin\SalesController@returnReject')->name('sales.returnreject');

		//mannual order
		Route::match(['get','post'],'mannual-orders/create-order','Admin\SalesController@createMannualOrder')->name('sales.createmanualorder');
		Route::get('mannual-orders/orders-list','Admin\SalesController@mannualOrdersList');
		Route::post('mannual-getsaledetail','Admin\SalesController@getMannualSalesDetail');
		Route::get('mannual-orders/order-invoice/{id}','Admin\SalesController@mannualOrderInvoice');
	});

				// Coupon
				Route::group(['prefix'=>'coupon'],function() {
					Route::match(['get','post'],'add','Admin\CouponController@add')->name('coupon.add');
					Route::get('list', 'Admin\CouponController@couponList');
					Route::post('change-status','Admin\CouponController@changeStatus')->name('coupon.changestatus');
					Route::get('update/{id}','Admin\CouponController@updateCoupon');
					Route::get('delete/{id}','Admin\CouponController@deleteCoupon');
					Route::post('change-order','Admin\CouponController@changeOrder')->name('coupon.change-order');
					Route::post('generate','Admin\CouponController@generateRandomCoupon')->name('coupon.generate');
					Route::post('sendToAllUsers/{id}', 'Admin\CouponController@sendToAllUsers')->name('coupon.sendToAllUsers');



				});

		Route::group(['prefix'=>'catalog'],function() {
			
			Route::group(['prefix'=>'category'],function() {

				Route::match(['get','post'],'add', 'Admin\CategoryController@add')->name('category.addcategory');
				Route::get('/list', 'Admin\CategoryController@categoryList');
				Route::get('update/{id}','Admin\CategoryController@updateCategory');
				Route::get('delete/{id}','Admin\CategoryController@deleteCategory');
				Route::post('change-status','Admin\CategoryController@changeStatus')->name('category.changestatus');
				Route::post('select-topcategory','Admin\CategoryController@selectTopCategory')->name('category.selectyopcategory');
			});

			Route::group(['prefix'=>'brand'],function() {
					
				Route::match(['get','post'],'add', 'Admin\BrandController@add')->name('brand.addbrand');
				Route::get('/list', 'Admin\BrandController@brandList');
				Route::get('brand-update/{id}','Admin\BrandController@updateBrand');
				Route::get('brand-delete/{id}','Admin\BrandController@deleteBrand');
				Route::post('change-status','Admin\BrandController@changeStatus')->name('brand.changestatus');
				Route::post('change-order','Admin\BrandController@changeOrder')->name('brand.change-order');
			});

			Route::group(['prefix'=>'product'],function() {
				Route::match(['get','post'],'add', 'Admin\ProductController@add')->name('product.addproduct');
				Route::match(['get','post'],'list', 'Admin\ProductController@productList')->name('product.filter');
				Route::get('update/{id}','Admin\ProductController@updateProduct');
				Route::get('delete/{id}','Admin\ProductController@deleteProduct');
				Route::post('change-status','Admin\ProductController@changeStatus')->name('product.changestatus');
				Route::post('topselling-status','Admin\ProductController@topSellingStatus')->name('product.topsellingstatus');
				Route::post('dealsoftheday','Admin\ProductController@dealsofTheDay')->name('product.dealsoftheday');
				Route::post('dealsoftheweek','Admin\ProductController@dealsofTheWeek')->name('product.dealsoftheweek');
				Route::post('newarrival','Admin\ProductController@newArrival')->name('product.newarrival');
				
				Route::match(['get','post'],'add-variant-product/{any}', 'Admin\ProductController@addVariantProduct')->name('product.addvariant');
				Route::get('variant-productlist/{id}', 'Admin\ProductController@variantProductList');
				Route::get('update-variant-product/{pparent_id}/{variant_id}', 'Admin\ProductController@updateVariantProduct');
				Route::get('delete-variantproduct/{id}','Admin\ProductController@deleteVariantProduct');
				Route::post('variantproduct-change-status','Admin\ProductController@changeVariantProductStatus')->name('product.variantproduct.changestatus');
			});

			Route::group(['prefix'=>'variant-attribute'],function() {
				Route::match(['get','post'],'add', 'Admin\ProductController@addVariantAttribute');
				Route::get('list', 'Admin\ProductController@attributeList');
				Route::get('update-variant-attribute/{id}','Admin\ProductController@updateVariantAttribute');
			});
			
			Route::group(['prefix'=>'variant'],function() {
				Route::match(['get','post'],'add', 'Admin\ProductController@addVariant');
				Route::get('list', 'Admin\ProductController@VariantList');
				Route::get('update/{id}','Admin\ProductController@updateVariant');
				Route::post('status','Admin\ProductController@statusVariant')->name('status.variant');
			});
			
		});
		

	});

	/*--------------------------------USER---------------------------------------------------------------*/

	
	Route::group(['prefix'=>'user','as'=>'user','middleware'=>['user-auth','checkUser'],'as'=>'user.'],function() {
		
		Route::match(['get','post'],'/dashboard', 'User\UserController@myaccount')->name('dashboard');
		Route::post('update-profile','ProfileController@updateProfile')->name('updateProfile');
		Route::get('wishlists','ProfileController@myWishlist')->name('my-wishlists');
		Route::get('delete-wishlist-product/{id}', "ProductController@deleteWishlistProduct");
		Route::match(['get','post'],'checkout','ProductController@checkout')->name('checkout');
		Route::get('order-placed','ProductController@orderPlaced');
		Route::get('remove-coupon', "ProductController@removeCoupon")->name('remove-coupon');
	});

	Route::match(['get', 'post'], 'contact-page', "HomeController@contacPage")->name('contact');
	Route::match(['post','get'],'category/{categoryslug}', "ProductController@productListing")->name("product-listing");
	Route::get('product_detail/{productdetailslug}', "ProductController@productDetail");

	Route::post('add-to-cart', "ProductController@addTocart");
	Route::get('/get-total-cart', "ProductController@totalCart");

	Route::post('wishlist-product', "ProductController@productWishlist")->name('wishlist-product'); 

	Route::get('user-login', 'Auth\LoginController@userLoginForm')->name('user-login');

	
	
	Route::post('user-login', 'Auth\LoginController@userLogin')->name('user.login'); 

	Route::post('/generate-and-send-magic-link', 'Auth\LoginController@generateAndSendMagicLink')->name('generate.and.send.magic.link');

	Route::match(['get', 'post'], '/verify-magic-link/{token}', 'Auth\LoginController@verifyMagicLink')->name('verify.magic.link');

	


	Route::match(['get','post'],'user-register', "Auth\RegisterController@userStore")->name('user.registration');
 
		// show cart items user
		Route::get('cart', "ProductController@cart")->name('cart'); 
		Route::get('cart-price-details', "ProductController@getCartPriceDetails")->name('cart-price-details'); 
		Route::post('update-cart', "ProductController@updateCart")->name('update-cart');
		Route::get('delete-cart/{id}', "ProductController@deleteCart");

		Route::post('/apply-coupon', 'CouponController@checkCoupon');
		Route::post('/reset-coupon-status', 'CouponController@resetCouponStatus')->name('reset-coupon-status');
		Route::get('/empty-cart', 'ProductController@deleteAall');
		Route::get('get-city', "HomeController@getCity");  

		Route::get('/get-cart', 'ProductController@getCartContent')->name('get-cart');



// google login
Route::get('auth/google', 'Auth\SocialLoginController@redirectToGoogle')->name('auth.google');
Route::get('auth/google/callback', 'Auth\SocialLoginController@handleGoogleCallback')->name('auth.google.callback');


Route::get('/login/github', 'Auth\SocialLoginController@redirectToGitHub')->name('auth.github');

Route::get('razorpay-payment', [RazorpayPaymentController::class, 'index'])->name('razorpay.payment.index');
Route::post('razorpay-payment', [RazorpayPaymentController::class, 'store'])->name('razorpay.payment.store');
Route::post('/razorpay-callback', [RazorpayPaymentController::class, 'razorpayCallback'])->name('razorpay.callback');

// GitHub Login Routes
Route::get('/auth/github', [SocialLoginController::class, 'redirectToGitHub'])->name('auth.github');
Route::get('/auth/github/callback', [SocialLoginController::class, 'handleGitHubCallback'])->name('auth.github.callback');

Route::match(['get','post'],'/logout','Auth\LoginController@logout')->name('logout');
// routes/web.php
Route::get('/get-product-slug/{product_id}', 'ProductController@getProductSlug');


Route::get('/get-user-address', 'ProfileController@getAddress');


Route::get('/get-address/{id}', [AddressController::class, 'getAddress'])->name('getAddress');

Route::match(['get','post'],'/update-address/{id}', 'User\UserController@updateBillingDetails')->name('updateAddress');

Route::post('/cancel-order/{id}', 'User\UserController@cancelOrder')->name('cancelOrder');

//password reset-check
Route::post('password-check', 'Auth\ResetPasswordController@checkCurrentPassword')->name('password-check');
Route::post('update-password', 'Auth\ResetPasswordController@updatePassword')->name('update-password');


//forgot-password
Route::get('/forgot-password', 'Auth\ResetPasswordController@forgortPasswordPage')->name('forgot-password-page');
Route::post('/send-otp', 'Auth\ResetPasswordController@sendOTP')->name('send-otp');
Route::post('/resend-otp', 'Auth\ResetPasswordController@resendOTP')->name('resend-otp');
Route::post('/reset-password', 'Auth\ResetPasswordController@resetPassword')->name('reset-password');
Route::post('/delete-previous-otp', 'Auth\ResetPasswordController@deletePreviousOTP')->name('delete-previous-otp');

// Notifications
Route::get('/notifications', 'ProductController@getNotifications')->name('getNotifications');

Route::get('/get-notifications', 'ProductController@showNotifications')->name('get-notifications-onhover');


Route::get('invoice', "InvoiceController@invoiceView")->name('invoiceDetail');
Route::get('generate-and-download-invoice/{order_id}', "InvoiceController@generateInvoice")->name('generateAndDownloadInvoice');
Route::get('/download-invoice/{order_id}', "InvoiceController@downloadInvoice")->name('downloadInvoice');
