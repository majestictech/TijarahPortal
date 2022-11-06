@include('admin.layout.header')
 
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
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
					<div><i class="bx bx-user-circle me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.config_email')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" data-toggle="validator" method="post" action="{{route('configemail.update')}}"  >
				@csrf
				
				    <div class="col-md-12">
                        <input type="checkbox" id="sendemail" name="sendemail" value="sendemail">
                        <label for="sendemail" class="form-label"> Send email to admin</label><br>
					</div>

					<div class="col-md-6">
						<label for="inputEmailAddress" class="form-label">{{ __('lang.low_inventory')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>

                    @if($stores->lowInventory === null)
                        <input type="email" name="lowInventory" class="form-control border-start-0" id="inputEmailAddress" placeholder="{{ __('lang.email')}}" required />
		            @else
					    <input type="email" name="lowInventory" class="form-control border-start-0" id="inputEmailAddress" value="{{$stores->lowInventory}}" placeholder="{{ __('lang.email')}}" required />
                    @endif
							
						</div>
					</div>

					<div class="col-md-6">
						<label for="inputEmailAddress" class="form-label">{{ __('lang.dayend_report')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
						  @if($stores->dayEndReport === null)
							<input type="email" name="dayEndReport" class="form-control border-start-0" id="inputEmailAddress" placeholder="{{ __('lang.email')}}" required />
						  @else
							<input type="email" name="dayEndReport" class="form-control border-start-0" id="inputEmailAddress" value="{{$stores->dayEndReport}}" placeholder="{{ __('lang.email')}}" required />
						 @endif
						</div>
					</div>
					
					<div class="col-md-6">
						<label for="inputEmailAddress" class="form-label">{{ __('lang.all_report')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
						@if($stores->allReport === null)
							<input type="email" name="allReport" class="form-control border-start-0" id="inputEmailAddress" placeholder="{{ __('lang.email')}}" required />
						@else
							<input type="email" name="allReport" class="form-control border-start-0" id="inputEmailAddress" value="{{$stores->allReport}}" placeholder="{{ __('lang.email')}}" required />
						@endif
						</div>
					</div>
					
					<div class="col-12">
					    <input type="hidden" name="id" value = "{{$stores->id}}">
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.updateemails')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>




<!--end row-->
 
@include('admin.layout.footer')