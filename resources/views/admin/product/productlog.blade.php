@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('storetype_manage','');

?>
<style >
	.hideDiv{
display:none;
	}
</style>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				@if(Auth::user()->roleId != 4)
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/store')}}"><i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</a>
				</li>
				<li class="breadcrumb-item" ><a class="text-primary" href="{{url('/admin/product/'.$storeId)}}"><i class="bx bx-book-content"></i> {{ __('lang.productlist')}}</a></li>
				@endif
				<li class="breadcrumb-item " aria-current="page"><i class="bx bx-book-content"></i> {{ __('lang.productlog')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
	    
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase"> {{ __('lang.productlog')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<table class="table mb-0 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
							<!-- <th scope="col" class="center">{{ __('lang.user')}}</th> -->
							<th scope="col" class="center" >{{ __('lang.previousstock')}}</th>
							<th scope="col" class="center" >{{ __('lang.newstock')}}</th>
							<th scope="col" class="center" >{{ __('lang.date')}}</th>
						</tr>
					</thead>
					<tbody>					
					@foreach($productlogs as $key =>$productlog)
						<tr>						
                           <!--  <td></td> -->
                            <td>{{$productlog->previousStock}}</td>
                            <td>{{$productlog->newStock}}</td>
                            <td>{{$productlog->created_at}}</td>

							<!-- <td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
									<a class="dropdown-item" href=""><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a></div>
								</div>
							</td> -->
						</tr>
					@endforeach					
					</tbody>
				</table>
                <div class="pagination_links">
    				{{$productlogs->links()}}
    			</div>
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
                    "targets": [2],
                    "orderable": false
                }]
          });


function buttonClicked() {

$(".hideDiv").show();
$(".showeDiv").hide();
}


</script>