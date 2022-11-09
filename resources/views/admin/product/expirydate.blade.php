@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('storetype_manage','');

?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-store-alt"></i> {{ __('lang.storetype')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
	    
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">Stock Expiry</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
							<th scope="col" class="center">Stock</th>
							<th scope="col" class="center" >Expiry Date</th>
						</tr>
					</thead>
					<tbody>
					
						<tr>
                            <td> 10</td>
                            <td>date</td>
						</tr>
					
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
                    "targets": [1],
                    "orderable": false
                }]
          });
</script>