@extends('layouts/app')

@section('title',__(' User Register'))

@section('content')

<main class="main pages">
        <div class="page-content pt-150 pb-150">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
                        <div class="row">
                            <div class="col-lg-6 col-md-8">
                                <div class="login_wrap widget-taber-content background-white">
                                    <div class="padding_eight_all bg-white">
                                        <div class="heading_s1">
                                            <h1 class="mb-5">Create an Account</h1>
                                            <p class="mb-30">Already have an account? <a href="{{ url('user-login') }}">Login</a></p>
                                        </div>
                                        <form id="formsubmit" action="{{ route('user.registration') }}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <input type="text" name="name" placeholder="Enter your name" value="{{ old('name') }}" required autocomplete="off" />
                                            </div>
                                            <div class="form-group">
                                                <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required autocomplete="off"/>
                                            </div>
                                            <div class="form-group">
                                                <input type="tel" placeholder="Enter your mobile" onkeypress="return /[0-9 ]/i.test(event.key)"  maxLength="10" name="mobile" value="{{ old('mobile') }}" required autocomplete="off"/>
                                            </div>
                                            <div class="form-group">
                                                <input name="password" type="password" placeholder="Enter your password" required autocomplete="off" />
                                            </div>
                                            <div class="form-group">
                                                <input name="password_confirmation" type="password" placeholder="Enter your confirm password" required autocomplete="off" />
                                            </div>
                                            <div class="login_footer form-group">
                                                <div class="chek-form">
                                                    <input type="text" required="" name="security_code" placeholder="Security code *" id="inputVal" onkeypress="return /[0-9]/i.test(event.key)" autocomplete="off"/>
                                                </div>
                                                <span class="security-code">
                                                    <b class="text-new" id="1"></b>
                                                    <b class="text-hot" id="2"></b>
                                                    <b class="text-sale" id="3"></b>
                                                    <b class="text-best" id="4"></b>
                                                </span>
                                            </div>
                                            <div class="login_footer form-group mb-50">
                                                <div class="chek-form">
                                                    <div class="custome-checkbox">
                                                        <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox12" value="" required/>
                                                        <label class="form-check-label" for="exampleCheckbox12"><span>I agree to terms &amp; Policy.</span></label>
                                                    </div>
                                                </div>
                                                <a href="page-privacy-policy.html"><i class="fi-rs-book-alt mr-5 text-muted"></i>Lean more</a>
                                            </div>
                                            <div class="form-group mb-30">
                                                <button type="submit" class="btn btn-fill-out btn-block hover-up font-weight-bold" id="formsubmitSubmit" style="display: flex;align-items: center;">Submit &amp; Register  &nbsp;&nbsp;&nbsp; <pre class="spinner-border spinner-border-sm"  style="color:white;font-size: 100%;position:relative;top:21%;right:7%;display:none" id="registerLoader"></pre></button>
                                            </div>
                                            <p class="font-xs text-muted"><strong>Note:</strong>Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our privacy policy</p>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 pr-30 d-none d-lg-block">
                                <div class="card-login mt-115">
                                    <a href="{{ route('auth.google') }}" class="social-login google-login">
                                        <img src="assets/imgs/theme/icons/logo-google.svg" alt="" />
                                        <span>Continue with Google</span>
                                    </a>
                                    <a href="" class="social-login apple-login">
                                        <img src="assets/imgs/theme/icons/logo-apple.svg" alt="" />
                                        <span>Continue with Apple</span>
                                    </a>
                                    <a href="{{ route('auth.github') }}" class="social-login github-login">
                                        <img src="{{ asset('assets/imgs/theme/logo-github.svg') }}" alt="" />
                                        <span>Continue with GitHub</span>
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</main>

<script src="{{ asset('admin-assets/js/app.min.js')}}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">

    <script>

        $("form#formsubmit").submit(function(e) {

            e.preventDefault();

            var formId = $(this).attr('id');
            var formAction = $(this).attr('action');
            var enteredCode = $('#inputVal').val();
            var generatedCode = $('#inputVal').attr('data-generated-code');

        if (enteredCode === generatedCode) {

            $.ajax({
                url: formAction,
                data: new FormData(this),
                async: false,
                dataType: 'json',
                type: 'post',
                beforeSend: function() {
                    $('#registerLoader').css('display', 'inline-block');
                    $('#' + formId + 'Submit').prop('disabled', true);
                },
                error: function(xhr, textStatus) {
                    if (xhr && xhr.responseJSON.msg) {
                        swal({
                            title: "Error!",
                            icon: "error",
                            text: xhr.responseJSON.msg,
                            button: "OK",
                            closeOnClickOutside: false
                        });
                    } else {
                        swal({
                            title: "Error!",
                            icon: "error",
                            text: xhr.statusText,
                            button: "OK",
                            closeOnClickOutside: false
                        });
                    }

                    $('#registerLoader').css('display', 'none');
                    $('#' + formId + 'Submit').prop('disabled', false);
                },

                success: function(data) {
                $('#registerLoader').css('display', 'none');
                $('#' + formId + 'Submit').prop('disabled', false);

                if(data.error == false){
                    $('#formsubmit')[0].reset();
                    swal.fire({
                        title: 'Success !',
                        icon: "success",
                        html: data.msg,
                        button: "OK",
                        closeOnClickOutside: false
                    });
                } else {
                    if(data.error == true){
                        toastr.error(data.msg);
                    }
                }
            },

                cache: false,
                contentType: false,
                processData: false,
                timeout: 5000
            });
        } else {

            toastr.error('Security codes do not match. Please try again.');
    }

        });
    </script>

    <script>
        function generattCode(){
            var rand = Math.floor(Math.random() * 10000);
            code = rand.toString()
            var singleNum = code.split('');

            var textnew = document.getElementById('1').innerHTML = singleNum[0];
            var texthot = document.getElementById('2').innerHTML = singleNum[1];
            var textsale = document.getElementById('3').innerHTML = singleNum[2];
            var textbest = document.getElementById('4').innerHTML = singleNum[3];

            document.getElementById('inputVal').setAttribute('data-generated-code', code);

            // var inputVal = document.getElementById('inputVal').value;
// alert(inputVal);
            // if(inputVal !=)
        }

        generattCode();


    </script>



@endsection
