@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
//helper::checkUserURLAccess('cashier_manage','');
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
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-user"></i> {{ __('lang.cashierlist')}}</li>
				
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
	   
		<div class="btn-group">
			<a href="{{url('admin/cashier/create/').'/'.$storeId}}" class="btn btn-primary add-list"><i class="bx bx-list-plus mr-3"></i>{{ __('lang.addcashier')}}</a>
		</div>
		
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.cashierlist')}} dubai mall</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<table class="table mb-0 table-striped " id="myTable">
					<thead class="borderless">
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
					@foreach($cashierdata as $key =>$cashier)
					@if($cashier->status==='Active')
						<tr> 
                            <td>{{$cashier->firstName}} {{$cashier->lastName}}</td>
                            <td>{{$cashier->contactNumber}}</td>
							<td>{{$cashier->email}}</td>
							<?php if(Auth::user()->roleId != 4 ){?>
							<td>{{$cashier->storeName}}</td>
							<?php } ?>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
									    
										<a class="dropdown-item" href="{{url('/admin/cashier/'.$cashier->id).'/edit'}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										
										<a class="dropdown-item" href="{{route('cashier.destroy',['id'=>$cashier->id])}}" onclick="return confirm('Are you sure you want to delete the cashier?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
									    
									</div>
								</div>
							</td>
						</tr>
						@else
						<tr style="background-color:#ffcccc;"> 
                            <td>{{$cashier->firstName}} {{$cashier->lastName}}</td>
                            <td>{{$cashier->contactNumber}}</td>
							<td>{{$cashier->email}}</td>
							<?php if(Auth::user()->roleId != 4 ){?>
							<td>{{$cashier->storeName}}</td>
							<?php } ?>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										<a class="dropdown-item" href="{{url('/admin/cashier/'.$cashier->id).'/edit'}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										<a class="dropdown-item" href="{{route('cashier.destroy',['id'=>$cashier->id])}}" onclick="return confirm('Are you sure you want to delete the cashier?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
									</div>
								</div>
							</td>
						</tr>
						@endif
						
					@endforeach
					</tbody>
				</table> -->
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
                    "targets": [4],
                    "orderable": false
                }]
          });
</script>