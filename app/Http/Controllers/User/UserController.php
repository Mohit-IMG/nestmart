<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;   


class UserController extends Controller
{
    public function myaccount(){

        $orders = \App\Models\Sales_detail::where('user_id',\Auth::user()->id)->get();

        $billingAddress = \App\Models\Addressbook::where('user_id', '=', Auth::user()->id) ->take(2)->get();

        $states=\App\Models\State::where('country_id','101')->orderBy('name','Asc')->get();

        $coupons = \App\Models\User::where('id',\Auth::user()->id)->first();
        $couponArray = json_decode($coupons->offer_coupons);


        return view('myaccount',compact('orders','billingAddress','states','couponArray'));
    }
    
    public function getCity(Request $request){

        $stateId=$request->get('state_id');

        $option="<option value='' selected >--Select City--</option>";

        if($stateId>0){

            $cityResult=\App\Models\City::where('state_id',$stateId)->get();

            foreach($cityResult as $city){
    
                $option.="<option value='".$city['id']."'>".ucfirst($city['name'])."</option>";
            }

        }

        return response(array('message'=>'City fetched successfully.','html'=>$option));
    }

    public function getState(Request $request){

        $country_id=$request->get('country_id');

        $option="<option value='' selected >--Select State--</option>";

        if($country_id>0){

            $stateResult=\App\Models\State::where('country_id',$country_id)->get();

            foreach($stateResult as $state){

                $option.="<option value='".$state['id']."'>".ucfirst($state['name'])."</option>";
            }
        }

        return response(array('message'=>'state fetched successfully.','html'=>$option));
    }

    public function updateBillingDetails(Request $request, $id)
    {
            $request->validate([
                'address_line1' => 'required',
                'address_line2' => 'nullable',
                'pincode' => 'required',
                'state_id' => 'required',
                'city_id' => 'required',
            ]);
    
                $address = \App\Models\Addressbook::findOrFail($id);
            
                $address->update($request->all());
            
                return response()->json(['message' => 'Address updated successfully']);
    }

    public function cancelOrder(Request $request, $id)
    {
        $findOrder = \App\Models\Sales_detail::where('id', $id)->first();
            $findOrder->update(['order_status' => '8', 'remark' => $request->cancelReason]);
            return response()->json(['message' => 'Request for order canceled submitted successfully.']);
    }
    
    
}
    

