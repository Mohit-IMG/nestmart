function showMsg(type, msg) {
    if (type == 'error') {
        $('.toast-body').html('<i class="fa fa-times-circle red"></i> ' + msg);
    } else if (type == 'success') {
        $('.toast-body').html('<i class="fa fa-check-circle green"></i> ' + msg);
    } else {
        $('.toast-body').html('<i class="fa fa-exclamation-circle warning"></i> ' + msg);
    }

    $(".toast").toast({ delay: 3000 });
    $('.toast').toast('show');
}

// for single add to cart

$(".addtocart").click(function(e) {

    e.preventDefault();

var product_id  =  $('#product_id').val();
var product_qty  = $('#product_qty').val();

$.ajax({
    method : "POST",
    url: baseUrl + '/add-to-cart',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: {
        'product_id': product_id,
        'product_qty': product_qty,
    },

        success: function(data) {
            getTotalCartProduct();
            toastr.success(data.message);
            // button.find('.loader').css('display', 'none');
            // button.prop('disabled', false);

           

            // if (button.data('type') == 'buynow') {

            //     location.href = baseUrl + "/cart";
            // }
        },
        cache: false,
        timeout: 5000
    });

});

// for multiple  add to cart

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


$(document).ready(productWishlist);

function productWishlist() {
    $('.wishlist').click(function() {

        var product_id  =  $('#product_id').val();
        if (!userLogin) {
            location.href = baseUrl + '/user-login';
            return false;
        }

        var isWishlistActive = $(this).hasClass('active');

        // Show a confirmation dialog before adding/removing from wishlist
        var confirmationMessage = isWishlistActive
            ? 'Are you sure you want to remove this product from your wishlist?'
            : 'Are you sure you want to add this product to your wishlist?';

        var confirmation = confirm(confirmationMessage);
        if (!confirmation) {
            return false; // Cancel the process if the user clicks "Cancel"
        }

        if (isWishlistActive) {
            $(this).removeClass('active');
            toastr.success('Product successfully removed from wishlist.');
        } else {
            $(this).addClass('active');
            toastr.success('Product Wishlisted successfully.');
        }

        $.ajax({
            url: baseUrl + '/wishlist-product',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { "product_id": product_id },
            dataType: 'json',
            type: 'post',
            beforeSend: function(xhr) {
               console.log("before send");
            },
            error: function(xhr, textStatus) {
                // Error handling code
                if (xhr && xhr.responseJSON.message) {
                    toastr.error(xhr.status + ': ' + xhr.responseJSON.message);
                } else {
                    toastr.error(xhr.status + ': ' + xhr.statusText);
                }
            },
            success: function(data) {
                getTotalCartProduct();
                if (!data.login) {
                    console.log(data);
                }
                
            },
            complete: function() {
                location.href = baseUrl + '/login';
            },
            cache: false,
            timeout: 5000
        });
    });
}


getTotalCartProduct()

function getTotalCartProduct() {

$.ajax({
    url: baseUrl + '/get-total-cart',
    dataType: 'json',
    type: 'get',
    error: function(xhr, textStatus) {
    }
    ,
    success: function(data) {

        $('#cart-count').html(data.total_count);

        $('#wishlist-count').html(data.total_wishlist);

    },
    cache: false,
    timeout: 5000
});


$(document).ready(function () {
    $('#couponForm').submit(function (e) {
        e.preventDefault();

        // Get coupon code and total amount from form
        const enteredCode = $('#couponCode').val();
        const totalAmount = $('#totalAmount').val(); // Assuming you have an element with id 'totalAmount'

        // AJAX request to apply coupon
        $.ajax({
            method: 'POST',
            url: baseUrl + '/apply-coupon',
            dataType: 'json',
            data: {
                'couponCode': enteredCode,
                'totalAmount': totalAmount,
                '_token': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (data) {
                if (data && data.message) {
                    
                    // Coupon applied successfully
                    if (data.discountedAmount) {
                        // Update rendered data 
                        $('#finalAmount').html(data.totalAmt);
                        $('#discountGiven').html(data.discountedAmount);
                        
                        // Display coupon applied message
                        $('.coupon-applied-msg').removeClass('d-none').fadeIn();

                        // Disable coupon input immediately
                        $('#couponCode').prop('disabled', true);
                        $('button[type="submit"]').prop('disabled', true);

                        // Hide coupon applied message after a delay
                        setTimeout(function () {
                            $('.coupon-applied-msg').fadeOut('slow', function () {
                                $(this).addClass('d-none');
                            });
                        }, 30000);
                    }
                } else {
                    // Invalid coupon code
                    toastr.error('Invalid coupon code. Please try again.');
                    if(data.message === 'expired' && data.message === 'used' ){
                        toastr.error(data.message);

                    }
                }
            },
            error: function (xhr, textStatus) {
                // Handle AJAX errors
                if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error('An error occurred while processing the request.');
                }
            },
            complete: function () {
                // Any cleanup or finalization code
            },
            cache: false,
            timeout: 5000
        });
    });
});


$(document).ready(getCity);


function getCity() {

    $('.statehtml').change(function() {

        $.ajax({
            url: baseUrl + '/get-city?state_id=' + $(this).val(),
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

                $('.cityHtml').html(data.html);
            },
            cache: false,
            timeout: 5000
        });
    });

}

}


function generateMagicLink() {
   
    var email = $('#email').val();
    var magicLinkButton = $('#magicLinkButton');

    if (!email) {
        toastr.error("Please enter your email");
        return;
    }

    // Disable the magic link button during the AJAX request
    magicLinkButton.prop('disabled', true);

    // Show the loader on the magic link button
    magicLinkButton.find('.spinner-border').css('display', 'inline-block');

    // Append the CSRF token to the FormData object
    var formData = new FormData();
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('email', email);

    $.ajax({
        type: 'POST',
        url: '/generate-and-send-magic-link',
        data: formData,
        processData: false,
        contentType: false,

        beforeSend: function () {
            $('#password').hide();
            $('#registerLoader').css('display', 'inline-block');
        },

        success: function (data) {
            toastr.success(data.message);
            if (data.user) {
                $("#name").val(data.user.name);
            }
        },

        error: function (xhr, status, error) {
            toastr.error(xhr.responseJSON.error || xhr.responseText || 'An error occurred');
        },

        complete: function () {
            magicLinkButton.prop('disabled', false);
            magicLinkButton.find('.spinner-border').css('display', 'none');
            $('#password').show();
        }
    });
}


