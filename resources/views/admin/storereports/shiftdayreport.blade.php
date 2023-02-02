<?php
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Helpers\AppHelper as Helper;

$Roles = config('app.Roles');
?>

@include('admin.layout.header')

<style type="text/css">
	.dataTables_filter, .dataTables_info, .dataTables_paginate {
     display: none;
}
</style>


<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item">
					<a class="text-primary" href="{{url('/admin/storereports/' . $storeId)}}">
						<i class="bx bx-home-alt"></i> {{ __('lang.storereports')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					<a class="text-primary" href="{{url('/admin/storereports/shiftreports/'.$storeId)}}">
						<i class="bx bx-store-alt"></i>{{ __('lang.shiftreports')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					<i class="bx bx-store-alt"></i>{{ __('lang.shiftdayreports')}}</li>
			</ol>
			
		</nav>
	</div>
	<div class="ms-auto">
		
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.shiftdayreports')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<table class="table mb-0 table-striped table-bordered" id="myTable">
				
					<thead>
						<tr>
							<th scope="col">{{ __('lang.shiftno.')}}</th>
							<th scope="col">{{ __('lang.cashier')}}</th>
							<th scope="col">{{ __('lang.openingbalance')}}</th>
							<th scope="col">{{ __('lang.closingbalance')}}</th>
							<th scope="col">{{ __('lang.adjustedamount')}}</th>
							<th scope="col">{{ __('lang.finalbalance')}}</th>
							<th scope="col" width="15%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($results as $key=>$shiftDayReport)
						<tr>
							<td>{{ $shiftDayReport->shiftId }}</td>
							<td>{{ $shiftDayReport->firstName }} {{ $shiftDayReport->lastName }}</td>
							<td>{{$shiftDayReport->shiftInBalance}}</td>
							<td>{{$shiftDayReport->shiftEndBalance}}</td>
							<td>--<!-- 0 SAR --></td>
							<td>--<!-- SAR 4,444 --></td>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										<a class="dropdown-item" href="{{url('/admin/storereports/shiftreport/'.$shiftDayReport->userId)}}"><i class="fadeIn animated bx bx-show"></i> {{ __('lang.view')}}</a>
									</div>
								</div>
							</td>
						</tr>
                        @endforeach
						
					</tbody>
				</table>
				
			</div>
		</div>
	</div>
</div>


@include('admin.layout.footer')