@extends('layouts.app')

@section('title', __('User Login'))

@section('content')

<main class="main pages">
    <div class="page-content pt-150 pb-150">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
                    <div class="row">
                        <div class="col-lg-6 pr-30 d-none d-lg-block">
                            <img class="border-radius-15" src="assets/imgs/page/login-1.png" alt="" />
                        </div>
                        <div class="col-lg-6 col-md-8">
                            <div class="login_wrap widget-taber-content background-white">
                                <div class="padding_eight_all bg-white">
                                    <div class="heading_s1">
                                        <h1 class="mb-5">Login</h1>
                                        <p class="mb-30">Don't have an account? <a href="{{ url('user-register') }}">Create here</a></p>
                                    </div>

                                    <!-- Display error messages -->
                                    @if(session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    <!-- Existing password login form -->
                                    <form action="{{ route('user.login') }}" id="passwordLoginForm" method="post">
                                        @csrf
                                        <p class="invalid-feedback" role="alert">
                                            <strong id="Message"></strong>
                                        </p>
                                        @error('error')
                                            <p class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </p>
                                        @enderror
                                        @error('email')
                                            <p class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </p>
                                        @enderror
                                        @error('password')
                                            <p class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </p>
                                        @enderror
                                        <div class="form-group">
                                            <input class="input" type="text" placeholder="Enter email" name="email" id="email" value="{{ old('email') }}" required autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <input class="input" name="password" type="password" id="password" placeholder="Enter password" required autocomplete="off">
                                        </div>
                                        <div class="login_footer form-group mb-50">
                                            <div class="chek-form">
                                                <div class="custome-checkbox">
                                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="remember"><span>Remember me</span></label>
                                                </div>
                                            </div>                                            
                                            <a href="{{route('forgot-password-page')}}" class="text-muted" id="forgot-password">
                                                <i class="fas fa-question-circle"></i> Forgot Password?
                                            </a>
                                        </div>
                                        <div class="form-group">
                                                <button type="submit" class="btn btn-heading btn-block hover-up" name="login">Log in</button>
                                                <!-- Add the magic link button here -->
                                                <button type="button" class="btn btn-heading btn-block hover-up" onclick="generateMagicLink()" id="magicLinkButton">
                                                Login with Magic Link
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
