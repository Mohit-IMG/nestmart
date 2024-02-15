@extends('layouts/master')

@section('title',__('Coupon List'))

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
                            <a class="btn-primary" href="{{ url('admin/coupon/add') }}">
                                <i class="fa fa-plus"></i> Add Coupon 
							</a>
                        </div>
					</div>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> Coupon List</h2>
					</div>
                    <div class="body">
                        <div class="table-responsive">
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-hover js-basic-example contact_list dataTable"
                                            id="DataTables_Table_0" role="grid"
                                            aria-describedby="DataTables_Table_0_info">
                                            <thead>
                                                <tr role="row">
                                                    <th class="center sorting sorting_asc" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 48.4167px;" aria-sort="ascending"
                                                        aria-label="#: activate to sort column descending"># ID</th>
                                                        <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 126.333px;"
                                                        aria-label=" Name : activate to sort column ascending">
                                                        Name
                                                    </th> 
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 126.333px;"
                                                        aria-label=" Name : activate to sort column ascending"> Value
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                    aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                    style="width: 126.333px;"
                                                    aria-label=" Name : activate to sort column ascending"> Description
                                                </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Status
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 85px;"
                                                        aria-label=" Action : activate to sort column ascending"> Action
                                                    </th> 
                                                </tr>
                                            </thead>
                                            <tbody class="row_position">
												@if(!empty($result))
													@foreach($result as $key=>$value)
														<tr class="gradeX odd"  id="{{ $value->id }}">
															<td class="center">{{ $key+1}}</td>
                                                            <td class="center">{{ ucfirst($value['code']) }}</td>
                                                            <td class="center">{{ ucfirst($value['value']) }}</td>
                                                            <td class="center">{{ ucfirst($value['description']) }}</td>
															<td class="center">
                                                                <div class="switch mt-3">
                                                                    <label>
                                                                        <input type="checkbox" class="-change" data-id="{{ $value['id'] }}"@if($value['status']=='active'){{ 'checked' }} @endif>
                                                                        <span class="lever switch-col-red layout-switch"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="center">
                                                            
                                                                <a href="{{ url('admin/coupon/update/'.$value['id'] )}}" title="Edit Brand" class="btn btn-tbl-edit">
                                                                    <i class="fas fa-pencil-alt"></i>
                                                                </a>
                                                                <a title="Delete Coupon" onclick="return confirm('Are you sure? You want to delete this Brand.')" href="{{ url('admin/coupon/delete/'.$value['id'] )}}" class="btn btn-tbl-delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                                <a href="javascript:void(0);" onclick="sendCouponToAllUsers('{{ $value['id'] }}')" title="Send" class="btn btn-tbl-send-to-all">
                                                                    <i class="fas fa-paper-plane"></i> Send
                                                                </a>
                                                                
                                                            </td>
														</tr>
													@endforeach
												@endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="center" rowspan="1" colspan="1">#</th>
                                                    <th class="center" rowspan="1" colspan="1"> Name </th>	
                                                    <th class="center" rowspan="1" colspan="1"> Value </th>	
                                                    <th class="center" rowspan="1" colspan="1"> Description </th>	
													<th class="center" rowspan="1" colspan="1"> Status </th>
                                                    <th class="center" rowspan="1" colspan="1"> Action </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add this modal at the end of your view, before the closing </body> tag -->
<div class="modal fade" id="sendCouponModal" tabindex="-1" role="dialog" aria-labelledby="sendCouponModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendCouponModalLabel">Send Coupon to All Users</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to send this coupon to all users?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmSendCouponBtn">Send Coupon</button>
            </div>
        </div>
    </div>
</div>

    </div>
</section>

@endsection
   
@push('custom_js')
    <script>
	
        $('.-change').change(function() {

            var status = $(this).prop('checked') == true ? 'active' : 'expired';
            var id = $(this).data('id');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('admin.coupon.changestatus') }}",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
                data: {
                    'status': status, 
                    'id': id
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
                    sweetAlertMsg('success',data.message);
                }
            });
		});
		
    </script> 

<script>
window.selectedCouponId = null; // Declare the variable here

function sendCouponToAllUsers(couponId) {
    window.selectedCouponId = couponId;
    // Showing the confirmation modal
    $('#sendCouponModal').modal('show');
}

$('#confirmSendCouponBtn').on('click', function () {
    // Close the modal
    $('#sendCouponModal').modal('hide');

    // Check if a coupon ID is selected
    if (!window.selectedCouponId) {
        alert('Please select a coupon before sending to all users.');
        return;
    }

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "{{ route('admin.coupon.sendToAllUsers', ['id' => '__id__']) }}".replace('__id__', window.selectedCouponId),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {}, // You may need to pass some data if required by the server
        beforeSend: function () {
            $('#preloader').css('display', 'block');
        },
        error: function (xhr, textStatus) {
            $('#preloader').css('display', 'none');
            // Handle specific HTTP status codes
            if (xhr.status === 400) {
                // Handle Bad Request (400) error
                sweetAlertMsg('error', xhr.responseJSON.message);
            } else if (xhr.status === 403) {
                // Handle Forbidden (403) error
                sweetAlertMsg('error', xhr.responseJSON.message);
            } else if (xhr.status === 404) {
                // Handle Not Found (404) error
                sweetAlertMsg('error', xhr.responseJSON.message);
            } else {
                // Handle other errors
                sweetAlertMsg('error', xhr.status + ': ' + xhr.statusText);
            }
        },
        success: function (data) {
            $('#preloader').css('display', 'none');
            // Handle the response message here
            if (data.message) {
                alert(data.message);
                sweetAlertMsg('success', data.message);
            } else {
                sweetAlertMsg('error', 'Unexpected response from the server.');
            }
        }
    });
});

</script>


@endpush