<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use Validator;
use App\Models\User;
use Razorpay\Api\Api;
use Exception;


class ProductController extends Controller
{


	public function productList(Request $request)
	{

		$offset = $request->input('offset', 0);
	
		$query = \App\Models\Product::select('products.name', 'products.store_id', 'variantproducts.id as variantproductid', 'variantproducts.sale_price', 'variantproducts.discount_type', 'variantproducts.discount_amount', 'variantproducts.slug', 'variantproducts.images')
			->where([['products.status', '=', '1']])
			->join('variantproducts', 'variantproducts.product_id', '=', 'products.id')
			->groupBy('variantproducts.product_id')
			->skip($offset)
			->take(10);
	
		if ($request->filled('min_price')) {
			$query->where('variantproducts.sale_price', '>=', $request->input('min_price'));
		}
	
		if ($request->filled('max_price')) {
			$query->where('variantproducts.sale_price', '<=', $request->input('max_price'));
		}
	
		if ($request->filled('category_slug')) {
			$getSlugCategoryId = \App\Models\Category::where('slug', $request->input('category_slug'))->first();
	
			if ($getSlugCategoryId) {
				$childCategory = \App\Helpers\commonHelper::getCategoryTreeidsArray($getSlugCategoryId->id);
	
				if ($childCategory) {
					$query->whereIn('products.category_id', $childCategory);
				} else {
					$query->where('products.category_id', $getSlugCategoryId->id);
				}
			}
		}
	
		if ($request->filled('brand_id')) {
			$brandIdArray = explode(',', $request->input('brand_id'));
	
			if (!empty($brandIdArray) && $brandIdArray[0] != '') {
				$query->where(function ($query1) use ($brandIdArray) {
					foreach ($brandIdArray as $brandId) {
						$query1->orWhere('products.brand_id', $brandId);
						$query1->orWhere('products.brand_id', 'LIKE', '%,' . $brandId);
						$query1->orWhere('products.brand_id', 'LIKE', $brandId . ',%');
						$query1->orWhere('products.brand_id', 'LIKE', '%,' . $brandId . ',%');
					}
				});
			}
		}
	
		if ($request->filled('attributeId')) {
			$attributeIdArray = explode(',', $request->input('attributeId'));
	
			if (!empty($attributeIdArray) && $attributeIdArray[0] != '') {
				$query->where(function ($query1) use ($attributeIdArray) {
					foreach ($attributeIdArray as $attributeId) {
						$query1->orWhere('variant_attributes', $attributeId);
						$query1->orWhere('variant_attributes', 'LIKE', '%,' . $attributeId);
						$query1->orWhere('variant_attributes', 'LIKE', $attributeId . ',%');
						$query1->orWhere('variant_attributes', 'LIKE', '%,' . $attributeId . ',%');
					}
				});
			}
		}
	
		$totalProducts = $query->count();
		$productResult = $query->get();
	
		if (!$productResult) {
			return response(['message' => 'Product not found.'], 403);
		} else {
			$result = [];
	
			foreach ($productResult as $value) {
				$imagesArray = explode(',', $value->images);
	
				$secondImage = null;
				if (isset($imagesArray[1])) {
					$secondImage = asset('uploads/variantproducts/' . $imagesArray[1]);
				}
	
				$result[] = [
					'variant_productid' => $value['variantproductid'],
					'name' => ucfirst($value['name']),
					'sale_price' => $value['sale_price'],
					'discount_amount' => $value['discount_amount'],
					'offer_price' => \App\Helpers\commonHelper::offerprice($value['sale_price'], $value['discount_type'], $value['discount_amount']),
					'first_image' => asset('uploads/variantproducts/' . $imagesArray[0]),
					'second_image' => $secondImage,
					'slug' => $value['slug'],
				];
			}

			return response(['message' => 'Product fetched successfully.', 'result' => $result, 'totalProducts' => $totalProducts], 200);
		}
	}

