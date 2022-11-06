@include('admin.layout.header')
    <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.editdeliveryslot')}}</h4>
                        </div>
                    </div>
					
                    <div class="card-body">
                         <form action="{{route('deliveryslot.update')}}" method = "post" data-toggle="validator">
						@csrf
              

							<div class="row"> 
                                <div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.slotname')}} *</label>
                                        <input type="text" value="{{$DeliveryData->slotName}}" name="slotName" class="form-control" placeholder="{{ __('lang.slotname')}}" data-error="{{ __('lang.req_name')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        
										<label>{{ __('lang.starttime')}} *</label>
                                        <input type="time" value="{{$DeliveryData->startingTime}}" name="startingTime" data-error="{{ __('lang.req_email')}}" class="form-control" placeholder="{{ __('lang.starttime')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        
										<label>{{ __('lang.endtime')}} *</label>
                                        <input type="time" value="{{$DeliveryData->endingTime}}" name="endingTime" data-error="{{ __('lang.req_email')}}" class="form-control" placeholder="{{ __('lang.endtime')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
															
								<!--<div class="col-md-6">
                                    <div class="form-group">
                                        
										<label>{{ __('lang.maxslot')}} *</label>
                                        <input type="number" value="{{$DeliveryData->maxSlot}}" name="maxSlot" data-error="{{ __('lang.req_email')}}" class="form-control" placeholder="{{ __('lang.maxslot')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								-->
								
		 
                            </div>  




							
							<input type="hidden" name="id" value = "{{$DeliveryData->id}}">							
                            <button type="submit" class="btn btn-primary mr-2">Edit Slot</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
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