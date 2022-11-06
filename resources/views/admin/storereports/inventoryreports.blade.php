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
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-store-alt"></i>{{ __('lang.inventoryreports')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.inventoryreports')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<form action="" method="GET" id ="filter_results">
					<div class="row">
						<!--<div class="col-md-3 mb-3 ">
							<select name="storeFilter" class="form-select single-select" id="storeFilter" onChange="this.form.submit();">
									<option value="" @if(empty($storeFilter)) selected="selected" @endif>{{ __('lang.inventoryreports')}}</option>
							</select>
						</div>-->
						<div class="col-md-4">
							<div class="row input-daterange pb-4">
								<div class="col-md-6">
									<input type="date" name="start_date" id="min" class="form-control" value="" placeholder="{{ __('lang.fromdate')}}"  />
								</div>
								<div class="col-md-6">
									<input type="date" name="end_date" id="max" class="form-control" value="" placeholder="{{ __('lang.todate')}}"  />
								</div>
							</div>
						</div>
					
						<div class="col-md-3 mb-3 ">
							<input type="text" name="search" style="height: 37px;" placeholder="Search" class="form-control form-control-sm" value=""/>
						</div>
						
					   <div class="col-md-2">
							 <button type="submit" class="btn btn-primary px-5" name="searchBtn" value="yes">Search</button>
						</div>
					</div>
				</form>
			    <!--<form action="" method="GET" id ="filter_results">
			       <div class="row"> 
						<div class="col-md-4 mb-3">
							<div class="input-group">
								<button class="btn btn-outline-secondary" type="button"><i class='bx bx-book-content'></i>
								</button>
								<select name="storeFilter" class="form-select single-select" id="storeFilter" onChange="this.form.submit();">
									<option value="" @if(empty($storeFilter)) selected="selected" @endif>{{ __('lang.inventoryreports')}}</option>
								</select>
							</div>
							<div class="pt-3">
								<button type="button" class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">Today</button>
								<button class="pt-1 pb-1 btn btn-primary text-white" style="border:none;  border-radius:5px">Yesterday</button>
								<button class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">{{ __('lang.thismonth')}}</button>		
							</div>
						</div>
    				</div>
    			</form>-->
				<div class="row pb-3">
					<div>
								<button type="button" class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">Today</button>
								<button class="pt-1 pb-1 btn btn-primary text-white" style="border:none;  border-radius:5px">Yesterday</button>
								<button class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">{{ __('lang.thismonth')}}</button>		
							</div>
				</div>
				
				<table class="table mb-0 table-striped table-bordered" id="myTable">
				
					<thead>
						<tr>
							<th scope="col">{{ __('lang.productname|brand|barcode')}}</th>
							<th scope="col">{{ __('lang.sellingprice')}}</th>
							<th scope="col">{{ __('lang.minimumquantity')}}</th>
							<th scope="col">{{ __('lang.instockvalue')}}</th>
							<th scope="col">{{ __('lang.availability')}}</th>
							<th scope="col">{{ __('lang.editproduct')}}</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
@include('admin.layout.footer')
