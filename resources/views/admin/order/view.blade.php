
@include('admin.layout.header')
   <div class="content-page">
     <div class="container-fluid">
                <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">Order #{{$orderdata->orderId}}</h4>
                    </div>
                    <?php if(Auth::user()->roleId != 4 ){?>
                    <a href="{{url('/admin/order')}}" class="btn btn-primary add-list">{{ __('lang.orderlist')}}</a>
                    <?php } ?>
                    <?php if(Auth::user()->roleId == 4 ){?>
                    <a href="{{url('/admin/order/'.$storeId)}}" class="btn btn-primary add-list">{{ __('lang.orderlist')}}</a>
                    <?php } ?>
                </div>
            </div>
			<div class="col-lg-12">
			<div class="card">
                   
                    <div class="card-body">
                            <div class="row">
							<div class="col-md-12">  
								<h4 class="mb-4">Customer Details</h4>
									<div class="form-group ">
										<div class="row">
											<label class="col-sm-2">Customer Name: </label>
											<div class="col-sm-8">
											    {{$orderdata->customerName}}
											</div>
										</div>
									</div>
									<div class="form-group ">
										<div class="row">
											<label class="col-sm-2">Customer Mobile: </label>
											<div class="col-sm-8">
											    {{$orderdata->contactNumber}}
											</div>
										</div>
									</div>							
                                   
									<h4 class="mb-4 mt-3">Store Details</h4>
									<div class="form-group ">
										<div class="row">
											<label class="col-sm-2">Store Name: </label>
											<div class="col-sm-8">
											    {{$orderdata->storename}}
											</div>
										</div>
									</div>
									 
									 
									 <div class="form-group ">
										<div class="row">
											<label class="col-sm-2">Store Address: </label>
											<div class="col-sm-8">
											    {{$orderdata->address}}
											</div>
										</div>
									</div>
									
									
									
									<!--
									
									<div class="form-group ">
										<div class="row">
											<label class="col-sm-2">Loyalty Points: </label>
											<div class="col-sm-8">
											    {{$orderdata->loyaltyPoints}}
											</div>
										</div>
									</div>
								-->
									<div class="form-group ">
									<div class="row">
										<label class="col-sm-2">Order Date: </label>
										<div class="col-sm-8">
											{{\Carbon\Carbon::parse($orderdata->created_at)->format('d M Y')}}
										</div>
									</div>
									</div>
									<div class="form-group ">
									<div class="row">
										<label class="col-sm-2">Order Total: </label>
										<div class="col-sm-8">
											SAR {{$orderdata->totalAmount}}
										</div>
									</div>
									</div>
									
									<div class="form-group ">
									<div class="row">
										<label class="col-sm-2">Payment Status: </label>
										<div class="col-sm-8">
										{{$orderdata->paymentStatus}}
										</div>
										
									</div>
									</div>
									<div><hr></div>
								
								                                 
                            </div>                            
                           
						
						<table class="table">
						  <thead>
							<tr>
							  <th scope="col">#</th>
							  <th scope="col">{{ __('lang.productname')}}</th>
							  <th scope="col">{{ __('lang.quantity')}}</th>
							  <th scope="col">{{ __('lang.sellingprice')}}</th>
							</tr>
						  </thead>
						  <tbody>
							@foreach($orderDetail as $key =>$orderData)
							<tr>
							  <th scope="row">{{ ++$key }}</th>
							  <td>{{$orderData['name']}}</td>
							  <td>{{$orderData['amount']}}</td>
							  <td>SAR {{$orderData['sellingPrice']}}</td>
							</tr>
							@endforeach
						  </tbody>
						</table>
						
						
						
						
						<div class="col-md-12"><hr></div>
						<div class="col-md-4 offset-md-7">
							<div class="form-group ">
								<div class="row">
									<label class="col-sm-4">Sub Total: </label>
									<div class="col-sm-8">
									SAR {{$orderdata->totalAmount}}
									</div>
									
								</div>
							</div>
							
							<div class="form-group ">
								<div class="row">
									<label class="col-sm-4">Total: </label>
									<div class="col-sm-8">
								    SAR {{$orderdata->totalAmount}}
									</div>
									
								</div>
							</div>
							
						
						
						</div>
						
                    </div>
                </div>
			
			</div>
			
			
			
			
			
			
        </div>
        <!-- Page end  -->
    </div>
    
      </div>
    </div>
    <!-- Wrapper End-->
@include('admin.layout.footer')