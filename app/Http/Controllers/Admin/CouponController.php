<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
class CouponController extends Controller
{


    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'string|required',
                'description' => 'min:1',
                'value' => 'numeric|min:1|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return response(['message' => $message], 403);
            } else {
                try {
                    $couponId = (int)$request->post('id');

                    $couponAlreadyExistName = \App\Models\Coupon::where([
                        ['code', '=', $request->post('name')],
                        ['id', '!=', $couponId],
                    ])->first();

                    if ($couponAlreadyExistName) {
                        return response(['message' => "Coupon name already exists."], 403);
                    }

                    $coupon = $couponId > 0 ? \App\Models\Coupon::find($couponId) : new \App\Models\Coupon();

                    $coupon->code = $request->post('name');
                    $coupon->description = $request->post('description');
                    $coupon->value = $request->post('value');

                    $coupon->save();

                    $responseMessage = $couponId > 0 ? 'Coupon updated successfully.' : 'Coupon generated successfully.';
                    $resetValue = $couponId > 0 ? false : true;

                    return response(['message' => $responseMessage, 'reset' => $resetValue], 200);
                } catch (\Exception $e) {
                    return response(['message' => $e->getMessage()], 403);
                }
            }
        }

        $result = [];
        return view('admin.coupon.add', compact('result'));
    }

    public function couponList(){
		
		$result=\App\Models\Coupon::orderBy('status','ASC')->get();		
		return view('admin.coupon.list',compact('result'));
	}

    public function deleteCoupon(Request $request,$id){
		
		$result=\App\Models\Coupon::find($id);
		
		if($result){
			
			\App\Models\Coupon::where('id',$id)->delete();
			
			return redirect()->back()->with('5fernsadminsuccess','Coupon deleted successfully.');
			
		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
		
	}
    
    public function changeStatus(Request $request){
		
		\App\Models\Coupon::where('id',$request->post('id'))->update(['status'=>$request->post('status')]);
		
		return response(array('message'=>'Coupon status changed successfully.'),200);
	}
    


    public function updateCoupon(Request $request, $id)
    {
        $result = \App\Models\Coupon::find($id);

        if ($result) {
            return view('admin.coupon.add', compact('result'));
        } else {
            return redirect()->back()->with('5fernsadminerror', 'Something went wrong. Please try again.');
        }
    }
    

public function sendToAllUsers($id)
{
    $coupon = \App\Models\Coupon::find($id);

    if (!$coupon) {
        return response()->json(['message' => 'Coupon not found.'], 404);
    }

    $users = \App\Models\User::all();
    $couponAlreadySentToAll = true; // Assume it's already sent to all users

    foreach ($users as $user) {
        $offerCoupons = json_decode($user->offer_coupons, true) ?? [];

        // Check if the coupon code already exists in the array
        if (!in_array($coupon->code, $offerCoupons)) {
            $couponAlreadySentToAll = false; // At least one user does not have the coupon
            $offerCoupons[] = $coupon->code;
            $user->update(['offer_coupons' => json_encode($offerCoupons)]);
        }
    }

    if ($couponAlreadySentToAll) {
        return response()->json(['message' => 'Coupon already sent to all users.'], 403); // Use 403 for forbidden/error
    }

    return response()->json(['message' => 'Coupon sent to all eligible users successfully.'], 200);
}


    public function generateRandomCoupon(Request $request)
    {
        if ($request->ajax()) {
            $randomCode = $this->generateRandomCode();
            return response()->json(['randomCode' => $randomCode]);
        }

        return abort(404);
    }

    private function generateRandomCode()
    {
        $websiteCode = "NM";
        $length = 6; 
        $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $randomPart = "";
    
        for ($i = 0; $i < $length; $i++) {
            $randomPart .= $charset[rand(0, strlen($charset) - 1)];
        }
    
        return $websiteCode . $randomPart;
    }
    
    
    


}


