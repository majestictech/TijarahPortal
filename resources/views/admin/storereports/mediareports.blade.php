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
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-store-alt"></i>{{ __('lang.mediareports')}}</li>
				
			</ol>
			
		</nav>
	</div>
	<div class="ms-auto">
		
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.mediareports')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">

			<form action="" method="GET" id ="filter_results">
    		    <div class="row">
					
                	<div class="col-md-4">
        				<div class="row input-daterange pb-4">
                            <div class="col-md-6">
                                <input type="date" name="start" id="min" class="form-control" value="{{$startDate}}" placeholder="{{ __('lang.fromdate')}}"  />
                            </div>
							<div class="col-md-6">
                                <input type="date" name="end" id="max" class="form-control" value="{{$endDate}}" placeholder="{{ __('lang.todate')}}"  />
                            </div>
        				</div>
        			</div>
    			
        			<div class="col-md-2 mb-3 ">
        			     <button type="submit" class="btn btn-primary px-5" name="searchBtn" value="yes">Search</button>
        			</div>
                </div>
				
    		</form>
				
				<table class="table mb-0 table-striped table-bordered" id="myTable">
				
					<thead>
						<tr>
							<th scope="col">{{ __('lang.mediatype')}}</th>
							<th scope="col">{{ __('lang.salescount')}}</th>
							<th scope="col">{{ __('lang.receivedamount(sar)')}}</th>
							
						</tr>
					</thead>
					
					<tbody>
						<tr>
							<td style="width:100px;">{{ __('lang.card')}}</td>
							<td style="width:100px;">{{$results['cardCount']}}</td>
							<td style="width:100px;">{{$results['cardAmount']}}</td>
						</tr>
						<tr>
							<td style="width:100px;">{{ __('lang.cash')}}</td>
							<td style="width:100px;">{{$results['cashCount']}}</td>
							<td style="width:100px;">{{$results['cashAmount']}}</td>
						</tr>
						
						<tr>
							<td style="width:100px;">{{ __('lang.credit')}}</td>
							<td style="width:100px;">{{$results['otherCount']}}</td>
							<td style="width:100px;">{{$results['otherAmount']}}</td>
						</tr>
						<tr>
							<td style="width:100px;">{{ __('lang.refund')}}</td>
							<td style="width:100px;">{{$results['refundCount']}}</td>
							<td style="width:100px;">{{$results['refundAmount']}}</td>
						</tr>
						
					</tbody>
				</table>
				
			</div>
		</div>
	</div>
</div>

@include('admin.layout.footer')


