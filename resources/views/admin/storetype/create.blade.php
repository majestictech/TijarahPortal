@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('storetype_manage','storetype_add');
?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/storetype')}}"><i class="bx bx-store-alt"></i> {{ __('lang.storetype')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addstoretype')}}</li>
			</ol>
		</nav>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<div class="card border-top border-0 border-4 border-secondary">
			<div class="card-body p-5 ">
				<div class="card-title d-flex align-items-center">
					<div><i class="bx bx-store-alt me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.addstoretype')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" method="post" action="{{route('storetype.store')}}" data-toggle="validator">
				@csrf
					<div class="col-md-12 ">
						<label for="name" class="form-label">{{ __('lang.storetype')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store-alt'></i></span>
							<input type="text" autofocus="autofocus" name="name" class="form-control border-start-0" id="name" placeholder="{{ __('lang.enterstoretype')}}" required>
						</div>
					</div>
					
					
					<div class="col-12">
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.addstoretype')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->
@include('admin.layout.footer')    
 