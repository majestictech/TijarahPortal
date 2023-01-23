<?php
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Helpers\AppHelper as Helper;

$Roles = config('app.Roles');
?>

@include('admin.layout.header')

<style type="text/css">
	.dataTables_filter, .dataTables_info, .dataTables_paginate {
     display: none;
}
</style>


<!--breadcrumb-->

<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.shiftreports')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<table class="table mb-0 table-striped table-bordered" id="myTable">
				
					<thead>
						<tr>
							<th scope="col">{{ __('lang.shiftno.')}}</th>
							<th scope="col">{{ __('lang.cashier')}}</th>
							<th scope="col">{{ __('lang.openingbalance')}}</th>
							<th scope="col">{{ __('lang.closingbalance')}}</th>
							<th scope="col">{{ __('lang.adjustedamount')}}</th>
							<th scope="col">{{ __('lang.finalbalance')}}</th>
							<th scope="col" width="15%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>Scavenger</td>
							<td>SAR 01</td>
							<td>SAR 4,444</td>
							<td>0 SAR</td>
							<td>SAR 4,444</td>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										<a class="dropdown-item" href="{{url('/admin/storereports/shiftreport/505')}}"><i class="fadeIn animated bx bx-show"></i> {{ __('lang.view')}}</a>
									</div>
								</div>
							</td>
						</tr>
                        <tr>
							<td>2</td>
							<td>Soul Reaper</td>
							<td>SAR 4,444</td>
							<td>SAR 8,889</td>
							<td>1 SAR</td>
							<td>SAR 8,8888</td>
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										<a class="dropdown-item" href="{{url('/admin/storereports/shiftreport/505')}}"><i class="fadeIn animated bx bx-show"></i> {{ __('lang.view')}}</a>
									</div>
								</div>
							</td>
						</tr>
						
					</tbody>
				</table>
				
			</div>
		</div>
	</div>
</div>


@include('admin.layout.footer')