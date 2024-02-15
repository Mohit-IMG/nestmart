@php $amt = round($orderData->net_amount) * 100; @endphp

<!-- Razorpay form -->
<form id="razorpayForm" action="{{ route('razorpay.payment.store') }}" method="POST">
    @csrf
    <input type="hidden" name="order_id" value="{{ $orderData->id }}">
    <!-- Add other form fields if necessary -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"
        data-key="{{ env('RAZORPAY_KEY') }}"
        data-amount="{{ $amt }}"
        data-buttontext="Pay {{ round($orderData->net_amount) }} INR"
        data-name="Nestmart"
        data-description="Rozerpay"
        data-image="https://i.ibb.co/PrrYkH1/logo.png"
        data-prefill.name="name"
        data-prefill.email="email"
        data-theme.color="#ff7529"
        data-display_currency="INR"
        data-currency="INR">
    </script>

    <!-- Add an ID to the Razorpay button -->
    <button type="button" id="razorpayButton" style="display: none;">Pay Now</button>
</form>

<!-- jQuery script -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Script to automatically trigger the Razorpay payment and hide the button -->
<script>
    $(document).ready(function () {
        // Check if Razorpay library is loaded
        if (typeof Razorpay !== 'undefined') {
            // Create a new instance of Razorpay
            var razorpayInstance = new Razorpay({
                key: '{{ env('RAZORPAY_KEY') }}',
                amount: {{ $amt }},
                currency: 'INR',
                name: 'Nestmart',
                description: 'Rozerpay',
                image: 'https://i.ibb.co/PrrYkH1/logo.png',
                prefill: {
                    name: 'name',
                    email: 'email',
                },
                theme: {
                    color: '#ff7529',
                },
                handler: function (response) {
                    // Handle the success event if needed
                    console.log('Payment success:', response);

                    // Save payment details to transactions table via Ajax
                    savePaymentDetails({
                    _token: "{{ csrf_token() }}",
                    order_id: '{{ $orderData->id }}',
                    razorpay_payment_id: response.razorpay_payment_id,
                    amount: {{ $amt }},
                    // Add other payment details as needed
                });

                    // Redirect to the checkout page after successful payment
                    window.location.href = "http://127.0.0.1:8000/user/checkout";
                },
                modal: {
                    ondismiss: function () {
                        // Handle the cancel event
                        console.log('Payment cancelled');
                        // Redirect back to the checkout page if cancelled
                        window.location.href = "http://127.0.0.1:8000/user/checkout";
                    },
                },
            });

            // Open the Razorpay payment popup as soon as the page loads
            razorpayInstance.open();

            // Hide the default Razorpay button
            $('.razorpay-payment-button').hide();
        } else {
            console.error('Razorpay library is not loaded.');
        }
    });

    // Function to save payment details via Ajax
    function savePaymentDetails(response) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: "{{ route('razorpay.callback') }}",
        data: {
            _token: response._token,
            order_id: response.order_id,
            razorpay_payment_id: response.razorpay_payment_id,
            amount:response.amount,
            // Add other payment details as needed
        },
        success: function (data) {
            console.log('Payment details saved:', data);
            alert('Payment details saved:', data);
        },
        error: function (xhr, textStatus) {
            console.error('Failed to save payment details:', xhr.statusText);
            alert('Failed to save payment details:', xhr.statusText);
        }
    });
}



</script>
