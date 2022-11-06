@include('admin.layout.header')							

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bxs-devices"></i> {{ __('lang.devices')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
		</div>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.devices')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
							<th scope="col">{{ __('lang.deviceid')}}</th>
							<th scope="col">{{ __('lang.printername')}}</th>
							<th scope="col">{{ __('lang.papersize')}}</th>
						<!--	<th scope="col" width="15%">{{ __('lang.action')}}</th>-->
						</tr>
					</thead>
					<tbody>
					@foreach($device as $key =>$device)
						<tr> 
                            <td>{{$device->deviceId}}</td>
							<td>{{$device->printerName}}</td>
							<td>{{$device->paperSize}}</td>
						<!--	<td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										<a class="dropdown-item" href="{{url('/admin/device/'.$device->id).'/edit'}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
										<a class="dropdown-item" href="{{route('device.destroy',['id'=>$device->id])}}" onclick="return confirm('Are you sure you want to delete the device?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.delete')}}</a>
									</div>
								</div>
							</td>-->
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
   "order": [[ 0, "asc" ]],
              'columnDefs': [{
                    "targets": [2],
                    "orderable": false
                }]
          });
</script>