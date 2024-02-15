<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\commonHelper;

use DB; 
use Validator;
use Razorpay\Api\Api;

class PreLoginController extends Controller
{
	
	public function sliderList(Request $request){
 
		try{
				
			$sliderResult=\App\Models\Slider::where([
													['status','=','1'],
													])->orderBy('sort_order','ASC')->get();
			
			if(!$sliderResult){
				
				return response(array("message" => 'Result not found.'),404); 
			}else{
				
				$result=[];
				
				foreach($sliderResult as $slider){
					
					$result[]=[
						'image'=>asset('uploads/sliders/'.$slider->image),
					];
				}
				return response(array("message" => 'Slider fetched successfully.','result'=>$result),200); 
				
			}
			
		}catch (\Exception $e){
			
			return response(array("message" => $e->getMessage()),403); 
		
		} 	 
		
	}

    public function categoryList(Request $request){

		$result=\App\Helpers\commonHelper::getCategoryTree(Null);
		
		if(!empty($result)){
			
			return response(array("message" => 'Category fetched successfully.','result'=>$result),200); 
		}else{
			
			return response(array("message" => 'Category not found.'),403); 
		}

	}

	public function topSellingProduct(Request $request){

		try{

			$result=\App\Models\Product::with(['UserData' => function ($query1) {
				$query1->select('name','id');
			}])->select('variantproducts.*','products.name')->where([
					['products.top_selling','=','1'],
					['products.status','=','1'],
					['products.recyclebin_status','=','0'],
					['variantproducts.status','=','1'],
					['variantproducts.recyclebin_status','=','0']
					])->join('variantproducts','variantproducts.product_id','=','products.id')->groupBy('variantproducts.product_id')->get();
	
			

			$products = [];
			foreach($result as $value){

				$imagesArray = explode(',', $value->images);

				$secondImage=Null;
					if(isset($imagesArray[1])){
						$secondImage=asset('uploads/products/'.$imagesArray[1]);
					}

				$products[]=[
					"varientProductId"=>$value['id'],
					"name"=>ucfirst($value['name']),
					'sale_price'=>$value['sale_price'],
					'discount_type'=>$value['discount_type'],
					'discount_amount'=>$value['discount_amount'],
					'offer_price'=>\App\Helpers\commonHelper::getOfferProductPrice($value['sale_price'],$value['discount_type'],$value['discount_amount']),
					'first_image'=>asset('uploads/products/'.$imagesArray[0]),
					'second_image'=>$secondImage,
					'slug'=>$value['slug'],
					'stock'=>$value['stock'],
					'store_name'=>$value['UserData']['name'],
				];

			}

			return response(array("error"=>false, "message"=>"Product Fetched Successfully.", "result"=>$products));

		}catch (\Exception $e){

			return response(array("message" => $e->getMessage()),403); 
		}
	}

	public function contactUs(Request $request){
		
			// $rules['name'] = 'string|required';
            // $rules['email'] = 'email';
            // $rules['mobile'] = 'numeric|required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10';		
            // $rules['message'] = 'required';
            
            // $validator = Validator::make($request->all(), $rules);
                
            // if ($validator->fails()) {
            //     $message = [];
            //     $messages_l = json_decode(json_encode($validator->messages()), true);
            //     foreach ($messages_l as $msg) {
            //         $message= $msg[0];
            //         break;
            //     }
                
            //     return response(array("error"=> true, "message"=>$message),200); 
                
            // }else{

                try{

                    $contact = new \App\Models\Contact();
                    $contact->name = $request->json()->get('name');
                    $contact->email = $request->json()->get('email');
                    $contact->mobile = $request->json()->get('mobile');
                    $contact->subject = $request->json()->get('subject');
                    $contact->message = $request->json()->get('message');

                    $contact->save();

                    return response(array("error"=> false, "message"=>'Thank you for getting in touch!'),200); 

                }catch (\Exception $e){
                    
                    return response(array("error" => true, "message" => $e->getMessage()), 403);

                }

            // }

	}

	

}
