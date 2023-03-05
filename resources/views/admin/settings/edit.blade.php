@include('admin.layout.header')
    <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.editprofile')}}</h4>
                        </div>
                    </div>
					
                    <div class="card-body">
                         <form action="{{route('settings.update')}}" method = "post" data-toggle="validator">
                @if($errors->any())
				<h4 class="error_msg">{{$errors->first()}}</h4>
				@endif
						@csrf
              

							<div class="row"> 
								<div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.firstname')}}*</label>
                                        <input type="text" name="firstName" value="{{Auth::user()->firstName}}" class="form-control" placeholder="Enter First Name" data-error="{{ __('lang.req_name')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.lastname')}}*</label>
                                        <input type="text" name="lastName" value="{{Auth::user()->lastName}}" class="form-control" placeholder="Enter Last Name" data-error="{{ __('lang.req_name')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        
										<label>{{ __('lang.email')}} *</label>
                                        <input type="text" name="email" class="form-control" value="{{Auth::user()->email}}" placeholder="{{ __('lang.enteremail')}}" data-error="{{ __('lang.req_email')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.phonenumber')}} *</label>
                                        <input type="text" name="contactNumber" data-error="{{ __('lang.req_phoneno')}}" value="{{Auth::user()->contactNumber}}" class="form-control" placeholder="{{ __('lang.enterphoneno')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								<div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.password')}}</label>
                                        <input type="password" name="password" class="form-control" placeholder="{{ __('lang.enterpassword')}}">
                                        
                                    </div>
                                </div> 
                                
                                <div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.reenterpass')}}</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter Password">
                                        
                                    </div>
                                </div> 
                                <div class="col-md-12 mb-4">                      
                                    <div class="form-group">
                                        <label>{{__('lang.image')}}</label>
                                        <input type="file" name="myfile" class="form-control" placeholder="Choose your Picture">
                                        
                                    </div>
                                </div> 

								 <div class="col-md-12"><button type="submit" class="btn btn-primary mr-2">{{ __('lang.updateprofile')}}</button></div>
                              </div>
                        </form>
						
                    </div>
                </div>
            </div>
        </div>
        <!-- Page end  -->
    </div>
      </div>
    </div>
@include('admin.layout.footer')