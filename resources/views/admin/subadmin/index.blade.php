@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('subadmin_manage','');

?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-user-circle"></i> {{ __('lang.subadmin')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
	    @if(helper::checkUserRights('subadmin_manage','subadmin_add'))
		<div class="btn-group">
			<a href="{{url('/admin/subadmin/create')}}" class="btn btn-primary"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addsub')}}</a>
		</div>
		@endif
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.allsubadmins')}}</h6>
		<hr/>

		<div class="card">
			<div class="card-body">
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
							<th scope="col">{{ __('lang.fullname')}}</th>
							<th scope="col">{{ __('lang.phonenumber')}}</th>
							<th scope="col">{{ __('lang.email')}}</th>
							<th scope="col" width="15%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
					@foreach($subadmindata as $key =>$subAdmindata)
						<tr> 
                            <td>{{$subAdmindata->firstName}} {{$subAdmindata->lastName}}</td>
                            <td>{{$subAdmindata->contactNumber}}</td>
							<td>{{$subAdmindata->email}}</td>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
									    @if(helper::checkUserRights('subadmin_manage','subadmin_edit'))
										<a class="dropdown-item" href="{{url('/admin/subadmin/'.$subAdmindata->id).'/edit'}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										@endif
										@if(helper::checkUserRights('subadmin_manage','subadmin_del'))
										<a class="dropdown-item" href="{{url('/admin/subadmin/'.$subAdmindata->id.'/delete')}}" onclick="return confirm('Are you sure you want to delete the SubAdmin?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
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
   "order": [[ 3, "asc" ]],
              'columnDefs': [{
                    "targets": [3],
                    "orderable": false
                }]
          });
</script>