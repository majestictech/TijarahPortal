@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess(' brand_manage','brand_add');
?>
 
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/brand')}}"><i class="bx bxl-bootstrap"></i> {{ __('lang.brands')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addbrand')}}</li>
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
					<div><i class="bx bxl-bootstrap me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.addbrand')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" enctype="multipart/form-data"  data-toggle="validator" method="post" action="{{route('brand.store')}}" >
				@csrf
					<div class="col-md-12 ">
						<label for="brandName" class="form-label">{{ __('lang.brandtitle')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" autofocus="autofocus" name="brandName" class="form-control border-start-0" id="brandName" placeholder="{{ __('lang.enterbrandtitle')}}" required />
						</div>
					</div>
						<div class="col-12">
							<label for="image" class="form-label">{{ __('lang.brandimage')}}</label>
							<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-file' ></i></span>
								<input type="file" name="brandImage" class="form-control border-start-0" id="brandImage" >
							</div>
						</div>
					<div class="col-12">
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.addbrand')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->
 
@include('admin.layout.footer')