	public function productListing(Request $request, $categoryslug)
	{
		if ($request->ajax()) {
			$queryParameters = [
				'max_price' => $request->post('max_price'),
				'min_price' => $request->post('min_price'),
				'brand_id' => $request->post('brand_id'),
				'attributeId' => $request->post('attributeId'),
				'category_slug' => $request->post('categoryslug'),
			];
			$result = $this->productList(new Request($queryParameters));
	
			if ($result->status() == 200) {
				$resultData = json_decode($result->content(), true);
				$data = $resultData['result'];
	
				$html = view('product-listing-product', compact('data'))->render();
	
				return response([
					'message' => $resultData['message'],
					'html' => $html,
					'totalProducts' => $resultData['totalProducts'],
					'total' => count($data),
				], $result->status());
			}
	
			return response(['message' => $resultData['message']], $result->status());
		}
	
		$getCategoryId = \App\Models\Category::where('slug', $categoryslug)->first();
	
		if ($getCategoryId) {
			$getSelCategoryTreeId = \App\Helpers\commonHelper::getParentId($getCategoryId->id);
	
			$slugCategoryResult = \App\Models\Category::whereIn('id', explode(',', $getSelCategoryTreeId))->orderBy('id', 'Asc')->get();
	
			$categoryResult = [];
	
			$category = \App\Models\Category::whereNull('parent_id')->get();
	
			$categoryResult = \App\Models\Category::with('children')->whereNull('parent_id')->get();
			// dd($categoryResult);
	
			$brands = \App\Models\Brand::where('status', 'Active')->orderBy('id', 'Desc')->get();
	
			return view('product-listing', compact('getCategoryId', 'brands', 'category', 'categoryslug', 'slugCategoryResult', 'categoryResult'));
		} else {
			return redirect()->back()->with('5fernsuser_error', 'Something went wrong. Please try again.');
		}
	}
	
    public function productDetail(Request $request,$productdetailslug){

        $productResult=\App\Models\Product::with(['UserData' => function ($query1) {
            $query1->select('name','id');
        }])->Select('products.*','variantproducts.*','variantproducts.id as variantid','products.id as productid')->where([
                            ['products.status','=','1'],
                            ['products.recyclebin_status','=','0'],
                            ['variantproducts.status','=','1'],
                            ['variantproducts.slug','=',$productdetailslug]
                            ])->join('variantproducts','variantproducts.product_id','=','products.id')->first();
        
        
        $imagesArray=[];

        $imagesArrayList=explode(',',$productResult->images);

        if(!empty($imagesArrayList) && !empty($imagesArrayList)!=''){

            foreach($imagesArrayList as $image){

                $imagesArray[]=asset('uploads/products/'.$image);

            }
        }

        $variantsProductResults=\App\Models\Variantproduct::where('product_id',array($productResult->productid))->where('status','1')->where('recyclebin_status','0')->get();

        $attributeArray = array();

        if($variantsProductResults->count() > 0){

            $variantAttributeId = "";
            foreach($variantsProductResults as $variantProduct){

                $variantAttributeId.=$variantProduct->variant_attributes.',';

            }

            $variantAttributeId = rtrim($variantAttributeId,',');
            
            $attributeArray=array_unique(explode(',',$variantAttributeId));
        }

        $variantsResult=\App\Models\Variant::whereIn('id',explode(',',$productResult->variant_id))->where('status','1')->orderBy('sort_order','ASC')->get();

        $variants=[];
				
        $attributes=[];

        if($variantsResult->count() > 0){
            
            foreach($variantsResult as $variant){
					
                $attributes=\App\Models\Variant_attribute::whereIn('id',$attributeArray)->where('variant_id',$variant->id)->where('status','1')->orderBy('sort_order','ASC')->get();
                
                $variants[]=array(
                    'id'=>$variant->id,
                    'name'=>ucfirst($variant->name),
                    'display_layout'=>$variant->display_layout,
                    'attribute'=>$attributes
                );
            }

        }

        $result=[
            'product_id'=>$productResult->productid,
            'provariantid'=>$productResult->variantid,
            'category_id'=>$productResult->category_id,
            'name'=>ucfirst($productResult->name),
            'sale_price'=>$productResult->sale_price,
            'discount_type'=>$productResult['discount_type'],
            'discount_amount'=>$productResult->discount_amount,
            'offer_price'=>\App\Helpers\commonHelper::getOfferProductPrice($productResult['sale_price'],$productResult['discount_type'],$productResult['discount_amount']),
            'stock'=>$productResult->stock,
            'images'=>implode(',',$imagesArray),
            'short_description'=>$productResult->short_description,
            'description'=>$productResult->description,
            'country_origin'=>$productResult->country_origin,
            'variants'=>$variants,
            'variant_attributes'=>$productResult->variant_attributes,
            'meta_title'=>$productResult->meta_title,
            'meta_keywords'=>$productResult->meta_keywords,
            'meta_description'=>$productResult->meta_description,
            'store_name'=>$productResult['UserData']['name'],
        ];

        $wishlist = [];
        return view('product_detail',compact('result','wishlist'));

    }

