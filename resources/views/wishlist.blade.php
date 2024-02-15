@extends('layouts/app')

@section('title',__(' Home'))

@section('content')

<main class="main">
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ url('/') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                <span></span> Shop 
            </div>
        </div>
    </div>
    <div class="container mb-30 mt-50">
        <div class="row">
            <div class="col-xl-10 col-lg-12 m-auto">
                <div class="mb-50">
                    @php
                        $resultData=json_decode($wishlist->content,true);
                    @endphp
                    <h1 class="heading-2 mb-10">Your Wishlist</h1>
                    <h6 class="text-body">There are <span class="text-brand">@if(!$resultData){{count($resultData['result'])}}@else 0 @endif</span> products in this list</h6>
                </div>
                <div class="table-responsive shopping-summery">
                    <table class="table table-wishlist">
                        <thead>
                            <tr class="main-heading">
                                <th class="custome-checkbox start pl-30"> 
                                </th>
                                <th scope="col" colspan="2">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Stock Status</th>
                                <th scope="col">Action</th>
                                <th scope="col" class="end">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                  
                            @if($wishlist->status == 200)
                                @foreach($resultData['result'] as $result)
                                    <tr class="pt-30">
                                        <td class="custome-checkbox pl-30">
                                        </td>
                                        <td class="image product-thumbnail pt-40"><img src="{{ $result['first_image']}}" style="height:116px;" alt="#" /></td>
                                        <td class="product-des product-name">
                                            <h6><a class="product-name mb-10" href="shop-product-right.html">{{ $result['name']}}</a></h6>
                                        </td>
                                        <td class="price" data-title="Price">
                                                @if($result['discount_amount']>0)
                                                    <span class="text-brand"> ₹ {{ $result['offer_price']}}<del>₹ {{ $result['sale_price'] }}</del></span>
                                                @else
                                                    <span class="text-brand"> ₹ {{ $result['sale_price'] }}</span>
                                                @endif
                                        </td>
                                        <td class="text-center detail-info" data-title="Stock">
                                            <span class="stock-status in-stock mb-0"> In Stock </span>
                                        </td>
                                        <td class="text-right product" data-title="Cart">
                                        <input type="hidden" class="product-id" value="{{$result['variant_productid']}}">
                                        <input type="hidden" class="product-qty" value="1">
                                            <button class="button button-add-to-cart"><i class="fi-rs-shopping-cart"></i>Add to cart</button>
                                        </td>
                                        <td class="action text-center" data-title="Remove">
                                            <a href="{{ url('user/delete-wishlist-product/'.$result['variant_productid']) }}" class="text-body"><i class="fi-rs-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <p style="margin-top:10px;text-align:center"><b>{{ $resultData['message'] }}</b></p>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection