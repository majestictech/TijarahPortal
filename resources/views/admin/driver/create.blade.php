@include('admin.layout.header')
 <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.adddriver')}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form data-toggle="validator" method="post" action="{{route('driver.store')}}" >
						@csrf
                            <div class="row"> 
                                <div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.fullname')}} *</label>
                                        <input type="text" name="fullName" class="form-control" placeholder="{{ __('lang.enterfullname')}}" data-error="{{ __('lang.req_name')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        
										<label>{{ __('lang.email')}} *</label>
                                        <input type="text" name="email" data-error="{{ __('lang.req_email')}}" class="form-control" placeholder="{{ __('lang.enteremail')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<!--<div class="col-md-6">
                                    <div class="form-group">
                                        
										<label>{{ __('lang.driveruniqueID')}} *</label>
                                        <input type="text" name="uniqueId" class="form-control" placeholder="{{ __('lang.enteruniqueID')}}">
                                    
                                    </div>
                                </div>-->								
								<div class="col-md-6">
                                    <div class="form-group">
										<label for="gender">{{ __('lang.gender')}} *</label>
											<select name="gender" class="form-control" data-error="{{ __('lang.req_gender')}}" id="gender" data-style="py-0"required>
											<option value="">{{ __('lang.selectgender')}}</option>
											@foreach($Gender as $key=>$gender)
												<option value="{{$key}}" >{{$gender}}</option>
											@endforeach
										</select>	
    
										<div class="help-block with-errors"></div>
                                    </div>
                                </div> 
		
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.phonenumber')}} *</label>
                                        <input type="text" name="contactNumber" data-error="{{ __('lang.req_phoneno')}}" class="form-control" placeholder="{{ __('lang.enterphoneno')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vehicleType">{{ __('lang.vehicletype')}} *</label>
                                        <select name="vehicleType" class="form-control" id="vehicleType" data-error="{{ __('lang.req_vehicletype')}}" required>
											<option value="">{{ __('lang.selectvehicletype')}}</option>
											@foreach($vehicledata as $key=>$Vehicletype)
												<option value="{{$Vehicletype->id}}" >{{$Vehicletype->typeofvehicle}}</option>
											@endforeach
										</select>	
										<div class="help-block with-errors"></div>
                                    </div>
                                </div> 
								
								<div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.vehiclenumber')}} *</label>
                                        <input type="text" name="vehicleNumber" data-error="{{ __('lang.req_vehiclenumber')}}" class="form-control" placeholder="{{ __('lang.vehiclenumber')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								
								
								<div class="col-md-12">
                                    <div class="form-group">
                                        
										<label>{{ __('lang.address')}} *</label>
                                        <textarea type="text" name="address" rows="4" data-error="{{ __('lang.req_address')}}" cols="50" class="form-control" placeholder="{{ __('lang.enteraddress')}}" required></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 


								
								<div class="col-md-12">
									<div class="form-group">
										<label>{{ __('lang.hoursofservice')}}</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>{{ __('lang.from')}} *</label>
										<input name="hoursOfServiceFrom" type="time" data-error="{{ __('lang.req_hoursofservice')}}" class="form-control" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>{{ __('lang.to')}} *</label>
										<input name="hoursOfServiceTo" type="time" data-error="{{ __('lang.req_hoursofservice')}}" class="form-control" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								
								
		 
                            </div>   

							
                            <button type="submit" class="btn btn-primary mr-2">{{ __('lang.adddriver')}}</button>
                            <button type="reset" class="btn btn-danger">{{ __('lang.reset')}}</button>
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