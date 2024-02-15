<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password Page</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">


    <!--Stylesheet-->
    <style media="screen">
        *,
        *:before,
        *:after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #080710;
        }

        .background {
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
        }

        .background .shape {
            height: 200px;
            width: 200px;
            position: absolute;
            border-radius: 50%;
        }

        .shape:first-child {
            background: linear-gradient(#1845ad,
                    #23a2f6);
            left: -80px;
            top: -80px;
        }

        .shape:last-child {
            background: linear-gradient(to right,
                    #ff512f,
                    #f09819);
            right: -30px;
            bottom: -80px;
        }

        form {
            height: 520px;
            width: 400px;
            background-color: rgba(255, 255, 255, 0.13);
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
            padding: 50px 35px;
        }

        form * {
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }

        form h3 {
            font-size: 32px;
            font-weight: 500;
            line-height: 42px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
        }

        input {
            display: block;
            height: 50px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.07);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
        }

        ::placeholder {
            color: #e5e5e5;
        }

        button {
            margin-top: 50px;
            width: 100%;
            background-color: #ffffff;
            color: #080710;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }

        .social {
            margin-top: 30px;
            display: flex;
        }

        .social div {
            background: red;
            width: 150px;
            border-radius: 3px;
            padding: 5px 10px 10px 5px;
            background-color: rgba(255, 255, 255, 0.27);
            color: #eaf0fb;
            text-align: center;
        }

        .social div:hover {
            background-color: rgba(255, 255, 255, 0.47);
        }

        .social .fb {
            margin-left: 25px;
        }

        .social i {
            margin-right: 4px;
        }
    </style>
    <style>
        #toast-container {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
        }

        .custom-toast-error {
            background-color: red;
            color: white;
        }

        button {
            width: 48%;
            /* Adjust the button width as needed */
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
</head>

<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form action="{{ route('reset-password') }}" method="POST" id="reset-form">
        <h3 align="center">Back to <span><a href="{{ route('user-login') }}">Login</a></span></h3>
        @csrf
        <p class="invalid-feedback" role="alert">
            <strong id="Message"></strong>
        </p>
        @foreach (['email', 'otp', 'new_password'] as $field)
            @error($field)
                <p class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </p>
            @enderror
        @endforeach

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <label for="otp">OTP</label>
        <input type="text" name="otp" id="otp" required>
        <label for="new_password">New Password</label>
        <input type="password" name="new_password" id="new_password" required>
        <div id="countdown-timer"></div>
        <button type="button" id="send-otp" class="sendOTP">Send OTP</button>
        <button type="button" id="resend-otp" class=" resendsendOTP d-none">Resend OTP</button>
        <button type="submit">Reset Password</button>

    </form>

    <script>
        $(document).ready(function() {
            @if (Session::has('5fernsadminerror'))
                $('#Message').html("{{ Session::get('5fernsadminerror') }}");
            @elseif (Session::has('5fernsadminsuccess'))
                $('#Message').html("{{ Session::get('5fernsadminsuccess') }}");
            @endif
        });
    </script>

    <script>
        const baseUrl = "{{ url('/') }}";
        $(document).ready(function() {
            var emailSent = false;

            $('.resendsendOTP').hide();

            $('#send-otp').on('click', function() {
                if (emailSent) {
                    alert("OTP already sent. Please check your email.");
                } else {
                    $('.resendsendOTP').show();
                    sendOTP();
                }
            });

            $('#resend-otp').on('click', function() {
                $('.sendOTP').hide();
                $('.resendsendOTP').hide();
                resendOTP();
            });

            $('#verify-otp').on('click', function() {
                verifyAndReset();
                $('.sendOTP').hide();
                $('.resendsendOTP').hide();
            });


            function resendOTP() {

                var countdownTimer = $('#countdown-timer');
                var email = $('#email').val();

                $.ajax({
                    url: "{{ route('resend-otp') }}",
                    type: 'POST',
                    data: {
                        email: email
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('.sendOTP').hide();
                        $('.resendsendOTP').hide();
                        countdownTimer.show();
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        emailSent = true;
                        startTimer(30, countdownTimer);
                        $('.resendsendOTP').hide();

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        toastr.error("An error occurred while resending OTP.");
                        $('.sendOTP').show();
                        countdownTimer.hide();
                    }
                });
            }

            function verifyAndReset() {
                var email = $('#email').val();
                var otp = $('#otp').val();
                var newPassword = $('#new_password').val();

                $.ajax({
                    url: "{{ route('reset-password') }}",
                    type: 'POST',
                    data: {
                        email: email,
                        otp: otp,
                        new_password: newPassword,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('.sendOTP').hide();
                        $('.resendsendOTP').hide();
                        countdownTimer.show();
                    },
                    success: function(response) {
                        toastr.success(response.success);
                        emailSent = true;
                        startTimer(30, countdownTimer);
                        $('.resendsendOTP').show();
                        toastr.success(response.success === 'updated');
                        if (response.success) {
                            window.location.href = baseUrl + 'login';
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        toastr.error("An error occurred while verifying OTP and resetting password.");
                    }
                });
            }

            function sendOTP() {
                var email = $('#email').val();
                var countdownTimer = $('#countdown-timer');

                $.ajax({
                    url: "{{ route('send-otp') }}",
                    type: 'POST',
                    data: {
                        email: email
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('.sendOTP').hide();
                        $('.resendsendOTP').hide();
                        countdownTimer.show();
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        emailSent = true;
                        startTimer(30, countdownTimer);
                        $('.resendsendOTP').hide();

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        toastr.error("An error occurred while sending OTP.");
                        $('.sendOTP').show();
                        $('.resendsendOTP').hide();
                        countdownTimer.hide();
                    }
                });
            }

            function startTimer(duration, timerElement) {
                var timer = duration;
                var interval = setInterval(function() {
                    $('#send-otp').prop('disabled', true);
                    timerElement.text('Resend OTP in ' + timer + 's');
                    if (--timer < 0) {
                        clearInterval(interval);
                        $('#send-otp').prop('disabled', false);
                        $('#resend-otp').prop('disabled', false);
                        $('#send-otp').text('Send OTP');
                        timerElement.text('');
                        $('.resendsendOTP').show();
                    }
                }, 1000);
            }

        });
    </script>
</body>

</html>
