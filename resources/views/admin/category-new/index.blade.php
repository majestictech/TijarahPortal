@include('admin.layout.header')							

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-home-circle"></i> {{ __('lang.categorylist')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<a href="{{url('admin/category/create/').'/'.$storeId}}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>{{ __('lang.addcategory')}}</a>
		</div>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">{{ __('lang.categorylist')}}</h6>
		<hr/>
		<div class="card">
			<div class="card-body">
				<table class="table mb-0 table-striped table-bordered">
					<thead>
						<tr>
							<th>{{ __('lang.sno')}}</th>
                            <th>{{ __('lang.categoryname')}}</th>
							<th>{{ __('lang.parentcategory')}}</th>      
                            <th width="10%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					@foreach($categoryData as $key =>$categoryData)
                        <tr>
						    <td>{{ ++$key }}</td> 
                            <td><div class="d-flex align-items-center">
                                    <img src="{{URL::asset('public/category').'/'.$categoryData->catImage}}"  class="img-fluid rounded proimage avatar-50 mr-3" alt="image"><br/>
                                    
                                </div>
									<div>
                                        {{$categoryData->name}}
                                    </div>
								</td>
							
                            <td>{{$categoryData->ParentName}}</td>
                            <td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										
										<a class="dropdown-item" href="{{url('/admin/category/'.$categoryData->id.'/edit')}}"><i class="fadeIn animated bx bx-edit"></i> Edit</a>
										<a class="dropdown-item" href="{{route('category.destroy',['id'=>$categoryData->id])}}"><i class="fadeIn animated bx bx-trash"></i> Delete</a>
										
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