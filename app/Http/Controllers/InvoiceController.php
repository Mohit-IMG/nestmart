<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
Use Auth;
Use DB;
Use Session;
use PDF;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{

    public function invoiceView(){
        return view('invoice');
    }


    public function generateInvoice(Request $request, $order_id) {
        try {
            $user = auth()->user();
            $salesDetails = $user->salesDetails->where('order_id', $order_id);
    
            if ($salesDetails->count() == 0) {
                return response(array('message' => 'No sales details found for this order!'), 404);
            } else {
                $data = [];
    
                foreach ($salesDetails as $salesDetail) {
                    $variantProduct = \App\Models\Variantproduct::find($salesDetail->product_id);
                    if ($variantProduct) {
                        $product = $variantProduct->product;
                        $finalAmount = $salesDetail->amount - $salesDetail->discount;
                        $address = \App\Models\Addressbook::where('user_id',\Auth::user()->id)->first();
                        $subTotal = $finalAmount*$salesDetail->qty;
                        $discount = $salesDetail->discount;
                        $vat = $salesDetail->discount * 0.10; 
                        $invoiceDate = $salesDetail->updated_at; 
                        $logoUrl = 'https://i.ibb.co/PrrYkH1/logo.png'; 
                        $data[] = [
                            'id' => $salesDetail->id,
                            'user_id' => $salesDetail->user_id,
                            'sale_id' => $salesDetail->sale_id,
                            'order_id' => $salesDetail->order_id,
                            'suborder_id' => $salesDetail->suborder_id,
                            'waybill_no' => $salesDetail->waybill_no,
                            'product_id' => $salesDetail->product_id,
                            'product_name' => $salesDetail->product_name,
                            'product_image' => $salesDetail->product_image,
                            'qty' => $salesDetail->qty,
                            'sub_total' => $salesDetail->sub_total,
                            'discount' => $salesDetail->discount,
                            'amount' => $finalAmount,
                            'order_status' => $salesDetail->order_status,
                            'payment_status' => $salesDetail->payment_status,
                            'remark' => $salesDetail->remark,
                            'created_at' => $salesDetail->created_at,
                            'updated_at' => $salesDetail->updated_at,
                            'is_approve' => $salesDetail->is_approve,
                            'product_name' => $salesDetail->product_name,
                            'tax_ratio' => $product->tax_ratio,
                            'short_description' => $product->short_description,
                            'logo' => 'https://i.ibb.co/KLGdWgF/e-shop1.png',
                        ];
                    }
                }
                return view('invoice', compact('data','address','subTotal','discount','vat','logoUrl','order_id','invoiceDate'));
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
    
            return response(array("message" => "Something went wrong. Please try again"), 403);
        }
    }


    public function downloadInvoice(Request $request, $order_id) {
        try {
            $user = auth()->user();
            $salesDetails = $user->salesDetails->where('order_id', $order_id);
            $data = [];
    
            
            foreach ($salesDetails as $salesDetail) {
                $variantProduct = \App\Models\Variantproduct::find($salesDetail->product_id);
                if ($variantProduct) {
                    $product = $variantProduct->product;
                    $finalAmount = $salesDetail->amount - $salesDetail->discount;
                    $address = \App\Models\Addressbook::where('user_id',\Auth::user()->id)->first();
                    $subTotal = $finalAmount*$salesDetail->qty;
                    $discount = $salesDetail->discount;
                    $vat = $salesDetail->discount * 0.10; 
                    $invoiceDate = $salesDetail->updated_at; 
                    $logoUrl = 'https://i.ibb.co/PrrYkH1/logo.png'; 
                    $data[] = [
                        'id' => $salesDetail->id,
                        'user_id' => $salesDetail->user_id,
                        'sale_id' => $salesDetail->sale_id,
                        'order_id' => $salesDetail->order_id,
                        'suborder_id' => $salesDetail->suborder_id,
                        'waybill_no' => $salesDetail->waybill_no,
                        'product_id' => $salesDetail->product_id,
                        'product_name' => $salesDetail->product_name,
                        'product_image' => $salesDetail->product_image,
                        'qty' => $salesDetail->qty,
                        'sub_total' => $salesDetail->sub_total,
                        'discount' => $salesDetail->discount,
                        'amount' => $finalAmount,
                        'order_status' => $salesDetail->order_status,
                        'payment_status' => $salesDetail->payment_status,
                        'remark' => $salesDetail->remark,
                        'created_at' => $salesDetail->created_at,
                        'updated_at' => $salesDetail->updated_at,
                        'is_approve' => $salesDetail->is_approve,
                        'product_name' => $salesDetail->product_name,
                        'tax_ratio' => $product->tax_ratio,
                        'short_description' => $product->short_description,
                        'logo' => 'https://i.ibb.co/KLGdWgF/e-shop1.png',
                    ];
                }
            }
    
            $address = \App\Models\Addressbook::where('user_id', \Auth::user()->id)->first();
            $subTotal = $finalAmount * $salesDetail->qty; 
            $discount = $salesDetail->discount;
            $vat = $salesDetail->discount * 0.10;
            $invoiceDate = $salesDetail->updated_at;
            $logoUrl = 'https://i.ibb.co/PrrYkH1/logo.png';
            $pdf = PDF::loadView('invoice', compact('data', 'address', 'subTotal', 'discount', 'vat', 'logoUrl', 'order_id', 'invoiceDate'))
            ->setPaper('a4', 'portrait');
        $filename = 'invoice_' . $order_id . '_' . time() . '.pdf';
        Storage::put('public/invoices/' . $filename, $pdf->output());
        return $pdf->stream($filename, ['Content-Disposition' => 'attachment']);
        } catch (\Exception $e) {
            echo $e->getMessage();
    
            return response(array("message" => "Something went wrong. Please try again"), 403);
        }
    }
    

}
