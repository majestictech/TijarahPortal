@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('store_manageusers_manage','store_manageusers_edit');
?>
 
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><a class="text-primary" href="{{url('/admin/store')}}"><i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a class="text-primary" href="{{url('admin/manageusers/').'/'.$userData->storeId}}"><i class="bx bx-user"></i> {{ __('lang.allstoreusers')}}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.edit')}}</li>
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
					<div><i class="bx bx-user me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.edituser')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" data-toggle="validator" method="post" action="{{route('cashier.update')}}" >
				@if($errors->any())
				<h4 class="error_msg">{{$errors->first()}}</h4>
				@endif
				@csrf
					<div class="col-md-6 ">
						<label for="firstname" class="form-label">{{ __('lang.firstname')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" autofocus="autofocus" onfocus="this.setSelectionRange(this.value.length,this.value.length);" name="firstName" class="form-control border-start-0" id="firstname" placeholder="{{ __('lang.firstname')}}" value="{{$userData->firstName}}" required />
						</div>
					</div>
					<div class="col-md-6">
						<label for="lastname" class="form-label">{{ __('lang.lastname')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" name="lastName" class="form-control border-start-0" id="lastname" placeholder="{{ __('lang.lastname')}}" value="{{$userData->lastName}}" />
						</div>
					</div>
					
					<div class="col-md-6">
						<label for="inputEmailAddress" class="form-label">{{ __('lang.email')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
							<input type="email" name="email" class="form-control border-start-0" id="inputEmailAddress" placeholder="{{ __('lang.email')}}" value="{{$userData->email}}" required/>
						</div>
					</div>
					<div class="col-md-6">
						<label for="contactnumber" class="form-label">{{ __('lang.contactnumber')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent">+966</span>
							<input type="number" name="contactNumber" class="form-control border-start-0" id="contactnumber" placeholder="{{ __('lang.contactnumber')}}" value="{{$userData->contactNumber}}" required/>
						</div>
					</div>

					<div class="col-md-6">
						<label for="chooseusers" class="form-label">{{ __('lang.userrole')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class="fadeIn animated bx bx-user-circle"></i></span>
							<select name="roleId" class="form-control" id="roleId"  required>
								<option value="">{{ __('lang.selectuserrole')}}</option>
								@foreach($massRoles as $key=>$massRole)
									<option value="{{$massRole->id}}" {{ ( $massRole->id == $userData->roleId) ? 'selected' : '' }} >{{$massRole->name}}</option>
								@endforeach
							</select>	
						</div>
					</div>	
					<div class="col-md-6">
						<label for="shiftname" class="form-label">{{ __('lang.shift')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-time'></i></span>
							<select name="shiftId" class="form-control" id="shiftId"  required>
								<option value="">{{ __('lang.shiftselect')}}</option>
								@foreach($shifts as $key=>$value)
									<option value="{{$value->id}}" {{ ( $value->id == $userData->shiftId) ? 'selected' : '' }} >{{$value->title}}</option>
									
								@endforeach
								
							</select>
						</div>
					</div>					
					
					<div class="col-6">
						<label for="inputChoosePassword" class="form-label">{{ __('lang.choosepassword')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-lock-open' ></i></span>
							<input type="password" class="form-control border-start-0" id="inputChoosePassword" placeholder="Choose Password"/>
						</div>
					</div>
					<div class="col-6">
						<label for="inputConfirmPassword" class="form-label">{{ __('lang.confirmpassword')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-lock' ></i></span>
							<input type="password" class="form-control border-start-0" id="inputConfirmPassword" placeholder="Confirm Password"/>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group ">
							  <label>{{ __('lang.status')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="active" value="Active" name="status" <?php if(isset($userData)){ if('Active' == $userData->status){ echo "checked"; } } ?>>
							  <label class="mb-0" for="active">
								{{ __('lang.active')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="Suspended" id="suspended" name="status" <?php if(isset($userData)){ if('Suspended' == $userData->status){ echo "checked"; } } ?>>
							  <label class="mb-0" for="suspended">
								{{ __('lang.suspended')}}
							  </label>
						</div>
					</div>
					
					<div class="col-12">
						<input type="hidden" name="id" value = "{{$userData->id}}">
						<input type="hidden" name="storeId" value = "{{$userData->storeId}}" />
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.edituser')}}</button>
						<button type="reset" class="btn btn-secondary px-5">{{ __('lang.reset')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->
 
@include('admin.layout.footer')