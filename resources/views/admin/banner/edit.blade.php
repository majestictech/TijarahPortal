
@include('admin.layout.header')
 
 <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.editbanner')}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('banner.update')}}" data-toggle="validator" method ="post"  enctype="multipart/form-data">
        @csrf
                            <div class="row">
                              
                                <div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.bannertitle')}} *</label>
                                        <input type="text" value="{{$BannerData->bannerTitle}}" class="form-control" name="bannerTitle" placeholder="{{ __('lang.enterbannertitle')}}" data-error="{{ __('lang.req_bannertitle')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>  
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.category')}} *</label>
                                        <?php echo $categoryList; ?>
                                    </div>
                                </div>
                    
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.bannerimage')}} *</label>
                                        <!--<input type="file" class="form-control image-file" name="file" accept="image/*" required>-->
										<input type="file"  name="bannerImage" data-error="{{ __('lang.req_image')}}" class="image-file form-control" >
										<div class="col-md-5 row pt-4">
										<img src="{{URL::asset('public/images').'/'.$BannerData->bannerImage}}" width="100%">
										</div>
										<div class="help-block with-errors"></div>

                                    </div>
                                </div>
								<div class="col-md-12">
									<label>{{ __('lang.bannerduration')}}</label>
								</div>
								<div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.from')}}</label>
                                        <input type="date" value="{{$BannerData->bannerFrom}}" data-error="{{ __('lang.req_bannerduration')}}"  class="form-control" name="bannerFrom" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>   
								<div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.to')}}</label>
                                        <input type="date" value="{{$BannerData->bannerTo}}" data-error="{{ __('lang.req_bannerduration')}}"  class="form-control" name="bannerTo" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>   
								<div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.description')}}</label>
                                        <textarea class="form-control limitdesc" maxlength="250"   name="description" rows="4" required>{{$BannerData->description}}</textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
                                    </div>
                                </div>
                     
                            </div>   
						  <input type="hidden" name="id" value = "{{$BannerData->id}}">	
                            <button type="submit" class="btn btn-primary mr-2">{{ __('lang.editbanner')}}</button>
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