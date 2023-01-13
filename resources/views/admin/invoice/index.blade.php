@include('admin.layout.header')							

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/store')}}"><i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-detail "></i> {{ __('lang.invoices')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<a href="{{url('admin/invoice/create/').'/'.$storeId}}" class="btn btn-primary"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addinvoices')}}</a>
		</div>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.invoices')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<form action="" method="GET" id ="filter_results">
					<div class="row">
						
						<div class="col-md-3 mb-3 ">
							<label for="roleFilter" class="form-label">{{ __('lang.filterby')}}</label>
							<div class="input-group">
								<button class="btn btn-outline-secondary" type="button"><i class='bx bx-user-circle'></i>
								</button>
								<select name="statusFilter" class="form-select single-select" id="statusFilter" onChange="this.form.submit();">
									<option value="">{{ __('lang.selectstatus')}}</option>
									<option value=""@if($statusFilter == '') selected="selected" @endif>{{ __('lang.all')}} </option>
									<option value="Complete" @if($statusFilter == 'Complete') selected="selected" @endif>{{ __('lang.complete')}} </option>
									<option value="Pending" @if($statusFilter == 'Pending') selected="selected" @endif>{{ __('lang.pending')}} </option>
								</select>                     
							</div>
						</div>
						<div class="col-md-2 mb-3 ">
							<label for="date" class="form-label">{{ __('lang.fromdate')}}</label>
							<input type="date" name="startDate" class="form-control form-control-sm" value="{{$startDate}}"/>                      
						</div>
						<div class="col-md-2 mb-3 ">
							<label for="date" class="form-label">{{ __('lang.todate')}}</label>
							<input type="date" name="endDate" class="form-control form-control-sm" value="{{$endDate}}"/>                      
						</div>
						<div class="col-md-2 mb-3 ">
							<label for="search" class="form-label">{{ __('lang.search')}}</label>
							<input type="text" name="search" class="form-control form-control-sm" value="{{$search}}"/>                      
						</div>
						<div class="col-md-2 mb-3 pt-4">
							<label for="" class="form-label"></label>
							<button type="submit" class="btn btn-primary px-5">{{ __('lang.search')}}</button>
						</div>  
					</div>
				</form>

				<!--<table class="table mb-0 table-striped table-bordered" id="myTable"> -->
				<table class="table mb-0 table-striped table-bordered" id="">
					<thead>
						<tr>
							<th scope="col">{{ __('lang.vendorname')}}</th>
							<th scope="col">{{ __('lang.invoicenumber')}}</th>
							<th scope="col">{{ __('lang.invoicedate')}}</th>
							<th scope="col">{{ __('lang.status')}}</th>
							<th scope="col" width="15%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
					@foreach($invoice as $key =>$invoices)
						<tr>
                            <td>{{$invoices->vendorName}}</td>
                            <td>{{$invoices->invoiceNumber}}</td>
                            <td>{{\Carbon\Carbon::parse($invoices->invoiceDate)->format('d M Y')}}</td>
							<td>{{$invoices->status}}</td>
                            <td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										<a class="dropdown-item" href="{{url('/admin/invoice/'.$invoices->id.'/edit')}}" <?php echo($invoices->status == 'Complete') ? 'hidden' : '' ?>><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
									</div>
								</div>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				
				{{ $invoice->appends(array('search' => $search,'startDate'=>$startDate,'endDate'=>$endDate, 'statusFilter'=>$statusFilter))->links() }}
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
                    "targets": [3],
                    "orderable": false
                }]
          });
</script>