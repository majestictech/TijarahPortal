@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('customerslider_manage','customerslider_add');
?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/customerscreen')}}/{{$storeId}}"><i class="bx bx-category"></i> {{ __('lang.slider')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">{{ __('lang.addsliderimage')}}</li>
			</ol>
		</nav>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<div class="card border-top border-0 border-4 border-secondary">
			<div class="card-body p-5 ">
				<div class="card-title d-flex align-items-center">
					<div><i class="bx bx-category me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.addslider')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" data-toggle="validator" method="post" action="{{route('customerscreen.store')}}" enctype="multipart/form-data">
						@csrf
                            
                            <div class="col-md-6">
                                <label class="form-label">{{ __('lang.image')}} *</label>
								<div class="custom-file">
								<input type="file" name="customerScreenImage" data-error="{{ __('lang.req_image')}}" class="custom-file-input form-control" required>
								<label class="custom-file-label" for="customFile">Upload JPG/PNG Image (Pref. Size:200x200)</label>
								</div>
									
                             </div>
							 
							<div class="col-12">				
								<input type="hidden" name="storeId" value = "{{$storeId}}" //>
								<button type="submit" class="btn btn-secondary px-5">{{ __('lang.addsliderimage')}}</button>
							</div>
                           
							
                            
                        </form></div>
		</div>		
	</div>
</div>
<!--end row-->
@include('admin.layout.footer')  