<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta content="width=device-width, initial-scale=1" name="viewport" />
		<title>@yield('title') | Nest Mart & Grocery</title>
		<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}" />
		<link rel="icon" href="{{ asset('admin-assets/images/favicon.svg') }}" type="image/x-icon">
		<link href="{{ asset('admin-assets/css/app.min.css') }}" rel="stylesheet">
		<link href="{{ asset('admin-assets/css/style.css')}}" rel="stylesheet" />
		<link href="{{ asset('admin-assets/css/fSelect.css')}}" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" >
		
		@stack('custom_css')
	
	</head>

	<body class="light">
		 <!-- Page Loader -->
		 <div class="page-loader-wrapper">
			<div class="loader">
				<div class="m-t-30">
					<img class="loading-img-spin" src="{{ asset('admin-assets/images/nest.png') }}" alt="admin">
				</div>
				<p>Please wait...</p>
			</div>
		</div>
		
		<div id="preloader" style="display: none;">
			 <div class="loader_spinner_inside"></div>
			 <span class="loader_spinner_text">Please Wait...</span>
		 </div>
	  
		<!-- #END# Page Loader -->
		<div class="overlay"></div>
		<nav class="navbar">
			<div class="container-fluid">
				<div class="navbar-header">
					<a href="#" onClick="return false;" class="navbar-toggle collapsed" data-bs-toggle="collapse"
						data-target="#navbar-collapse" aria-expanded="false"></a>
					<a href="#" onClick="return false;" class="bars"></a>
					<a class="navbar-brand" href="{{url('admin/dashboard')}}">
						<img  style="height: 43px;"src="{{ asset('admin-assets/images/nest.png') }}" alt="" />
						
						
					</a>
				</div>
				<div class="collapse navbar-collapse" id="navbar-collapse">
					<ul class="pull-left">
						<li>
							<a href="#" onClick="return false;" class="sidemenu-collapse">
								<i data-feather="menu"></i>
							</a>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<!-- #END# Notifications-->
						<li class="dropdown user_profile" style="margin-right: 30px;">
							<div class="dropdown-toggle" data-bs-toggle="dropdown">
								<img src="@if(\Auth::user()->profileimage != null) {{asset('uploads/user/'.\Auth::user()->profileimage)}} @else {{ asset('admin-assets/images/emptys.png') }} @endif" alt="" style="width:50px">
							</div>
							<ul class="dropdown-menu pullDown">
								<li class="body">
									<ul class="user_dw_menu">
										
										<li>
										<a href="{{ url('change-password')}}">
										<i class="fa fa-key"></i> 
										Change Password
										</a>
											
											<a href="#" onclick="event.preventDefault();
													document.getElementById('logout-form').submit();">
											<i class="fas fa-power-off"></i> Logout
											</a>
											<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
												@csrf
											</form>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<!-- #END# Tasks -->
					</ul>
				</div>
			</div>
		</nav>
		<!-- #Top Bar -->
		<div>
			<!-- Left Sidebar -->
			<aside id="leftsidebar" class="sidebar" style="background-color:#216e23;">
				<!-- Menu -->
				<div class="menu">
					@php echo \App\Helpers\commonHelper::getSidebarMenu(); @endphp
				</div>
				<!-- #Menu -->
			</aside>
			<!-- #END# Left Sidebar -->
		</div>
		
		@yield('content')
		 
		<script src="{{ asset('admin-assets/js/app.min.js')}}"></script>
		<script src="{{ asset('admin-assets/js/table.min.js') }}"></script>
		<script src="{{ asset('admin-assets/js/jquery-datatable.js') }}"></script>
		<script src="{{ asset('admin-assets/js/index.js') }}"></script> 
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>  
		<script src="{{ asset('admin-assets/js/fSelect.js') }}"></script> 
		<script src="{{ asset('admin-assets/js/common.js') }}"></script> 
		<script>
		$(document).ready(function(){
			@if(Session::has('5fernsadminerror'))
				sweetAlertMsg('error',"{{ Session::get('5fernsadminerror') }}");
			@elseif(Session::has('5fernsadminsuccess'))
				sweetAlertMsg('success',"{{ Session::get('5fernsadminsuccess') }}");
			@endif
		 });

		 var baseUrl = "{{ url('/') }}";
		   
		</script>
		
		@stack('custom_js')
	</body>

</html>