@extends('layouts/app')

@section('title', __('Home'))

@section('content')

<main class="main">
    <div class="container mb-30">
        <div class="row flex-row-reverse">
            <div class="col-lg-4-5">
                <div class="shop-product-fillter">
                    <div class="totall-product">
                        <p>We found <strong class="text-brand">29</strong> items for you!</p>
                    </div>
                    <div class="sort-by-product-area">
                        <div class="sort-by-cover">
                            <div class="sort-by-product-wrap">
                                <div class="sort-by">
                                    <span><i class="fi-rs-apps-sort"></i>Sort by:</span>
                                </div>
                                <div class="sort-by-dropdown-wrap">
                                    <span> Featured <i class="fi-rs-angle-small-down"></i></span>
                                </div>
                            </div>
                            <div class="sort-by-dropdown">
                                <ul>
                                    <li><a class="active" href="#">Featured</a></li>
                                    <li><a href="#">Price: Low to High</a></li>
                                    <li><a href="#">Price: High to Low</a></li>
                                    <li><a href="#">Release Date</a></li>
                                    <li><a href="#">Avg. Rating</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row product-grid" id="productGridContainer">
                    <!-- Ajax Content Here -->
                </div>
            </div>
            <div class="col-lg-1-5 primary-sidebar sticky-sidebar">
                <div class="sidebar-widget price_range range mb-30">
                    <h5 class="section-title style-1 mb-30">Filter by price</h5>
                    <div class="">
                        <div id="slider-range" class=""></div>
                        <p class="range-value">
                            Range:
                            <input type="text" id="amount" readonly>
                        </p>
                    </div>
                    <input type="hidden" id="min_price" value="0" />
                    <input type="hidden" id="max_price" value="500000" />

                    @if(!empty($brands))
                        <div class="list-group">
                            <div class="list-group-item mb-10 mt-10">
                                <label class="fw-900">Brands</label>
                                <div class="custome-checkbox">
                                    @foreach($brands as $key=>$brand)
                                        <input class="brand_id form-check-input" type="checkbox" name="checkbox" onchange="setSortOrder()" id="brandCheckbox{{$brand['id']}}" value="{{$brand['id']}}">
                                        <label class="form-check-label" for="brandCheckbox{{$brand['id']}}"><span>{{$brand->brand_name}}</span></label>
                                        <br>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Loader Element -->
    <div id="loader" style="display: none; text-align: center; width: 100%; position: relative;">
        <i style="color: black; font-size: 25px;" class="fa fa-refresh fa-spin fa-3x fa-fw"></i>
        <p>Please wait</p>
    </div>
</main>

@endsection

@push('custom_js')
<script src="https://cdn.jsdelivr.net/lodash/4.17.21/lodash.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    function setSortOrder() {
        offset = 0;
        notEmptyPost = true;
        newData = true;
        $('#productScroll').html(loading_set);
        getProductData();
    }

    var notscrolly = true;
    var notEmptyPost = true;
    var newData = true;
    var offset = 0;

    getProductData();

    $(document).ready(function() {
        max = "500000";
        $("#slider-range").slider({
            range: true,
            min: 0,
            max: 500000, // Assuming max is a number
            values: [0, 500000],
            slide: function(event, ui) {
                $('#min_price').val(ui.values[0]);
                $('#max_price').val(ui.values[1]);
                $("#amount").val("₹ " + ui.values[0] + " - ₹ " + ui.values[1]);
            },
            change: function(event, ui) {
                setSortOrder(); // Fetch products when the slider values change
            }
        });

        // Assuming you have a function named setSortOrder
        setSortOrder();

        // Use lodash debounce to delay scroll event
        var debouncedScroll = _.debounce(function() {
            var divheight = $('#productGridContainer').outerHeight();
            if (notscrolly == true && notEmptyPost == true && $(window).scrollTop() + $(window).height() / 2 >= divheight) {
                getProductData();
            }
        }, 200); // Adjust the delay as needed

        $(window).scroll(debouncedScroll);
    });

    function getProductData() {
        var minPrice = $('#min_price').val();
        var maxPrice = $('#max_price').val();
        var brandId = $('.brand_id:checked').map(function() {
            return this.value;
        }).get().join(',');

        // Log the selected brands and filter range values
        console.log('Selected Brands: ' + brandId + '\nRange Value 1: ' + minPrice + '\nRange Value 2: ' + maxPrice);

        $.ajax({
            url: "{{ route('product-listing', $categoryslug) }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            type: 'post',
            data: {
                "min_price": minPrice,
                "max_price": maxPrice,
                "brand_id": brandId,
                "categoryslug": "{{$categoryslug}}",
            },
            beforeSend: function() {
                notscrolly = false;
                // Show loader here
                $('#loader').show();
            },
            success: function(response) {
                if (newData) {
                    $('#productGridContainer').html(response.html);
                    newData = false;
                } else {
                    $('#productGridContainer').append(response.html);
                }

                // Assuming you have a function named productWishlist
                productWishlist();

                $('#totalProducts').html(response.total);

                notscrolly = true;
                // Hide loader here
                $('#loader').hide();
            },
            error: function(error) {
                console.error("Error fetching product data:", error);
                // Handle error here
                // Hide loader here
                $('#loader').hide();
            }
        });
    }

    function applyFilter() {
        getProductData(); // You can call the getProductData function on button click
    }
</script>
@endpush
