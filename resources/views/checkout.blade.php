@extends('layouts/app')

@section('title',__(' Home'))

@section('content')

<style>
.cross-button {
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: red;
    color: white;
    text-align: center;
    line-height: 20px;
}
</style>

<main class="main">
   <form action="{{ route('user.checkout') }}" method="Post" class="m-0" id="checkout" autocomplete="off">
      <div class="page-header breadcrumb-wrap">
         <div class="container">
            <div class="breadcrumb">
               <a href="{{url('/')}}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a> 
               <span></span> Checkout
            </div>
         </div>
      </div>
      <div class="container mb-80 mt-50">
         <div class="row">
            <div class="col-lg-8 mb-40">
               <h3 class="heading-2 mb-10">Checkout</h3>
               <div class="d-flex justify-content-between">
                  <h6 class="text-body">There are products in your cart</h6>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-lg-7">
               @foreach($resultAddressBook as $address)
               <div class="address-box mb-20" style="border: 1px solid green; padding: 10px; border-radius: 5px;"> 
                  <input type="radio" name="address_id" id="inlineRadio1" value="{{$address->id}}" style="transform: scale(0.6); width: 16px; height: 16px; margin-right: 8px;">
                  <label for="inlineRadio1" class="address-label" style="padding: 6px 12px; border: 1px solid green; border-radius: 4px; font-size: 14px; margin: 0;">{{$address->address_line1}}</label>
               </div>
               @endforeach
               
               <div class="address-box mb-20" style="border: 1px solid green; padding: 10px; border-radius: 5px;">
                  <div class="bordered-address" style="display: flex; align-items: center;">
                        <input @if(empty($resultAddressBook)) checked @endif type="radio" name="address_id" id="inlineRadio2" value="0" style="transform: scale(0.6); width: 16px; height: 16px; margin-right: 8px;">
                        <label for="inlineRadio2" class="address-label" style="padding: 6px 12px; border: 1px solid green; border-radius: 4px; font-size: 14px; margin: 0;">New Address</label>
                  </div>
               </div>
               <div class="row billing-details">
                  <h4 class="mb-30">Billing Details</h4>
                  @csrf
                     <div class="row">
                        <div class="form-group col-lg-6">
                           <input type="text" name="name" placeholder="Enter name *" class="form-control addressRequired" onkeydown="return /^[A-Za-z\s]*$/.test(event.key)">
                        </div>
                        <div class="form-group col-lg-6">
                           <input type="text" name="email" placeholder="Email address *" class="form-control addressRequired">
                        </div>
                     </div>
                     <div class="row shipping_calculator">
                        <div class="form-group col-lg-6">
                           <input  type="tel" name="mobile" placeholder="Phone *" class="form-control addressRequired" maxlength="10" onkeypress="return /[0-9 ]/i.test(event.key)" >
                        </div>                        
                        <div class="form-group col-lg-6">
                           <input type="tel" name="pincode" class="form-control addressRequired" placeholder="Pin Code*" minlength="5" maxlength="6" onkeypress="return /[0-9 ]/i.test(event.key)">
                        </div>
                     </div>
                     <div class="row shipping_calculator">
                        <div class="form-group col-lg-6">
                           <div class="custom_select">
                              <select class="selectbox state statehtml form-control" name="state_id">
                                 <option value="">--Select state--</option> 
                                 @foreach($states as $state)
                                    <option value="{{$state->id}}">{{ucfirst($state->name)}}</option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                        
                        <div class="form-group col-lg-6">
                           <select class="selectbox cityHtml form-control" name="city_id" >
                                 <option value="">--Select city--</option> 
                           </select>
                        </div>
                     </div>
                     <div class="form-group col-md-12">
                        <input type="text" name="address_1" placeholder="Address 1*"  class="form-control addressRequired">
                     </div>
                     <div class="form-group col-md-12">
                        <input type="text" name="address_2" placeholder="Address 2 (Opitional)"  class="form-control">
                     </div>
                  
               </div>
            </div>
            <div class="col-lg-5">
               <div class="border p-40 cart-totals ml-30 mb-50">
                  <div class="d-flex align-items-end justify-content-between mb-30">
                     <h4>Your Order</h4>
                     <h6 class="text-muted">Subtotal</h6>
                  </div>
                  <div class="divider-2 mb-30"></div>
                  <div class="table-responsive order_table checkout">
                     <table class="table no-border">
                        <tbody>
                        @php
                           $subtotal = 0;
                     @endphp
                           @foreach($cartProductDetail as $product)
                           <tr>
                              @php
                                 $imageArray = explode(',',$product['cartProduct'][0]['images']);
                              @endphp

                              <td class="image product-thumbnail"><img src="{{asset('uploads/products/'.$imageArray[0])}}" alt="#" style="height:100px;width:100px;"></td>
                              <td>
                              @php
                                 $productName = \App\Models\Product::where('id',$product['cartProduct'][0]['product_id'])->first();
                              @endphp

                              <?php
                              $productName = $productName->name;

                              $words = str_word_count($productName, 1);
                              $limitedWords = array_slice($words, 0, 2);
                              $limitedName = implode(' ', $limitedWords);

                              if (str_word_count($productName) > 2) {
                                 $limitedName .= '...';
                              }
                              ?>
                                 <h6 class="w-160 mb-5"><a href="{{ url('product_detail/'.$product['cartProduct'][0]['slug']) }}" class="text-heading">{{$limitedName}}</a></h6>
                                 </span>
                              </td>
                              <td>
                                 <h6 class="text-muted pl-20 pr-20">x {{$product->qty}}</h6>
                              </td>
                              <td>
                              @php
                                 $product_discounted_price = ($product['cartProduct'][0]['sale_price']*$product['cartProduct'][0]['discount_amount'])/100;
                                 $productPrice = $product['cartProduct'][0]['sale_price'] - $product_discounted_price;

                                 $subtotal += $productPrice * $product->qty;
                                 
                              @endphp
                                 <h4 class="text-brand">₹{{$productPrice}}</h4>
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                     <table class="table no-border">
                        <tbody>
                           <tr>
                              <td class="cart_total_label">
                                 <h6 class="text-muted">Subtotal</h6>
                              </td>
                              <td class="cart_total_amount">
                                 <h4 class="text-brand text-end">₹{{$subtotal}}</h4>
                              </td>
                           </tr>
                           <tr>
                              <td class="cart_total_label">
                                 <h6 class="text-muted">Coupon Name</h6>
                              </td>
                              @if(\Auth::user()->couponstatus == 'inactive' || session('enteredCode'))
                                 @if(session('enteredCode'))   
                                    <td class="cart_total_amount">
                                       <h6 class="text-brand text-end">{{session('enteredCode')}} <a href="{{route('user.remove-coupon')}}" class="cross-button" style="text-decoration: none;">&#x2716;</a></h6>
                                    </td>
                                 @else
                                    <td class="cart_total_amount">
                                       <h6 class="text-brand text-end">{{\Auth::user()->coupencode}} <a href="{{route('user.remove-coupon')}}" class="cross-button" style="text-decoration: none;">&#x2716;</a></h6>
                                    </td>
                                 @endif
                              @else
                              <td class="cart_total_amount">
                                 <h6 class="text-brand text-end">Not Applied</h6>
                              </td>
                              @endif

                           </tr>
                           <tr>
                              <td class="cart_total_label">
                                 <h6 class="text-muted">Coupon Discount</h6>
                              </td>
                              @if(\Auth::user()->couponstatus == 'inactive' || session('enteredCode'))
                              <td class="cart_total_amount">
                                 @php
                                    $coupon = \App\Models\Coupon::where('code',session('enteredCode'))->first();

                                    if(session('enteredCode')) {
                                       $discountAmt = ($subtotal *$coupon['value'])/100;
                                    }else{
                                       $discountAmt = ($subtotal *10) /100;
                                    }  
                                          
                                 @endphp
                                 <input type="hidden" name="discountvalue" value="{{$discountAmt}}">
                                 <h4 class="text-brand text-end">₹{{$discountAmt}}</h4>
                              </td>
                              @else
                              <td class="cart_total_amount">
                                 <h4 class="text-brand text-end">₹0</h4>
                              </td>
                              @endif
                           </tr>
                           <tr>
                              <td class="cart_total_label">
                                 <h6 class="text-muted">Grand Total</h6>
                              </td>
                              @if(\Auth::user()->couponstatus == 'inactive' || session('enteredCode'))
                              <td class="cart_total_amount">
                                 @php
                                          $totalAmt = $subtotal - $discountAmt;
                                 @endphp
                                    <h4 class="text-brand text-end">₹{{$totalAmt}}</h4>
                                 </td>
                                 @else
                                 <td class="cart_total_amount">
                                    <h4 class="text-brand text-end">₹{{$subtotal}}</h4>
                                 </td>
                              @endif
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="payment ml-30">
                  <h4 class="mb-30">Payment</h4>
                  <div class="payment_option">
                     <div class="custome-radio">
                        <input class="form-check-input" required="" type="radio" name="payment_type" value="2" id="exampleRadios4" checked="">
                        <label class="form-check-label" for="exampleRadios4" data-bs-toggle="collapse" data-target="#checkPayment" aria-controls="checkPayment">Cash on delivery</label>
                     </div>
                     <div class="custome-radio">
                        <input class="form-check-input" required="" type="radio" name="payment_type"  value="1" id="exampleRadios5" checked="">
                        <label class="form-check-label" for="exampleRadios5" data-bs-toggle="collapse" data-target="#paypal" aria-controls="paypal">Online Getway</label>
                     </div>
                  </div>
                  <div class="payment-logo d-flex">
                     <img class="mr-15" src="assets/imgs/theme/icons/payment-paypal.svg" alt="">
                     <img class="mr-15" src="assets/imgs/theme/icons/payment-visa.svg" alt="">
                     <img class="mr-15" src="assets/imgs/theme/icons/payment-master.svg" alt="">
                     <img src="assets/imgs/theme/icons/payment-zapper.svg" alt="">
                  </div>
                  <button type="submit" class="btn btn-fill-out btn-block mt-30">Place an Order<i class="fi-rs-sign-out ml-15"></i></button>
               </div>
            </div>
         </div>
      </div>
   </form>
