@include('admin.layout.header')
    <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.editsalesman')}}</h4>
                        </div>
                    </div>
					
                    <div class="card-body">
                         <form action="{{route('salesman.update')}}" method = "post" data-toggle="validator">
						@csrf

							<div class="row"> 
                                <div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.fullname')}} *</label>
                                        <input type="text" name="fullName" value="{{$salesmanData->fullName}}" class="form-control" placeholder="{{ __('lang.enterfullname')}}" data-error="{{ __('lang.req_name')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        
										<label>{{ __('lang.email')}} *</label>
                                        <input type="text" name="email" data-error="{{ __('lang.req_email')}}" class="form-control" value="{{$salesmanData->email}}" placeholder="{{ __('lang.enteremail')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<!--<div class="col-md-6">
                                    <div class="form-group">
                                        
										<label>{{ __('lang.salesmanuniqueID')}}</label>
                                        <input type="text" name="uniqueId" value="{{$salesmanData->uniqueId}}" class="form-control" placeholder="{{ __('lang.enteruniqueID')}}" >
                
                                    </div>
                                </div>	-->
							
							<div class="col-md-6">
                                    <div class="form-group">
										<label for="gender">{{ __('lang.gender')}} *</label>
										<select class="form-control" name="gender" data-error="{{ __('lang.req_gender')}}" id="gender" required>
											<option value="">{{ __('lang.selectgender')}}</option>
											@foreach($Gender as $key=>$gender)
											<?php 
											$selected='';
											if(old("gender") && old("gender")==$key){
												$selected='selected="selected"';
											}elseif(isset($salesmanData) && $salesmanData->gender == $key){
												$selected='selected="selected"';
											}
											?>
											<option {{$selected}} value="{{$key}}" >{{$gender}}</option>
											@endforeach
										</select>	
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>

							
		
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.phonenumber')}} *</label>
                                        <input type="text" data-error="{{ __('lang.req_phoneno')}}" name="contactNumber" value="{{$salesmanData->contactNumber}}" class="form-control" placeholder="{{ __('lang.enterphoneno')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vehicleType">{{ __('lang.vehicletype')}} *</label>
										<select class="form-control" data-error="{{ __('lang.req_vehicletype')}}" name="vehicleType" required>
   
											  <option>{{ __('lang.selectvehicletype')}}</option>
												
											  @foreach ($vehicledata as $key => $value)
												<option value="{{ $value->id }}" {{ ( $value->id == $salesmanData->vehicleType) ? 'selected' : '' }}> 
													{{ $value->typeofvehicle }} 
												</option>
											  @endforeach    
											</select>
				
										<div class="help-block with-errors"></div>
                                    </div>
                                </div> 
								
	
								
								<div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.vehiclenumber')}} *</label>
                                        <input type="text" name="vehicleNumber" value="{{$salesmanData->vehicleNumber}}" class="form-control" placeholder="{{ __('lang.vehiclenumber')}}" data-error="{{ __('lang.req_vehiclenumber')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								
								
								<div class="col-md-12">
                                    <div class="form-group">
                                        
										<label>{{ __('lang.address')}} *</label>
                                        <textarea type="text" name="address" rows="4" cols="50" class="form-control" placeholder="{{ __('lang.enteraddress')}}" data-error="{{ __('lang.req_address')}}" required>{{$salesmanData->address}}</textarea>
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
										<input name="hoursOfServiceFrom" data-error="{{ __('lang.req_hoursofservice')}}" type="time" value="{{$salesmanData->hoursOfServiceFrom}}" class="form-control" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>{{ __('lang.to')}} *</label>
										<input name="hoursOfServiceTo" data-error="{{ __('lang.req_hoursofservice')}}" type="time" value="{{$salesmanData->hoursOfServiceTo}}" class="form-control" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								
								
		 
                            </div> 
							
							
							
							
							<input type="hidden" name="id" value = "{{$salesmanData->id}}">							
                            <button type="submit" class="btn btn-primary mr-2">{{ __('lang.editsalesman')}}</button>
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