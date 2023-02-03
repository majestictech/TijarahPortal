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
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-store-alt"></i>{{ __('lang.shiftreports')}}</li>
				
			</ol>
			
		</nav>
	</div>
	<div class="ms-auto">
		
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.shiftreports')}}</h6>
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
                <!-- <div class="row pb-3">
    				<div>
                        <button name="type" value="today" class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">Today</button>
                        <button name="type" value="yesterday" class="pt-1 pb-1 btn btn-primary text-white" style="border:none;  border-radius:5px">Yesterday</button>
                        <button name="type" value="thismonth" class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">{{ __('lang.thismonth')}}</button>
                    </div>
    					
    			</div> -->
    		</form>
				
				<table class="table mb-0 table-striped table-bordered" id="myTable">
				
					<thead>
						<tr>
							<th scope="col">{{ __('lang.date')}}</th>
							<th scope="col">{{ __('lang.no.ofdevices/terminals')}}</th>
							<th scope="col">{{ __('lang.totalshifts')}}</th>
							<th scope="col">{{ __('lang.systemamount')}}</th>
							<th scope="col">{{ __('lang.adjustedamount')}}</th>
							<th scope="col">{{ __('lang.finalbalance')}}</th>
							<th scope="col" width="15%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
					@foreach($results as $key=>$shiftReport)
						<tr>
							<td>{{ $shiftReport->dateCreated }}</td>
							<td>1</td>
							<td>{{$shiftReport->totalShifts}}</td>
							<td>SAR {{$shiftReport->shiftEndCDBalance}}</td>
							<td>SAR {{$shiftReport->adjustAmount}}</td>
							<td>SAR {{$shiftReport->shiftEndBalance}}</td>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										<a class="dropdown-item" href="{{url('/admin/storereports/shiftdayreport/'.$shiftReport->storeId.'/'.$shiftReport->dateCreated)}}"><i class="fadeIn animated bx bx-show"></i> {{ __('lang.view')}}</a>
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


