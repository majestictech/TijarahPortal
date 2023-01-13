@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/store')}}"><i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">{{ __('lang.editcategory')}}</li>
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
					<div><i class="bx bx-store-alt me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.editcategory')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" data-toggle="validator" method="post" action="{{route('category.update')}}" enctype="multipart/form-data">
						@csrf
                            <div class="col-md-6 ">
								<label for="storename" class="form-label">{{ __('lang.categoryname')}} *</label>
								<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
									<input type="text" name="name" value="{{$categoryData->name}}" class="form-control border-start-0" id="storename" placeholder="{{ __('lang.entercategoryname')}}" />
								</div>
							</div> 
							<div class="col-md-6 ">
								<label class="form-label">{{ __('lang.categoryname_ar')}} *</label>
								<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
									<input type="text" name="name_ar" value="{{$categoryData->name_ar}}" class="form-control border-start-0" placeholder="{{ __('lang.entercategoryname_ar')}}" />
								</div>
							</div> 							
                            <div class="col-md-6 ">
								<label class="form-label">{{ __('lang.categoryname_ur')}} *</label>
								<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
									<input type="text" name="name_ur" value="{{$categoryData->name_ur}}" class="form-control border-start-0" placeholder="{{ __('lang.entercategoryname_ur')}}" />
								</div>
							</div> 
							<div class="col-md-6 ">
								<label class="form-label">{{ __('lang.categoryname_ml')}} *</label>
								<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
									<input type="text" name="name_ml" value="{{$categoryData->name_ml}}" class="form-control border-start-0" placeholder="{{ __('lang.entercategoryname_ml')}}" />
								</div>
							</div>
 							<div class="col-md-6 ">
								<label class="form-label">{{ __('lang.categoryname_bn')}} *</label>
								<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
									<input type="text" name="name_bn" value="{{$categoryData->name_bn}}" class="form-control border-start-0" placeholder="{{ __('lang.entercategoryname_bn')}}" />
								</div>
							</div>
                            
                            <div class="col-6">
								<label for="category" class="form-label">{{ __('lang.category')}}</label>
								<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
									<?php echo $categoryList; ?>
								</div>
							</div>
							<div class="col-md-12">
								<label for="description" class="form-label">{{ __('lang.description')}}</label>
								<textarea class="form-control" name="description" id="description" rows="4">{{$categoryData->description}}</textarea>
							</div>
							<div class="col-md-12">
								<label for="descriptionar" class="form-label">{{ __('lang.description_ar')}}</label>
								<textarea class="form-control" name="description_ar" id="descriptionar" rows="4">{{$categoryData->description_ar}}</textarea>
							</div>
							<div class="col-md-12">
								<label for="descriptionur" class="form-label">{{ __('lang.description_ur')}}</label>
								<textarea class="form-control" name="description_ur" id="descriptionur" rows="4">{{$categoryData->description_ur}}</textarea>
							</div>
							<div class="col-md-12">
								<label for="descriptionml" class="form-label">{{ __('lang.description_ml')}}</label>
								<textarea class="form-control" name="description_ml" id="descriptionml" rows="4">{{$categoryData->description_ml}}</textarea>
							</div>
							<div class="col-md-12">
								<label for="descriptionml" class="form-label">{{ __('lang.description_bn')}}</label>
								<textarea class="form-control" name="description_bn" id="descriptionml" rows="4">{{$categoryData->description_bn}}</textarea>
							</div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('lang.image')}}</label>
								<div class="custom-file">
								<input type="file" name="catImage" data-error="{{ __('lang.req_image')}}" class="custom-file-input form-control">
								<label class="custom-file-label" for="customFile">Choose file</label>
								</div>
								<div class="col-md-5 row pt-4">
									<img src="{{URL::asset('public/category').'/'.$categoryData->catImage}}" width="100%">
								</div>
									
                             </div>
							 <div class="col-6">
								<label for="title" class="form-label">{{ __('lang.metatagtitle')}}</label>
								<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-map' ></i></span>
									<input type="text" name="metaTitle" value="{{$categoryData->metaTitle}}" class="form-control border-start-0" id="title" placeholder="{{ __('lang.metatagtitle')}}" />
								</div>
							</div>
							<div class="col-6">
								<label for="metatagdescription" class="form-label">{{ __('lang.metatagdescription')}}</label>
								<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-map' ></i></span>
									<input type="text" name="metatagdescription" value="{{$categoryData->metaDescription}}" class="form-control border-start-0" id="metatagdescription" placeholder="{{ __('lang.metatagdescription')}}" />
								</div>
							</div>
							<div class="col-6">
								<label for="metaKeyword" class="form-label">{{ __('lang.metatagkeyword')}}</label>
								<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-map' ></i></span>
									<input type="text" name="metaKeyword" value="{{$categoryData->metaKeyword}}" class="form-control border-start-0" id="metaKeyword" placeholder="{{ __('lang.metatagkeyword')}}" />
								</div>
							</div>
							<input type="hidden" name="storeId" value = "{{$categoryData->storeId}}" //>
							<div class="col-12">				
								<input type="hidden" name="id" value = "{{$categoryData->id}}" //>
								<button type="submit" class="btn btn-secondary px-5">{{ __('lang.editcategory')}}</button>
							</div>
                           
							
                            
                        </form></div>
		</div>		
	</div>
</div>
<!--end row-->
@include('admin.layout.footer')  