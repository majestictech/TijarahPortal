@include('admin.layout.header')							

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-time "></i> {{ __('lang.shift')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<a href="{{url('admin/shift/create/').'/'.$storeId}}" class="btn btn-primary"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addshift')}}</a>
		</div>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.shifts')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<form action="" method="GET" id ="filter_results">
					<div class="row">
						<div class="col-md-6"></div>
						<div class="col-md-3 mb-3 ">
							<label for="search" class="form-label">Search</label>
							<input type="text" name="search" class="form-control form-control-sm" value=""/>                      
						</div>
						<div class="col-md-3 mb-3 pt-4">
							<label for="" class="form-label"></label>
							<button type="submit" class="btn btn-primary px-5">Search</button>
						</div>  
					</div>
				</form>
				<!--<table class="table mb-0 table-striped table-bordered" id="myTable">-->
				<table class="table mb-0 table-striped table-bordered" id="">
					<thead>
						<tr>
							<th scope="col">{{ __('lang.title')}}</th>
							<th scope="col">{{ __('lang.from')}}</th>
							<th scope="col">{{ __('lang.to')}}</th>
							<th scope="col" width="15%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
					@foreach($shift as $key =>$shiftdata)
						<tr>
                            <td>{{$shiftdata->title}}</td>
                            <td>{{$shiftdata->hoursOfServiceFrom}}</td>
                            <td>{{$shiftdata->hoursOfServiceTo}}</td>
                            <td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="fadeIn animated bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										<a class="dropdown-item" href="{{url('/admin/shift/'.$shiftdata->id.'/edit')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
									</div>
								</div>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				{{ $shift->links() }}
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
                    "targets": [1,2,3],
                    "orderable": false
                }]
          });
</script>