	public function addToCart(Request $request){

        $product_id = $request->input('product_id');
        $product_qty = $request->input('product_qty');
    
        if(Auth::check()){

            $cart = \App\Models\addtocart::where('product_id',$product_id)->where('user_id',Auth::user()->id)->first();
                       
            if(!$cart){
                $cart=new \App\Models\Addtocart();
                $cart->user_id=Auth::user()->id;
                $cart->product_id=$request->product_id;
                $cart->qty=$request->get('product_qty');
                $cart->save();

                $wishlist = \App\Models\Wishlist::where('product_id',$request->product_id)->where('user_id',Auth::user()->id)->delete();

                return response(array("message" => "Product successfully added to cart."), 200);	
            }else{
                if($request->get('update') == 'update'){

                    $cart->qty=$request->get('product_qty');	

                }else{
                    $cart->qty+=$request->get('product_qty');
                }
                $cart->save();
                return response(array("message" => "Product quantity updated in cart."), 200);	
            }

        }
							
	}

    public function productWishlist(Request $request){

		if(!Session::has('userToken')){

			Session::flash('5fernsuser_error','Please first login.');
			return response(array('message'=>'Please login first','login'=>false),200);

		}else{

            $result=\App\Models\Variantproduct::where('id',$request->get('product_id'))->where('status','1')->first();
            if($result){
                
                $checkWishlist=\App\Models\Wishlist::where([
                    ['product_id',$request->get('product_id')],
                    ['user_id',\Auth::user()->id],
                    ])->first();
                    // print_r($result);die;

                if($checkWishlist){

                    \App\Models\Wishlist::where('id',$checkWishlist->id)->delete();

                    $wishlistResult=\App\Models\Wishlist::select('product_id')->where('user_id',\Auth::user()->id)->pluck('product_id')->toArray();

                    return response(array("message" => "Product successfully removed from wishlist.","wishlistid"=>$wishlistResult),200);

                }else{	
                    
                    $wishlist=new \App\Models\Wishlist();
                    $wishlist->user_id=\Auth::user()->id;
                    $wishlist->product_id=$request->get('product_id');
                    $wishlist->save();

                    $wishlistResult=\App\Models\Wishlist::select('product_id')->where('user_id',\Auth::user()->id)->pluck('product_id')->toArray();

                    return response(array("message" => "Product Wishlisted successfully.","wishlistid"=>$wishlistResult),200); 
                }

				Session::put('wishlist_user',$resultData['wishlistid']);
			}
			return response(array('message'=>$resultData['message'],'login'=>true),$apiData->status);

		}
	}

    public function deleteWishlistProduct(Request $request,$id){

        $deleteWishlist = \App\Models\Wishlist::where('product_id',$id)->delete();
        
        return redirect('user/wishlists');
    }

