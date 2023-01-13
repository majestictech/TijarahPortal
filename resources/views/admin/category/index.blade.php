@include('admin.layout.header')							
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('cat_manage','');
?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-category"></i> {{ __('lang.categorylist')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
	    @if(helper::checkUserRights('cat_manage','cat_add'))
		<div class="btn-group">
			<a href="{{url('admin/category/create/')}}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>{{ __('lang.addcategory')}}</a>
		</div>
		@endif
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.categorylist')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
		        <form action="" method="GET" id ="filter_results">
			       <div class="row"> 
                	<div class="col-md-4 mb-3">
    					<label for="storeFilter" class="form-label">{{ __('lang.filterby')}}</label>
    					<div class="input-group">
    						<button class="btn btn-outline-secondary" type="button"><i class='bx bx-store'></i>
    						</button>
    						<select name="storeFilter" class="form-select single-select" id="storeFilter" onChange="this.form.submit();">
    							<option value="" @if(empty($storeFilter)) selected="selected" @endif>{{ __('lang.storetype')}}</option>
    								@foreach($storetype as $key=>$storetypes)
    								    <option value="{{ $storetypes->id }}" @if($storetypes->id==$storeFilter) selected="selected" @endif >{{ $storetypes->name }} ({!! App\Helpers\AppHelper::storeTypeCategory($storetypes->id) !!})</option>
    								@endforeach
    						</select>
    					</div>
    				</div>
    		        
                    </div>
			    </form>
			    
			    
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
                            <th width="15%">{{ __('lang.catimage')}}</th>
							<th>{{ __('lang.categoryname')}}</th>      
                            <th width="15%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					@foreach($categoryData as $key =>$category)
                        <tr>
                            <td><div class="d-flex align-items-center">
                                    <img src="{{URL::asset('public/category/100x100').'/'.$category->catImage}}"  class="img-fluid rounded catimage avatar-50 mr-3" alt="image"><br/>
                                    <!--<img src="data:image/png;base64,{{$category->catImgBase64}}"  class="img-fluid rounded catimage avatar-50 mr-3" alt="image"><br/>-->
                                </div>
									
								</td>
							
                            <td>{{$category->name}}</td>
                            <td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										@if(helper::checkUserRights('cat_manage','cat_edit'))
										<a class="dropdown-item" href="{{url('/admin/category/'.$category->id.'/edit')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										@endif
										@if(helper::checkUserRights('cat_manage','cat_del'))
										<a class="dropdown-item" href="{{route('category.destroy',['id'=>$category->id])}}" onclick="return confirm('Are you sure you want to delete the Category?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
										@endif
									</div>
								</div>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			    @if ($categoryDataCount > 0)
				<div class="pagination_links">
			    	{{$categoryData->appends(array('storeFilter'=>$storeFilter))->links()}}
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
   "order": [[ 2, "asc" ]],
              'columnDefs': [{
                    "targets": [0,2],
                    "orderable": false
                }]
          });
</script>
<style>
    .dataTables_filter,.dataTables_info,.dataTables_paginate,.dataTables_length {display:none;}
</style>