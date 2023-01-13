@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('vat_manage','vat_edit');
?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/vat')}}"><i class="bx bx-box"></i> {{ __('lang.vat')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.editvat')}}</li>
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
					<div><i class="bx bx-box me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.editvat')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" method="post" action="{{route('vat.update')}}" data-toggle="validator">
				@if($errors->any())
				<h4 class="error_msg">{{$errors->first()}}</h4>
				@endif
				@csrf
				    <div class="col-md-12 ">
						<label for="name" class="form-label">{{ __('lang.name')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-box'></i></span>
							<input type="text" autofocus="autofocus" onfocus="this.setSelectionRange(this.value.length,this.value.length);" name="name" class="form-control border-start-0" value="{{$vat->name}}" id="name" placeholder="{{ __('lang.entername')}}" required>
						</div>
					</div>
					
					
					<div class="col-md-12">
						<label for="value" class="form-label">{{ __('lang.vat')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-comment-detail' ></i></span>
							<input type="number" name="value" class="form-control border-start-0" value="{{$vat->value}}" id="value" placeholder="{{ __('lang.entervatpercent')}}" required>
						</div>
					</div>
					
					<div class="col-12">
						<input type="hidden" name="id" value = "{{$vat->id}}">
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.editvat')}}</button>
						<button type="reset" class="btn btn-primary px-5">{{ __('lang.reset')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->
@include('admin.layout.footer')    
 