<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Sales_detail;

use Validator;
// use Razorpay\Api\Api;
use Session;
use DB;


class SalesController extends Controller
{

	
    public function salesList(Request $request,$type){ 

		$salesQuery=\App\Models\Sales::orderBy('id','DESC');

		if($type=='failed'){

			$orderStatus='0';
			$title='Failed Sales List';
			
		}else if($type=='pending'){

			$orderStatus='1';
			$title='Pending Sales List';
			
		}else if($type=='rejected'){
			
			$orderStatus='7';
			$title='Cancel By Admin Sales List';
			
		}else if($type=='confirmed'){

			$orderStatus='2';
			$title='Confirmed Sales List';
			
		}else if($type=='shipped'){
			
			$orderStatus='10';
			$title='Shipped Sales List';
			
		}else if($type=='delivered'){
			
			$orderStatus='9';
			$title='Delivered Sales List';
			
		}else if($type=='return'){
			$orderStatus='8';
			$title='Return Sales List';

		}else{
			
			return redirect()->back()->with('5fernsadminerror','Something went wrong. please try again.');
		}

		$salesQuery->with(['getsalesdetailchild'=>function($query) use($orderStatus){
			$query->select('id','return_status','sale_id','suborder_id','payment_status');
			$query->where('order_status',$orderStatus);
		}]);

		$salesQuery->withCount(['getsalesdetailchild as gross_amount'=>function($query2) use($orderStatus){
			$query2->select(DB::raw("SUM(sub_total)"));
			$query2->where('order_status',$orderStatus);
		}]);

		$salesQuery->withCount(['getsalesdetailchild as net_amount'=>function($query2) use($orderStatus){
			$query2->select(DB::raw("SUM(amount)")); 
			$query2->where('order_status',$orderStatus);
		}]);

		$result=$salesQuery->having('gross_amount','>','0')->get();
		// dd($result);
// echo "<pre>";print_r($result->toArray());die;
		return view('admin.sales.list',compact('result','title','type'));
	}
	
	public function getSalesDetail(Request $request){
		
		$rules = [
            'type' => 'required|in:approve,reject,view',
            'id' => 'required|numeric',
			'pageType'=>'required'
		];

		$validator = Validator::make($request->all(), $rules);
		 
		if ($validator->fails()) {
			$message = [];
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return response(array('message'=>$message),403);
			
		}else{
			
			$orderStatus=1;
			
			if($request->post('pageType')=='pending'){
				
				$orderStatus=1;
			}
			if($request->post('pageType')=='rejected'){
				
				$orderStatus=7;
			}
			if($request->post('pageType')=='confirmed'){
				
				$orderStatus=2;
			}
			if($request->post('pageType')=='shipped'){
				
				$orderStatus=10;
			}
			if($request->post('pageType')=='delivered'){
				
				$orderStatus=9;
			}
			if($request->post('pageType')=='return'){
				
				$orderStatus=8;
			}
			
			if($request->post('pageType')=='failed'){

				$salesDetail=Sales_detail::Select('sales_details.*','variantproducts.variant_id','variantproducts.variant_attributes')->join('variantproducts','variantproducts.id','=','sales_details.product_id')->where('sale_id',$request->post('id'))->where('payment_status','1')->get();
			}else{
				$salesDetail=Sales_detail::Select('sales_details.*','variantproducts.variant_id','variantproducts.variant_attributes')->join('variantproducts','variantproducts.id','=','sales_details.product_id')->where('sale_id',$request->post('id'))->where('order_status',$orderStatus)->get();
			}

			if($salesDetail->count()==0){
			
				return response(array('message'=>'Orders not found.'),404);
				
			}else{
				
				$sales=Sales::where('id',$request->post('id'))->first();
				$type=$request->post('type');
				$pageType=$request->post('pageType');
				$orderId=$salesDetail[0]->order_id;
				$html=view('admin.sales.sales_detail_modal',compact('salesDetail','sales','type','pageType','orderId'))->render();
				
				return response(array('message'=>'Product detail fetched successfully.','html'=>$html),200);
			}
		}

	}
	