    public function totalCart(){
		if(\Auth::check()){
			$total_cart = \App\Models\Addtocart::where('user_id', '=', \Auth::user()->id)->count(); 
			$total_wishlist = \App\Models\Wishlist::where('user_id', '=', \Auth::user()->id)->count(); 
			return response(array('message' => "added namaste", 'total_count' => $total_cart, 'total_wishlist' => $total_wishlist), 200);
		}
	}

    public function cart(Request $request){

		if(!Session::has('userToken')){

			$result=Session::get('5ferns_cartuser');
			
		}else{

			$apiData=\App\Helpers\commonHelper::callAPI('userTokenget','/cart-list');

			$result=[];
			
			if($apiData->status == 200){
	
			$apiDataResult = json_decode($apiData->content,true);
	
			$result = $apiDataResult['data'];
		}
		
		$resultAddressBook = \App\Models\Addressbook::where('user_id',\Auth::user()->id)->get();
		return view('cart',compact('result','resultAddressBook'));
		}
	
		
	}
	
	public function getCartPriceDetails(Request $request){
		
		$result=array(); $dd = 0; $totalItems='0'; $totalMrp=0;$totalShipping=0; $discountAmount=0; $finalAmount=0;$couponAmount=0;$shoppingWallet = 0;$coupondiscount=0;

		if(!Session::has('userToken') && Session::get('5ferns_cartuser')){
			
			$result=Session::get('5ferns_cartuser');

		}else{

			$result=[];

			$apiData=\App\Helpers\commonHelper::callAPI('userTokenget','/cart-list');
			$resultData=json_decode($apiData->content,true);

			if($apiData->status==200){

				$result=$resultData['data'];
			}

		}
		
		$totalItems = count($result);
		if(!empty($result)){

			foreach($result as $value){

				$shippingAmount=0;

				$totalShipping+=($shippingAmount*$value['qty']);
				$totalMrp+=($value['sale_price']*$value['qty']);
				$discountAmount+=(($value['sale_price']-$value['offer_price'])*$value['qty']);
				$finalAmount+=(($value['offer_price'])*$value['qty']);
			}
		}

                $html=view('cart_price_details',compact('totalItems','totalMrp','totalShipping','discountAmount','finalAmount'))->render();
                return response(array('message'=>'Product price detail fetched successfully.','html'=>$html));
	}

    public function updateCart(Request $request)
    {
        if (Auth::check()) {
            $id = $request->input('id');
            $product_id = $request->input('product_id');
            $product_qty = $request->input('product_qty');
            $update = $request->input('update');
    
            $cart = \App\Models\addtocart::where('id', $id)->where('user_id', Auth::user()->id)->first();
    
            if ($cart) {
                if ($update == 'update') {
                    $cart->qty = $product_qty;
                } else {
                    $cart->qty += $product_qty;
                }
    
                $cart->save();
                return response(['message' => 'Product quantity updated in cart.'], 200);
            } else {
                return response(['message' => 'Cart item not found.'], 404);
            }
        } else {
            return response(['message' => 'User not authenticated.'], 401);
        }
    }

    public function deleteAall(Request $request)
    {
            $cartItems = \App\Models\Addtocart::where('user_id', $request->user()->id)->get();

            foreach ($cartItems as $cartItem) {
                $cartItem->delete();
            }

            return response(['message' => 'Cart deleted successfully.'], 200);

    }

	public function deleteCart(Request $request,$cartId){
		
		if(isset($request) && (Session::has('userToken'))){

			$result=\App\Helpers\commonHelper::callAPI('userTokenpost','/delete-cart',json_encode(array('id'=>$cartId)));
			$resultData=json_decode($result->content,true);

			Session::flash('5fernsuser_success',$resultData['message']);

		}else{

			$cartData=Session::get('5ferns_cartuser');

			if(!empty($cartData) && isset($cartData[$cartId])){

				unset($cartData[$cartId]);

				Session::put('5ferns_cartuser',$cartData);

				Session::flash('5fernsuser_success','Cart delete successfully.');

			}else{

				Session::flash('5fernsuser_error','Something went wrong. Please try again.');

			}
		} 

		return redirect('cart');
	}

