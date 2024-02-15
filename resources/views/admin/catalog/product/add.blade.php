@extends('layouts.master')

@section('title')
	@if(!empty($result))
		Update
	@else
		Add
	@endif
	Product
@endsection

@push('custom_css')
 	<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
 	<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
	<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="{{ asset('admin-assets/css/combotreestyle.css') }}">

	<style>
		.fs-wrap {
			display: inline-block;
			cursor: pointer;
			line-height: 2;
			width: 100%;
		}
		.fs-dropdown {
			position: absolute;
			background-color: #fff;
			border: 1px solid #ddd;
			width: 100%;
			margin-top: 5px;
			z-index: 1000;
		}

	</style>

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
                            <a class="btn-primary" href="{{ url('admin/catalog/product/list')}}">
                                <i class="fa fa-list"></i> Product List 
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
						<h2><i class="fa fa-th"></i> @if(!empty($result)) Update @else Add @endif Product</h2>
					</div>
					<div class="body">
						<form id="form" action="{{ route('admin.product.addproduct') }}" method="post" enctype="multipart/form-data"  autocomplete="off">
						@csrf
						<input  value="@if(!empty($result)){{ $result['id'] }}@else{{ '0' }}@endif" type="hidden" required class="form-control" name="id" />
						
						<div class="row clearfix">
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Category <label class="text-danger">*</label></label>
										<!-- <select class="form-control" name="category_id" required >
											<option  selected value="">--Select--</option>
											@if(!empty($category))
												@foreach($category as $raw)
													<option value="{{ $raw['id'] }}" @if(!empty($result) && $raw['id']==$result['category_id']) {{ 'selected' }} @endif>{{ \App\Helpers\commonHelper::getParentName($raw['id']) }}</option>
												@endforeach
											@endif
										</select> -->
										<input type="text" required id="justAnotherInputBox" name="" placeholder="Select Category" autocomplete="off"/>
										<input type="hidden" name="category_id" value="@if(!empty($result)){{$result['category_id'] }}@endif" id="category_input_box_hidden" required />

									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Select Variants</label>
										<div class="row clearfix">
										@if($variants)
											@php $variantsData=[]; @endphp
										
											@if($result)
												@php $variantsData=explode(',',$result->variant_id);  @endphp
											@endif			
										<select class="form-control select" name="variant_id[]" required multiple>
											<option  selected value="">--Select--</option>
											@if(!empty($variants))
												@foreach($variants as $vari)
													<option value="{{ $vari->id }}" @if(in_array($vari->id,$variantsData)) {{ 'selected' }} @endif>{{ ucfirst($vari->name) }}</option>
												@endforeach
											@endif
										</select>
										@endif
										</div>
									</div>
								</div>
							</div>
						</div>
						
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Product Title</label>
										<input  value="@if(!empty($result)){{ $result['name'] }}@endif" type="text" required class="form-control" placeholder="Enter Product Title" name="name" >
									</div>
								</div>
							</div>
						</div>
						
						<div class="row clearfix">
						<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Brand <label class="text-danger">*</label></label>
										<select class="form-control" name="brand_id" required >
											<option  selected value="">--Select--</option>
											@if(!empty($brands))
												@foreach($brands as $raw)
													<option value="{{ $raw['id'] }}" @if(!empty($result) && $raw['id']==$result['brand_id']) {{ 'selected' }} @endif>{{ ucfirst($raw['brand_name']) }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Tax Ratio (%) <label class="text-danger">*</label></label>
										<input  value="@if(!empty($result)){{ $result['tax_ratio'] }}@endif" onkeypress="return /[0-9 ]/i.test(event.key)" type="tel" required class="form-control" placeholder="Enter Tax Ratio (%)" name="tax_ratio" >
									</div>
								</div>
							</div>
						</div>
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Short Description <label class="text-danger">*</label></label>
										<textarea class="form-control" id="short_description" name="short_description" required  placeholder="Short Description">@if(!empty($result)){{ $result['short_description'] }}@endif</textarea>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Description <label class="text-danger">*</label></label>
										<textarea class="form-control" id="summernote" name="description" required placeholder="Description">@if(!empty($result)){{ $result['description'] }}@endif</textarea>
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
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

	<script>

		$('.select').fSelect({
			placeholder: 'Select Variant Attributes',
			numDisplayed: 3,
			overflowText: '{n} selected',
			noResultsText: 'No results found',
			searchText: 'Search',
			showSearch: true
		});

		$('#summernote').summernote({
			placeholder: 'Enter Description',
			tabsize: 2,
			height: 200,
		});

		var comboTree2;
		
		$.ajax({
			type: "get",
			dataType: "json",
			url: "{{ route('admin.product.addproduct') }}",
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
		
			beforeSend:function(){
				$('#preloader').css('display','block');
			},
			error:function(xhr,textStatus){
					
				if(xhr && xhr.responseJSON.message){
					sweetAlertMsg('error', xhr.status + ': ' + xhr.responseJSON.message);
				}else{
					sweetAlertMsg('error', xhr.status + ': ' + xhr.statusText);
				}
				$('#preloader').css('display','none');
			},
			success: function(data){
				$('#preloader').css('display','none');
				
				var SampleJSONData =data.category;

				jQuery(document).ready(function($) {

						comboTree2 = $('#justAnotherInputBox').comboTree({
							source : SampleJSONData,
							isMultiple: false,
							collapse: true,
							selected: [@if(!empty($result)){{ $result['category_id'] }}@endif]
						});

						comboTree2.onChange(function(){
							$('#category_input_box_hidden').val((comboTree2.getSelectedIds())[0]);
						});


				});
			}
		});

	</script>
	<script src="{{ asset('admin-assets/js/comboTreePlugin.js')}}"  type="text/javascript"></script>


@endpush

