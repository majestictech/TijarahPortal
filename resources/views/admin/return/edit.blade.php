
@include('admin.layout.header')  
<div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.editreturn')}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('return.update')}}" method="post" data-toggle="validator" enctype="multipart/form-data">
						@csrf
                            <div class="row">   
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.referenceno')}} *</label>
                                        <input type="text" readonly value="{{$returnData->referenceNo}}" name="referenceNo" class="form-control" placeholder="{{ __('lang.enterreferenceno')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 							
                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.ordernumber')}} *</label>
                                        <select class="form-control" data-error="{{ __('lang.req_ordernumber')}}" name="orderId" required>
										<option>{{ __('lang.selectorder')}}</option>
										@foreach ($orderdata as $key => $value)
											<option value="{{ $value->id }}" {{ ( $value->id == $returnData->orderId) ? 'selected' : '' }}> 
												{{ $value->orderId }} 
											</option>
										@endforeach    
										</select>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="returnStatus">{{ __('lang.returntype')}} *</label>
										<select class="form-control" data-error="{{ __('lang.req_returntype')}}" name="returnStatus" required>
   
											  <option>{{ __('lang.selectreturntype')}}</option>
											@foreach ($returnstatus as $key => $value)
												<option value="{{ $value->id }}" {{ ( $value->id == $returnData->returnStatus) ? 'selected' : '' }}> 
													{{ $value->name }} 
												</option>
											  @endforeach    
										</select>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div> 
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.returnnote')}}</label>
                                        <textarea class="form-control limitdesc" maxlength="250" name="returnNote" rows="4">{{$returnData->returnNote}}</textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
                                    </div>
                                </div>
                                <!--<div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.returnnote')}}</label>
                                        <div id="quill-tool">
                                            <button class="ql-bold" data-toggle="tooltip" data-placement="bottom" title="{{ __('lang.bold')}}"></button>
                                            <button class="ql-underline" data-toggle="tooltip" data-placement="bottom" title="{{ __('lang.underline')}}"></button>
                                            <button class="ql-italic" data-toggle="tooltip" data-placement="bottom" title="{{ __('lang.additalic')}}"></button>        
                                            <button class="ql-code-block" data-toggle="tooltip" data-placement="bottom" title="{{ __('lang.showcode')}}"></button>
                                        </div>
                                        <div id="quill-toolbar" name="returnNote" value="{{$returnData->returnNote}}" style="height:100px;">
										{{$returnData->returnNote}}
                                        </div>
                                    </div>
                                </div> -->
                            </div>   
							<input type="hidden" name="id" value = "{{$returnData->id}}">							
                            <button type="submit" class="btn btn-primary mr-2">{{ __('lang.editreturn')}}</button>
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