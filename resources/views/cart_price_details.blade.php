<style>
    .coupon-box .radio-icon-div {
        border-bottom: 1px solid #c2c2c2;
    }

    .coupon-box .radio-icon-div .radio-icon {
        width: 25px;
        height: 25px;
        display: inline-block;
        float: left;
    }

    .coupon-box .custom-title span {
        padding: 2px 10px;
        border-width: 2px;
        border-style: dashed;
    }

    .radio-icon-div ul {
        list-style: none;
    }
</style>

<div class="container">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <div class="row">
        <div class="col-lg-5">
            <div class="p-40">
                <h4 class="mb-10">Apply Coupon</h4>
                <p class="mb-30"><span class="font-lg text-muted">Using A Promo Code?</span></p>
                <input type="hidden" value="{{ $finalAmount }}" name="totalAmount" id="totalAmount">
                <form id="couponForm" action="{{ url('/apply-coupon') }}" method="post">
                    @csrf
                    <div class="d-flex justify-content-between">
                        @php
                            $hasEnteredCode = session('enteredCode');
                        @endphp

                        <input class="font-medium mr-15 coupon"
                               value="{{ (\Auth::user()->couponstatus == 'inactive' || $hasEnteredCode) ? $hasEnteredCode : '' }}"
                               @if(\Auth::user()->couponstatus == 'inactive' || $hasEnteredCode) disabled @endif
                               name="Coupon"
                               id="couponCode"
                               placeholder="Enter Your Coupon" disabled style="width:50%;cursor: not-allowed;">

                        <button type="submit" class="btn">
                            <i class="fi-rs-label mr-10"></i>Apply
                        </button>
                    </div>
                    <p class="coupon-applied-msg d-none text-white">Coupon applied successfully!</p>

                    @php
                        $user = \App\Models\User::where('id', \Auth::user()->id)->first();
                        $coupons = json_decode($user->offer_coupons);
                    @endphp

                    @if (!empty($coupons))
                        @foreach ($coupons as $coupon)
                            @php $couponVal = \App\Models\Coupon::where('code', $coupon)->first(); @endphp
                            <div class="coupon-box my-3">
                                <div class="radio-icon-div">
                                    <div class="radio-icon">
                                        <input type="radio" name="radio" class="coupon-radio" data-code="{{ $couponVal->code }}" checked="checked">
                                        <label class="radio-style" for="radio"></label>
                                    </div>
                                    <div class="radio-label-text">
                                        <div class="row">
                                            <h3 class="custom-title"><span>{{ $couponVal->code }}</span></h3>
                                            <h5 class="custom-desc my-2">
                                                <ul>
                                                    <li class="coupon-description">{{ round($couponVal->value) }}% off on selected items</li>
                                                    <!-- <li class="coupons-description">Valid till 25 December 2016</li> -->
                                                </ul>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="divider-2 mb-30"></div>
            <style>
                p.coupon-applied-msg {
                    background-color: #3BB77E;
                    color: #fff;
                    padding: 10px;
                    border-radius: 5px;
                    margin: 10px 0;
                    text-align: center;
                }

                /* Additional styling for better visibility */
                p.coupon-applied-msg:before,
                p.coupon-applied-msg:after {
                    content: '';
                    display: table;
                }

                p.coupon-applied-msg:after {
                    clear: both;
                }
            </style>
            <div class="border p-md-4 cart-totals ml-30">
                <div class="table-responsive">
                    <table class="table no-border">
                        <tbody>
                        <!-- Discount Section -->
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">Discount</h6>
                            </td>
                            <td class="cart_total_amount" id="discountAmount">
                                <h5 class="text-heading text-end">₹{{ round($discountAmount) }}</h5>
                            </td>
                        </tr>
                        <!-- End Discount Section -->

                        <!-- Coupon Section -->
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">Coupon Amount</h6>
                            </td>
                            <td class="cart_total_amount" id="couponAmount">
                                @php
                                    $coupon = \App\Models\Coupon::where('code', session('enteredCode'))->first();

                                    if (session()->has('enteredCode')) {
                                        $cpamt = ($finalAmount * $coupon['value']) / 100;
                                    } else {
                                        $cpamt = ($finalAmount * 10) / 100;
                                    }
                                @endphp
                                @if($hasEnteredCode)
                                    <h5 class="text-heading text-end" id="discountGiven">₹ {{ $cpamt }}</h5>
                                @else
                                    <h5 class="text-heading text-end" id="discountGiven">
                                        @if(\Auth::user()->couponstatus == 'active' || \Auth::user()->couponstatus == 'used') ₹ 0 @else ₹{{ $cpamt }} @endif
                                    </h5>
                                @endif
                            </td>
                        </tr>
                        <!-- End Coupon Section -->

                        <!-- Price Section -->
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">Price</h6>
                            </td>
                            <td class="cart_total_amount" id="totalMrp">
                                <h4 class="text-brand text-end">₹{{ round($totalMrp) }}</h4>
                            </td>
                        </tr>
                        <!-- End Price Section -->

                        <!-- Shipping Section -->
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">Shipping</h6>
                            </td>
                            <td class="cart_total_amount" id="totalShipping">
                                <h5 class="text-heading text-end">₹{{ round($totalShipping) }}</h5>
                            </td>
                        </tr>
                        <!-- End Shipping Section -->

                        <!-- Estimate Section -->
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">Estimate for</h6>
                            </td>
                            <td class="cart_total_amount">
                                <h5 class="text-heading text-end">India</h5>
                            </td>
                        </tr>
                        <!-- End Estimate Section -->

                        <!-- Total Section -->
                        <tr>
                            <td class="cart_total_label">
                                <h6 class="text-muted">Total</h6>
                            </td>
                            <td class="cart_total_amount">
                                @if(\Auth::user()->couponstatus == 'inactive' || $hasEnteredCode)
                                    <h4 class="text-brand text-end" id="finalAmount">₹{{ round($finalAmount) - $cpamt }}</h4>
                                @else
                                    <h4 class="text-brand text-end" id="finalAmount">₹{{ round($finalAmount) }}</h4>
                                @endif
                            </td>
                        </tr>
                        <!-- End Total Section -->
                        </tbody>
                    </table>
                </div>
                <a href="{{ url('user/checkout') }}" class="btn mb-20 w-100">Proceed To CheckOut<i class="fi-rs-sign-out ml-15"></i></a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initially, uncheck all radio buttons
        $('.coupon-radio').prop('checked', false);

        $('.coupon-radio').change(function() {
            // Check only the selected radio button
            $(this).prop('checked', true);

            var selectedCouponCode = $(this).data('code');
            $('#selectedCouponCode').val(selectedCouponCode);
            $('#couponCode').val(selectedCouponCode);
            // Your AJAX or further processing code here
        });
    });
</script>

@push('custom_js')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
@endpush
