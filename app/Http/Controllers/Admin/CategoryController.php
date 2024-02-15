<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class CategoryController extends Controller
{
    public function add(Request $request){
			
		if($request->isMethod('post')){
			
			$rules=[
				'id'=>'numeric|required',
				'name'=>'string|required',
				'meta_title'=>'string|required',
				'meta_keywords'=>'string|required',
				'meta_description'=>'string|required',
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
				
				$parent=\App\Models\Category::where('id',$request->post('parent_id'))->where('store_id',\Auth::user()->id)->first();
				
				$chkAlreadyExistName=\App\Models\Category::where([
													['recyclebin_status','=','0'],
													['name','=',$request->post('name')],
													['parent_id','=',$request->post('parent_id')],
													['id','!=',$request->post('id')],
													['store_id','=',\Auth::user()->id]
													])->first();
				
				if(!$parent && $request->post('parent_id')!=''){
					
					return response(array('message'=>"Parent category doesn't exist."),403);
				
				}else if($chkAlreadyExistName){
					
					return response(array('message'=>"Category name already exist with this selected category."),403);
					
				}else{
					
					$filename=$request->post('old_image');
					
					if($request->hasFile('image')){
						
						$filename = \App\Helpers\commonHelper::uploadFile($request->file('image'),'category');
					} 
					
					if((int) $request->post('id')>0){
						
						$category=\App\Models\Category::where('id',$request->post('id'))->where('store_id',\Auth::user()->id)->first();
					}else{
						
						$category=new \App\Models\Category();
						$category->store_id=\Auth::user()->id;
					}

						$category->lastupdated_by=\Auth::user()->id;

					$parentIds=\App\Helpers\commonHelper::getParentId($request->post('parent_id'),\Auth::user()->id);
					$parentCategoryResult=\App\Models\Category::whereIn('id',explode(',',$parentIds))->orderBy('id','ASC')->get();
					
					$slug="";
					if($parentCategoryResult->count()>0){

						foreach($parentCategoryResult as $parentCategory){

							$slug.=Str::slug($parentCategory->name).'-';
						}
					}

					$slug.=Str::slug($request->post('name'));

					$category->name=$request->post('name');
					$category->slug=strtolower($slug);
					$category->parent_id=$request->post('parent_id');
					$category->image=$filename;
					$category->description=$request->post('description');
					$category->meta_title=$request->post('meta_title');
					$category->meta_keywords=$request->post('meta_keywords');
					$category->meta_description=$request->post('meta_description');
					
					$category->save();
					
					if((int) $request->post('id')==0){
						
						

						return response(array('message'=>'Category added successfully.','reset'=>false,'script'=>true),200);
					}else{
						
						return response(array('message'=>'Category updated successfully.','reset'=>false),200);
					
					}
					
				}
			}
			return response(array('message'=>'Data not found.'),403);
		}
		if($request->ajax()){
			$category=\App\Helpers\commonHelper::getCategoryTreeForAddCategory($parent_id=null);
			return response(array('message'=>'','category'=>$category),200);
		}
		$category=\App\Helpers\commonHelper::getCategoryTreeForAddCategory($parent_id=null);
		$result=[];
        return view('admin.catalog.category.add',compact('category','result'));
    }
	
	public function categoryList(){

		$result=\App\Models\Category::where('recyclebin_status','0')->orderBy('id','DESC')->where('store_id',\Auth::user()->id)->get();
		
		return view('admin.catalog.category.list',compact('result'));
	}
	
	public function updateCategory(Request $request,$id){
		
		$result=\App\Models\Category::where('id',$id)->where('store_id',\Auth::user()->id)->first();
		
		if($result){
			
			$category=\App\Models\Category::where('recyclebin_status','0')->orderBy('name','ASC')->where('store_id',\Auth::user()->id)->get();
			return view('admin.catalog.category.add',compact('category','result'));
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function deleteCategory(Request $request,$id){

		$result=\App\Models\Category::where('id',$id)->where('store_id',\Auth::user()->id)->first();
		
		if($result){

			$childId=\App\Helpers\commonHelper::getAllCategoryTreeidsArray($id,\Auth::user()->id);

			if(!empty($childId)){

				foreach($childId as $child){

					\App\Models\Category::where('id',$child)->where('store_id',\Auth::user()->id)->update(['recyclebin_status'=>'1','recyclebin_datetime'=>date('Y-m-d H:i:s')]);
				}
			}

			\App\Models\Category::where('id',$id)->where('store_id',\Auth::user()->id)->update(['recyclebin_status'=>'1','recyclebin_datetime'=>date('Y-m-d H:i:s')]);
			
			return redirect()->back()->with('5fernsadminsuccess','Category deleted successfully.');
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
	
	public function changeStatus(Request $request){

		\App\Models\Category::where('id',$request->post('id'))->where('store_id',\Auth::user()->id)->update(['status'=>$request->post('status')]);
		
		if($request->post('status') == 0){

			$childCategoryResult=\App\Helpers\commonHelper::getCategoryTreeidsArray($request->post('id'),\Auth::user()->id);

			if($childCategoryResult){
				
				foreach($childCategoryResult as $child){
					
					Category::where('id',$child)->where('store_id',\Auth::user()->id)->update(['status'=>$request->post('status')]);
				}
				
			}
		}
		
		return response(array('message'=>'Category status changed successfully.'),200);
	}
	
	public function selectTopCategory(Request $request){
		
		$checkParentCategory=\App\Models\Category::where('id',$request->post('id'))->where('store_id',\Auth::user()->id)->where('parent_id',Null)->first();
		
		if(!$checkParentCategory){
			
			return response(array('message'=>"Yon can't make featured for this category becuase selected category is not parent."),403);
			
		}else{
			
			\App\Models\Category::where('id',$request->post('id'))->where('store_id',\Auth::user()->id)->update(['top_category'=>$request->post('status')]);
			
			return response(array('message'=>'Top Category status changed successfully.'),200);
		}
	}
}
