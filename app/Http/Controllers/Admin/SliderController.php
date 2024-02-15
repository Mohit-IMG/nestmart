<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use \App\Models\Slider;

class SliderController extends Controller
{
    public function add(Request $request){

		if($request->isMethod('post')){			
			$rules=[
				'id'=>'numeric|required',						
			];
			 
			if((int) $request->post('id')==0){
						
				$rules['image']='required|image|mimes:jpeg,png,jpg,gif';
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
				
				try{
					if((int) $request->post('id')>0){
						
						$slider=Slider::find($request->post('id'));
					}else{
						
						$slider=new Slider();
					
					}
					
					$filename=$request->post('old_image');
					
					if($request->hasFile('image')){
						$filename = \App\Helpers\commonHelper::uploadFile($request->file('image'),'sliders');
							
					} 
					
					$slider->image=$filename;
					$slider->save();
					
					if((int) $request->post('id')>0){
						
						return response(array('message'=>'Slider updated successfully.','reset'=>false),200);
					}else{
						
						return response(array('message'=>'Slider added successfully.','reset'=>true,'script'=>true),200);
					
					}
				}catch (\Exception $e){
			
					return response(array("message" => $e->getMessage()),403); 
				
				}
			}
			
			return response(array('message'=>'Data not found.'),403);
		}
		
		$result=[];
        return view('admin.slider.add',compact('result'));
    }

    public function sliderList(){
		
		$result=\App\Models\Slider::orderBy('sort_order','ASC')->get();		
		return view('admin.slider.list',compact('result'));
	}
	
	public function changeStatus(Request $request){
		
		Slider::where('id',$request->post('id'))->update(['status'=>$request->post('status')]);
		
		return response(array('message'=>'Slider status changed successfully.'),200);
	}
	
	public function updateSlider(Request $request,$id){
		
		$result=Slider::find($id);
		
		if($result){
			
			return view('admin.slider.add',compact('result'));
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function deleteSlider(Request $request,$id){
		
		$result=Slider::find($id);
		
		if($result){
			
			Slider::where('id',$id)->delete();;
			
			return redirect()->back()->with('5fernsadminsuccess','Slider deleted successfully.');
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}

	public function changeOrder(Request $request){
		
		$allData = $request->allData;
		$i = 1;
		foreach ($allData as $key => $value) {
			Slider::where('id',$value)->update(array('sort_order'=>$i));
			$i++;
		}
		
	}
}
