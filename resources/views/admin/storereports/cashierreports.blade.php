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
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-store-alt"></i>{{ __('lang.cashierreports')}}</li>
				
			</ol>
			
		</nav>
	</div>
	<div class="ms-auto">
		
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.cashierreports')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				
			<form action="" method="GET" id ="filter_results">
    		    <div class="row">
					
                	<div class="col-md-4">
        				<div class="row input-daterange pb-4">
                            <div class="col-md-6">
                                <input type="date" name="start" value="{{$startDate}}" class="form-control" value="" placeholder="{{ __('lang.fromdate')}}"  />
                            </div>
							<div class="col-md-6">
                                <input type="date" name="end" value="{{$endDate}}" class="form-control" value="" placeholder="{{ __('lang.todate')}}"  />
                            </div>
        				</div>
        			</div>
    			
        		    <div class="col-md-3 mb-3 ">
    				    <input type="text" name="search" style="height: 37px;" placeholder="Search" value="{{$search}}" class="form-control form-control-sm" value=""/>
                    </div>
        		    
        		    
        		    
        			<div class="col-md-2 mb-3 ">
        			     <button type="submit" class="btn btn-primary px-5" name="searchBtn" value="yes">Search</button>
        			</div>
                </div>
    		</form>
				
				<table class="table mb-0 table-striped table-bordered" id="myTable">
				
					<thead>
						<tr>
							<th scope="col">{{ __('lang.name')}}</th>
							<th scope="col">{{ __('lang.id')}}</th>
							<th scope="col">{{ __('lang.phoneno')}}</th>
							<th scope="col">{{ __('lang.emailid')}}</th>
							<th scope="col">{{ __('lang.totalnoofinvoices')}}</th>
							<th scope="col">{{ __('lang.totalamountofsales')}}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($results as $result)
						<tr>
							<td>{{$result->Name}}</td>
							<td>{{$result->userId}}</td>
							<td>{{$result->contactNumber}}</td>
							<td>{{$result->email}}</td>
							<td>{{$result->billCount}}</td>
							<td>{{$result->totalSales}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				
			</div>
		</div>
	</div>
</div>


@include('admin.layout.footer')