	public function updateOrderStatus(Request $request){
		
		$rules = [
            'type' => 'required|in:approve,reject',
            'saleids' => 'required',
			'order_id'=>'required|exists:sales,order_id'
		];
  
		$validator = Validator::make($request->all(), $rules);
		 
		if ($validator->fails()) {

			$message = [];
			$messages_l = json_decode(json_encode($validator->messages()), true);
			foreach ($messages_l as $msg) {
				$message= $msg[0];
				break;
			}
			
			return redirect('admin/sales/list/pending')->with('5fernsadminerror',$message);
			
		}else{
			
			$salesIds=$request->post('saleids');
			
			$salesResult=\App\Models\Sales::with('UserData')->where('order_id',$request->post('order_id'))->first();
 
			if(!empty($salesIds) && $salesIds[0]!='' && $salesResult){
				
				try{

					$salesResult->total_created_order=($salesResult->total_created_order+1);
					$salesResult->save();

					$suborderId = $salesResult->order_id . '_' . $salesResult->total_created_order;


					$orderStatus=1;

					if($request->post('type')=='approve'){
						
						$orderStatus=2;
					}

					if($request->post('type')=='reject'){
						
						$orderStatus=7;

					}

					$totalDiscount=0;$totalAmount=0;
					foreach($salesIds as $key=>$sale){

						$salesDetailResult= Sales_detail::find($sale);
						$totalDiscount+=$salesDetailResult->discount; 
						$totalAmount+=$salesDetailResult->sub_total; 

						if($request->post('type')=='reject'){

							Sales_detail::where('id',$sale)->where('order_status','1')->update(['order_status'=>$orderStatus,'payment_status'=>'3', 'suborder_id'=>$suborderId]);
		 
						}else{
 
							Sales_detail::where('id',$sale)->where('order_status','1')->update(['is_approve'=>'1','order_status'=>$orderStatus,'suborder_id'=>$suborderId]);
						}
						
					}

					if($request->post('type')=='approve'){
						
						$walletTxnDetail=[];
						if($totalDiscount>0 && $salesResult['UserData']['designation_id']=='5'){

							$discountWallet = \App\Helpers\commonHelper::getDiscountWalletBalance($salesResult->user_id);
							$discountWalletBalance=$discountWallet['crSuccess']-$discountWallet['drSuccess']-$discountWallet['drPending'];
							
							if($discountWalletBalance >= $totalDiscount){
		
								$discountWalletData=\App\Helpers\commonHelper::createDiscountWalletData($salesResult->user_id, 'Dr',$totalDiscount ,'Pending', $suborderId." Order id's cashback deducted");
								$walletTxnDetail['discount']=$discountWalletData['txnid'];

								$totalDiscount-=$totalDiscount;
								
							}elseif($discountWalletBalance >0){
		
								$totalDiscount-=$discountWalletBalance; 

								$discountWalletData=\App\Helpers\commonHelper::createDiscountWalletData($salesResult->user_id, 'Dr',$discountWalletBalance ,'Pending', $suborderId." Order id's cashback deducted");
								$walletTxnDetail['discount']=$discountWalletData['txnid'];

							}
		
							if($totalDiscount>0){
		
								$shoppingWalletData=\App\Helpers\commonHelper::creatShoppingWalletData($salesResult->user_id, 'Cr',$totalDiscount ,'Pending', $suborderId." Order id's cashback received");
								$walletTxnDetail['shopping']=$shoppingWalletData['txnid'];
							}
						} 

						$approveOrder=new \App\Models\Approvedorder();
						$approveOrder->user_id=$salesResult->user_id;
						$approveOrder->order_id=$suborderId;
						$approveOrder->amount=$totalAmount;
						$approveOrder->walletdetail=json_encode($walletTxnDetail);
						$approveOrder->save();
					}

					if($request->post('type')=='reject'){
 
						$shoppingWalletData=\App\Helpers\commonHelper::creatShoppingWalletData($salesResult->user_id, 'Cr',$totalAmount ,'Refund', $suborderId." Order id's refund received");
						$walletTxnDetail['shopping']=$shoppingWalletData['txnid'];

						$rejectOrder=new \App\Models\Rejectorder();
						$rejectOrder->user_id=$salesResult->user_id;
						$rejectOrder->order_id=$suborderId;
						$rejectOrder->amount=$totalAmount;
						$rejectOrder->walletdetail=json_encode($walletTxnDetail);
						$rejectOrder->save();
					}
					
					return redirect('admin/sales/list/pending')->with('5fernsadminsuccess','Order status changed successfully.');

				}catch(\Exception $e){
					
					return redirect('admin/sales/list/pending')->with('5fernsadminerror',$e->getMessage());
				}
				
			}else{
				
				return redirect('admin/sales/list/pending')->with('5fernsadminerror','Something went wrong. Please try again.');
			}
			
		}
		
	}

