@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('consumers_manage','consumers_edit');
?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/store')}}"><i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/customer/'.$customer->sId)}}"><i class="bx bx-group"></i> {{ __('lang.customers')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.editcustomer')}}</li>
			</ol>
		</nav>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<!--<h6 class="mb-0 text-uppercase">Form with icons</h6> -->
		<hr/>
		
		<div class="card border-top border-0 border-4 border-secondary">
			<div class="card-body p-5 ">
				<div class="card-title d-flex align-items-center">
					<div><i class="bx bx-store-alt me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.editcustomer')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" method="post" action="{{route('customer.update')}}" data-toggle="validator">
				@if($errors->any())
				<h4 class="error_msg">{{$errors->first()}}</h4>
				@endif
				@csrf
					<div class="col-md-6 ">
						<label for="name" class="form-label">{{ __('lang.name')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" autofocus="autofocus" onfocus="this.setSelectionRange(this.value.length,this.value.length);" name="customerName" class="form-control border-start-0" id="name" placeholder="{{ __('lang.name')}}" value="{{$customer->customerName}}" required>
						</div>
					</div>
					<div class="col-md-6">
						<label for="inputEmailAddress" class="form-label">{{ __('lang.email')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
							<input type="email" name="email" class="form-control border-start-0" id="inputEmailAddress" placeholder="{{ __('lang.email')}}" value="{{$customer->email}}" required>
						</div>
					</div>
					
					<div class="col-md-6">
						<label for="contactnumber" class="form-label">{{ __('lang.contactnumber')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent">+966</span>
							<input type="number" name="contactNumber" class="form-control border-start-0" id="contactnumber" placeholder="{{ __('lang.contactnumber')}}" value="{{$customer->contactNumber}}" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  maxlength="9" required />
						</div>
					</div>
					<div class="col-md-6">
						<label for="customerVat" class="form-label">{{ __('lang.vatnumber')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="number" name="customerVat" class="form-control border-start-0" id="customerVat" placeholder="{{ __('lang.vatnumber')}}" value="{{$customer->customerVat}}"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  maxlength="15" minlength="15"  />
						</div>
					</div>
					
					<div class="col-md-6">
						<label for="dob" class="form-label">{{ __('lang.dob')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
							<input type="date" class="form-control border-start-0" name="dob" id="dob" placeholder="{{ __('lang.dob')}}" value="{{$customer->dob}}" required>
						</div>
					</div>
					
					<div class="col-md-6">
						<label for="doa" class="form-label">{{ __('lang.doa')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-id-card'></i></span>
							<input type="date" class="form-control border-start-0" name="doa" id="doa" placeholder="Date of Anniversary" value="{{$customer->doa}}" />
						</div>
					</div>
					
					<div class="col-12">
						<label for="inputAddress" class="form-label">{{ __('lang.address')}} *</label>
						<textarea class="form-control" name="address" id="inputAddress" placeholder="Enter Address" rows="3" required>{{$customer->address}}</textarea>
					</div>
					
					<div class="col-12">
						<input type="hidden" name="id" value = "{{$customer->id}}">
						<input type="hidden" name="storeId" value = "{{$customer->storeName}}" />
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.editcustomer')}}</button>
						<button type="reset" class="btn btn-primary px-5">{{ __('lang.reset')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->

@include('admin.layout.footer')