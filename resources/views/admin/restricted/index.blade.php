@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<!--<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bxl-bootstrap"></i> {{ __('lang.brands')}}</li>
			</ol>
		</nav>-->
	</div>
	
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		
		<hr/>
		<div class="card">
			<div class="card-body">
				You are not an authorised user.
			</div>
		</div>
	</div>
</div>
<!--end row-->

@include('admin.layout.footer')