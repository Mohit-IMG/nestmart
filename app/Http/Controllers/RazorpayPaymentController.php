<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Session;
use Exception;
use App\Models\Transaction;

class RazorpayPaymentController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */

    public function index(Request $request)
    {      
        $orderData = \App\Models\Sales::where('order_id',$request->query('order_id'))->first();
        return view('razorpayView',compact('orderData'));
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
{
    // dd($request);
    $input = $request->all();

    $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

    $payment = $api->payment->fetch($input['razorpay_payment_id']);

    if (count($input) && !empty($input['razorpay_payment_id'])) {
        try {
            $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    Session::put('success', 'Payment successful');

    // Get the order ID from the query parameters
    $orderId = $request->query('order_id');

    // Assuming you have a route named 'razorpay.payment.index'
    $razorpayRedirectUrl = route('razorpay.payment.index', ['order_id' => $orderId]);
    return response()->json([
        'success' => 'Payment successful',
        'razorpay_redirect_url' => $razorpayRedirectUrl,
    ]);
}


public function razorpayCallback(Request $request)
{
    try {
        $transaction = new Transaction();
        $orderId = $request->query('order_id');
        $transactionId=strtotime("now").rand(11,99);
        $transaction->order_id = $request->input('order_id');
        $transaction->razorpay_paymentid = $request->input('razorpay_payment_id');
        $transaction->transaction_id = $transactionId;
        $transaction->payment_status = '0';
        $transaction->particular_id = '1';
        $transaction->customer_name = \Auth::user()->name;
        $transaction->customer_id = \Auth::user()->id;
        $transaction->contact = \Auth::user()->mobile;
        $transaction->amount = $request->input('amount')/100;
        $transaction->save();

        return response(['message' => 'Payment details saved successfully.'], 200);
    } catch (\Exception $e) {
        return response(['message' => 'Failed to save payment details.'], 500);
    }
}

}
