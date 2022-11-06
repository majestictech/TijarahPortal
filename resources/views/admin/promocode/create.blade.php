@include('admin.layout.header')

 
 <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.addpromocode')}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('promocode.store')}}" method="post"  data-toggle="validator">
						@csrf
                            <div class="row">
                              
                                <div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.promoname')}} *</label>
                                        <input type="text" name="promoName" class="form-control" placeholder="{{ __('lang.enterpromoname')}}" data-error="{{ __('lang.req_promoname')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>    
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.promocodetype')}} *</label>
                                        <select name="promocodeType" data-error="{{ __('lang.req_promocodetype')}}" class="form-control" data-style="py-0" required>
                                            <option value="">{{ __('lang.selectpromocodetype')}}</option>
											@foreach($promotypedata as $key=>$Promotype)
												<option value="{{$Promotype->id}}">{{$Promotype->promoType}}</option>
											@endforeach
                
                                        </select>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.enterpercentageamountoff')}} *</label>
                                        <input type="text" name="offAmount" class="form-control" placeholder="{{ __('lang.eg2050')}}" data-error="{{ __('lang.req_percentageamountoff')}}" required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div> 
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.appliestocategory')}} *</label>
                                        <select class="selectpicker form-control" name="productsIds[]" multiple="">
										@foreach($productdata as $key=>$Producttype)
											<option value="{{$Producttype->id}}">{{$Producttype->name}}</option>
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
                                        <input type="date" class="form-control" data-error="{{ __('lang.req_promodate')}}" name="promoFrom" /required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div> 
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.todate')}}</label>
                                        <input type="date" class="form-control" data-error="{{ __('lang.req_promodate')}}"	 name="promoTo" /required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div> 
								<div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.vouchercode')}}</label>
                                        <input type="text" name="voucherCode" data-error="{{ __('lang.req_vouchercode')}}" class="form-control" placeholder="{{ __('lang.entervouchercode')}}"  required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                    
                                
                     
                            </div>                            
                            <button type="submit" class="btn btn-primary mr-2">{{ __('lang.addpromocode')}}</button>
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