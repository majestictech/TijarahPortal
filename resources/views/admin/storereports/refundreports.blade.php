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
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/storereports/' . $storeId)}}"><i class="bx bx-home-alt"></i> {{ __('lang.storereports')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-store-alt"></i>{{ __('lang.refundreports')}}</li>
				
			</ol>
			
		</nav>
	</div>
	<div class="ms-auto">
		
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.refundreports')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<form action="" method="GET" id ="filter_results">
					<div class="row">
						<!--<div class="col-md-3 mb-3 ">
							<select name="storeFilter" class="form-select single-select" id="storeFilter" onChange="this.form.submit();">
									<option value="" @if(empty($storeFilter)) selected="selected" @endif>{{ __('lang.refundreports')}}</option>
							</select>
						</div>-->
						<div class="col-md-4">
							<div class="row input-daterange pb-4">
								<div class="col-md-6">
									<input type="date" name="startDate" value="{{$startDate}}" id="startDate" class="form-control" placeholder="{{ __('lang.fromdate')}}"  />
								</div>
								<div class="col-md-6">
									<input type="date" name="endDate" value="{{$endDate}}" id="endDate" class="form-control"  placeholder="{{ __('lang.todate')}}"  />
								</div>
							</div>
						</div>
					
						<div class="col-md-3 mb-3 ">
							<input type="text" name="search" style="height: 37px;" placeholder="Search" class="form-control form-control-sm" value=""/>
						</div>
						
						<div class="col-md-2 mb-3 ">
							 <button type="submit" class="btn btn-primary px-5" name="type" value="refundcustom">Search</button>
						</div>
					</div>
					<div class="row pb-3">
					<div>
						<button name="type" value="refundtoday" class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">Today</button>
						<button name="type" value="refundyesterday" class="pt-1 pb-1 btn btn-primary text-white" style="border:none;  border-radius:5px">Yesterday</button>
						<!--<button name="type" value="refundcustom" class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">{{ __('lang.thismonth')}}</button>-->
						</div>
				</div>
				</form>
				
			   
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
							<th scope="col">{{ __('lang.refundbillno')}}</th>
							<th scope="col">{{ __('lang.refundbilldate')}}</th>
							<th scope="col">{{ __('lang.customername')}}</th>
							<th scope="col">{{ __('lang.refundeditems')}}</th>
							<th scope="col">{{ __('lang.refundedquantity')}}</th>
							<th scope="col">{{ __('lang.refundedamount(sar)')}}</th>
						</tr>
					</thead>
					<tbody>
					    @foreach($datas['refunddata'] as $result)
							<tr>
								<td>{{$result->orderNumber}}</td>
								<td>{{$result->created_at}}</td>
								@if($result->customerName)
									<td> {{$result->customerName}}</td>
								@else
									<td>Guest</td>
								@endif
								<td>{{$result->refundQty}}</td>
								<td>{{$result->qty}}</td>
								<td>{{$result->totalAmount}}</td>
								
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@include('admin.layout.footer')