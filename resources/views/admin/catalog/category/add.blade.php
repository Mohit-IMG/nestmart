@extends('layouts.master')

@section('title')
	@if(!empty($result))
		Update
	@else
		Add
	@endif
	Category
@endsection


@push('custom_css')
	<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
	<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="{{ asset('admin-assets/css/combotreestyle.css') }}">
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>	

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
                            <a class="btn-primary" href="{{ url('admin/catalog/category/list')}}">
                                <i class="fa fa-list"></i> Category List 
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
						<h2><i class="fa fa-th"></i> @if(!empty($result)) Update @else Add @endif Category</h2>
					</div>
					<div class="body">
						<form id="form" action="{{ route('admin.category.addcategory') }}" method="post" enctype="multipart/form-data"  autocomplete="off">
						@csrf
						<div class="row clearfix">
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Category </label>
										<!-- <select class="form-control" name="parent_id">
											<option  selected value="">--Select--</option>
											@if(!empty($category))
												@foreach($category as $raw)
												<option value="{{ $raw['id'] }}" @if(!empty($result) && $raw['id']==$result['parent_id']) {{ 'selected' }} @endif>{{ \App\Helpers\commonHelper::getParentName($raw['id']) }}</option>
													@if(!empty($raw['Children']))
														@foreach($raw['Children'] as $child)
															<option value="{{ $child['id'] }}" @if(!empty($result) && $child['id']==$result['parent_id']) {{ 'selected' }} @endif>->{{ $child['name'] }}</option>
														@endforeach
													@endif
												@endforeach
											@endif
										</select> -->
										<input type="text" id="justAnotherInputBox" name="" placeholder="Select Category" autocomplete="off"/>
										<input type="hidden" name="parent_id" value="@if(!empty($result)){{$result['parent_id'] }}@endif" id="category_input_box_hidden" required />
										<input type="hidden" name="id" value="@if(!empty($result)){{ $result['id'] }}@else{{ '0' }}@endif"  required />
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Name <label class="text-danger">*</label></label>
										<input  value="@if(!empty($result)){{ $result['name'] }}@endif" type="text" required class="form-control" placeholder="Enter Name" name="name" >
									</div>
								</div>
							</div>
							
						</div>
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Image <label class="text-danger">*</label></label>
										<input type="file" id="uploadImage" class="form-control"  name="image" @if(!$result) required @endif  data-type="single" data-image-preview="category" accept="image/*"   >
										<p style="color:red;width:100%">Size must be 250*250</p>
									</div>
								</div>
								
								<div class="form-group previewimages col-md-6" id="category">
									@if($result)
										<img src="{{ asset('uploads/category/'.$result->image) }}" style="width: 100px;border:1px solid #222;margin-right: 13px" />
										<input type="hidden" name="old_image" value="{{ $result->image }}" />
									@endif
								</div>
							</div>
						</div>
						
						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Description (Optional)</label>
										<textarea class="form-control"  name="description" placeholder="Description">@if(!empty($result)){{ $result['description'] }}@endif</textarea>
									</div>
								</div>
							</div>
						</div>

						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Meta Title <label class="text-danger">*</label></label>
										<input type="text" class="form-control" name="meta_title" placeholder="Meta Title" value="@if(!empty($result)){{ $result['meta_title'] }}@endif"/>
									</div>
								</div>
							</div>
						</div>

						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Meta Keywords <label class="text-danger">*</label></label>
										<textarea class="form-control" name="meta_keywords" placeholder="Meta Keywords">@if(!empty($result)){{ $result['meta_keywords'] }}@endif</textarea>
									</div>
								</div>
							</div>
						</div>

						<div class="row clearfix">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-line">
										<label for="inputName">Meta Description <label class="text-danger">*</label></label>
										<textarea class="form-control" name="meta_description" placeholder="Meta Description">@if(!empty($result)){{ $result['meta_description'] }}@endif</textarea>
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


	<script>
		function resetFormData(){

			$('#category').html('');

		}

		var comboTree2;
		
		$.ajax({
			type: "get",
			dataType: "json",
			url: "{{ route('admin.category.addcategory') }}",
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
							selected: [@if(!empty($result)){{ $result['parent_id'] }}@endif]
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

