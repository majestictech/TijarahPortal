<style>
.badge:hover
{
	cursor: pointer;
}
</style>
<?php
use App\Helpers\AppHelper as Helper;
if(Auth::user()->roleId == 4 )
{

$storeId = helper::getStoreId();
}
	
?>
@include('admin.layout.header')
   <div class="content-page">
     <div class="container-fluid">
                <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{ __('lang.customerdetails')}}</h4>
                        <p class="mb-0">{{ __('lang.customerdetailsdesc')}}</p>
                    </div>

                    <a href="{{url('/admin/customer/'.$customer->storeName)}}" class="btn btn-primary add-list">{{ __('lang.customerlist')}}</a>
          
                </div>
            </div>
			
		    <div class="col-lg-9">
			  <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0 tbl-server-info">
                 
                    <tbody >
					
                        <tr class="table-active">
                            <td><b>{{ __('lang.name')}}</b></td> 
                            <td>{{$customer->customerName}}</td> 
							
						</tr>
						<tr>
                            <td><b>{{ __('lang.email')}}</b></td>
                            <td>{{$customer->email}}</td>
						</tr>
				
						<tr class="table-active">
                            <td><b>{{ __('lang.contactname')}}</b></td>
                            <td>{{$customer->contactNumber}}</td>
						</tr >
						
						<tr>
                            <td><b>{{ __('lang.dob')}}</b></td>
                            <td>{{$customer->dob}}</td>
						</tr>
						<tr>
                            <td><b>{{ __('lang.doa')}}</b></td>
                            <td>{{$customer->doa}}</td>
						</tr>
						
						
						
                        
						<tr class="table-active">
                            <td><b>{{ __('lang.address')}}</b></td>
                            <td>{{$customer->address}}</td>
						</tr>


						
                                   
                                                  
                    </tbody>
                </table>
                </div>
      
            </div>
			
			
        </div>
        <!-- Page end  -->
					<div>
                        <h4 class="mt-3 mb-3">{{ __('lang.loyaltypoints')}}</h4>
						<table class="data-table table mb-0 tbl-server-info">
							<thead class="bg-white text-uppercase">
								<tr class="table-active">
									<th>{{ __('lang.sno')}}</th>	
									<th>{{ __('lang.store')}}</th>
									<th>{{ __('lang.loyaltypoints')}}</th>
									<th>{{ __('lang.action')}}</th>
								</tr>
							</thead>
							<tbody class="light-body">
							@foreach($customerloyalty as $key => $storecustomer)
								<tr>
									<td>{{++$key}}</td> 
									<td>{{$storecustomer->storeName}}</td>
									<td>{{$storecustomer->loyaltyPoints}}</td>
									                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <!--<a class="badge badge-info mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"
                                        href="{{url('/admin/customer/loyaltyview/'.$storecustomer->storeid)}}"><i class="ri-eye-line mr-0"></i></a>-->
								
									
									<a class="btn view btn-primary" data-placement="top" title="" data-original-title="View" data-toggle="modal" data-target="#{{$storecustomer->storeName}}"
                                       ><i class="bx bx-show"></i></a>
									
									
                                </div>
                            </td>
								</tr>
							@endforeach
							<!-- Modal -->
							</tbody>
						</table>
                    </div>
					
					<div class="modal fade" id="Rweha" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('lang.loyaltypt')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="table-responsive rounded mb-3">
				 <table class="data-table table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
							<th>{{ __('lang.sno')}}</th>
							<th>{{ __('lang.storename')}}</th>
							<th>{{ __('lang.orderid')}}</th>
							<th>{{ __('lang.loyaltypoint')}}</th>
							<th>{{ __('lang.type')}}</th>
                            
						 </tr>
                    </thead>
					<tbody class="ligth-body">
					@foreach($loyaltytransactions as $key =>$ls)
					<tr>
                        <td>{{ ++$key }}</td> 
                        <td>{{$ls->storeName}}</td>
                        <td>{{$ls->orderId}}</td>
                        <td>{{$ls->points}}</td>
                        <td>{{$ls->type}}</td>
					</tr>
					@endforeach
					</tbody>
				</table>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.close')}}</button>
        
      </div>
    </div>
  </div>
</div>

					
					<div>
                        <h4 class="mb-3 mt-3">{{ __('lang.orders')}}</h4>
						<div class="table-responsive rounded mb-3">
						<table class="data-table table mb-0 tbl-server-info">
							<thead class="bg-white text-uppercase">
								<tr class="table-active">
									<th>{{ __('lang.sno')}}</th>	
									<th>{{ __('lang.storename')}}</th>
									<th>{{ __('lang.bill')}}</th>
									<th>{{ __('lang.amount')}}</th>
									<th>{{ __('lang.action')}}</th>
								</tr>
							</thead>
							<tbody class="">
							@foreach($customerstore as $key => $sc)						
							<tr>
									<td>{{++$key}}</td> 
									<td>{{$sc->storeName}}</td>
									<td>{{$sc->orderId}}</td>
									<td>SAR {{$sc->totalAmount}}</td>
							<td>
                                <div class="d-flex align-items-center list-action">
                                    <!--<a class="badge badge-info mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"
                                        href="{{url('/admin/order/'.$sc->orderid.'/view')}}"><i class="ri-eye-line mr-0"></i></a> -->
										<a class="btn view btn-primary" data-placement="top" title="" data-original-title="View" data-toggle="modal" data-target="#{{$sc->orderId}}"
                                       ><i class="bx bx-show"></i></i></a>
                                </div>
                            </td>
								</tr>
								@endforeach
							</tbody>
						</table>
						</div>
                    </div>
					
					<div class="modal fade" id="TIJ6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('lang.ordersummary')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="table-responsive rounded mb-3">
				 <table class="data-table table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
							  <th scope="col">#</th>
							  <th scope="col">{{ __('lang.proimage')}}</th>
							  <th scope="col">{{ __('lang.productname')}}</th>
							  <th scope="col">{{ __('lang.unit')}}</th>
							  <th scope="col">{{ __('lang.quantity')}}</th>
							  <th scope="col">{{ __('lang.price')}}</th>
						 </tr>
                    </thead>
                    
					<tbody class="ligth-body">
					@if(!empty($orderDetail)) {
					@foreach($orderDetail as $key =>$orderData)
						<tr>
						  <th scope="row">{{ ++$key }}</th>
						  <td><img src="{{URL::asset('public/products').'/'.$orderData['productImage']}}"  class="img-fluid rounded avatar-50 mr-3" alt="image"></td>
						  <td>{{$orderData['name']}}</td>
						  <td>{{$orderData['weight']}} {{$orderData['weightClass']}}</td>
						  <td>{{$orderData['amount']}}</td>
						  <td>SAR {{$orderData['price']}}</td>
						</tr>
					@endforeach
					@endif
					</tbody>
					
				</table>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.close')}}</button>
        
      </div>
    </div>
  </div>
</div>
					
					
					
					
    </div>
    
      </div>
    </div>
    <!-- Wrapper End-->
@include('admin.layout.footer')