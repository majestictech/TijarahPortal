@include('admin.layout.header')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/shift')}}"><i class="bx bx-box"></i> {{ __('lang.shift')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.editshift')}}</li>
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
					<div><i class="bx bx-time me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.editshift')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" method="post" action="{{route('shift.update')}}" data-toggle="validator">
				@if($errors->any())
				<h4 class="error_msg">{{$errors->first()}}</h4>
				@endif
				@csrf
				  	<div class="col-md-12 ">
						<label for="name" class="form-label">{{ __('lang.title')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-box'></i></span>
							<input type="text" autofocus="autofocus" onfocus="this.setSelectionRange(this.value.length,this.value.length);" name="title" value="{{$shift->title}}" class="form-control border-start-0" id="title" placeholder="{{ __('lang.title')}}" required>
						</div>
					</div>
					
					
					<div class="col-12">
						<label for="shifttime" class="form-label">{{ __('lang.shifttime')}} *</label>
					</div>
					<div class="col-6">
						<label for="from" class="form-label">{{ __('lang.from')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-time' ></i></span>
							<input type="time" name="hoursOfServiceFrom" value="{{$shift->hoursOfServiceFrom}}" class="form-control border-start-0" id="from" required  />
						</div>
					</div>
					<div class="col-6">
						<label for="to" class="form-label">{{ __('lang.to')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-time' ></i></span>
							<input type="time" name="hoursOfServiceTo" value="{{$shift->hoursOfServiceTo}}" class="form-control border-start-0" id="to" required  />
						</div>
					</div>
					
					<div class="col-12">
						<input type="hidden" name="id" value = "{{$shift->id}}">
						<input type="hidden" name="storeId" value = "{{$shift->storeId}}" //>
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.editshift')}}</button>
						<button type="reset" class="btn btn-primary px-5">{{ __('lang.reset')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->
@include('admin.layout.footer')    
 