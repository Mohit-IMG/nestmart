<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Brand;

class BrandController extends Controller
{
    public function add(Request $request){

		if($request->isMethod('post')){			
			$rules=[				
				'name'=>'string|required',
				'short_description'=>'max:330',				
			];
			 
			if((int) $request->post('id')==0 || $request->hasFile('image')){
						
				$rules['image']='required|image|mimes:jpeg,png,jpg,gif,svg,webp';
			
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
                $brandAlreadyExistName=Brand::where([
                    ['brand_name','=',$request->post('name')],
                    ['id','!=',$request->post('id')],
                    ['deleted_at',null],
                ])->first();

                if($brandAlreadyExistName){
					
					return response(array('message'=>"Brand name already exist."),403);
					
				}else{
				
				try{
					if((int) $request->post('id')>0){
						
						$brand=Brand::find($request->post('id'));
					}else{
						
						$brand=new Brand();
					
					}
					
                    $filename=$request->post('old_image');

                    if($request->hasFile('image')){
						$filename = \App\Helpers\commonHelper::uploadFile($request->file('image'),'brands'); 
					} 
										
					$brand->image=$filename;
					$brand->brand_name=$request->post('name');
					$brand->short_description=$request->post('short_description');					
					
					$brand->save();
					
					if((int) $request->post('id')>0){
						
						return response(array('message'=>'Brand updated successfully.','reset'=>false),200);
					}else{
						
						return response(array('message'=>'Brand added successfully.','reset'=>true,'script'=>true),200);
					
					}
				}catch (\Exception $e){
			
					return response(array("message" => $e->getMessage()),403); 
				
				}
			}
			
			return response(array('message'=>'Data not found.'),403);
		}
        }
		$result=[];
        return view('admin.brand.add',compact('result'));
    }

    public function brandList(){
		
		$result=\App\Models\Brand::orderBy('sort_order','ASC')->get();		
		return view('admin.brand.list',compact('result'));
	}

    public function deleteBrand(Request $request,$id){
		
		$result=Brand::find($id);
		
		if($result){
			
			Brand::where('id',$id)->delete();;
			
			return redirect()->back()->with('5fernsadminsuccess','Brand deleted successfully.');
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
    
    public function changeStatus(Request $request){
		
		Brand::where('id',$request->post('id'))->update(['status'=>$request->post('status')]);
		
		return response(array('message'=>'Brand status changed successfully.'),200);
	}
    
    public function changeOrder(Request $request){
		
		$allData = $request->allData;
		$i = 1;
		foreach ($allData as $key => $value) {
			Brand::where('id',$value)->update(array('sort_order'=>$i));
			$i++;
		}
		
	}

    public function updateBrand(Request $request,$id){
		
		$result=Brand::find($id);
		
		if($result){
			
			return view('admin.brand.add',compact('result'));
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
}
