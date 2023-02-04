<?php
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Helpers\AppHelper as Helper;

$Roles = config('app.Roles');
?>
<style>
	.col-md-6 {
		padding-left: 16px;
		
	}
	.btn {
		background-image: linear-gradient(to right, #144b15,#396a38,#1f5420,#0b430d,#295d29);
		padding: 40px 10px;
	}
	
	.btn-style {
		width: 100%; 
	}
	
</style>

@include('admin.layout.header')
<div class="row">
	<div class="col-12">
		<div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
			<div class="d-flex">
				@if($storedata->printStoreNameAr != null)
					<h4 class="mb-3">{{$storedata->storeName}}  ({{$storedata->printStoreNameAr}}) </h4>
				@else
					<h4 class="mb-3">{{$storedata->storeName}} &nbsp; </h4>
				@endif
				<h5 style="margin-top: 2px;"> &nbsp;- (Store ID- TIJ{{$storedata->id}})  (User ID-{{$storedata->userId}})</h5>
			</div>
		</div>
	</div>
	<div class="col-3">
		<div class="card radius-10 overflow-hidden">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<p class="mb-0 text-secondary font-14"><a href="{{url('/admin/order/'.$storedata->id)}}">{{ __('lang.todaysorders')}}</a></p>
						<h5 class="my-0">{{$todayorderCount}}</h5>
					</div>
					<div class="text-primary ms-auto font-30"><i class='bx bx-cart-alt'></i>
					</div>
				</div>
				</div>
			<div class="mt-1" id="chart1"></div>
		</div>
	</div>
	<div class="col-3">
		<div class="card radius-10 overflow-hidden">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<p class="mb-0 text-secondary font-14"><a href="{{url('/admin/order/'.$storedata->id)}}">{{ __('lang.totalorders')}}</a></p>
						<h5 class="my-0">{{$allorderCount}}</h5>
					</div>
					<div class="text-info ms-auto font-30"><i class='bx bx-cart'></i>
					</div>
				</div>
			</div>
			<div class="mt-1" id="chart5"></div>
		</div>
	</div>
	
	<div class="col-3">
		<div class="card radius-10 overflow-hidden">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<p class="mb-0 text-secondary font-14"><a href="{{url('/admin/report/revenue')}}">{{ __('lang.todaysrevenue')}}</a></p>
						<h5 class="my-0">SAR {{$revenue}}</h5>
					</div>
					<div class="text-danger ms-auto font-30">ريال
					</div>
				</div>
			</div>
			<div class="mt-1" id="chart2"></div>
		</div>
	</div>
	<div class="col-3">
		<div class="card radius-10 overflow-hidden">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<p class="mb-0 text-secondary font-14"><a href="{{url('/admin/customer/'.$storedata->id)}}">{{ __('lang.customers')}}</a></p>
						<h5 class="my-0">{{$allcustomer}}</h5>
					</div>
					<div class="text-success ms-auto font-30"><i class='bx bx-group'></i>
					</div>
				</div>
			</div>
			<div class="mt-1" id="chart3"></div>
		</div>
	</div>
	
</div>
<!--end row-->
	<div class="row mb-4 mt-5 text-center">
			<div class="col-md-6">
				<a href="{{url('/admin/storereports/salesreports/' . $storeId)}}" class="btn btn-style btn-primary" style="padding: 40px 10px;">
					{{ __('lang.salesreports')}}
				</a>
			</div>
            <div class="col-md-6">
				<a href="{{url('/admin/storereports/vatreports/' . $storeId)}}" class="btn btn-style btn-primary" style="padding: 40px 10px;">
					{{ __('lang.vatreports')}}
				</a>
			</div>
		</div>
        <div class="row mb-4 text-center"> 
			<div class="col-md-6">
				<a href="{{url('/admin/storereports/refundreports/' . $storeId)}}" class=" btn btn-style btn-primary" style="padding: 40px 10px;">
					{{ __('lang.refundreports')}}
				</a>
			</div>
			<div class="col-md-6">
				<a href="{{url('/admin/storereports/profitlossreports/' . $storeId)}}" class="btn btn-style btn-primary" style="padding: 40px 10px;">
					{{ __('lang.profit&loss')}}
				</a>
			</div>
           
            
        </div>
        <div class="row mb-4 text-center">
		<div class="col-md-6">
				<a href="{{url('/admin/storereports/shiftreports/' . $storeId)}}" class="btn btn-style btn-primary" style="padding: 40px 10px;">
					{{ __('lang.shiftreports')}}
				</a>
			</div>
			
			<div class="col-md-6">
				<a href="{{url('/admin/storereports/mediareports/' . $storeId)}}" class="btn btn-style btn-primary" style="padding: 40px 10px;">
					{{ __('lang.mediareports')}}
				</a>
			</div>
		</div>
		<div class="row mb-4 text-center">
			<div class="col-md-6">
				<a href="{{url('/admin/storereports/cashierreports/' . $storeId)}}" class="btn btn-style btn-primary" style="padding: 40px 10px;">
					{{ __('lang.cashierreports')}}
				</a>
			</div>
			<!-- <div class="col-md-6">
				<a href="{{url('/admin/storereports/purchasereports/' . $storeId)}}" class="btn btn-style btn-primary" style="padding: 40px 10px;">
					{{ __('lang.purchasereports')}}
				</a>
			</div> -->
			
			
		</div>
		<div class="row mb-4 text-center">
			 <!-- <div class="col-md-6">
				<a href="{{url('/admin/storereports/inventoryreports/' . $storeId)}}" class="btn btn-style btn-primary" style="padding: 40px 10px;">
                {{ __('lang.inventoryreports')}}
				</a>
			</div> -->
		</div>
		

@include('admin.layout.footer')