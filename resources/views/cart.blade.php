@extends('layouts/app')

@section('title','Cart Checkout')
@section('meta_keywords','Cart Checkout')
@section('meta_description','Cart Checkout')

@section('content')

<main class="main">
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="index.html" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                <span></span> Shop
                <span></span> Cart
            </div>
        </div>
    </div>
    <div class="container mb-80 mt-50">
        <div class="row">
            <div class="col-lg-8 mb-40">
                <h1 class="heading-2 mb-10">Your Cart</h1>
                <div class="d-flex justify-content-between">
                @if(!empty($result) && count($result) > 0)
                    <h6 class="text-body">There are <span class="text-brand" >@if(!empty($result) && count($result)>0){{count($result)}} @else 0 @endif</span> products in your cart</h6>
                    {{-- <h6 class="text-body"><a href="javascript:void(0)" class="text-muted" ><i class="fi-rs-trash mr-5"></i>Clear Cart</a></h6> --}}
                        <h6 class="text-body">
                            <a href="javascript:void(0)" class="text-muted" id="clearCartLink">
                                <i class="fi-rs-trash mr-5"></i>Clear Cart
                            </a>
                        </h6>
                    @else
                            <img src="{{ asset('assets/imgs/shop/noproduct1.png') }}" style="margin-left: 369px;" alt="no product image" />
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive shopping-summery">
                @if(!empty($result) && count($result) > 0)
                    <table class="table table-wishlist">
                        <thead>
                            <tr class="main-heading">
                                <th class="custome-checkbox start pl-30">
                                    <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox11" value="">
                                    <label class="form-check-label" for="exampleCheckbox11"></label>
                                </th>
                                <th scope="col" colspan="2">Product</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col" class="end">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result as $raw)
                            @php
                                    $productResult=\App\Models\Variantproduct::find($raw['product_id']);
                                @endphp
                            <tr>
                                <td class="custome-checkbox pl-30">
                                    <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox3" value="">
                                    <label class="form-check-label" for="exampleCheckbox3"></label>
                                </td>
                                <td class="image product-thumbnail"><img src="{{ $raw['image'] }}" alt="#"></td>
                                <td class="product-des product-name">
                                    <h6 class="mb-5"><a class="product-name mb-10 text-heading" href="{{ url('product_detail/'.$productResult->slug) }}">{{ ucfirst($raw['name'] )}}</a></h6>
                                    <div class="product-rate-cover">
                                        @if($productResult)
                                        @php echo \App\Helpers\commonHelper::getVaraintName($productResult->variant_id,$productResult->variant_attributes); @endphp
                                    @endif
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" style="width:90%"></div>
                                        </div>
                                        <span class="font-small ml-5 text-muted"> (4.0)</span>
                                    </div>
                                </td>
                                <td class="price" data-title="Price">
                                    @if(\Auth::check() && \Auth::user()->designation_id == '2')
                                    <h4 class="text-body">₹{{ $raw['offer_price'] }} </h4>
                                    @endif
                                </td>
                                <td class="text-center detail-info" data-title="Stock">
                                    <div class="detail-extralink mr-15">
                                        @php
                                        $qtyArray=['1','2','3','4','5','6','7','8','9','10'];
                                    @endphp
                                    <input type="hidden" class="cart_id" value="{{ $raw['cartid'] }}" name="cart_id" />
                                    <input type="hidden" class="product_id" value="{{ $raw['product_id'] }}" id="product_id"name="product_id" />   
                                            <select class="form-field-select selectpicker mr-2 cartqty form-control" data-width="80px" onchange="onCartQtyChange(this,{{ $raw['product_id'] }})">
                                                @foreach($qtyArray as $qty)
                                                    <option value="{{ $qty }}" @if($qty == $raw['qty']) selected @endif>{{ $qty }}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </td>
                                {{-- <td class="action text-center" data-title="Remove"><a href="#" class="text-body"><i class="fi-rs-trash"></i></a></td> --}}
                                <td class="action text-center" data-title="Remove">
                                    <a onclick="confirm('Are you sure? you want to delete this item.')"
                                        href="{{ url('delete-cart/'.$raw['cartid']) }}">
                                        <div class="remove-product"><i class="fi-rs-trash"></i></div>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>

                @if(!empty($result) && count($result) > 0)
                    <div class="row mt-50" id="price_details">

                        {{-- Ajax Content here --}}

                    </div>
                @endif

            </div>
        </div>
    </div>
</main>

@endsection

@push('custom_js')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>

    function onCartQtyChange(selectElement, product_id) {
    var id = selectElement.closest('tr').querySelector('.cart_id').value;
    var product_qty = selectElement.value;
    var product_id = product_id;
    console.log('Cart ID:', id);
    console.log('New quantity:', product_qty);
    console.log('productId:', product_id);
    var update = 'update';

    $.ajax({
        url: "{{ route('update-cart') }}",
        dataType: 'json',
        type: 'post',
        async: false,
        data: {
            "id": id,
            "product_qty": product_qty,
            "product_id": product_id,
            "update": update
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            // alert("Your cart is being updated. Act now! Your exclusive coupon awaits at checkout for big discounts.");
        },
        error: function (xhr, textStatus) {
            if (xhr && xhr.responseJSON.message) {
                showMsg('error', xhr.status + ': ' + xhr.responseJSON.message);
            } else {
                showMsg('error', xhr.status + ': ' + xhr.statusText);
            }
        },
        success: function (data) {
            getPriceDetail();
        },
        cache: false,
        timeout: 5000
    });
}

</script>

<script>
    
    var couponId = 0;
    var countryId = 0;
    var couponDiscType = 0;
    var couponDiscAmt = 0;

</script>

<script>
    function getPriceDetail() {
    alert()
        console.log('Calling getPriceDetail function...');
        $.ajax({
            url: "{{ route('cart-price-details') }}",
            dataType: 'json',
            type: 'get',
            async: false,
            data: {
                "coupondisc_type": couponDiscType,
                "coupondisc_amount": couponDiscAmt,
                "countryId": countryId,
            },
            beforeSend: function () {
                console.log('Before sending the AJAX request...');
                $('#price_details').html(loading_set);
            },
            error: function (xhr, textStatus) {
                console.error('Error in getPriceDetail AJAX request:', textStatus, xhr);
            },
            success: function (data) {
                console.log('Success in getPriceDetail AJAX request:', data);
                $('#price_details').html(data.html);
            },
            complete: function () {
                console.log('AJAX request completed.');
            },
            cache: false,
            timeout: 5000
        });
    }

    getPriceDetail();


    function deleteAll() {
        $.ajax({
            url: baseUrl + '/empty-cart',
            dataType: 'json',
            type: 'get',
            success: function(data) {
                if(data.message){
                toastr.success(data.message);
                window.location.href = baseUrl;
            }
            },
            cache: false,
            timeout: 5000
        });
    }

    $(document).ready(function() {
        $("#clearCartLink").click(function() {
            deleteAll();
        });
    });

    


</script>


@endpush
