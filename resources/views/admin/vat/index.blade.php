@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('vat_manage','');

?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-box"></i> {{ __('lang.vat')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
	    @if(helper::checkUserRights('vat_manage','vat_add'))
		<div class="btn-group">
			<a href="{{url('/admin/vat/create')}}" class="btn btn-primary"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addvat')}}</a>
		</div>
		@endif
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.allvat')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
							<th scope="col">{{ __('lang.name')}}</th>
							<th scope="col">{{ __('lang.vat')}}</th>
							@if(helper::checkUserRights('vat_manage','vat_edit') || helper::checkUserRights('vat_manage','vat_del'))
							<th scope="col" width="15%">{{ __('lang.action')}}</th>
							@endif
						</tr>
					</thead>
					<tbody>
					@foreach($vat as $key =>$vatdata)
						<tr>
                            <td>{{$vatdata->name}}</td>
                            <td>{{$vatdata->value}}</td>
                            @if(helper::checkUserRights('vat_manage','vat_edit') || helper::checkUserRights('vat_manage','vat_del'))
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
									    @if(helper::checkUserRights('vat_manage','vat_edit'))
										<a class="dropdown-item" href="{{url('/admin/vat/'.$vatdata->id.'/edit')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										@endif
										@if(helper::checkUserRights('vat_manage','vat_del'))
										<a class="dropdown-item" href="{{url('/admin/vat/'.$vatdata->id.'/delete')}}"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
										@endif
									</div>
								</div>
							</td>
							@endif
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
                    "targets": [0,2],
                    "orderable": false
                }]
          });
</script>


