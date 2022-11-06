@include('admin.layout.header')
 <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.addslot')}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form data-toggle="validator" method="post" action="{{route('deliveryslot.store')}}" >
						@csrf
                            <div class="row"> 
                                <div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.slotname')}} *</label>
                                        <input type="text" name="slotName" class="form-control" placeholder="{{ __('lang.slotname')}}" data-error="{{ __('lang.req_name')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        
										<label>{{ __('lang.starttime')}} *</label>
                                        <input type="time" name="startingTime" data-error="{{ __('lang.req_email')}}" class="form-control" placeholder="{{ __('lang.starttime')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        
										<label>{{ __('lang.endtime')}} *</label>
                                        <input type="time" name="endingTime" data-error="{{ __('lang.req_email')}}" class="form-control" placeholder="{{ __('lang.endtime')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
															
								<!--<div class="col-md-6">
                                    <div class="form-group">
                                        
										<label>{{ __('lang.maxslot')}} *</label>
                                        <input type="number" name="maxSlot" data-error="{{ __('lang.req_email')}}" class="form-control" placeholder="{{ __('lang.maxslot')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								-->
		
								
		 
                            </div>   

							
                            <button type="submit" class="btn btn-primary mr-2">{{ __('lang.addslot')}}</button>
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