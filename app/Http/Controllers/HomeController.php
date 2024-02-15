<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class HomeController extends Controller
{
    Public function index(){

        $sliderApi = \App\Helpers\commonHelper::callApi('GET','/slider-list');
        
        $sliderResult = array();
        if($sliderApi->status == 200){
            $sliderApiResult = json_decode($sliderApi->content,true);
            $sliderResult = $sliderApiResult['result'];
        }

        $categoryApi = \App\Helpers\commonHelper::callApi('GET','/category-list');

        $categoryResult = array();
        if($categoryApi->status == 200){
            $categoryApiResult = json_decode($categoryApi->content,true);
            $categoryResult = $categoryApiResult['result'];
        }

        $productApi = \App\Helpers\commonHelper::callApi('GET','/topselling-product');
        
        $productResult = array();
        if($productApi->status == 200){
            $productApiResult = json_decode($productApi->content,true);
            $productResult = $productApiResult['result'];
        }
        

// print_r($data);die;
        return view('home',compact('sliderResult','categoryResult','productResult'));
    }

    public function contacPage(Request $request){

        if($request->isMethod('post')){

        $data=array(
                "name"=>$request->post('name'),
                "email"=>$request->post('email'),
                "mobile"=>$request->post('mobile'),
                "subject"=>$request->post('subject'),
                "message"=>$request->post('message')
            );
            $contactUsApi = \App\Helpers\commonHelper::callApi('POST', '/contact-us', json_encode($data));
        
            $contactApiResult = json_decode($contactUsApi->content,true);
            
            return response(array('error'=>false, "message"=>$contactApiResult['message']), 200);

        }

        return view('contact');
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
}
