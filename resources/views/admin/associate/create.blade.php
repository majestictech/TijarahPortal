@include('admin.layout.header')
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
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/subadmin')}}"><i class="bx bx-user-circle"></i> {{ __('lang.associate')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addassociate')}}</li>
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
					<h5 class="mb-0 text-primary">{{ __('lang.addassociate')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" data-toggle="validator" method="post" action="{{route('associate.store')}}" >
				@if($errors->any())
				<h4 class="error_msg">{{$errors->first()}}</h4>
				@endif
				@csrf
					<div class="col-md-6 ">
						<label for="firstname" class="form-label">{{ __('lang.firstname')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" autofocus="autofocus" name="firstName" class="form-control border-start-0" id="firstname" placeholder="{{ __('lang.firstname')}}" required />
						</div>
					</div>
					<div class="col-md-6">
						<label for="lastname" class="form-label">{{ __('lang.lastname')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" name="lastName" class="form-control border-start-0" id="lastname" placeholder="{{ __('lang.lastname')}}" required />
						</div>
					</div>
					<div class="col-md-6">
						<label for="contactnumber" class="form-label">{{ __('lang.contactnumber')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent">+966</span>
							<input type="number" name="contactNumber" class="form-control border-start-0" id="contactnumber" placeholder="{{ __('lang.contactnumber')}}" required />
						</div>
					</div>
					<div class="col-md-6">
						<label for="inputEmailAddress" class="form-label">{{ __('lang.email')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
							<input type="email" name="email" class="form-control border-start-0" id="inputEmailAddress" placeholder="{{ __('lang.email')}}" required />
						</div>
					</div>
					<div class="col-6">
						<label for="inputChoosePassword" class="form-label">{{ __('lang.choosepassword')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-lock-open' ></i></span>
							<input type="password" name="password" class="form-control border-start-0" id="inputChoosePassword" placeholder="Choose Password" required />
						</div>
					</div>
					<div class="col-6">
						<label for="inputConfirmPassword" class="form-label">{{ __('lang.confirmpassword')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-lock' ></i></span>
							<input type="password" name="passwordConfirmation" class="form-control border-start-0" id="inputConfirmPassword" placeholder="Confirm Password" required />
						</div>
					</div>
					
					<div class="col-12">
					    <label class="form-label">{{ __('lang.cashier')}}</label>
                            <input type="checkbox" class="check" id="cashier"/><br>

                         
                         <label class="form-label">{{ __('lang.create')}}</label>
                        <input  class="questionCheckBox" type="checkbox"/>
                        
                        <label class="form-label">{{ __('lang.edit')}}</label>
                        <input class="questionCheckBox" type="checkbox" />
                        
                        <label class="form-label">{{ __('lang.delete')}}</label>
                        <input class="questionCheckBox" type="checkbox" />
                    </div>
                					
					<div class="col-12">
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.addassociate')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->


    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
    $(function () {
        $('.check').on('change', function () {
            
            if(this.checked){
                 $('.questionCheckBox').prop('checked',true);
            }
            else{
                $('.questionCheckBox').prop('checked',false);
            }
        });
    });
    
    $(function (){
        $('.questionCheckBox').on('change', function()
        {
            if(this.checked){
                 $('.check').prop('checked',true);
            }
        });
        
    });
  </script> 

 
@include('admin.layout.footer')