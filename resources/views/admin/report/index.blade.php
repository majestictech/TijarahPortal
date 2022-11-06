<?php
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Helpers\AppHelper as Helper;

$Roles = config('app.Roles');
?>

@include('admin.layout.header')

<div class="card radius-10 overflow-hidden">
	<div class="card-body">
		<div class="d-flex align-items-center">
			<div>
				<p class="mb-0 text-secondary font-14"><a href="#">{{ __('lang.todaysorders')}}</a></p>
				<h5 class="my-0">{{$todayorderCount ?? ''}}</h5>
			</div>
			<div class="text-primary ms-auto font-30"><i class='bx bx-cart-alt'></i>
			</div>
		</div>
	 </div>
	<div class="mt-1" id="chart1"></div>
</div>
<div class="col">
    <div class="card radius-10 overflow-hidden">
    	<div class="card-body">
    		<div class="d-flex align-items-center">
    			<div>
    				<p class="mb-0 text-secondary font-14"><a href="{{url('/admin/order')}}">{{ __('lang.totalorders')}}</a></p>
    				<h5 class="my-0">{{$allorderCount ?? ''}}</h5>
    			</div>
    		<div class="text-info ms-auto font-30"><i class='bx bx-cart'></i>
    	    </div>
    	</div>
    </div>
    <div class="mt-1" id="chart5"></div>
    </div>
</div>
<div class="col">
    <div class="card radius-10 overflow-hidden">
    	<div class="card-body">
    		<div class="d-flex align-items-center">
    			<div>
    			    <p class="mb-0 text-secondary font-14"><a href="#">{{ __('lang.todaysrevenue')}}</a></p>
    			    <h5 class="my-0">SAR {{$revenue ?? ''}}</h5>
    			</div>
    			<div class="text-danger ms-auto font-30">ريال
    			</div>
    		</div>
    	</div>
    	<div class="mt-1" id="chart2"></div>
    </div>
</div>
<div class="col">
    <div class="card radius-10 overflow-hidden">
    	<div class="card-body">
    		<div class="d-flex align-items-center">
    			<div>
    		        <p class="mb-0 text-secondary font-14"><a href="#">{{ __('lang.customers')}}</a></p>
    			    <h5 class="my-0">{{$allcustomer ?? ''}}</h5>
    			</div>
    		<div class="text-success ms-auto font-30"><i class='bx bx-group'></i>
    	    </div>
        </div>
    </div>
    <div class="mt-1" id="chart3"></div>
    </div>
</div><!--end row-->


@include('admin.layout.footer')