@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('subadmin_manage','subadmin_edit');
?>
 
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/subscription')}}"><i class="bx bx-notepad"></i> {{ __('lang.subscription')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.editsubscription')}}</li>
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
					<h5 class="mb-0 text-primary">{{ __('lang.editsubscription')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" data-toggle="validator" method="post" action="{{route('subscription.update')}}" >
				@csrf
					<div class="col-md-6 ">
						<label for="subscriptionExpiry" class="form-label">Subcription Plan</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag' ></i></span>
							<input type="text" required name="plan" class="form-control border-start-0" id="plan"  value="{{$subscriptionData->plan}}" placeholder="Subcription Plan">
						</div>
					</div>
					
					<div class="col-md-6 ">
						<label for="subscriptionExpiry" class="form-label">Price</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag' ></i></span>
							<input type="number" required name="price" class="form-control border-start-0" id="price"  value="{{$subscriptionData->price}}"  placeholder="Price">
						</div>
					</div>
					
					<div class="col-md-6">
						<!--<label for="subscriptionplans" class="form-label">{{ __('lang.subscriptionplansduration')}} *</label>
						<select name="durationId" class="form-control" id="durationId"  required>
							<option value="">{{ __('lang.selectmonth')}}</option>
								@foreach($durations as $key=>$value)
									<option value="{{$value->id}}" {{ ( $value->id == $subscriptionData->duration_id) ? 'selected' : '' }}
									>{{$value->duration}}</option>
								@endforeach	
						</select> -->

						<label for="subscriptionplans" class="form-label">{{ __('lang.subscriptionplansduration')}} *</label>
						<div class="input-group">
							<select name="duration" class="form-select single-select" id="durationId">
							@foreach($durations as $key=>$value)
								<option value="{{$value->id}}" {{ ( $value->id == $subscriptionData->duration_id) ? 'selected' : '' }}> {{$value->duration}}</option>
								@endforeach		
							</select>
						 </div>
					</div>
				
					
					<div class="col-6">
											
					</div>
					
					
					<div class="col-12">
						<input type="hidden" name="id" value = "{{$subscriptionData->id}}">
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.editsubscription')}}</button>
						<button type="reset" class="btn btn-secondary px-5">{{ __('lang.reset')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->
 
@include('admin.layout.footer')