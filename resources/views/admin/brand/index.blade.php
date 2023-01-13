@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('brand_manage','');

?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bxl-bootstrap"></i> {{ __('lang.brands')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
	    @if(helper::checkUserRights('brand_manage','brand_add'))
		<div class="btn-group">
			<a href="{{url('/admin/brand/create')}}" class="btn btn-primary"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addbrand')}}</a>
		</div>
		@endif
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.brands')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
                            <th>{{ __('lang.id')}}</th>
                            <th>{{ __('lang.brandtitle')}}</th>
                            <th>{{ __('lang.brandimage')}}</th>
							<th>{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
					@foreach($brand as $key =>$BrandData)
						<tr>
                            <td>{{ ++$key }}</td>
                            <td>{{$BrandData->brandName}}</td>
                            <td>
                                <?php if($BrandData->brandImage == '' ||  $BrandData->brandImage == null){?>
                                    <img src="{{URL::asset('public/images/no-image.jpg')}}" style="border:1px solid;">
                              
                                <?php } ?>
                                
                                <?php if($BrandData->brandImage != '' ){?>
                                    <img src="{{URL::asset('public/brand').'/'.$BrandData->brandImage}}" class="img-responsive mr-3" width="200"alt="image">
                                <?php } ?>                               
                                
                            </td>

                            <td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
									    @if(helper::checkUserRights('brand_manage','brand_edit'))
										<a class="dropdown-item" href="{{url('/admin/brand/'.$BrandData->id.'/edit')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										@endif
										<!--<a class="dropdown-item" href="{{route('brand.destroy',['id'=>$BrandData->id])}}" onclick="return confirm('Are you sure you want to delete the Brand?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>-->
									</div>
								</div>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				 @if ($brandcount > 0)
    				<div class="pagination_links">
    				{{$brand->links()}}
    				

    				</div>
				@endif
		
			</div>
		</div>
	</div>
</div>
<!--end row-->

@include('admin.layout.footer')
<script>
var table = $('#myTable').DataTable({
   "order": [[ 1, "asc" ]],
              'columnDefs': [{
                    "targets": [2,3],
                    "orderable": false
                }]
          });
</script>
<style>
    .dataTables_filter,.dataTables_info,.dataTables_paginate,.dataTables_length {display:none;}
</style>