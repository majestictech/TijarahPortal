@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('subadmin_manage','subadmin_add');
?>
 <style>
     .error_msg{
	color:red;
	font-size:18px;
	
}
 </style>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/subscription')}}"><i class="bx bx-notepad"></i> {{ __('lang.subscription')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addsubscription')}}</li>
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
					<div><i class="bx bx-notepad me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.addsubscription')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" data-toggle="validator" method="post" action="{{route('subscription.store')}}" >
				@if($errors->any())
				<h4 class="error_msg">{{$errors->first()}}</h4>
				@endif
				@csrf
					<div class="col-md-6 ">
						<label for="subscriptionExpiry" class="form-label">Subcription Plan</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag' ></i></span>
							<input type="text" required name="plan" class="form-control border-start-0" id="plan"  placeholder="Subcription Plan">
						</div>
					</div>
					
					<div class="col-md-6 ">
						<label for="subscriptionExpiry" class="form-label">Price</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag' ></i></span>
							<input type="number" required name="price" class="form-control border-start-0" id="price"  placeholder="Price">
						</div>
					</div>
					
					<div class="col-md-6">
						<label for="subscriptionplans" class="form-label">{{ __('lang.subscriptionplansduration')}} *</label>

						<select name="duration" class="form-select single-select" id="duration" required>
							<option value="">{{ __('lang.selectplan')}}</option>
								@foreach($durations as $kay=>$duration)			
								<option value="{{$duration->id}}">  {{$duration->duration}}  </option>
								@endforeach		
						</select>
					</div>
					
					<div class="col-6">
											
					</div>
					
					<div class="col-12">
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.addsubscription')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->
 
@include('admin.layout.footer')

<script>
$('.datepicker').datepicker({
  // Escape any “rule” characters with an exclamation mark (!).
  format: 'You selecte!d: dddd, dd mmm, yyyy',
  formatSubmit: 'yyyy/mm/dd',
  hiddenPrefix: 'prefix__',
  hiddenSuffix: '__suffix'
})
</script>