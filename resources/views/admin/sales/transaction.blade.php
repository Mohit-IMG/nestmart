@extends('layouts/master')

@section('title',__('Transaction List'))

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
					<div class="header">
						<h2><i class="fa fa-th"></i> Transaction List</h2>
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
                                                        style="width: 141.983px;"
                                                        aria-label=" Mobile : activate to sort column ascending"> Order Id
                                                    </th>
                                                
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Transaction Id
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Order Date
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 126.333px;"
                                                        aria-label=" Name : activate to sort column ascending"> Name
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Amount
                                                    </th>
                                                    <th class="center sorting" tabindex="0"
                                                        aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                        style="width: 193.017px;"
                                                        aria-label=" Email : activate to sort column ascending"> Payment status
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
												@if(!empty($result))
													@foreach($result as $key=>$value)
                                                    <tr>
														<td class="center">{{ $key+1 }}</td>
														<td class="center">{{ $value['order_id'] }}</td>
														<td class="center">{{ $value['transaction_id'] }}</td>
														<td class="center">{{ date('d-M-Y H:i:s',strtotime($value['created_at'])) }}</td>
														<td class="center">{{ \App\Helpers\commonHelper::geUserNameById($value['user_id']) }}</td>
														<td class="center">{{ $value['amount']}}</td>
														<td class="center">
															@if($value['payment_status']=='0')
																<div class="badge col-orange">Pending</div>
															@elseif($value['payment_status']=='2')
																<div class="badge col-green">Success</div>	
															@else
                                                            <div class="badge col-red">Failed</div>

															@endif
														</td>
														
                                                    </tr>
													@endforeach
												@endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="center" rowspan="1" colspan="1">#</th>
                                                    <th class="center" rowspan="1" colspan="1"> Order Id </th>
                                                    <th class="center" rowspan="1" colspan="1"> Transaction Id </th>
                                                    <th class="center" rowspan="1" colspan="1"> Order Date </th>
                                                    <th class="center" rowspan="1" colspan="1"> Name </th>
                                                    <th class="center" rowspan="1" colspan="1"> Amount </th>
                                                    <th class="center" rowspan="1" colspan="1"> Payment status </th>
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
    </div>
</section>

	<div id="productdetailModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content"  id="productDetail">
			</div>
		</div>
	</div>

@endsection

@push('custom_js')
    <script>
	
        $('.orderready').click(function() {

            var sale_id = $(this).data('sale_id');
            var suborder_id = $(this).data('suborderid');
            var type = $(this).data('type');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('admin.sales.orderready') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'sale_id': sale_id,
                    'suborder_id':suborder_id,
                    'type':type,
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
                    location.reload();
                }
            });
        });
		
		$('.getorderdetail').click(function() {
			
            var id = $(this).data('sale_id');
            var type = $(this).data('type');
            var pageType = $(this).data('page_type');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ url('admin/sales/getsaledetail') }}",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
                data: {
                    'id': id,
					'type':type,
                    'pageType':pageType
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
					$('#productDetail').html(data.html);
					$('#productdetailModal').modal('toggle');
					
					$('#preloader').css('display','none');
                }
            });
		});
		
    </script>                                           
@endpush