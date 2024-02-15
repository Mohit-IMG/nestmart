@php
    $offset = $offset ?? 0; 
@endphp
@if(!empty($data))
	@foreach($data as $value)


		<div class="col-md-3 col-12 col-sm-6">
			<div class="product-cart-wrap mb-30">
				<div class="product-img-action-wrap">
					<div class="product-img product-img-zoom">
						<a href="{{url('product-detail/'.$value['slug'])}}">
							<img src="{{ $value['first_image'] }}" class="default-img img-fluid first" alt="First Image">
							@if($value['second_image'])
							<img src="{{ $value['second_image'] }}" class="hover-img img-fluid second" alt="">
							@endif
						</a>
					</div>
					<div class="product-action-1">
						<a aria-label="Add To Wishlist" class="action-btn wishlist " href="javascript:void()" data-productid=""><i class="fa fa-heart-o" aria-hidden="true"></i></a>
						<a aria-label="Quick view" class="action-btn" href="{{url('product-detail/'.$value['slug'])}}"><i class="fa fa-eye" aria-hidden="true"></i></a>
					</div>
					
				</div>
				<div class="product-content-wrap">
				
					<h2><a href="{{url('product-detail/'.$value['slug'])}}">{{ $value['name'] }}</a></h2>
					<div>
						<span class="font-small text-muted">By <a href="#"></a></span>
					</div>
					<div class="product-card-bottom">
						<div class="product-price">
							
							<span> ₹ {{ $value['sale_price'] }}</p>
							
							
						</div>
						<div class="add-cart product_data">
							<input type="hidden" class="product-id" value="{{$value['variant_productid']}}">
							<input type="hidden" class="product-qty" value="1">
							<button type="submit" class="button button-add-to-cart" id="addtocart"><i class="fi-rs-shopping-cart"></i>Add</button>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		
	@endforeach

@elseif(empty($data) && $offset==0)

	<img src="{{ asset('images/product-not-available.jpg') }}" width="100%" />
	
@endif
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

<script>
$(document).ready(function() {
    $('.button-add-to-cart').click(function(e) {
        e.preventDefault();
        var productDiv = $(this).closest('.product');
        var productId = productDiv.find('.product-id').val();
        var productQty = productDiv.find('.product-qty').val();
        
        // AJAX call to add product to cart
        $.ajax({
            method: "POST",
            url: baseUrl + '/add-to-cart', // Adjust the URL to your endpoint
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'product_id': productId,
                'product_qty': productQty,
                // Add other necessary product details here
            },
            success: function(data) {
                getTotalCartProduct();
                toastr.success(data.message);
            },
            error: function(xhr, status, error) {
                // Handle errors if any
                console.error(xhr.responseText);
            },
            cache: false,
            timeout: 5000
        });
    });
});


    function getTotalCartProduct() {
        $.ajax({
            url: baseUrl + '/get-total-cart',
            dataType: 'json',
            type: 'get',
            error: function(xhr, textStatus) {
                if (xhr && xhr.responseJSON.message) {
                    showMsg('error', xhr.status + ': ' + xhr.responseJSON.message);
                } else {
                    showMsg('error', xhr.status + ': ' + xhr.statusText);
                }
            },
            success: function(data) {
                $('#total_cart_product').html(data.total_count);
                $('#total_wishlist').html(data.total_wishlist);
            },
            cache: false,
            timeout: 5000
        });
    }
});
</script>
