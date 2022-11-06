@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
?>
  <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.addcategory')}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form data-toggle="validator" method="post" action="{{route('category.store')}}" enctype="multipart/form-data">
						@csrf
                            <div class="row">                              
                                <div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.categoryname')}} *</label>
                                        <input type="text" name="name" class="form-control" placeholder="{{ __('lang.entercategoryname')}}" data-error="{{ __('lang.req_categoryname')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.categoryname_ar')}} *</label>
                                        <input type="text" name="name_ar" class="form-control" placeholder="{{ __('lang.entercategoryname_ar')}}"data-error="{{ __('lang.req_categoryname_ar')}}" required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.categoryname_ur')}} *</label>
                                        <input type="text" name="name_ur" data-error="{{ __('lang.req_categoryname_ur')}}" class="form-control" placeholder="{{ __('lang.entercategoryname_ur')}}" required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.categoryname_ml')}} *</label>
                                        <input type="text" name="name_ml" data-error="{{ __('lang.req_categoryname_ml')}}" class="form-control" placeholder="{{ __('lang.entercategoryname_ml')}}" required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.categoryname_bn')}} *</label>
                                        <input type="text" name="name_bn" class="form-control" data-error="{{ __('lang.req_categoryname_bn')}}" placeholder="{{ __('lang.entercategoryname_bn')}}" required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.parentcategory')}}</label>
										<?php echo $categoryList; ?>
                                    </div>
                                </div>
								<div class="col-md-12"> 
									<div class="form-group">
										<label>{{ __('lang.description')}}</label>
										<textarea class="form-control limitdesc"  maxlength="250" name="description" rows="4"></textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
									</div>
								</div>
								<div class="col-md-12"> 
									<div class="form-group">
										<label>{{ __('lang.description_ar')}}</label>
										<textarea class="form-control limitdesc"  maxlength="250" name="description_ar" rows="4"></textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
									</div>
								</div>
								<div class="col-md-12"> 
									<div class="form-group">
										<label>{{ __('lang.description_ur')}}</label>
										<textarea class="form-control limitdesc"  maxlength="250" name="description_ur" rows="4"></textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
									</div>
								</div>
								<div class="col-md-12"> 
									<div class="form-group">
										<label>{{ __('lang.description_ml')}}</label>
										<textarea class="form-control limitdesc"  maxlength="250" name="description_ml" rows="4"></textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
									</div>
								</div>
								<div class="col-md-12"> 
									<div class="form-group">
										<label>{{ __('lang.description_bn')}}</label>
										<textarea class="form-control limitdesc"  maxlength="250" name="description_bn" rows="4"></textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
									</div>
								</div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.image')}} *</label>
                                        <input type="file" name="catImage" data-error="{{ __('lang.req_image')}}" class="image-file form-control" required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-6"> 
									<div class="form-group">
										<label>{{ __('lang.metatagtitle')}}</label>
										<input type="text" name="metaTitle" class="form-control" placeholder="{{ __('lang.metatagtitle')}}">
									</div>
								</div>
								<div class="col-md-6"> 
									<div class="form-group">
										<label>{{ __('lang.metatagdescription')}}</label>
										<input type="text" name="metaDescription" class="form-control" placeholder="{{ __('lang.metatagdescription')}}">
									</div>
								</div>
								<div class="col-md-6"> 
									<div class="form-group">
										<label>{{ __('lang.metatagkeyword')}}</label>
										<input type="text" name="metaKeyword" class="form-control" placeholder="{{ __('lang.metatagkeyword')}}">
									</div>
								</div>
                            </div>                            
                            <button type="submit" class="btn btn-primary mr-2">{{ __('lang.addcategory')}}</button>
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