</main>
@endsection

@push('custom_js')
   
<script>
    $(document).ready(function() {
        $('.billing-details').hide();

        $('#inlineRadio2').change(function() {
            if ($(this).is(':checked')) {
                $('.billing-details').slideDown();
            } else {
                $('.billing-details').slideUp();
            }
        });

        $('#inlineRadio1').change(function() {
            if ($(this).is(':checked')) {
                $('.billing-details').slideUp();
            }
        });
    });

    $("form#checkout").submit(function(e) {
        e.preventDefault();

        var formId = $(this).attr('id');
        var formAction = $(this).attr('action');
        var formData = new FormData(this);

        var discountAmt = $('[name="discountvalue"]').val();

        formData.append('discountvalue', discountAmt);

        $.ajax({
            url: formAction,
            data: formData,
            dataType: 'json',
            type: 'post',
            async: true,
            beforeSend: function() {
                $('.checkoutLoader').css('display', 'inline-block');
                $('#' + formId + 'Submit').prop('disabled', true);
            },
            error: function(xhr, textStatus) {
                if (xhr && xhr.responseJSON.message) {
                    showMsg('error', xhr.responseJSON.message);
                } else {
                    showMsg('error', xhr.statusText);
                }
                $('.checkoutLoader').css('display', 'none');
                $('#' + formId + 'Submit').prop('disabled', false);
            },
            success: function(data) {
                $('.checkoutLoader').css('display', 'none');
                $('#' + formId + 'Submit').prop('disabled', false);

                if (data.checkout_type == 'cod') {
                    location.href = "{{ url('user/order-placed?order_id=') }}" + data.checkout_order_id;
                } else {
                    if (data.checkout_type == 'online') {
                     var redirectTo = '{{ route("razorpay.payment.index") }}' + '?order_id=' + data.checkout_order_id;
                     // alert(redirectTo)
                     window.location.href = redirectTo;     
                    }
                }
            },
            cache: false,
            contentType: false,
            processData: false,
            timeout: 5000
        });
    });
</script>

@endpush

