<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Variantproduct;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function add(Request $request){

		if($request->isMethod('post')){
			
			$rules=[
				'id'=>'numeric|required',
				'category_id'=>'numeric|required',
				'variant_id'=>'required',
				'name'=>'string|required|unique:products,name,'.\Auth::user()->id.',store_id',
				'short_description'=>'required',
				'description'=>'required',
			];
			
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
				
				$chkAlreadyExistName=Product::where([
													['name','=',$request->post('name')],
													['category_id','=',$request->post('category_id')],
													['id','!=',$request->post('id')],
													['recyclebin_status','0'],
													['store_id','=',\Auth::user()->id]
												])->first();
				
				if($chkAlreadyExistName){
					
					return response(array('message'=>"Product name already exist with this selected category."),403);
					
				}else{
					
					try{
						if((int) $request->post('id')>0){
							
							$product=Product::where('id',$request->post('id'))->where('store_id','=',\Auth::user()->id)->first();
							
						}else{
							
							$product=new Product();
							$product->store_id=\Auth::user()->id;
							
						
						}

						$product->lastupdated_by=\Auth::user()->id;
						
						$variants=$request->post('variant_id');
						sort($variants);
						
						$product->category_id=$request->post('category_id');
						$product->brand_id=$request->post('brand_id');
						$product->variant_id=implode(',',$variants);
						$product->name=$request->post('name');
						$product->tax_ratio=$request->post('tax_ratio');
						$product->short_description=$request->post('short_description');
						$product->description=$request->post('description');
						
						$product->save();
						
						if((int) $request->post('id')>0){
							
							return response(array('message'=>'Product updated successfully.','reset'=>false),200);
						}else{
							
							
							return response(array('message'=>'Product added successfully.','reset'=>true),200);
						
						}
					}catch (\Exception $e){
				
						return response(array("message" => $e->getMessage()),403); 
					
					}
					 
				}
			}
			 
			return response(array('message'=>'Data not found.'),403);
		}
		if($request->ajax()){
			$category=\App\Helpers\commonHelper::getCategoryTreeForAddProduct($parent_id=null);
			return response(array('message'=>'','category'=>$category),200);
		}
		$category=\App\Models\Category::where('recyclebin_status','0')->where('status','1')->orderBy('name','ASC')->get();
		$variants=\App\Models\Variant::where('status','1')->orderBy('sort_order','ASC')->get();
		$brands=\App\Models\Brand::where('status','Active')->orderBy('sort_order','ASC')->get();
		$result=[];
        return view('admin.catalog.product.add',compact('category','result','variants','brands'));
    }
	
	public function productList(Request $request){
		
		$query=Product::where('recyclebin_status','0')->where('store_id','=',\Auth::user()->id)->orderBy('id','DESC');
		
		$type="";
		$cate="";
		if($request->isMethod('post')){

			if(isset($request->category_id)){
				$query=$query->where('category_id',$request->category_id);
				$cate = $request->category_id;
			}

			if($request->type == 'top_selling'){

				$query=$query->where('top_selling','1');

				$type="top_selling";
		 	}elseif($request->type == 'deals_oftheday'){
	
				$query=$query->where('deals_oftheday','1');
				$type="deals_oftheday"; 
		 	}elseif($request->type == 'deals_oftheweek'){
	
				$query=$query->where('deals_oftheweek','1');
				$type="deals_oftheweek";
 
			} 

		}

		$result=$query->get();
		$category=\App\Models\Category::where('recyclebin_status','0')->where('store_id','=',\Auth::user()->id)->where('status','1')->orderBy('name','ASC')->get();
		$homeSetting=[];
		
		return view('admin.catalog.product.list',compact('result','type','category','cate','homeSetting'));
	}
	
	public function updateProduct(Request $request,$id){
		
		$result=Product::where('id',$id)->where('store_id','=',\Auth::user()->id)->first();
		
		if($result){
			
			$category=\App\Models\Category::where('recyclebin_status','0')->where('store_id','=',\Auth::user()->id)->where('status','1')->orderBy('name','ASC')->get();
			$variants=\App\Models\Variant::where('status','1')->where('store_id','=',\Auth::user()->id)->orderBy('sort_order','ASC')->get();
			$brands=\App\Models\Brand::where('status','Active')->orderBy('sort_order','ASC')->get();
			return view('admin.catalog.product.add',compact('category','result','variants','brands'));
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function deleteProduct(Request $request,$id){
		
		$result=Product::where('id',$id)->where('store_id','=',\Auth::user()->id)->first();
		
		if($result){
			
			$category=Product::where('id',$id)->where('store_id','=',\Auth::user()->id)->update(['recyclebin_status'=>'1','recyclebin_datetime'=>date('Y-m-d H:i:s')]);
			
			return redirect()->back()->with('5fernsadminsuccess','Category deleted successfully.');
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function changeStatus(Request $request){
		
		Product::where('id',$request->post('id'))->where('store_id','=',\Auth::user()->id)->update(['status'=>$request->post('status')]);
		
		return response(array('message'=>'Product status changed successfully.'),200);
	}
	
	public function topSellingStatus(Request $request){
		
		Product::where('id',$request->post('id'))->where('store_id','=',\Auth::user()->id)->update(['top_selling'=>$request->post('status')]);
		
		return response(array('message'=>'Top selling product status changed successfully.'),200);
	}
	
	
	public function dealsofTheDay(Request $request){
		
		Product::where('id',$request->post('id'))->where('store_id','=',\Auth::user()->id)->update(['deals_oftheday'=>$request->post('status')]);
		
		return response(array('message'=>'Deals of the day status changed successfully.'),200);
	}
	
		
	
	public function newArrival(Request $request){
		
		Product::where('id',$request->post('id'))->where('store_id','=',\Auth::user()->id)->update(['new_arrival'=>$request->post('status')]);
		
		return response(array('message'=>'New arrival status changed successfully.'),200);
	}
	
	
	
	public function addVariantProduct(Request $request,$product_id){
		
		$parentProduct=Product::where('id',$product_id)->where('store_id','=',\Auth::user()->id)->where('status','1')->first();
		
		if($parentProduct){

			$variants=Variant::whereIn('id',explode(',',$parentProduct->variant_id))->where('store_id','=',\Auth::user()->id)->where('status','1')->orderBy('sort_order','ASC')->get();
		}
		
		if($request->ajax()){

			$rules=[
				'id'=>'numeric|required',
				'product_id'=>'numeric|required',
				'variant_attributes'=>'required',
				'discount_type'=>'numeric|required|in:1,2',
				'discount_amount'=>'required',
				'stock'=>'numeric|required',
				'sale_price'=>'required',
				'meta_title'=>'required',
				'meta_keywords'=>'required',
				'meta_description'=>'required'
			];
			
			if((int) $request->post('id')==0){
				
				$rules['uploadfile']='required';
				
			}
			
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
				
				// check parent product 
				if(!$parentProduct){
					
					return response(array('message'=>'Something went wrong. Please try again'),403);
					
				}else if($request->post('discount_type')=='1' && (int) $request->post('discount_amount')>=100){
					
					return response(array('message'=>'Discount value can not be 100% or more.'),403);
					
				}else if($request->post('discount_type')=='2' && ($request->post('sale_price')<=$request->post('discount_amount'))){
					
					return response(array('message'=>'Discount value can not be equal or more than to Sale Price.'),403);
					
				}else{

					try{
						
						$variantIds=[];
							
						if($variants){
							
							foreach($variants as $vari){
								
								$variantIds[]=$vari->id;
							}
						}
						 
						sort($variantIds);
						$variantAttributes=$request->post('variant_attributes');
						sort($variantAttributes);
						
						// check exit attribute with variant
						
						$existAttributeResult=Variantproduct::where([
																	['product_id','=',$parentProduct->id],
																	['variant_id','=',implode(',',$variantIds)],
																	['variant_attributes','=',implode(',',$variantAttributes)],
																	['id','!=',$request->post('id')],
																	['recyclebin_status','0'],
																	['store_id','=',\Auth::user()->id],
																	])->first();
																	
						if($variants->count()!=count($request->post('variant_attributes'))){
							
							return response(array('message'=>'Some attribute values sare missing. Please try again.'),403);
							
						}else if($existAttributeResult){
							
							return response(array('message'=>'This attribute alreay exist.'),403);
							
						}else{
							
							$image_array = array();
							$productImage="";

							if(isset($request->uploadfile)){
								foreach($request->uploadfile as $image){

									if($image != 'undefined'){
										$image_update = \App\Helpers\commonHelper::uploadFile($image,'products');
										$image_array[] = $image_update;
									}
								}
							}
							
							if(!empty($request->post('images'))){
								
								$image_array=array_merge($request->post('images'),$image_array);
								
							}
							
							if(!empty($image_array) && $image_array[0]!=''){
							
								$productImage = implode(",",$image_array);
							}
				
					
							if((int) $request->post('id')>0){
								
								$variProduct=Variantproduct::where('id',$request->post('id'))->where('store_id','=',\Auth::user()->id)->first();

							}else{
								
								$variProduct=new Variantproduct();
								$variProduct->store_id=\Auth::user()->id;
								
							
							} 

							$variProduct->updated_by=\Auth::user()->id;
						
							 
							$variProduct->product_id=$product_id;
							$variProduct->variant_id=implode(',',$variantIds);
							$variProduct->variant_attributes=implode(',',$variantAttributes);
							$variProduct->sale_price=$request->post('sale_price');
							$variProduct->discount_type=$request->post('discount_type');   
							$variProduct->discount_amount=$request->post('discount_amount');   
							$variProduct->stock=$request->post('stock');   
							$variProduct->images=$productImage;
							$variProduct->meta_title=$request->post('meta_title');
							$variProduct->meta_keywords=$request->post('meta_keywords');
							$variProduct->meta_description=$request->post('meta_description');
							$variProduct->status='1';
						
							$variProduct->save();

							$variProduct->sku_id=\App\Helpers\commonHelper::getSkuCode($parentProduct->name, $variProduct->id);
							$variProduct->slug=Str::slug($parentProduct->name.'-'.str_pad($variProduct->id, 4, '0', STR_PAD_LEFT));
							$variProduct->save();
							
							return response(array('message'=>'Variant Product updated successfully.','reset'=>false),200);
						
						}
					}catch (\Exception $e){
							
						return response(array("message" => $e->getMessage()),403); 
					
					}
					
				}
				
			}
			
			return response(array('message'=>'Data not found.'),403);
		}
		
		if($parentProduct){
			
			$result=[];
			return view('admin.catalog.product.add_variantproduct',compact('result','product_id','variants','parentProduct'));
			
		}else{
			
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
    }
	
	public function updateVariantProduct(Request $request,$product_id,$variProductId){
		
		$parentProduct=Product::where('id',$product_id)->where('store_id',\Auth::user()->id)->first();
		$result=Variantproduct::where('product_id',$product_id)->where('store_id',\Auth::user()->id)->where('id',$variProductId)->first();
		
		if($parentProduct && $result){
		
			$variants=Variant::whereIn('id',explode(',',$parentProduct->variant_id))->where('store_id',\Auth::user()->id)->where('status','1')->orderBy('sort_order','ASC')->get();
			return view('admin.catalog.product.add_variantproduct',compact('result','product_id','variants','parentProduct'));
			
		}else{
			
			return redirect()->back()->with('angelaccentdminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function variantProductList(Request $request,$productId){
		
		$parentProduct=Product::where('recyclebin_status','0')->where('store_id',\Auth::user()->id)->where('status','1')->where('id',$productId)->first();

		if($parentProduct){

			$result=Variantproduct::where('product_id',$productId)->where('store_id',\Auth::user()->id)->where('recyclebin_status','0')->orderBy('id','DESC')->get();
	
			return view('admin.catalog.product.variantproductlist',compact('result','parentProduct'));
		
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
			
		}
	}
	
	public function deleteVariantProduct(Request $request,$id){
		
		$result=Variantproduct::where('id',$id)->where('store_id',\Auth::user()->id)->first();
		
		if($result){
			
			$category=Variantproduct::where('id',$id)->where('store_id',\Auth::user()->id)->update(['recyclebin_status'=>'1','recyclebin_datetime'=>date('Y-m-d H:i:s')]);
			
			return redirect()->back()->with('5fernsadminsuccess','Variant Product deleted successfully.');
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	
	public function changeVariantProductStatus(Request $request){
		
		Variantproduct::where('id',$request->post('id'))->where('store_id',\Auth::user()->id)->update(['status'=>$request->post('status')]);
		
		return response(array('message'=>'Variant product status changed successfully.'),200);
	}
	
	
	public function addVariantAttribute(Request $request){
		
		if($request->isMethod('post')){
			
			$rules=[
				'id'=>'numeric|required',
				'variant_id'=>'numeric|required',
				'title'=>'required',
				'color'=>'required'
			];
			
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
				
				$chkAlreadyExistAttribute=\App\Models\Variant_attribute::where([
													['variant_id','=',$request->post('variant_id')],
													['title','=',$request->post('title')],
													['id','!=',$request->post('id')],
													['store_id','=',\Auth::user()->id]
													])->first();
				
				if($chkAlreadyExistAttribute){
					
					return response(array('message'=>"This attribute already exist."),403);
					
				}else{
					
					try{
						
						if((int) $request->post('id')>0){
							
							$attribute=\App\Models\Variant_attribute::where('id',$request->post('id'))->where('store_id',\Auth::user()->id)->first();
							
						}else{
							
							$attribute=new \App\Models\Variant_attribute();
							$attribute->store_id=\Auth::user()->id;
							
						
						}
						
							$attribute->lastupdated_by=\Auth::user()->id;

							$attribute->variant_id=$request->post('variant_id');
							$attribute->title=$request->post('title');
							$attribute->color=$request->post('color');
							$attribute->status='1';
						
						
						$attribute->save();
						
						if((int) $request->post('id')>0){
							
							return response(array('message'=>'Attribute updated successfully.','reset'=>false),200);
						}else{
							
							return response(array('message'=>'Attribute added successfully.','reset'=>true),200);
						
						}
					}catch (\Exception $e){
				
						return response(array("message" => $e->getMessage()),403); 
					
					}
					 
				}
			}
			return response(array('message'=>'Data not found.'),403);
		}
		
		$variants=\App\Models\Variant::where('status','1')->where('store_id',\Auth::user()->id)->orderBy('name','ASC')->get();
		$result=[];
        return view('admin.catalog.product.add_variantattributes',compact('variants','result'));
		
	}
	
	public function attributeList(Request $request){
		
		$result=\App\Models\Variant_attribute::select('variants.name','variant_attributes.*')->where('variant_attributes.store_id',\Auth::user()->id)->orderBy('variant_attributes.id','DESC')->join('variants','variants.id','=','variant_attributes.variant_id')->where('variants.status','1')->where('variant_attributes.status','1')->get();
	
		return view('admin.catalog.product.variantattributelist',compact('result'));
	}
	
	public function updateVariantAttribute(Request $request,$id){
		 
		$result=\App\Models\Variant_attribute::where('id',$id)->where('store_id',\Auth::user()->id)->first();
		
		if($result){
		
			$variants=\App\Models\Variant::where('status','1')->where('store_id',\Auth::user()->id)->orderBy('name','ASC')->get();
			return view('admin.catalog.product.add_variantattributes',compact('variants','result'));
			
		}else{
			
			return redirect()->back()->with('angelaccentdminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function dealsofTheWeek(Request $request){
		
		Product::where('id',$request->post('id'))->where('store_id',\Auth::user()->id)->update(['deals_oftheweek'=>$request->post('status')]);
		
		return response(array('message'=>'Deals of the week status changed successfully.'),200);
	}

	
	public function addVariant(Request $request){
		
		if($request->isMethod('post')){

			$rules=[
				'id'=>'numeric|required',
				'name'=>'string|required',
				'sort_order'=>'required',
				'display_layout'=>'numeric|required',
			];
			
			
			if((int) $request->post('id')==0){
				
				$rules['attribute_value']='required';
				$rules['color']='required';
			} 
 
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()){
				$message = "";
				$messages_l = json_decode(json_encode($validator->messages()), true);
				foreach ($messages_l as $msg) {
					$message= $msg[0];
					break;
				}
				
				return response(array('message'=>$message),403);
				
			}elseif((count(array_unique(array(count(array_unique($request->post('attribute_value'))),count($request->post('sort_order')),count($request->post('color')),count($request->post('attribute_id'))))))!='1'){
				
				return response(array('message'=>'Something went wrong in variant attributes. Please check once.'),403);
				
			}else{
				
				$chkAlreadyExistVariant=\App\Models\Variant::where([
													['id','!=',$request->post('id')],
													['name','=',$request->post('name')],
													['store_id','=',\Auth::user()->id]
												])->first();
				
				if($chkAlreadyExistVariant){
					
					return response(array('message'=>"This Variant already exist."),403);
					
				}else{
					
					try{
						
						if((int) $request->post('id')>0){
							
							$Variant=\App\Models\Variant::where('id',$request->post('id'))->where('store_id',\Auth::user()->id)->first();
							
						}else{
							
							$Variant=new \App\Models\Variant();
							$Variant->store_id=\Auth::user()->id; 
							
							
						}
						
							$Variant->lastupdated_by=\Auth::user()->id;

							$Variant->name=$request->post('name');
							$Variant->sort_order=$request->post('variant_sort_order');
							$Variant->display_layout=$request->post('display_layout');
							$Variant->status='1';
						
						
						$Variant->save();
						
						//add variant attributes
							
						if(!empty($request->post('attribute_value'))){
								
							foreach($request->post('attribute_value') as $key=>$attvalue){
								
								if($request->post('attribute_id')[$key]>0){
 
									$attribute=\App\Models\Variant_attribute::where('id',$request->post('attribute_id')[$key])->first();

								}else{

									$attribute=new \App\Models\Variant_attribute();
								}
								
								$attribute->store_id=\Auth::user()->id;
								$attribute->variant_id=$Variant->id;
								$attribute->title=$attvalue;
								$attribute->sort_order=$request->post('sort_order')[$key];
								$attribute->color=$request->post('color')[$key];

								if(isset($request->post('status')[$key])){

									$attribute->status='1';

								}else{

									$attribute->status='0';

								}
								
								$attribute->save();
								
							}
						}
							
						
						if((int) $request->post('id')>0){
							
							return response(array('message'=>'Variant updated successfully.','reset'=>false,'script'=>true),200);
						}else{
							
							return response(array('message'=>'Variant added successfully.','reset'=>true),200);
						
						}
						
					}catch (\Exception $e){
				
						return response(array("message" => $e->getMessage()),403); 
					
					}
					 
				}
			}
			return response(array('message'=>'Data not found.'),403);
		}
		
		$result=[];
		$variantResult=[];
        return view('admin.catalog.product.add_variants',compact('result','variantResult'));
		
	}
		
	public function VariantList(Request $request){
		
		$result=\App\Models\Variant::where('store_id',\Auth::user()->id)->orderBy('id','DESC')->get();
	
		return view('admin.catalog.product.list_variants',compact('result'));
	}
	
	public function updateVariant(Request $request,$id){
		 
		$result=\App\Models\Variant::where('id',$id)->where('store_id',\Auth::user()->id)->first();
		
		if($result){
		
			$variantResult=\App\Models\Variant_attribute::where('variant_id',$result->id)->where('store_id',\Auth::user()->id)->get();

			return view('admin.catalog.product.add_variants',compact('result','variantResult'));
			
		}else{
			
			return redirect()->back()->with('angelaccentdminerror','Something went wrong. Please try again.');
		}
		
	}
		
	public function statusVariant(Request $request){
		
		Variant::where('id',$request->post('id'))->where('store_id',\Auth::user()->id)->update(['status'=>$request->post('status')]);
		
		return response(array('message'=>'Variant status changed successfully.'),200);
	}
}
