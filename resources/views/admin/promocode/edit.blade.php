
@include('admin.layout.header')

 
 <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.editpromocode')}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('promocode.update')}}" method="post"  data-toggle="validator">
						@csrf
							<div class="row">
                              
                                <div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.promoname')}} *</label>
                                        <input type="text" name="promoName" value="{{$promocodeData->promoName}}" class="form-control" placeholder="{{ __('lang.enterpromoname')}}" data-error="{{ __('lang.req_promoname')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>    
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.promocodetype')}} *</label>
                                        <select class="form-control" data-error="{{ __('lang.req_promocodetype')}}" name="promocodeType" required>
   
											  <option>{{ __('lang.selectpromocodetype')}}</option>
												
											  @foreach ($promodata as $key => $value)
												<option value="{{ $value->id }}" {{ ( $value->id == $promocodeData->promocodeType) ? 'selected' : '' }}> 
													{{ $value->promoType }} 
												</option>
											  @endforeach    
										</select>
										
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.enterpercentageamountoff')}} *</label>
                                        <input type="text" name="offAmount" value="{{$promocodeData->offAmount}}" class="form-control" placeholder="{{ __('lang.eg2050')}}" data-error="{{ __('lang.req_percentageamountoff')}}" required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div> 
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.appliestocategory')}} *</label>
                                        <select class="selectpicker form-control" name="productsIds[]" multiple="">
										@foreach($productdata as $key=>$Producttype)
											<!--<option value="{{$Producttype->id}}" {{ ( $Producttype->id == $promocodeData->productsIds) ? 'selected' : '' }}>{{$Producttype->name}}</option>-->
											<option value="{{$Producttype->id}}" <?php if(in_array($Producttype->id,explode(",",$promocodeData->productsIds))) {echo 'selected';} ?>>{{$Producttype->name}}</option>
										@endforeach
									 
									</select>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-12">
                                    <label for="expiry">{{ __('lang.promocodeduration')}}</label>
                                </div> 
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.fromdate')}}</label>
                                        <input type="date" class="form-control" value="{{$promocodeData->promoFrom}}" data-error="{{ __('lang.req_promodate')}}" name="promoFrom" required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div> 
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.todate')}}</label>
                                        <input type="date" class="form-control" value="{{$promocodeData->promoTo}}" data-error="{{ __('lang.req_promodate')}}" name="promoTo" required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div> 
								<div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.vouchercode')}}</label>
                                        <input type="text" name="voucherCode" value="{{$promocodeData->voucherCode}}" class="form-control" placeholder="{{ __('lang.entervouchercode')}}" data-error="{{ __('lang.req_vouchercode')}}" required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                    
                                
                     
                            </div>   
							
						                          
							<input type="hidden" name="id" value = "{{$promocodeData->id}}">							
                            <button type="submit" class="btn btn-primary mr-2">{{ __('lang.editpromocode')}}</button>
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
    <!-- Wrapper End-->
@include('admin.layout.footer')