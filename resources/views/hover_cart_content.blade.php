@if(count($userCartData) != 0)
    @php
        $subtotal = 0; // Initialize subtotal variable
    @endphp

    @foreach($userCartData as $item)
        <li class="cart-item">
        
        @php 
            $product = \App\Models\Variantproduct::with('proData')->where('id',$item['product_id'])->first();
            $discountPrice = $product['sale_price']*$product['discount_amount']/100;
            $productPrice = $product['sale_price'] - $discountPrice;

            $subtotal += $productPrice * $item['qty'];

            $image = explode(',',$product['images']);
 
        @endphp

        <?php
            $productName = $product['proData']['name']; // Assuming $productName is the variable containing the product name

            $words = str_word_count($productName, 1); // Splitting the name into an array of words
            $limitedWords = array_slice($words, 0, 2); // Limiting to the first 15 words

            $limitedName = implode(' ', $limitedWords); // Joining the limited words back into a string
            
            if (str_word_count($productName) > 2) {
                $limitedName .= '...'; // Adding ellipsis if the original name exceeded 15 words
            }
        ?>

            <div class="shopping-cart-img">
                <a href="{{url('product_detail/'.$product['slug'])}}">
                    <img alt="Nest" src="{{ asset('uploads/products/'.$image[0]) }}" />
                </a>
            </div>
            <div class="shopping-cart-title">
                <h4><a href="{{url('product_detail/'.$product['slug'])}}">{{ $limitedName }}</a></h4>
                <h4><span>{{ $item['qty'] }} × </span>₹{{ $productPrice  }}</h4>
            </div>
            <!-- ... Additional cart item details -->
        </li>

    @endforeach

        <div class="shopping-cart-footer">
            <div class="shopping-cart-total">
                <h4>Total <span>₹{{$subtotal}}</span></h4>
            </div>
            <div class="shopping-cart-button">
                <a href="{{ route('cart') }}" class="outline">View cart</a>
            </div>
        </div>
@else

  <h5 style="color:red;"><b>Cart is empty!</h5>
@endif
