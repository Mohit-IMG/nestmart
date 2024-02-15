<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Validator;
use Auth;
use Session;
use DB;
use Str;
use Carbon\Carbon;
use App\Models\Addtocart;
use App\Models\Variantproduct;

class PostLoginController extends Controller
{
    public function wishlistProductList(Request $request)
    {
        try {
            $result = \App\Models\Wishlist::select('wishlists.id as wishlistid', 'products.name', 'variantproducts.id', 'variantproducts.sale_price', 'variantproducts.discount_type', 'variantproducts.discount_amount', 'variantproducts.slug', 'variantproducts.images')
                ->join('variantproducts', 'variantproducts.id', '=', 'wishlists.product_id')
                ->join('products', 'products.id', '=', 'variantproducts.product_id')
                ->where([
                    ['products.status', '=', '1'],
                    ['variantproducts.status', '=', '1'],
                    ['wishlists.user_id', '=', $request->user()->id],
                ])->get();

            if ($result->count() == 0) {
                return response(array("message" => 'Products Not Found.'), 404);
            } else {
                $products = [];
                foreach ($result as $value) {
                    $imagesArray = explode(',', $value->images);

                    $products[] = [
                        'wishlistid' => $value['wishlistid'],
                        'variant_productid' => $value['id'],
                        'name' => ucfirst($value['name']),
                        'sale_price' => $value['sale_price'],
                        'discount_amount' => $value['discount_amount'],
                        'offer_price' => \App\Helpers\commonHelper::getOfferProductPrice($value['sale_price'], $value['discount_type'], $value['discount_amount']),
                        'first_image' => asset('uploads/products/' . $imagesArray[0]),
                        'slug' => $value['slug']
                    ];
                }

                return response(array("message" => 'Wishlist Product fetched successfully.', 'result' => $products), 200);
            }
        } catch (\Exception $e) {
            return response(array("message" => $e->getMessage()), 403);
        }
    }

    public function cartList(Request $request)
    {
        try {
            $result = DB::table('addtocarts')
                ->select('addtocarts.id as cartid', 'products.name as productname', 'addtocarts.qty', 'variantproducts.sale_price', 'variantproducts.id', 'variantproducts.discount_type', 'variantproducts.discount_amount', 'variantproducts.images', 'products.short_description', 'variantproducts.stock')
                ->join('variantproducts', 'variantproducts.id', '=', 'addtocarts.product_id')
                ->join('products', 'products.id', '=', 'variantproducts.product_id')
                ->where('addtocarts.user_id', '=', Auth::user()->id)
                ->get();

            if ($result->count() == 0) {
                return response(array('message' => 'Cart list is empty !'), 404);
            } else {
                $data = [];
                $i = 0;
                while ($i < count($result)) {
                    $cartListDetail = $result[$i];
                    $image = explode(',', $cartListDetail->images);

                    $data[] = [
                        'cartid' => $cartListDetail->cartid,
                        'product_id' => $cartListDetail->id,
                        'name' => $cartListDetail->productname,
                        'qty' => $cartListDetail->qty,
                        'sale_price' => $cartListDetail->sale_price,
                        'offer_price' => \App\Helpers\commonHelper::offerprice($cartListDetail->sale_price, $cartListDetail->discount_type, $cartListDetail->discount_amount),
                        'discount_amount' => $cartListDetail->discount_amount,
                        'discount_type' => $cartListDetail->discount_type,
                        'image' => asset('uploads/products/' . $image[0]),
                        'short_description' => $cartListDetail->short_description,
                        'stock' => $cartListDetail->stock
                    ];

                    $i++;
                }

                return response(array("message" => "Cart list data fetched successfully.", "data" => $data), 200);
            }
        } catch (\Exception $e) {
            return response(array("message" => "Something went wrong. Please try again"), 403);
        }
    }

