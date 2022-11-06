@include('admin.layout.header')
 <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.addproduct')}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" action="{{route('product.store')}}" data-toggle="validator">
						@csrf
                            <div class="row">
                              
                                <div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.productname')}} *</label>
                                        <input type="text" name="name" class="form-control" placeholder="{{ __('lang.enterproductname')}}" data-error="{{ __('lang.req_productname')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>   
								
								
								<div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.productname_ar')}} *</label>
                                        <input type="text" name="name_ar" class="form-control" placeholder="{{ __('lang.enterproductname_ar')}}" data-error="{{ __('lang.req_productname_ar')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								<div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.productname_ur')}} *</label>
                                        <input type="text" name="name_ur" class="form-control" placeholder="{{ __('lang.enterproductname_ur')}}" data-error="{{ __('lang.req_productname_ur')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								<div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.productname_ml')}} *</label>
                                        <input type="text" name="name_ml" class="form-control" placeholder="{{ __('lang.enterproductname_ml')}}" data-error="{{ __('lang.req_productname_ml')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								<div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.productname_bn')}} *</label>
                                        <input type="text" name="name_bn" class="form-control" placeholder="{{ __('lang.enterproductname_bn')}}" data-error="{{ __('lang.req_productname_bn')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

	
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.code')}} *</label>
                                        <input type="text" name="code" class="form-control" placeholder="{{ __('lang.entercode')}}" data-error	="{{ __('lang.req_code')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.barcode')}} *</label>
                                        <input type="text" name="barCode" class="form-control" placeholder="{{ __('lang.enterbarcode')}}" data-error="{{ __('lang.req_barcode')}}" required>
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
                                        <input type="text" name="price" class="form-control" placeholder="{{ __('lang.enterprice')}} " data-error="{{ __('lang.req_price')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('lang.brands')}} *</label>
                                        <select name="brandId" class="form-control" id="brandId" data-error="{{ __('lang.req_brand')}}" required>
											<option value="">{{ __('lang.brandclass')}}</option>
											@foreach($brands as $key=>$Brands)
												<option value="{{$Brands->id}}" >{{$Brands->brandName}} </option>
											@endforeach
										</select>		
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
								
								<div class="col-md-12">
									<label>{{ __('lang.specialpricedaterange')}}</label>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.specialprice')}} </label>
                                        <input type="text" name="splPrice" class="form-control">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('lang.from')}}</label>
                                        <input type="date" name="splPriceFrom" class="form-control">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('lang.to')}}</label>
                                        <input type="date" name="splPriceTo" class="form-control">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.tax')}} *</label>
                                        <select name="taxClassId" class="form-control" id="taxClassId" data-error="{{ __('lang.req_weight')}}" required>
											<option value="">{{ __('lang.taxclass')}}</option>
											@foreach($taxdata as $key=>$Taxtype)
												<option value="{{$Taxtype->id}}" >{{$Taxtype->name}} ({{$Taxtype->value}}%)</option>
											@endforeach
										</select>	
										
										
										
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('lang.weight')}} *</label>
                                        <input type="text" name="weight" class="form-control">
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-3">
                                    <div class="form-group">
										<label for="weightClassId">{{ __('lang.weightclass')}} *</label>
                                        <select name="weightClassId" class="form-control" id="weightClassId" data-error="{{ __('lang.req_weight')}}" required>
											<option value="">Select Type</option>
											@foreach($weightdata as $key=>$Weighttype)
												<option value="{{$Weighttype->id}}" >{{$Weighttype->name}}</option>
											@endforeach
										</select>	
										
										
										
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                                    
                                    <div class="form-group">
                                        <label>{{ __('lang.minorderquantity')}} *</label>
                                        <input type="number" name="minOrderQty" class="form-control" placeholder="{{ __('lang.enterminorderquantity')}}" data-error="{{ __('lang.req_quantity')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-6">                                    
                                    <!--
									<div class="form-group">
                                        <label>{{ __('lang.status')}}</label><br/>
										
                                        <input type="radio" class="radio-input mr-2" id="checkbox1">
										<label for="checkbox1" class="mb-0">{{ __('lang.available')}}</label>
										
                                        <input type="radio" class="checkbox-input mr-2 ml-2" id="checkbox2">
										<label for="checkbox1" class="mb-0">{{ __('lang.notavailable')}}</label>
                                        <div class="help-block with-errors"></div>
                                    </div>
									-->									
									
									<div class="form-group">
									  <label>{{ __('lang.status')}}</label><br/>
									  <input class="radio-input mr-2" type="radio" id="available" value="Available" name="status" checked>
									  <label class="mb-0" for="available">
										{{ __('lang.available')}}
									  </label>
									
									  <input class="checkbox-input mr-2 ml-2" type="radio" value="Not Available" id="not_available" name="status">
									  <label class="mb-0" for="not_available">
										{{ __('lang.notavailable')}}
									  </label>
									</div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.image')}} *</label>
                                        <input type="file" class="form-control image-file" data-error="{{ __('lang.req_image')}}" name="productImage" required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.productdescdetails')}}</label>
                                        <textarea class="form-control limitdesc"  maxlength="250" name="description" rows="4"></textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
                                    </div>
                                </div>
								 <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.productdescdetails_ar')}}</label>
                                        <textarea class="form-control limitdesc" maxlength="250" name="desArabic" rows="4"></textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
                                    </div>
                                </div>
								 <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.productdescdetails_ur')}}</label>
                                        <textarea class="form-control limitdesc" maxlength="250" name="desUrdu" rows="4"></textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
                                    </div>
                                </div>
								 <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.productdescdetails_ml')}}</label>
                                        <textarea class="form-control limitdesc" maxlength="250" name="desMalay" rows="4"></textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
                                    </div>
                                </div>
								 <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.productdescdetails_bn')}}</label>
                                        <textarea class="form-control limitdesc" maxlength="250" name="desBengali" rows="4"></textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.producttags')}}</label>
                                        <input type="text" class="form-control" placeholder="{{ __('lang.enterproducttags')}}" name="productTags">
                                       
                                    </div>
                                </div>
								<div class="col-md-6"> 
									<div class="form-group">
										<label>{{ __('lang.metatagtitle')}}</label>
										<input type="text" class="form-control" name="metaTitle" placeholder="{{ __('lang.metatagtitle')}}">
									</div>
								</div>
								<div class="col-md-6"> 
									<div class="form-group">
										<label>{{ __('lang.metatagdescription')}}</label>
										<input type="text" class="form-control" name="metaDescription" placeholder="{{ __('lang.metatagdescription')}}">
									</div>
								</div>
								<div class="col-md-6"> 
									<div class="form-group">
										<label>{{ __('lang.metatagkeyword')}}</label>
										<input type="text" class="form-control" name="metaKeyword" placeholder="{{ __('lang.metatagkeyword')}}">
									</div>
                            </div>  
							<button id="btnSubmit" type="submit" class="btn btn-primary mr-2">{{ __('lang.addproduct')}}</button>
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
<script type="text/javascript">
$(".limitdesc").keypress(function() {
    if($(this).val().length > 250) {
		var description = $(this).val();
		$(this).val(description.substring(0, 250));
		$(this).parent().closest('div').children('.error').html("Length should be less than 250")
   }
});
</script>