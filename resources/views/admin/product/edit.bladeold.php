@include('admin.layout.header')
 <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.editproduct')}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" action="{{route('product.update')}}" data-toggle="validator">
						@csrf
                            <div class="row">
                              
                                <div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.productname')}} *</label>
                                        <input type="text" name="name" value="{{$ProductData->name}}" class="form-control" placeholder="{{ __('lang.enterproductname')}}" data-error="{{ __('lang.req_productname')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>   
								
								
								<div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.productname_ar')}} *</label>
                                        <input type="text" name="name_ar"  value="{{$ProductData->name_ar}}" class="form-control" placeholder="{{ __('lang.enterproductname_ar')}}" data-error="{{ __('lang.req_productname_ar')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								<div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.productname_ur')}} *</label>
                                        <input type="text" name="name_ur" value="{{$ProductData->name_ur}}" class="form-control" placeholder="{{ __('lang.enterproductname_ur')}}" data-error="{{ __('lang.req_productname_ur')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								<div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.productname_ml')}} *</label>
                                        <input type="text" name="name_ml" value="{{$ProductData->name_ml}}" class="form-control" placeholder="{{ __('lang.enterproductname_ml')}}" data-error="{{ __('lang.req_productname_ml')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								<div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.productname_bn')}} *</label>
                                        <input type="text" name="name_bn" class="form-control" value="{{$ProductData->name_bn}}" placeholder="{{ __('lang.enterproductname_bn')}}" data-error="{{ __('lang.req_productname_bn')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
				
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.code')}} *</label>
                                        <input type="text" name="code" value="{{$ProductData->code}}" class="form-control" placeholder="{{ __('lang.entercode')}}" data-error="{{ __('lang.req_code')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.barcode')}} *</label>
                                        <input type="text" name="barCode" value="{{$ProductData->barCode}}" class="form-control" placeholder="{{ __('lang.enterbarcode')}}" data-error="{{ __('lang.req_barcode')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('lang.category')}} *</label>
                                        <?php echo $categoryList; ?>
                                    </div>
                                </div>
								
								
								<div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('lang.price')}} *</label>
                                        <input type="text" value="{{$ProductData->price}}" name="price" class="form-control" placeholder="{{ __('lang.enterprice')}} " data-errors="{{ __('lang.req_price')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('lang.brands')}} *</label>
                                        <select class="form-control" data-error="{{ __('lang.req_brand')}}" name="brandId" required>
										  <option>{{ __('lang.brandclass')}}</option>
										  @foreach ($brands as $key => $value)
											<option value="{{ $value->id }}" {{ ( $value->id == $ProductData->brandId) ? 'selected' : '' }}> 
												{{ $value->brandName }}
											</option>
										  @endforeach    
										</select>
										
										
										
                                    </div>
                                </div>
								
								
								<div class="col-md-12">
									<label>{{ __('lang.specialpricedaterange')}}</label>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.specialprice')}} </label>
                                        <input type="text" value="{{$ProductData->splPrice}}" name="splPrice" class="form-control">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('lang.from')}}</label>
                                        <input type="date" value="{{$ProductData->splPriceFrom}}" name="splPriceFrom" class="form-control">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('lang.to')}}</label>
                                        <input type="date" value="{{$ProductData->splPriceTo}}" name="splPriceTo" class="form-control">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.tax')}} *</label>
                                        <select class="form-control" data-error="{{ __('lang.req_weight')}}" name="taxClassId" required>
										  <option>{{ __('lang.taxclass')}}</option>
										  @foreach ($taxdata as $key => $value)
											<option value="{{ $value->id }}" {{ ( $value->id == $ProductData->taxClassId) ? 'selected' : '' }}> 
												{{ $value->name }} ({{ $value->value }}% )
											</option>
										  @endforeach    
										</select>
										
										
										
                                    </div>
                                </div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('lang.weight')}} *</label>
                                        <input type="text" value="{{$ProductData->weight}}" name="weight" class="form-control">
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('lang.weightclass')}} *</label>
                                        <select class="form-control" data-error="{{ __('lang.req_weight')}}" name="weightClassId" required>
										  <option>{{ __('lang.weightclass')}}</option>
										  @foreach ($weightdata as $key => $value)
											<option value="{{ $value->id }}" {{ ( $value->id == $ProductData->weightClassId) ? 'selected' : '' }}> 
												{{ $value->name }} 
											</option>
										  @endforeach    
										</select>
										
										
										
										
                                    </div>
                                </div>
                                <div class="col-md-6">                                    
                                    <div class="form-group">
                                        <label>{{ __('lang.minorderquantity')}} *</label>
                                        <input type="number" name="minOrderQty" value="{{$ProductData->minOrderQty}}" class="form-control" data-error="{{ __('lang.req_quantity')}}"  placeholder="{{ __('lang.enterminorderquantity')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								
								<div class="col-md-6">                                    
                                    <div class="form-group">
									  <label>{{ __('lang.status')}}</label><br/>
									  <input class="radio-input mr-2" type="radio" id="available" value="Available" <?php if(isset($ProductData)){ if('Available' == $ProductData->status){ echo "checked=checked"; } } ?> name="status">
									  <label class="mb-0" for="available">
										{{ __('lang.available')}}
									  </label>
									
									  <input class="checkbox-input mr-2 ml-2" type="radio" value="Not Available" id="not_available" <?php if(isset($ProductData)){ if('Not Available' == $ProductData->status){ echo "checked=checked"; } } ?> name="status">
									  <label class="mb-0" for="not_available">
										{{ __('lang.notavailable')}}
									  </label>
									</div>
																
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.image')}} *</label>
                                        <input type="file" class="form-control image-file" value="{{$ProductData->productImage}}" name="productImage" data-error="{{ __('lang.req_image')}}">
										<div class="col-md-5 row pt-4">
										<img src="{{URL::asset('public/products').'/'.$ProductData->productImage}}" width="100%">
										</div>
										
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.productdescdetails')}}</label>
                                        <textarea class="form-control limitdesc" maxlength="250"   name="description" rows="4">{{$ProductData->description}}</textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
                                    </div>
                                </div>
								 <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.productdescdetails_ar')}}</label>
                                        <textarea class="form-control limitdesc" maxlength="250" name="desArabic" rows="4">{{$ProductData->desArabic}}</textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
                                    </div>
                                </div>
								 <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.productdescdetails_ur')}}</label>
                                        <textarea class="form-control limitdesc" maxlength="250" name="desUrdu" rows="4">{{$ProductData->desUrdu}}</textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
                                    </div>
                                </div>
								 <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.productdescdetails_ml')}}</label>
                                        <textarea class="form-control limitdesc" maxlength="250" name="desMalay" rows="4">{{$ProductData->desMalay}}</textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
                                    </div>
                                </div>
								 <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.productdescdetails_bn')}}</label>
                                        <textarea class="form-control limitdesc" maxlength="250" name="desBengali" rows="4">{{$ProductData->desBengali}}</textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.producttags')}}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('lang.enterproducttags')}}" name="productTags" value="{{$ProductData->productTags}}" >
                                    </div>
                                </div>
								<div class="col-md-6"> 
									<div class="form-group">
										<label>{{ __('lang.metatagtitle')}}</label>
										<input type="text" value="{{$ProductData->metaTitle}}" class="form-control" name="metaTitle" placeholder="{{ __('lang.metatagtitle')}}" >
									</div>
								</div>
								<div class="col-md-6"> 
									<div class="form-group">
										<label>{{ __('lang.metatagdescription')}}</label>
										<input type="text" value="{{$ProductData->metaDescription}}" class="form-control" name="metaDescription" placeholder="{{ __('lang.metatagdescription')}}" >
									</div>
								</div>
								<div class="col-md-6"> 
									<div class="form-group">
										<label>{{ __('lang.metatagkeyword')}}</label>
										<input type="text" class="form-control" value="{{$ProductData->metaKeyword}}" name="metaKeyword" placeholder="{{ __('lang.metatagkeyword')}}">
									</div>
                            </div>  
							<input type="hidden" name="id" value = "{{$ProductData->id}}">	
							<button id="btnSubmit" type="submit" class="btn btn-primary mr-2">{{ __('lang.editproduct')}}</button>
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