    public function deleteCart(Request $request)
    {
        $rules = [
            'id' => 'required|numeric'
        ];

        $validator = Validator::make($request->json()->all(), $rules);

        if ($validator->fails()) {
            $message = '';
            $messages_l = json_decode(json_encode($validator->messages()), true);

            for ($i = 0; $i < count($messages_l); $i++) {
                $msg = $messages_l[$i];
                $message = $msg[0];
                break;
            }

            return response(array('message' => $message), 403);
        } else {
            try {
                $cartItem = \App\Models\Addtocart::where([
                    ['user_id', '=', $request->user()->id],
                    ['id', '=', $request->json()->get('id')]
                ])->first();

                if (!$cartItem) {
                    return response(['message' => 'Product not found in the cart.'], 404);
                }

                $cartItem->delete();

                return response(['message' => 'Product deleted successfully.'], 200);
            } catch (\Exception $e) {
                return response(['message' => 'Something went wrong. Please try again.'], 403);
            }
        }
    }

    public function checkout(Request $request){
		// $requestArray = $request->toArray();
		// $fixedArray = json_decode(array_key_first($requestArray), true);
		
		// echo "<pre>";
		// print_r($fixedArray);
		// die;
		// echo "hii";die;working
		$rules['type']='numeric|required|in:1,2';
		
		$rules['address_id']='numeric|required';

		if($request->json()->get('address_id') == '0'){

			$rules['name']='string|required';
			$rules['email']='email|required';
			$rules['mobile']='required';
			$rules['country_id']='required|numeric';
			$rules['phone_code']='required|numeric';
			$rules['state_id']='required|numeric';
			$rules['city_id']='required|numeric';
			$rules['address_line1']='required|string';

			if($request->json()->get('country_id')=='101'){

				$rules['pincode']="required|digits:6";
	
			}else{
	
				$rules['pincode']="required|digits:5";
			}

		}
		$rules['payment_type']='required|numeric|in:1,2';
		
		$customMessages = [
			'required' => 'The :attribute field is required.'
		];
		
		$validator = Validator::make($request->json()->all(), $rules, $customMessages);
		if ($validator->fails()){
			
			$message = "";
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('message'=>$message),403);
			
		}else{
			
			try{
				// $requestArray = $request->toArray();
				// $fixedArray = json_decode(array_key_first($requestArray), true);
				
				// echo "<pre>";
				// print_r($fixedArray);
				// die;
				$totalSales =  \App\Models\Sales::count();

				$sales=new \App\Models\Sales();
				
				$orderId=date("Y").str_pad(date("m"), 2, "0", STR_PAD_LEFT).str_pad(date("d"), 2, "0", STR_PAD_LEFT)."-FFP-".rand(11,99).str_pad(($totalSales+1), 3, "0", STR_PAD_LEFT);

				$sales->order_id=$orderId;

				if($request->json()->get('address_id') == '0'){

					$address= new \App\Models\Addressbook();
					$address->user_id=$request->user()->id;
					$address->name=$request->json()->get('name');
					$address->mobile=$request->json()->get('mobile');
					$address->email=$request->json()->get('email');
					$address->address_line1=$request->json()->get('address_line1');
					$address->address_line2=$request->json()->get('address_line2');
					$address->country_id=$request->json()->get('country_id');
					$address->state_id=$request->json()->get('state_id');
					$address->city_id=$request->json()->get('city_id');
					$address->pincode=$request->json()->get('pincode');
					$address->save();
					
				}else{

					$address= \App\Models\Addressbook::where('id',$request->json()->get('address_id'))->first();
				}

				$sales->user_id=$request->user()->id; 
				$sales->checkout_type='2';
				$sales->name=$address->name;
				$sales->email=$address->email;
				$sales->phone_code='+91';
				$sales->mobile=$address->mobile;
				$sales->country_id=$address->country_id;
				$sales->state_id=$address->state_id;
				$sales->city_id=$address->city_id;
				$sales->address_line1=$address->address_line1;
				$sales->address_line2=$address->address_line2;
				$sales->pincode=$address->pincode;
				$sales->type='home';

				$cartData=\App\Models\Addtocart::where('user_id',$request->user()->id)->get();
				$sales->payment_type=$request->json()->get('payment_type');

				$subTotal=0; $totalShipping=0; $discount=0; $couponId=0; $couponAmount=0; $netAmount=0;$shoppingWallet =0;

				if(!empty($cartData)){

					foreach($cartData as $cart){
						
						$productResult= \App\Models\Variantproduct::where('id',$cart['product_id'])->first();

						$offerPrice=\App\Helpers\commonHelper::getOfferProductPrice($productResult->sale_price,$productResult->discount_type,$productResult->discount_amount);
 
						$shippingAmount=0;

						$subTotal+=($productResult['sale_price']*$cart['qty']);

						$totalShipping+=($shippingAmount*$cart['qty']);

						if($request->user()->designation_id == '2'){

							$discount+=(($productResult['sale_price']-$offerPrice)*$cart['qty']);

						}else{

							$discount=0;
						}
						
					}

                    if($request->user()->couponstatus == 'inactive'){

                        $couponDiscount=($subTotal*10)/100;
                        $netAmount=($subTotal)-$couponDiscount;

                    }else{
                        $netAmount = $subTotal;
                    }
                    
					$netAmount=($subTotal)-$discount;
                    
                    
				}

				$paymentType = $request->json()->get('payment_type');
				
				
				$netAmount+=$totalShipping;
				$sales->subtotal= $subTotal;
				$sales->shipping=$totalShipping;
				$sales->couponcode_id=$couponId;
				$sales->couponcode_amount=$couponAmount;
				$sales->discount=$discount;
				$sales->net_amount=$netAmount;
				$sales->payment_type=$paymentType;
				$sales->save();

				$saleId=$sales->id;
				
				if(!empty($cartData)){

					foreach($cartData as $cart){

						$salesDetail=new \App\Models\Sales_detail();

						$productResult=\App\Models\Product::select('products.name','variantproducts.*')->where('variantproducts.id',$cart['product_id'])
															->join('variantproducts','variantproducts.product_id','=','products.id')->first();
						
						$imagesArray=explode(',',$productResult->images);

						$offerPrice=\App\Helpers\commonHelper::getOfferProductPrice($productResult->sale_price,$productResult->discount_type,$productResult->discount_amount);

						$salesDetail->user_id=$request->user()->id;
						$salesDetail->sale_id=$saleId;
						$salesDetail->order_id=$orderId;
						$salesDetail->product_id=$productResult->id;
						$salesDetail->product_name=$productResult->name;
						$salesDetail->product_image=asset('uploads/products/'.$imagesArray[0]);
						$salesDetail->qty=$cart['qty'];
						$salesDetail->product_sku_code=$productResult->sku_id;
						$salesDetail->remark=$cart['remark'];
						$salesDetail->sub_total=($productResult->sale_price*$cart['qty']);
						$salesDetail->discount=((($productResult->sale_price)-$offerPrice)*$cart['qty']);
						
						$salesDetail->amount=(($productResult->sale_price-$salesDetail->discount)*$cart['qty']);
						$salesDetail->order_status='1';
						$salesDetail->payment_status='0';
						if($paymentType==2){

							$salesDetail->payment_status='1';
						}
						$salesDetail->save();

					}
				}

				// if user use entire product amount from shopping wallet
				
				if($paymentType==1){
					
					$transactionId=strtotime("now").rand(11,99);

					$razorpayRedirectUrl = route('razorpay.payment.index', ['order_id' => $orderId]);
					\App\Models\Addtocart::where('user_id',$request->user()->id)->delete();

					return response([
						'message' => "Checkout successfully.",
						'checkout_type' => 'online',
						'amount' => $netAmount,
						'order' => $orderId,
						'address' => $address,
						'transactionId' => $transactionId,
						'razorpay_redirect_url' => url('razorpay-payment', ['order_id' => $orderId]), 
					], 200);
					
					
						
				}else{
					
					\App\Models\Addtocart::where('user_id',$request->user()->id)->delete();
					return response(array('message'=>"Checkout successfully.",'checkout_type'=>'cod','amount'=>$netAmount,'order'=>$orderId,'address'=>$address),200);

				}
				
			}catch (\Exception $e){
				
				return response(array("message" => "Something went wrong.please try again"),403); 
				
			}
		}
		
	}

}
