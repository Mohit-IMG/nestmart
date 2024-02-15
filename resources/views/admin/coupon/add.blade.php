@extends('layouts.master')

@section('title')
	@if(!empty($result))
		Update
	@else
		Add
	@endif
	Coupon
@endsection
@push('custom_css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush
@section('content')

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i>  Go To</h2>
					</div>
					<div class="body">
						<div class="btn-group top-head-btn">
                            <a class="btn-primary" href="{{ url('admin/coupon/list')}}">
                                <i class="fa fa-list"></i> Coupon List 
							</a>
                        </div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> @if(!empty($result)) Update @else Add @endif Coupon</h2>
					</div>
					<div class="body">
						<form id="form" action="{{ route('admin.coupon.add') }}" method="post" enctype="multipart/form-data"  autocomplete="off">
						@csrf
						<input type="hidden" name="id" value="@if(!empty($result)){{$result['id']}}@else{{ 0 }}@endif"  required />

						<div class="row clearfix">
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Name <label class="text-danger">*</label></label>
										<input  value="@if(!empty($result)){{ $result['code'] }}@endif" type="text" required class="form-control" placeholder="Enter Name or Click generate" name="name" id="randomCoupon">
									</div>
								</div>
							</div>
						</div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-info" onclick="generateRandomCoupon()">Generate</button>
                        </div>
                    </div>
						<div class="row clearfix">
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Value <label class="text-danger">*</label></label>
										<input  value="@if(!empty($result)){{ $result['value'] }}@endif" type="number" required class="form-control" placeholder="0" name="value" >
									</div>
								</div>
							</div>
						</div>
						
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="inputName">Description</label>
                                        <textarea class="form-control" name="description" id="summernote" placeholder="Description">@if(!empty($result)){{ $result['description'] }}@endif</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
						
						<div class="col-lg-12 p-t-20 text-center">
							@if(empty($result)) 
								<button type="reset" class="btn btn-danger waves-effect">Reset</button>
							@endif
							<button style="background:#353c48;" type="submit" class="btn btn-primary waves-effect m-r-15" >@if(!empty($result)) Update @else Submit @endif</button> 
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@push('custom_js')
    {{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 300,
                // Add any other Summernote options here
            });
        });
    </script>

<script>
    function generateRandomCoupon() {
        $.ajax({
            type: "POST",
            url: "{{ route('admin.coupon.generate') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var randomCoupon = document.getElementById('randomCoupon');
                randomCoupon.value = response.randomCode;
            },
            error: function(xhr) {
                console.log('Error generating random coupon code:', xhr.responseText);
            }
        });
    }
</script>
@endpush