	public function orderInvoice(Request $request,$id){

		$result=\App\Models\Sales_detail::select('sales.name','sales.country_id','sales.state_id','sales.address_line1','sales.address_line2','sales.city_id','sales.pincode','sales_details.*')->where('sales.id',$id)->where('sales_details.is_approve','1')->join('sales','sales_details.sale_id','=','sales.id')->get();

		if($result->count()==0){

			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');

		}else{

			return view('admin.sales.order_invoice',compact('result'));

		}
		
	}

	public function orderReady(Request $request){
 
		$result=\App\Models\Sales::with(['getsalesdetailchild'=>function($query) use($request){
			$query->where('suborder_id',$request->post('suborder_id'));
			$query->where('is_approve','1');
		}])->where('sales.id',$request->post('sale_id'))->first()->toArray();

		if(empty($result) || empty($result['getsalesdetailchild'])){

			return response(array('message'=>"Something went wrong. Please try again."),404);

		}else{

			
			$salesIds=$request->post('sale_id');

			if($request->post('type') == 'shipped'){
				$order_status = '10';

				Sales_detail::where('sale_id',$salesIds)->where('suborder_id',$request->post('suborder_id'))->where('order_status','2')->update(['order_status'=>$order_status,'is_approve'=>'1']);

			}elseif($request->post('type') == 'delivered'){ 

				$order_status = '9';

				Sales_detail::where('sale_id',$salesIds)->where('suborder_id',$request->post('suborder_id'))->where('order_status','10')->update(['order_status'=>$order_status,'is_approve'=>'1']);

				//update wallet data
				$approvedOrders=\App\Models\Approvedorder::with('UserData')->where('order_id',$request->post('suborder_id'))->first();
// dd($approvedOrders);

				if($approvedOrders && $approvedOrders['UserData']['designation_id']=='5'){

					$approvedOrdersData=json_decode($approvedOrders->walletdetail,true);

					if(isset($approvedOrdersData['shopping'])){

						\App\Models\ShoppingWallet::where('txn_id',$approvedOrdersData['shopping'])->update(['status'=>'Success']);
						\App\Models\Shoppingwalletstatement::where('txn_id',$approvedOrdersData['shopping'])->update(['status'=>'Success']);
					}

					if(isset($approvedOrdersData['discount'])){

						\App\Models\DiscountWallet::where('txn_id',$approvedOrdersData['discount'])->update(['status'=>'Success']);
						\App\Models\Discountwalletstatement::where('txn_id',$approvedOrdersData['discount'])->update(['status'=>'Success']);
					}
				}
			} 
			
			return response(array('message'=>"Order ".ucfirst($request->post('type')). 'Successfully.'),200);
		}

	}
	public function returnApprove(Request $request){

		$result = \App\Models\Sales_detail::where('id', $request->post('id'))
					->update([
						'return_status' => 'Approved',
						'reject_remark' => $request->post('remarks')
					]);

		if($result){
			return redirect()->back()->with('5fernsadminsuccess','Return Approved Successfully.');
		}else{
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}

	}
	public function returnReject(Request $request){
		$salesResult=\App\Models\Sales_detail::where('id',$request->post('id'))->update(['return_status'=>'Rejected','reject_remark'=>$request->post('remarks')]);
		if($salesResult){
			return redirect()->back()->with('5fernsadminsuccess','Return rejected Successfully.');
		}else{
			return redirect()->back()->with('5fernsadminerror','Something went wrong. Please try again.');
		}
               
	}
}
