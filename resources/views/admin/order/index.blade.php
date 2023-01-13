@include('admin.layout.header')	
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('orders_manage','');
?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-list-ol"></i> {{ __('lang.orders')}}</li>
			</ol>
		</nav>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.allorders')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
	        <form action="" method="GET" id ="filter_results">
			<input type="hidden" name="storeId" value="{{ $storeId}}">	
    		    <div class="row"> 
					<div class="col-md-3">
							<label for="roleFilter" class="form-label">{{ __('lang.filterby')}}</label>
							<div class="input-group">
								<button class="btn btn-outline-secondary" type="button"><i class='bx bx-user-circle'></i>
								</button>
								<select name="orderFilter" class="form-select single-select" id="orderFilter" onChange="this.form.submit();">
									<option value="" >{{ __('lang.selectstoretype')}}</option>
										<option value="" @if($orderFilter == '') selected="selected" @endif>{{ __('lang.all')}}</option>
										<option value="WalkIn" @if($orderFilter == 'WalkIn') selected="selected" @endif>{{ __('lang.storeCompleted')}}</option>
										<option value="OutForDelivery"  @if($orderFilter == 'OutForDelivery') selected="selected" @endif>{{ __('lang.outForDelivery')}}</option>
										<option value="DeliveryCompleted"  @if($orderFilter == 'DeliveryCompleted') selected="selected" @endif>{{ __('lang.deliveryCompleted')}}</option>
								</select>
							</div>
						</div>
                	<div class="col-md-3 mt-4 pt-1">
        				<div class="row input-daterange pb-4">
                            <div class="col-md-6">
                                <input type="date" name="start_date" id="min" class="form-control" value="{{$startdate}}" placeholder="{{ __('lang.fromdate')}}"  />
                            </div>
                            <div class="col-md-6">
                                <input type="date" name="end_date" id="max" class="form-control" value="{{$enddate}}" placeholder="{{ __('lang.todate')}}"  />
                            </div>
        				</div>
        			</div>
    			
        		     <div class="col-md-2 mt-4 pt-1">
    				    <input type="text" name="search" style="height: 37px;" class="form-control form-control-sm" value="{{$search}}"/>
                    </div>
        		    
        		    
        		    
        			<div class="col-md-2 mt-4 pt-1">
					
					<button type="submit" class="btn btn-primary px-5" name="searchBtn" value="yes">{{ __('lang.search')}}</button>
        			</div>
        			
        			<div class="col-md-2 mt-4 pt-1 text-end">
        			    <button type="submit" class="btn btn-primary px-5" name="exportBtn" value="yes"> {{ __('lang.export')}} </button>
        			     <!--<a type="button" name="upload" href="{{route('order.export')}}" class="btn btn-primary px-5" style="height: 36px; border-radius: 2px; margin-left: 20px;" value="OK">Export</a>-->
        			</div>
    			
    			
                </div>
    		</form>
    		<!--
    		<form method="post"  enctype="multipart/form-data" action="{{route('order.export')}}" style="display:flex;">
    		    <div class="row"> 
                	<div class="col-lg-5">
                        <input type="date" name="start_date" id="min" class="form-control" placeholder="{{ __('lang.fromdate')}}"  />
                    </div>
                    <div class="col-lg-5">
                        <input type="date" name="end_date" id="max" class="form-control" placeholder="{{ __('lang.todate')}}"  />
                    </div>
                    <div class="col-lg-2">
        		   
        				{{ csrf_field() }}
        				<div class="form-group">
        					<input type="submit" name="upload" class="btn btn-primary" style="height: 36px; border-radius: 2px; margin-left: 20px;" value="OK">
        				</div>
        
                    </div>
    			
    			
                </div>
    		</form>
    		-->    
    			    
    			    
    			   
                

      


				<table class="table mb-0 table-striped table-bordered" id="0">
					<thead>
						<tr>
							<th scope="col">{{ __('lang.placedon')}}</th>
							<th scope="col">{{ __('lang.ordernumber')}}</th>
							<th scope="col">{{ __('lang.payment')}}</th>

							<?php if(Auth::user()->roleId != 4 ){?>
								<th scope="col">{{ __('lang.storename')}}</th>
							<?php } ?>

							<th scope="col">{{ __('lang.ordertype')}}</th>

							<th scope="col">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
    					@foreach($orders as $key =>$orderData)
    						<tr>
                                <td>{{\Carbon\Carbon::parse($orderData->created_at)->format('d M Y')}}</td>
                                <td>{{$orderData->orderId}}</td>
                                <td> {{ __('lang.sar')}} {{$orderData->totalAmount}}</td>

								<?php if(Auth::user()->roleId != 4 ){?>
                                	<td>{{$orderData->storeName}}</td>
								<?php } ?>

								<!--<td class="orderType">{{$orderData->orderType}}</td>-->
								<td class="orderType">
									@if ($orderData->orderType ==  'WalkIn') 
										 <?php echo 'Walk In';?>
									@elseif ($orderData->orderType ==  'OutForDelivery') 
										 <?php echo 'Out For Delivery';?>
									@elseif ($orderData->orderType ==  'Delivered') 
										 <?php echo 'Delivered';?>
									@endif
								</td>

                                <td>
    								<div class="btn-group">
    									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
    									</button>
    									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
    										<a class="dropdown-item" href="{{url('/admin/order/'.$orderData->id.'/view')}}"><i class="fadeIn animated bx bx-show"></i> {{ __('lang.view')}}</a>
    									</div>
    								</div>
    							</td>
    						</tr>
    					@endforeach
					</tbody>
				</table>
                 
                
                
                @if ($postcount > 0)
				<div class="pagination_links">
				{{$orders->appends(array('storeId' => $storeId	,'search' => $search,'start_date'=>$startdate,'end_date'=>$enddate))->links()}}
				</div>
				@endif
			</div>
		</div>
	</div>
</div>
<!--end row-->

@include('admin.layout.footer')

<!--<script>
var minDate, maxDate;
 
// Custom filtering function which will search data in column four between two values
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = minDate.val();
        var max = maxDate.val();
        var date = new Date( data[2] );
 
        if (
            ( min === null && max === null ) ||
            ( min === null && date <= max ) ||
            ( min <= date   && max === null ) ||
            ( min <= date   && date <= max )
        ) {
            return true;
        }
        return false;
    }
);
 
$(document).ready(function() {
    // Create date inputs
    minDate = new DateTime($('#min'), {
        format: 'MMMM Do YYYY'
    });
    maxDate = new DateTime($('#max'), {
        format: 'MMMM Do YYYY'
    });
 
    // DataTables initialisation
    var table = $('#myTable').DataTable();
 
    // Refilter the table
    $('#min, #max').on('change', function () {
        table.draw();
    });
});
</script>-->

<script>
/*
$('#example').dataTable( {
paging: false,
searching: false,
ordre: false
} );
*/
</script>
<script>


/*
var table = $('#myTable').DataTable({
   "order": [[ 4, "asc" ]],
              'columnDefs': [{
                    "targets": [4],
                    "orderable": false
                }]
          });
		  */
</script>
<style>
   /* .dataTables_filter,.dataTables_info,.dataTables_paginate {display:none;} */
    
</style>