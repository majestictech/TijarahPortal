@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('storetype_manage','');

?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-store-alt"></i> {{ __('lang.storetype')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
	    @if(helper::checkUserRights('storetype_manage','storetype_add'))
		<div class="btn-group">
			<a href="{{url('/admin/storetype/create')}}" class="btn btn-primary"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addstoretype')}}</a>
		</div>
		@endif
	</div>
</div>
<!--end breadcrumb-->
<div class="row">

	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.allstoretype')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
			<form action="" method="GET" id ="filter_results">
				<div class="row">
					<div class="col-md-3 mb-3 ">
						<label for="search" class="form-label">{{ __('lang.search')}}</label>
						<input type="text" name="search" class="form-control form-control-sm" value="{{$search}}"/>                      
					</div>
					<div class="col-md-3 mb-3 pt-4">
						<label for="" class="form-label"></label>
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.search')}}</button>
					</div>  
				</div>
			</form>
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
							<th scope="col">{{ __('lang.storetype')}}</th>
							<th scope="col">{{ __('lang.type')}}</th>
							<th scope="col" width="15%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
					@foreach($storetype as $key =>$storetypedata)
						<tr>
                            <td>{{$storetypedata->name}}</td>
                            <td>{{$storetypedata->type}}</td>
                            <td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
									    @if(helper::checkUserRights('storetype_manage','storetype_edit'))
										<a class="dropdown-item" href="{{url('/admin/storetype/'.$storetypedata->id.'/edit')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										@endif
										@if(helper::checkUserRights('storetype_manage','storetype_edit'))
										<a class="dropdown-item" href="{{url('/admin/storetype/'.$storetypedata->id.'/delete')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.delete')}}</a>
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
   "order": [[ 2, "asc" ]],
              'columnDefs': [{
                    "targets": [2],
                    "orderable": false
                }]
          });
</script>