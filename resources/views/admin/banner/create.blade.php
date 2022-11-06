
@include('admin.layout.header')
 
 <div class="content-page">
     <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('lang.addbanner')}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('banner.store')}}" data-toggle="validator" method ="post"  enctype="multipart/form-data">
        @csrf
                            <div class="row">
                              
                                <div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.bannertitle')}} *</label>
                                        <input type="text" class="form-control" name="bannerTitle" placeholder="{{ __('lang.enterbannertitle')}}" data-error="{{ __('lang.req_bannertitle')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>    
								<div class="col-md-6">    
								<div class="form-group">
									<label>{{ __('lang.category')}} *</label>
                                    <?php echo $categoryList; ?>
									<div class="help-block with-errors"></div>
                                </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.bannerimage')}} *</label>
                                        <!--<input type="file" class="form-control image-file" name="file" accept="image/*" required>-->
										<input type="file" name="bannerImage" data-error="{{ __('lang.req_image')}}" class="image-file form-control" required>
										<div class="help-block with-errors"></div>
                                    </div>
                                </div>
								<div class="col-md-12">
									<label>{{ __('lang.bannerduration')}}</label>
								</div>
								<div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.from')}}</label>
                                        <input type="date" class="form-control" data-error="{{ __('lang.req_bannerduration')}}"  name="bannerFrom" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>   
								<div class="col-md-6">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.to')}}</label>
                                        <input type="date" class="form-control" data-error="{{ __('lang.req_bannerduration')}}" name="bannerTo" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> 
								<div class="col-md-12">                      
                                    <div class="form-group">
                                        <label>{{ __('lang.description')}}</label>
                                        <textarea class="form-control limitdesc"  maxlength="250" name="description" rows="4" required></textarea>
										<div class="error">{{ __('lang.req_length')}}</div>
                                    </div>
                                </div> 								
                     
                            </div>                            
                            <button type="submit" class="btn btn-primary mr-2">{{ __('lang.addbanner')}}</button>
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