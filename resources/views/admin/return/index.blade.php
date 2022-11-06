
@include('admin.layout.header')
 <div class="content-page">
     <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{ __('lang.returnlist')}}</h4>
                        <p class="mb-0">{{ __('lang.returndesc1')}} 
						 <br>{{ __('lang.returndesc2')}}</p>
                    </div>
                    <!--<a href="{{url('/admin/return/create')}}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>{{ __('lang.addreturn')}}</a>-->
                </div>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>{{ __('lang.sno')}}</th>	
                            <th>{{ __('lang.date')}}</th>
                            <th>{{ __('lang.referenceno')}}</th>
                            <th>{{ __('lang.ordernumber')}}</th>
							<th>{{ __('lang.orderstatus')}}</th>
                            <th>{{ __('lang.action')}}</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @foreach($returns as $key =>$returnData)
						<tr>
                            <td>{{ ++$key }}</td> 
                            <td>{{$returnData->dateOrder}}</td>
                            <td>{{$returnData->referenceNo}}</td>
                            <td>{{$returnData->orderId}}</td>
							<td><div class="badge badge-primary">{{$returnData->name}}</div></td>
                            <td>
                                <div class="d-flex align-items-center list-action">
                                <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"
                                href="{{url('/admin/return/'.$returnData->id.'/edit')}}"><i class="ri-pencil-line mr-0"></i></a>
                                <!--<a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"
                               href="{{route('return.destroy',['id'=>$returnData->id])}}" onclick="return confirm('Are you sure you want to delete this return?');"><i class="ri-delete-bin-line mr-0"></i></a>-->
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