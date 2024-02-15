<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel Login | Nest Mart & Grocery</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('admin-assets/images/nest.png') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/admin_login.css') }}">
</head>

<body style="background: #216e23;">
    <div class="workSpace">
   
        <div class="left">
            <img class="img-fluid" src="{{ asset('admin-assets/images/nest.png') }}" alt="">
          
        </div>
        <div class="right">
            <form action="{{ route('admin.login') }}" id="" method="post">
                @csrf
                <h1 style="color:#d4ba2a;">SignIn</h1>
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
                <input class="input" type="text" placeholder="Enter email" name="email" value="{{ old('email') }}"
                    required autocomplete="off">
                <input class="input" name="password" type="password" placeholder="Enter password" required
                    autocomplete="off">
                <div class="login-btn-box">
                    <button class="submit" style="background-color:#d4ba2a">Log In</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('admin-assets/js/app.min.js')}}"></script> 

    <script>
        $(document).ready(function(){
            @if(Session::has('5fernsadminerror'))
               
                $('#Message').html("{{ Session::get('5fernsadminerror') }}");
            @elseif(Session::has('5fernsadminsuccess'))
                $('#Message').html("{{ Session::get('5fernsadminsuccess') }}");
            @endif
            });

    </script>
</body>

</html>