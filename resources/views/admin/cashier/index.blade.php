@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('cashier_manage','');
helper::checkStoreId($storeId);
?>


<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><a class="text-primary" href="{{url('/admin/store')}}"><i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-user"></i> {{ __('lang.allstoreusers')}}</li>
				
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
	   
		<div class="btn-group">
			<a href="{{url('admin/manageusers/create/').'/'.$storeId}}" class="btn btn-primary add-list"><i class="bx bx-list-plus mr-3"></i>{{ __('lang.adduser')}}</a>
		</div>
		
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.allstoreusers')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<!--Filter Start-->
				<form action="" method="GET" id ="filter_results">
			      	<div class="row"> 
						<div class="col-md-3 mb-3">
							<label for="roleFilter" class="form-label">{{ __('lang.filterby')}}</label>
							<div class="input-group">
								<button class="btn btn-outline-secondary" type="button"><i class='bx bx-user'></i>
								</button>
								<select name="roleFilter" class="form-select single-select" id="roleFilter" onChange="this.form.submit();">
									<option value="" >{{ __('lang.allstoreusers')}}</option>
									@foreach($massRoles as $key=>$massRole)
									<option value="{{$massRole->id}}" @if($massRole->id==$roleFilter && $roleFilter != '7, 9, 10, 13') selected="selected" @endif >{{$massRole->name}}</option>
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


				<!--Filter End-->
				<table class="table mb-0 table-striped table-bordered" id="">
					<thead>
						<tr>
							<th scope="col">{{ __('lang.fullname')}}</th>
							<th scope="col">{{ __('lang.phonenumber')}}</th>
							<th scope="col">{{ __('lang.email')}}</th>
						    <?php if(Auth::user()->roleId != 4 ){?>
							<th>{{ __('lang.role')}}</th>
							<?php } ?>
							<th scope="col" width="15%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
					@foreach($usersdata as $key =>$user)
					@if($user->status==='Active')
						<tr> 
                            <td>{{$user->firstName}} {{$user->lastName}}</td>
                            <td>{{$user->contactNumber}}</td>
							<td>{{$user->email}}</td>
							<?php if(Auth::user()->roleId != 4 ){?>
							<td>{{$user->role}}</td>
							<?php } ?>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
									    
										<a class="dropdown-item" href="{{url('/admin/manageusers/'.$user->id).'/edit'}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										
										<a class="dropdown-item" href="{{route('cashier.destroy',['id'=>$user->id])}}" onclick="return confirm('Are you sure you want to delete the user?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
									    
									</div>
								</div>
							</td>
						</tr>
						@else
						<tr style="background-color:#ffcccc;"> 
                            <td>{{$user->firstName}} {{$user->lastName}}</td>
                            <td>{{$user->contactNumber}}</td>
							<td>{{$user->email}}</td>
							<?php if(Auth::user()->roleId != 4 ){?>
							<td>{{$user->role}}</td>
							<?php } ?>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										<a class="dropdown-item" href="{{url('/admin/manageusers/'.$user->id).'/edit'}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										<a class="dropdown-item" href="{{route('cashier.destroy',['id'=>$user->id])}}" onclick="return confirm('Are you sure you want to delete the user?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
									</div>
								</div>
							</td>
						</tr>
						@endif
						
					@endforeach
					</tbody>
				</table>
				{{ $usersdata->appends(array('search' => $search,'roleFilter'=>$roleFilter))->links() }}
				
			</div>
		</div>
	</div>
</div>
<!--end row-->

@include('admin.layout.footer')

<script>/*
var table = $('#myTable').DataTable({
   "order": [[ 1, "asc" ]],
              'columnDefs': [{
                    "targets": [4],
                    "orderable": false
                }]
          });
		  */
</script>

<style>
	/*
    .dataTables_filter {display:none;}
    .dropdown-menu,.dropdown-menu.dropdown-menu-right.dropdown-menu-lg-end {  margin-bottom: 20px !important;}
	*/
</style>