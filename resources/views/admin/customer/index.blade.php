@include('admin.layout.header')							
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('consumers_manage','');

?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-group"></i> {{ __('lang.customers')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
	    @if(helper::checkUserRights('consumers_manage','consumers_add'))
		<div class="btn-group">
			<a href="{{url('admin/customer/create').'/'.$storeId}}" class="btn btn-primary"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addcustomer')}}</a>
		</div>
		@endif
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.allconsumers')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
							<th scope="col">{{ __('lang.name')}}</th>
							<th scope="col">{{ __('lang.email')}}</th>
							<th scope="col">{{ __('lang.contactnumber')}}</th>
							<th scope="col">{{ __('lang.address')}}</th>
							<th scope="col">{{ __('lang.registrationdate')}}</th>
							<th scope="col" width="15%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
					@foreach($customer as $key =>$customerData)
						<tr>
                            <td>{{$customerData->customerName}}</td>
                            <td>{{$customerData->email}}</td>
                            <td>{{$customerData->contactNumber}}</td>
							<td>{{$customerData->address}}</td>
							<td>{{\Carbon\Carbon::parse($customerData->created_at)->format('d M Y')}}</td>
                            <td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
									    @if(helper::checkUserRights('consumers_manage','consumers_view'))
										<a class="dropdown-item" href="{{url('/admin/customer/'.$customerData->id.'/view')}}"><i class="fadeIn animated bx bx-show"></i> {{ __('lang.view')}}</a>
										@endif
										@if(helper::checkUserRights('consumers_manage','consumers_edit'))
										<a class="dropdown-item" href="{{url('/admin/customer/'.$customerData->id.'/edit')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										@endif
										@if(helper::checkUserRights('consumers_manage','consumers_del'))
										<a class="dropdown-item" href="{{route('customer.destroy',['id'=>$customerData->id])}}" onclick="return confirm('Are you sure you want to delete the consumer?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
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
                    "targets": [5],
                    "orderable": false
                }]
          });
</script>
