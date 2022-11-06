@include('admin.layout.header')
<?php
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Helpers\AppHelper as Helper;

$Roles = config('app.Roles');

helper::checkStoreId($storeId);

?>

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
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-store-alt"></i>{{ __('lang.salesreports')}}</li>
				
			</ol>
			
		</nav>
	</div>
	<div class="ms-auto">
		
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.salesreports')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
    			<form action="" method="GET" id ="filter_results">
        		    <div class="row">
    					<div class="col-md-3 mb-3 ">
    						<select name="reportType" class="form-select single-select" id="type" onChange="this.form.submit();">
        						<option value="" @if(empty($reportType)) selected="selected" @endif>{{ __('lang.salesreports')}}</option>
    							<option value="daily" @if($reportType == 'daily') selected="selected" @endif>{{ __('lang.dailysalesreports')}}</option>
    							<option value="billwise" @if($reportType == 'billwise') selected="selected" @endif>{{ __('lang.billwisereport')}}</option>
    							<option value="category" @if($reportType == 'category') selected="selected" @endif>{{ __('lang.categoryreport')}}</option>
    							<option value="productwise" @if($reportType == 'productwise') selected="selected" @endif>{{ __('lang.productwisereport')}}</option>
        					</select>
    					</div>
                    	<div class="col-md-4">
            				<div class="row input-daterange pb-4">
                                <div class="col-md-6">
                                    <input type="date" name="start_date" value="{{$start_date}}" id="min" class="form-control" placeholder="{{ __('lang.fromdate')}}"  />
                                </div>
    							<div class="col-md-6">
                                    <input type="date" name="end_date" id="max" class="form-control" value="{{$end_date}}" placeholder="{{ __('lang.todate')}}"  />
                                </div>
            				</div>
            			</div>
            			
            			<div class="col-md-2 mb-3 ">
            			     <button type="submit" class="btn btn-primary px-5" name="searchBtn" value="yes">Search</button>
            			</div>
                    </div>
    				<div class="row pb-3">
    				    @if($reportType == 'daily')
    				        <div>
        						<button name="type" value="today" class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">Today</button>
        						<button name="type" value="yesterday" class="pt-1 pb-1 btn btn-primary text-white" style="border:none;  border-radius:5px">Yesterday</button>
        						<button name="type" value="thismonth" class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">{{ __('lang.thismonth')}}</button>
        					</div>
    					@elseif($reportType == 'billwise')
    					    <div>
        					    <button name="type" value="billwisetoday" class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">Today</button>
        						<button name="type" value="billwisethismonth" class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">{{ __('lang.thismonth')}}</button>
        					</div>
    					@endif
    				</div>
        		</form>
				
				<table class="table mb-0 table-striped table-bordered" id="myTable" data-ordering="false">
				    @if($reportType == 'daily')
    					<thead>
    						<tr>
    							<th scope="col">{{ __('lang.date')}}</th>
    							<th scope="col">{{ __('lang.totalbills')}}</th>
    							<th scope="col">{{ __('lang.totaltaxable')}}</th>
    							<th scope="col">{{ __('lang.taxamount')}}</th>
    							<th scope="col">{{ __('lang.totalamount')}}</th>
    						</tr>
    					</thead>
    					<tbody>
    						@foreach($results['bills'] as $result)
    						<tr>
    							<td class="sorting_desc">{{$result->created_at}}</td>
    							<td>{{$result->totalBill}}</td>
    							<td>{{$result->totalAmount - $result->vat}}</td>
    							<td>{{$result->vat}}</td>
    							<td>{{$result->totalAmount}}</td>
    						</tr>
    						@endforeach
    					</tbody>
					@elseif($reportType == 'billwise')
    					<thead>
    						<tr>
    							<th scope="col">{{ __('lang.date')}}</th>
    							<th scope="col">{{ __('lang.totalbills')}}</th>
    							<th scope="col">{{ __('lang.totalamount')}}</th>
    						</tr>
    					</thead>
    					<tbody>
    						@foreach($results['bills'] as $result)
    						<tr>
    							<td>{{$result->created_at}}</td>
    							<td>{{$result->orderId}}</td>
    							<td>{{$result->totalAmount}}</td>
    						</tr>
    						@endforeach
    					</tbody>
					@endif
				</table>
				
			</div>
		</div>
	</div>
</div>

@include('admin.layout.footer')