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
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-store-alt"></i>{{ __('lang.profit&lossreports')}}</li>
				
			</ol>
			
		</nav>
	</div>
	<div class="ms-auto">
		
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.profit&lossreports')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				
			<form action="" method="GET" id ="filter_results">
    		    <div class="row">
					
                	<div class="col-md-4">
        				<div class="row input-daterange pb-4">
                            <div class="col-md-6">
                                <input type="date" name="start" class="form-control" value="{{$startDate}}" placeholder="{{ __('lang.fromdate')}}"  />
                            </div>
							<div class="col-md-6">
                                <input type="date" name="end" value="{{$endDate}}" class="form-control"  placeholder="{{ __('lang.todate')}}"  />
                            </div>
        				</div>
        			</div>
    			
        		    <div class="col-md-3 mb-3 ">
    				    <input type="text" name="search" style="height: 37px;" placeholder="Search" value="{{$search}}" class="form-control form-control-sm"/>
                    </div>
        		    
        		    
        		    
        			<div class="col-md-2 mb-3 ">
        			     <button type="submit" class="btn btn-primary px-5" name="searchBtn">Search</button>
        			</div>
                </div>
    		</form>
				<!-- <div class="row pb-3">
						<div>
								<button type="button" class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">{{ __('lang.profitmargin')}}</button>
								<button class="pt-1 pb-1 btn btn-primary text-white" style="border:none;  border-radius:5px">{{ __('lang.profit&loss')}}</button>
								<button class="pt-1 pb-1 btn btn-primary text-white" style="border:none; border-radius:5px">{{ __('lang.profit&lossprojection')}}</button>		
						</div>
				</div> -->
				<table class="table mb-0 table-striped table-bordered" id="myTable">
				
					<thead>
						<tr>
							<th scope="col">{{ __('lang.name')}}</th>
							<!-- <th scope="col">{{ __('lang.mrp(sar)')}}</th> -->
							<th scope="col">{{ __('lang.c.p(sar)')}}</th>
							<!-- <th scope="col">{{ __('lang.c.p(sar)excl')}}</th> -->
							<th scope="col">{{ __('lang.s.p(sar)')}}</th>
							<th scope="col">{{ __('lang.qty')}}</th>
							<th scope="col">{{ __('lang.margin(sar)')}}</th>
							<th scope="col">{{ __('lang.percentage')}}</th>
							<th scope="col">{{ __('lang.totalmargin(sar)')}}</th>
						</tr>
					</thead>
					@foreach($results as $key =>$result)
					<tr>
						<td>{{$result->productName}}</td>
					<!-- 	<td>{{$result->price}}</td> -->
						<td>{{$result->costPrice}}</td>
						<!-- <td>{{$result->costPrice}}</td> -->
						<td>{{$result->price}}</td>
						<td>{{$result->qty}}</td>
						<td>{{$result->margin}}</td>
						<td>{{$result->percentprofit}}</td>
						<td>{{($result->qty) * ($result->margin)}}</td>
					</tr>
					@endforeach
				</table>
				{{ $results->appends(array('search' => $search,'start'=>$startDate, 'end'=>$endDate))->links() }}
			</div>
		</div>
	</div>
</div>


@include('admin.layout.footer')


