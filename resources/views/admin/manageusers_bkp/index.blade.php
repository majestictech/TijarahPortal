@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('cashier_manage','');
//helper::checkStoreId($storeId);
?>


<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><a class="text-primary" href="{{url('/admin/store')}}"><i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</a></li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-user"></i> {{ __('lang.manageusers')}}</li>
				
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
	   
		<div class="btn-group">
			<a href="{{url('admin/manageusers/create/')}}" class="btn btn-primary add-list"><i class="bx bx-list-plus mr-3"></i>{{ __('lang.adduser')}}</a>
		</div>
		
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.allusers')}} of dubai mall</h6>
	</div>
		
		<hr/>
		<div class="card">
			<div class="card-body">
			  	<form action="" method="GET" id ="filter_results">
					<div class="row">	
						<div class="col-xl-3">
						</div>
						<div class="col-xl-3 align-items-center">
							<div class="input-group">
								<select name="userFilter" class="form-select single-select" id="userFilter" onchange="" required>
								@foreach($masRoles as $key=>$masRole)
										<option value="{{ $masRole->id }}">{{ $masRole->name }} </option>
									@endforeach
								</select>
							</div>
						</div>
					<div class="col-xl-3">
						<input type="text" name="search" class="form-control" placeholder="search" value=""/>
					</div>
					<div class="col-xl-3">
						<label for="search" class="form-label"></label>
						<button type="submit" class="btn btn-primary px-5">Search</button>
					</div>
				</form>
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
							<th scope="col">{{ __('lang.fullname')}}</th>
							<th scope="col">{{ __('lang.phonenumber')}}</th>
							<th scope="col">{{ __('lang.email')}}</th>
						    <?php if(Auth::user()->roleId != 4 ){?>
							<th>{{ __('lang.store')}}</th>
							<?php } ?>
							<th scope="col" width="15%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
					@foreach($manageusersdata as $key =>$manageusers)
					@if($manageusers->status==='Active')
						<tr> 
                            <td>{{$manageusers->firstName}} {{$manageusers->lastName}}</td>
                            <td>{{$manageusers->contactNumber}}</td>
							<td>{{$manageusers->email}}</td>
							<?php if(Auth::user()->roleId != 4 ){?>
							<td>xyz</td>
							<?php } ?>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
									    
										<a class="dropdown-item" href="{{url('admin/manageusers/.$manageusers->id./edit')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										
										<a class="dropdown-item" href="" onclick="return confirm('Are you sure you want to delete the cashier?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
									    
									</div>
								</div>
							</td>
						</tr>
						@else
						<tr style="background-color:#ffcccc;"> 
                            <td>123{{$manageusers->firstName}} {{$manageusers->lastName}}</td>
                            <td>{{$manageusers->contactNumber}}</td>
							<td>{{$manageusers->email}}</td>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">123<i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										<a class="dropdown-item" href="{{url('admin/manageusers/.$manageusers->id./edit/')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										<a class="dropdown-item" href="" onclick="return confirm('Are you sure you want to delete the cashier?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
									</div>
								</div>
							</td>
						</tr>
						@endif
						
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
</div>
<!--end row-->

@include('admin.layout.footer')

<script>
var table = $('#myTable').DataTable({
   "order": [[ 4, "asc" ]],
              'columnDefs': [{
                    "targets": [4],
                    "orderable": false
                }]
          });


</script>