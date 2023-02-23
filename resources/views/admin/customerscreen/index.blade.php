@include('admin.layout.header')							
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('customerslider_manage','');
?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-category"></i> {{ __('lang.sliderimagelist')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
	    @if(helper::checkUserRights('customerslider_manage'))
		<div class="btn-group">
			<a href="{{url('admin/customerscreen/create/')}}/{{$storeId}}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>{{ __('lang.addsliderimage')}}</a>
		</div>
		@endif
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.sliderimagelist')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
		        
			    
			    
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
                            <th>{{ __('lang.sliderimage')}}</th>
                            <th width="15%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					@foreach($sliderData as $key =>$slider)
                        <tr>
                            <td><div class="d-flex align-items-center">
                                    <img src="data:image/png;base64,{{$slider->image}}"  class="img-fluid rounded catimage avatar-50 mr-3" alt="image"><br/>
                                </div>
									
								</td>
							
                            <td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										@if(helper::checkUserRights('customerslider_manage','customerslider_edit'))
										<a class="dropdown-item" href="{{url('/admin/customerscreen/'.$slider->id.'/edit')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										@endif
										@if(helper::checkUserRights('customerslider_manage','customerslider_delete'))
										<a class="dropdown-item" href="{{route('customerscreen.destroy',['id'=>$slider->id])}}" onclick="return confirm('Are you sure you want to delete the Slider?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
										@endif
									</div>
								</div>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			    
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
                    "targets": [0,2],
                    "orderable": false
                }]
          });
</script>
<style>
    .dataTables_filter,.dataTables_info,.dataTables_paginate,.dataTables_length {display:none;}
</style>