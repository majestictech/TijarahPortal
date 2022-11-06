@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('adminmanagement_manage','');

?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-user-circle"></i> {{ __('lang.adminmanagement')}}123</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<a href="{{url('/admin/adminmanagement/create')}}" class="btn btn-primary"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addadmin')}}</a>
		</div>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.alladmins')}}</h6>
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
					@foreach($adminmanagementdata as $key =>$adminManagementdata)
						<tr> 
                            <td>{{$adminManagementdata->firstName}} {{$adminManagementdata->lastName}}</td>
                            <td>{{$adminManagementdata->contactNumber}}</td>
							<td>{{$adminManagementdata->email}}</td>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										<a class="dropdown-item" href="{{url('/admin/adminmanagement/'.$adminManagementdata->id).'/edit'}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										<!--
										@if(helper::checkUserRights('adminmanagement_manage','adminmanagement_del'))
										<a class="dropdown-item" href="{{url('/admin/adminmanagement/'.$adminManagementdata->id.'/delete')}}" onclick="return confirm('Are you sure you want to delete the Admin?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
									    @endif
										-->
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
                    "targets": [3],
                    "orderable": false
                }]
          });
</script>