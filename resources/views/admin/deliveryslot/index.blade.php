
@include('admin.layout.header')
   <div class="content-page">
     <div class="container-fluid">
                <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{ __('lang.deliveryslot')}}</h4>
                        <p class="mb-0">{{ __('lang.slotdesc1')}}<br>
                         {{ __('lang.desc2')}}</p>
                    </div>
                    <a href="{{url('/admin/deliveryslot/create')}}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>{{ __('lang.addslot')}}</a>
                </div>
            </div>
			@if(session('success'))
					<h1>{{session('success')}}</h1>
				@endif

            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>{{ __('lang.sno')}}</th>
                            <th>{{ __('lang.deliveryslotname')}}</th>
                            <th>{{ __('lang.timestart')}}</th>
                            <th>{{ __('lang.timeend')}}</th>
                            <!--<th>{{ __('lang.slotmax')}}</th>-->
                            
							<th>{{ __('lang.action')}}</th>
                        </tr>				
						
                    </thead>
                    <tbody class="ligth-body">
                        @foreach($deliveryData as $key =>$delivery)
						
						<tr>
                            <td>{{ ++$key }}</td> 
                            <td>{{$delivery->slotName}}</td>
                            <td>{{$delivery->startingTime}}</td>
                            <td>{{$delivery->endingTime}}</td>
                            <!--<td>{{$delivery->maxSlot}}</td>                            -->
                            <td>
                                <div class="d-flex align-items-center list-action">
                                   <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top"  data-original-title="Edit" href="{{url('/admin/deliveryslot/'.$delivery->id.'/edit')}}" class="btn btn-success"><i class="ri-pencil-line mr-0"></i></a>
										
										
									
                                     <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"
                                        href="{{route('deliveryslot.destroy',['id'=>$delivery->id])}}" onclick="return confirm('Are you sure you want to delete this Delivery Slot?');"><i class="ri-delete-bin-line mr-0"></i></a>
										
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