    public function checkCoupon(Request $request)
    {
        $couponCode = $request->input('coupon_code');

        // Replace the following with your actual coupon validation logic
        if ($couponCode === "YOUR_VALID_COUPON_CODE") {
            // Assume a fixed discount amount for demonstration purposes
            $discountAmount = 10.00;

            return response()->json(['amount' => $discountAmount]);
        } else {
            return response()->json(['error' => 'Invalid coupon code.']);
        }
    }

    public function checkOut(Request $request){
		if($request->ajax()){

			$rules['payment_type']='required|in:1,2';

			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()){
				
				$message = "";
				$messages_l = json_decode(json_encode($validator->messages()), true);
				foreach ($messages_l as $msg) {
					$message= $msg[0];
					break;
				}
				
				return response(array('message'=>$message),403);
				
				
			}else{


				if(Session::has('userToken')){

                    if($request->post('address_id') == '0'){
						
						$data=array(
							'type'=>'1',
							'name'=>$request->post('name'),
							'email'=>$request->post('email'),
							'phone_code'=>'91',
							'mobile'=>$request->post('mobile'),
							'country_id'=>'101',
							'state_id'=>$request->post('state_id'),
							'city_id'=>$request->post('city_id'),
							'address_line1'=>$request->post('address_1'),
							'address_line2'=>$request->post('address_2'),
							'pincode'=>$request->post('pincode'),
							'payment_type'=>$request->post('payment_type'),
							'address_id'=>$request->post('address_id'),
							'shopping_wallet'=>$request->post('shopping_wallet') == true ? 1 : 0,
						);

					}else{

						$data=array(
							'type'=>'1',
							'address_id'=>$request->post('address_id'),
							'payment_type'=>$request->post('payment_type'),
							'shopping_wallet'=>$request->post('shopping_wallet') == true ? 1 : 0,
						);
						
					}
					
					
					$apiData=\App\Helpers\commonHelper::callAPI('userTokenpost','/checkout',json_encode($data));
					// echo "<pre>";print_r($apiData);die;

					
					
				}else{
		
					return response(array('message'=>'Login required'),403);
				}

				$resultData=json_decode($apiData->content,true);
				$apiData=\App\Helpers\commonHelper::callAPI('userTokenpost','/checkout',json_encode($data));
				// echo "<pre>";print_r($resultData);die;
				// order confirmation
				$data = array(
					'name'=>$resultData['address']['name'],
					'email'=>$resultData['address']['email'],
					'mobile'=>$resultData['address']['mobile'],
					'add_line1'=>$resultData['address']['address_line1'],
					'add_line2'=>$resultData['address']['address_line2'],
					'order_id'=>$resultData['order'],
					'order_date'=>date('Y-m-d'),
					// 'product_name'=>$salesDetail->product_name,
					// 'qty'=>$salesDetail->qty,
					// 'product_price'=>$salesDetail->amount,
					// 'product_image'=>$salesDetail->product_image,
					'template' => 'order_confirmation',
					'image' => 'https://i.ibb.co/PrrYkH1/logo.png',
					'subject' => "Your Order Confirmation: ".$resultData['order'] ,
				);
				
				// welcome Coupon used
				$user = \App\Models\User::find(\Auth::user()->id); 
				$user->couponstatus='used';
                $user->save();
				
				// Admin Coupon destroy
				$user = \Auth::user();
				$enteredCode = session('enteredCode');
				if ($user) {
					$validCoupons = json_decode($user->offer_coupons);

					if (is_array($validCoupons)) {
						$validCoupons = array_diff($validCoupons, [$enteredCode]);
						// Update the user's offer_coupons without the used coupon
						$user->offer_coupons = array_values($validCoupons); // Reset array keys
						$user->save();
					}
				}
				
				session()->forget('enteredCode');   //session destroy
				
				// \App\Helpers\commonHelper::emailSendToUser($data);
                
				if($apiData->status==200){
					
					if($resultData['checkout_type'] == 'cod'){
						
						return response(array('message'=>'Order Placed successfully.','checkout_type'=>'cod','checkout_order_id'=>$resultData['order']),$apiData->status);
	
					}elseif($resultData['checkout_type'] == 'online'){
						
						if($request->post('address_id') == '0'){
	
							$name=$request->post('name');
							$mobile=$request->post('mobile');
							$email=$request->post('email');
							
						}else{
		
							$address= \App\Models\Addressbook::where('id',$request->post('address_id'))->first();
	
							$name=$address->name;
							$mobile=$address->mobile;
							$email=$address->email;
						}
						
						return response(array('message'=>"Checkout successfully.",'checkout_type'=>'online','type'=>'razorpay','checkout_order_id'=>$resultData['order'], 'data'=>$resultData),200);
						
					
					}
					
					
				}else{
	
					return response(array('message'=>$resultData['message']),403);
				}
			}

		}else{

            $resultAddressBook= \App\Models\Addressbook::where('user_id',\Auth::user()->id)->get();
			
			$states=\App\Models\State::where('country_id','101')->orderBy('name','Asc')->get();

            $cartProductDetail = \App\Models\Addtocart::with('cartProduct')->get();

			return view('checkout',compact('states','resultAddressBook','cartProductDetail'));
		}
		
	}

    public function removeCoupon(){

		if(session('enteredCode')){
			session()->forget('enteredCode');
		}else{
			$removeCoupon = \App\Models\User::where('id',\Auth::user()->id)->first();
			$removeCoupon->couponstatus='active';
			$removeCoupon->save();
		}

        return redirect()->back();
    

    }

    public function orderPlaced(Request $request){

		if(isset($_GET['order_id']) && $_GET['order_id'] != ''){

			$result=\App\Models\Sales::where('order_id',$_GET['order_id'])->first();

			$orderId = $_GET['order_id'];

			if($result->payment_type == '1' || $result->payment_type == '4'){

				$curl = curl_init();

				curl_setopt_array($curl, array(
					CURLOPT_URL => env('CASHFREE_URL')."/".$orderId,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'GET',
					CURLOPT_HTTPHEADER => array(
						'x-client-id: '.env('CASHFREE_API_KEY'),
						'x-client-secret: '.env('CASHFREE_API_SECRET'),
						'x-api-version: 2021-05-21'
					),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if(!$err) {
					
					$result = json_decode($response, true);

					if($result["order_status"] == 'PAID'){

						return view('order_placed',compact('orderId'));

					}else {
						
						return redirect('user/checkout')->with('5fernsuser_error','Order payment declined!');
					}

				}else {

					return redirect('user/checkout')->with('5fernsuser_error','Something went wrong.please try again');


				}


			}else{

				if($result){

					return view('order_placed',compact('orderId'));

				}else{

					return redirect('user/checkout')->with('5fernsuser_error','Something went wrong.please try again');

				}
			}

		}else{

			return redirect('/')->with('5fernsuser_error','Something went wrong.please try again');

		}

	}

	public function getProductSlug($product_id)
	{

		$productSlug = \App\Models\Variantproduct::where('id', $product_id)->first();
		$slug = $productSlug->slug;

		return response()->json(['product_slug' => $slug]);
	}

	public function getCartContent(){
		$userCartData = \App\Models\Addtocart::where('id',\Auth::user()->id)->get();
		return view('hover_cart_content', compact('userCartData'))->render();
	}


	public function showNotifications()
	{
		$user = User::find(auth()->user()->id);
	
		$userNotificationData = \App\Models\Notification::where('notifiable_id', $user->id)
			->get();
		
		return view('notifications', compact('userNotificationData'))->render();
	}
	
	

}
