@include('admin.layout.header')	
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('loyaltypointshistory_manage','');
?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-hive"></i> {{ __('lang.loyaltypointshistorylist')}}</li>
			</ol>
		</nav>
	</div>
</div>
<!--end breadcrumb-->

<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.allloyaltypoint')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
							<th scope="col">{{ __('lang.storename')}}</th>
							<th scope="col">{{ __('lang.ordernumber')}}</th>
							<th scope="col">{{ __('lang.customers')}}</th>
							<th scope="col">{{ __('lang.loyaltypoints')}}</th>
							<th scope="col">{{ __('lang.type')}}</th>
							<th scope="col" >{{ __('lang.date')}}</th>
						</tr>
					</thead>
					<tbody>
					@foreach($loyaltyHistory as $key =>$loyalty)
						<tr>
                            <td>{{$loyalty->storeName}}</td>
                            <td>{{$loyalty->orderId}}</td>
                            <td>{{$loyalty->customerName}}</td>
							<td>{{$loyalty->points}}</td>
							<td>{{$loyalty->type}}</td>
							<td>{{\Carbon\Carbon::parse($loyalty->date)->format('d M Y')}}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!--end row-->

@include('admin.layout.footer')

<script>
var table = $('#myTable').DataTable({
   "order": [[ 1, "asc" ]],
              'columnDefs': [{
                    "targets": [5],
                    "orderable": false
                }]
          });
</script>