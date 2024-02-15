@extends('layouts.app')

@section('title','Order Placed')
@section('meta_keywords','Order Placed')
@section('meta_description','Order Placed')

@section('content')

<div class="container-fluid thanku-box ">
    <div class="container">
        <div class="row justify-content-center">
              <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="thanku-card">
                    <div class="thnk-img text-center">
                        <img src="{{asset('images/thanku.png')}}" alt="" class="img-fluid">
                    </div>
                    <div class="thankyou-text text-center pt-5">
                        <h4><span>Thank You!</span> Your Order Placed Successfully.</h4>
                        <p class="pt-3">Your Order has been placed successfully. Thanks for shopping with us. We will notify you once your shipment is ready.
                        </p>
                        <br>
                        <p class="pt-2 pb-4">Order id: {{ $orderId }}</p>
                        <!-- <a href="#" class="order-btn"><i class="fa fa-long-arrow-left pe-2" aria-hidden="true"></i>View Order</a> -->
                    </div>
                </div>
              </div>


        </div>
    </div>
</div>

@endsection

