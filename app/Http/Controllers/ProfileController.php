<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function myWishlist(Request $request){

		$wishlist=\App\Helpers\commonHelper::callAPI('userTokenget','/wishlist-product-list');
		return view('wishlist',compact('wishlist'));
	}

	public function updateProfile(Request $request)
	{
		if ($request->ajax()) {
			$rules = [
				'name' => 'required',
				'profileimage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
				'bname' => 'required',
				'mobile' => 'required|numeric|min:10',
			];
	
			if ($request->has('password')) {
				$rules['password'] = 'required|min:8';
				$rules['password_confirmation'] = 'required|same:password|min:8';
			}
	
			$validator = \Validator::make($request->all(), $rules);
	
			if ($validator->fails()) {
				return response()->json(['error' => true, 'msg' => $validator->errors()->first()]);
			}
	
			try {
				$user = \Auth::user();
	
				$user->name = $request->input('name');
				$user->business_name = $request->input('bname');
				$user->mobile = $request->input('mobile');
	
				if ($request->has('password')) {
					$user->password = Hash::make($request->input('password'));
				}
	
				// Handle image upload
				if ($request->hasFile('profileimage')) {
					$profileImage = $request->file('profileimage');
					$imageName = time() . '.' . $profileImage->getClientOriginalExtension();
					$profileImage->move(public_path('uploads/profile_images'), $imageName);
					$user->profileimage = $imageName;
				}
	
				$user->save();
	
				return response()->json(['error' => false, 'msg' => 'Profile Updated successfully','user'=>$user]);
			} catch (\Exception $e) {
				return response()->json(['error' => true, 'msg' => 'Something went wrong! Please try again.']);
			}
		} else {
			// Handle non-AJAX request here if needed
		}
	}
	
	

}

