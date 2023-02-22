@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
// helper::checkUserURLAccess('adminmanagement_manage','');

?>

<!--breadcrumb-->

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-user-circle"></i> {{ __('lang.usersmanagement')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<a href="{{url('admin/usersmanagement/create')}}" class="btn btn-primary"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.adduser')}}</a>
		</div>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.allusers')}}</h6>
		<hr/>

		<div class="card">
			<div class="card-body">
				<form action="" method="GET" id ="filter_results">
					<div class="row">
						<div class="col-md-3 mb-3">
							<label for="roleFilter" class="form-label">{{ __('lang.filterby')}}</label>
							<div class="input-group">
								<button class="btn btn-outline-secondary" type="button"><i class='bx bx-user-circle'></i>
								</button>
								<select name="roleFilter" class="form-select single-select" id="roleFilter" onChange="this.form.submit();">
									<option value="" >{{ __('lang.selectuserrole')}}</option>
									@foreach($masRoles as $key=>$masRole)

										<option value="{{$masRole->id}}" @if($masRole->id==$roleFilter) selected="selected" @endif> {{$masRole->name}} </option>
									@endforeach
								</select>
							</div>
						</div>
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
				<!--<table class="table mb-0 table-striped table-bordered" id="myTable">-->
				<table class="table mb-0 table-striped table-bordered" id="">
					<thead>
						<tr>
							<th scope="col">{{ __('lang.fullname')}}</th>
							<th scope="col">{{ __('lang.phonenumber')}}</th>
							<th scope="col">{{ __('lang.email')}}</th>
							<th scope="col">{{ __('lang.user')}}</th>
							<th scope="col" width="15%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
					@foreach($usersmanagementdata as $key =>$usersManagementData)
						<tr> 
                            <td><!-- {{$usersManagementData->roleId}} -->{{$usersManagementData->firstName}} {{$usersManagementData->lastName}}</td>
                            <td>{{$usersManagementData->contactNumber}}</td>
							<td>{{$usersManagementData->email}}</td>
							<td>
								@foreach($masRoles as $key =>$masRole)
									<?php
									echo ($usersManagementData->roleId == $masRole->id) ?  $masRole->name: "";
									?>
								@endforeach
							</td>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										<a class="dropdown-item" href="{{url('/admin/usersmanagement/'.$usersManagementData->id).'/edit'}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										<!--
										@if(helper::checkUserRights('adminmanagement_manage','adminmanagement_del'))
										<a class="dropdown-item" href="{{url('/admin/adminmanagement/'.$usersManagementData->id.'/delete')}}" onclick="return confirm('Are you sure you want to delete the Admin?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
									    @endif
										-->
									</div>
								</div>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				
				{{ $usersmanagementdata->appends(array('search' => $search,'roleFilter'=>$roleFilter))->links() }}
			</div>
		</div>
	</div>
</div>
<!--end row-->

@include('admin.layout.footer')

<script>
	/*
var table = $('#myTable').DataTable({
   "order": [[ 4, "asc" ]],
              'columnDefs': [{
                    "targets": [3],
                    "orderable": false
                }]
          });*/
</script>
<style>
   /* .dataTables_filter {display:none;}
    .dropdown-menu,.dropdown-menu.dropdown-menu-right.dropdown-menu-lg-end {  margin-bottom: 20px !important;}*/
</style>
