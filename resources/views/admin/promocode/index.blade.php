
@include('admin.layout.header')

  <div class="content-page">
     <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{ __('lang.promocodelist')}}</h4>
                        <p class="mb-0">{{ __('lang.promocodedesc1')}}<br> 
						{{ __('lang.promocodedesc2')}}</p>
                    </div>
                    <a href="{{url('/admin/promocode/create')}}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>{{ __('lang.addpromocode')}}</a>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>{{ __('lang.sno')}}</th>
                            <th>{{ __('lang.promoname')}}</th>
                            <th>{{ __('lang.promocodetype')}}</th>
							<!--<th>{{ __('lang.appliestocategory')}}</th>-->
							<th>{{ __('lang.appliestoproducts')}}</th>
							<th>{{ __('lang.expirydate')}}</th>
							<th>{{ __('lang.vouchercode')}}</th>
							<th>{{ __('lang.action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
					@foreach($promocodeData as $key =>$promocodeData)
                        <tr>
						
                            <td>{{ ++$key }}</td> 
							<td>{{$promocodeData->promoName}}</td>
                            <td>{{$promocodeData->promoType}}</td>
							<td>{{$promocodeData->name}}</td>
							<td>{{$promocodeData->promoTo}}</td>
                            <td>{{$promocodeData->voucherCode}}</td>
							        
                            <td>
                                <div class="d-flex align-items-center list-action">
                             
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top"  data-original-title="Edit" href="{{url('/admin/promocode/'.$promocodeData->id.'/edit')}}" class="btn btn-success"><i class="ri-pencil-line mr-0"></i></a>
                                    <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"
                                        href="{{route('promocode.destroy',['id'=>$promocodeData->id])}}" onclick="return confirm('Are you sure you want to delete this Promocode?');"><i class="ri-delete-bin-line mr-0"></i></a>
                                </div>
                            </td>
                        </tr>
						@endforeach
               
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <!-- Page end  -->
    </div>
</div>
</div>

    <!-- Wrapper End-->
@include('admin.layout.footer')