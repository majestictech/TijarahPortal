
@include('admin.layout.header')  
<div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.addreturn')}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form data-toggle="validator" method="post" action="{{route('return.store')}}" enctype="multipart/form-data">
						@csrf
                            <div class="row">   
								<div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.referenceno')}} *</label>
                                        <input type="text" name="referenceNo" class="form-control" placeholder="{{ __('lang.enterreferenceno')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 							
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dateOrder">{{ __('lang.date')}} *</label>
                                        <input type="date" class="form-control" data-error="{{ __('lang.req_date')}}" id="dateOrder" name="dateOrder" required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>  
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.ordernumber')}} *</label>
                                        <select name="orderNo" class="form-control" data-error="{{ __('lang.req_ordernumber')}}" data-style="py-0"required>
											<option value="">{{ __('lang.selectorder')}}</option>
											<option >#9088880</option>
											<option >#9088881</option>
											<option >#9088882</option>

										</select>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.attachdocument')}}</label>
										<input type="file" name="attachDoc" data-error="{{ __('lang.req_image')}}" class="image-file form-control">	
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.returnnote')}}</label>
                                        <div id="quill-tool">
                                            <button class="ql-bold" data-toggle="tooltip" data-placement="bottom" title="{{ __('lang.bold')}}"></button>
                                            <button class="ql-underline" data-toggle="tooltip" data-placement="bottom" title="{{ __('lang.underline')}}"></button>
                                            <button class="ql-italic" data-toggle="tooltip" data-placement="bottom" title="{{ __('lang.additalic')}}"></button>
                                            <button class="ql-code-block" data-toggle="tooltip" data-placement="bottom" title="{{ __('lang.showcode')}}"></button>
                                        </div>
                                        <div id="quill-toolbar" name="returnNote">
                                        </div>
                                    </div>
                                </div> 
                            </div>                            
                            <button type="submit" class="btn btn-primary mr-2">{{ __('lang.addreturn')}}</button>
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