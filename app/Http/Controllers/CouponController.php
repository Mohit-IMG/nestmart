<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{

    

    public function checkCoupon(Request $request)
    {
        $enteredCode = $request->input('couponCode');
        $totalAmount = $request->input('totalAmount');
        $user = auth()->user();
    
        if ($enteredCode === 'welcome' ) {
            if($user->couponstatus == 'active'){
                if ($user->couponstatus == 'active') {
                    $discountedAmount = ($totalAmount * 10) / 100;
                    $totalAmt = $totalAmount - $discountedAmount;
        
                    // Update coupon status to 'inactive'
                    $user->couponstatus='inactive';
                    $user->save();
                    } else {
                        $discountedAmount = 0;
                        $totalAmt = $totalAmount;
                    }
                    
                    return response()->json([
                        'message' => 'Welcome coupon applied successfully.',
                        'discountedAmount' => $discountedAmount,
                        'totalAmt' => $totalAmt,
                        'enteredCouponCode' => $enteredCode,
                          'totalAmount' => $totalAmount,
                        'userCoupon' => \Auth::user()->coupencode,
                    ]);
            }
            else {
                return response()->json(['message' => 'Welcome coupon already applied.'], 404);
            }
               
        }
    
        // Check if offer_coupon is not null and contains a valid JSON array
        if (!is_null($user->offer_coupons) && is_string($user->offer_coupons)) {
            $validCoupons = json_decode($user->offer_coupons);
    
            if (json_last_error() === JSON_ERROR_NONE && is_array($validCoupons) && in_array($enteredCode, $validCoupons)) {
                // Find the coupon in the coupons table based on the entered code
                $coupon = Coupon::where('code', $enteredCode)->first();
                
                $request->session()->put('enteredCode', $enteredCode);  //Session for storing coupon value from coupomn table
                
                if ($coupon) {
                    // Apply the coupon logic using the retrieved coupon value
                    $discountedAmount = ($totalAmount * $coupon->value) / 100;
                    $totalAmt = $totalAmount - $discountedAmount;
    
                    // Include more information in the response
                    return response()->json([
                        'message' => 'Coupon applied successfully.',
                        'discountedAmount' => $discountedAmount,
                        'totalAmt' => $totalAmt,
                        'enteredCouponCode' => $enteredCode,
                        'totalAmount' => $totalAmount,
                        'userCoupon' => $user->coupencode,
                    ]);
                }
            }
        }
    
        // Invalid coupon code or offer_coupon is not set
        return response()->json(['message' => 'You have already been used this coupon code.'], 422);
    }
    
    public function resetCouponStatus(Request $request)
    {
        $cartId = $request->input('cart_id');
        $enteredCode = $request->session()->get('enteredCouponCode');
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        if ($enteredCode === 'welcome' && $user->couponstatus !== 'active') {
            // Reset coupon status to 'inactive' for the 'welcome' coupon
            $user->update(['couponstatus' => 'active']);
    
            return response()->json(['message' => 'Coupon status reset successfully']);
        }
    
        // Check if the entered coupon code is valid
        $validCoupons = json_decode($user->offer_coupon, true);
    
        if (in_array($enteredCode, $validCoupons)) {
            return response()->json(['message' => 'Coupon applied successfully.']);
        } else {
            return response()->json(['message' => 'Entered coupon code is not valid.'], 422);
        }
    }
    
    


}
    
