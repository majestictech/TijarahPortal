<?php
use App\Helpers\AppHelper as Helper;
?>
@include('admin.layout.header')
   <div class="content-page">
     <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7 w-100">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        @if($storedata->printStoreNameAr != null)
                            <h4 class="mb-3">{{$storedata->storeName}} &nbsp; ({{$storedata->printStoreNameAr}})</h4>
                        @else
                            <h4 class="mb-3">{{$storedata->storeName}} &nbsp;</h4>
                        @endif
                        <h5 style="margin-top: 2px;">(Store ID- TIJ{{$storedata->id}}) &nbsp; (User ID-{{$storedata->userId}})</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-5">
                <div class="row">
				@if(helper::checkUserRights('store_manage','store_storereports'))
                <form method="post"  enctype="multipart/form-data" action="{{route('report.export')}}" style="display:flex;">
                    <div class="col-lg-5">
                        <input type="date" name="start_date" id="min" class="form-control" placeholder="{{ __('lang.fromdate')}}"  />
                    </div>
                    <div class="col-lg-5">
                        <input type="date" name="end_date" id="max" class="form-control" placeholder="{{ __('lang.todate')}}"  />
                    </div>
                    <div class="col-lg-2">
        		   
        				{{ csrf_field() }}
        				<div class="form-group">
        				    <input type="hidden" name="storeId" value="{{$storedata->id}}" />
        					<input type="submit" name="upload" class="btn btn-primary" style="height: 36px; border-radius: 2px; margin-left: 20px;" value="OK">
        				</div>
        
                    </div>
    
    			</form> 
				@endif
    			</div>
			</div>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            	<div class="col-3">
            		<div class="card radius-10 overflow-hidden">
            			<div class="card-body">
            				<div class="d-flex align-items-center">
            					<div>
            						<p class="mb-0 text-secondary font-14"><a href="{{url('/admin/order/'.$storedata->id)}}">{{ __('lang.todaysorders')}}</a></p>
            						<h5 class="my-0">{{$todayorderCount}}</h5>
            					</div>
            					<div class="text-primary ms-auto font-30"><i class='bx bx-cart-alt'></i>
            					</div>
            				</div>
            			 </div>
            			<div class="mt-1" id="chart1"></div>
            		</div>
            	</div>
            	<div class="col-3">
            		<div class="card radius-10 overflow-hidden">
            			<div class="card-body">
            				<div class="d-flex align-items-center">
            					<div>
            						<p class="mb-0 text-secondary font-14"><a href="{{url('/admin/order/'.$storedata->id)}}">{{ __('lang.totalorders')}}</a></p>
            						<h5 class="my-0">{{$allorderCount}}</h5>
            					</div>
            					<div class="text-info ms-auto font-30"><i class='bx bx-cart'></i>
            					</div>
            				</div>
            			</div>
            			<div class="mt-1" id="chart5"></div>
            		</div>
            	</div>
            	
            	<div class="col-3">
            		<div class="card radius-10 overflow-hidden">
            			<div class="card-body">
            				<div class="d-flex align-items-center">
            					<div>
            						<p class="mb-0 text-secondary font-14"><a href="{{url('/admin/report/revenue')}}">{{ __('lang.todaysrevenue')}}</a></p>
            						<h5 class="my-0">SAR {{$revenue}}</h5>
            					</div>
            					<div class="text-danger ms-auto font-30">ريال
            					</div>
            				</div>
            			</div>
            			<div class="mt-1" id="chart2"></div>
            		</div>
            	</div>
            	<div class="col-3">
            		<div class="card radius-10 overflow-hidden">
            			<div class="card-body">
            				<div class="d-flex align-items-center">
            					<div>
            						<p class="mb-0 text-secondary font-14"><a href="{{url('/admin/customer/'.$storedata->id)}}">{{ __('lang.customers')}}</a></p>
            						<h5 class="my-0">{{$allcustomer}}</h5>
            					</div>
            					<div class="text-success ms-auto font-30"><i class='bx bx-group'></i>
            					</div>
            				</div>
            			</div>
            			<div class="mt-1" id="chart3"></div>
            		</div>
            	</div>
            	
            </div>
			
		    <div class="col-lg-12">
			  <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0 tbl-server-info">
                 
                    <tbody >
                        
					    <!--<tr class="table-active">
                            <td><b>Store ID</b></td> 
                            <td>{{$storedata->id}}</td> 
							
						</tr>
						
						<tr>
                            <td><b>User ID</b></td> 
                            <td>{{$storedata->userId}}</td> 
							
						</tr>
						
                        <tr class="table-active">
                            <td><b>Store Name</b></td> 
                            <td>{{$storedata->storeName}}</td> 
							
						</tr>-->
						
						
						<tr>
                            <td><b>{{ __('lang.registrationnumber')}}</b></td>
                            <td>{{$storedata->regNo}}</td>
						</tr>
						
						<tr class="table-active">
                            <td><b>{{ __('lang.storetype')}}</b></td>
                            <td>{{$storedata->name}}</td>
						</tr>
						
						<tr >
                            <td><b>{{ __('lang.ownername')}}</b></td>
                            <td>{{$storedata->firstName}}{{$storedata->lastName}}</td>
						</tr>
						<tr class="table-active">
                            <td><b>{{ __('lang.mobilenumber')}}</b></td>
                            <td>{{$storedata->contactNumber}}</td>
						</tr >
						<tr >
                            <td><b>Email</b></td>
                            <td>{{$storedata->email}}</td>
						</tr>
						<tr class="table-active">
                            <td><b>App Type</b></td>
                            <td>{{$storedata->appType}}</td>
						</tr>
						<tr >
                            <td><b>{{ __('lang.shopsize')}}</b></td>
                            <td>{{$storedata->shopSize}}</td>
						</tr >
						<tr class="table-active">
                            <td><b>{{ __('lang.vatnumber')}}</b></td>
                            <td>{{$storedata->vatNumber}}</td>
						</tr >
						<tr >
							<td><b>{{ __('lang.state')}}</b></td>
							<td>{{$storedata->state}}</td>
						</tr>
						<tr class="table-active">
                            <td><b>{{ __('lang.country')}}</b></td>
                            <td>{{$storedata->nicename}}</td>
						</tr>
                    	<tr >
                            <td><b>{{ __('lang.inventorylink')}}</b></td>
                            <td>{{$storedata->manageInventory}}</td>
						</tr >
						<tr class="table-active">
                            <td><b>{{ __('lang.smsalert')}}</b></td>
                            <td>{{$storedata->smsAlert}}</td>
						</tr>
						<tr >
                            <td><b>{{ __('lang.autoglobalcat')}}</b></td>
                            <td>{{$storedata->autoGlobalCat}}</td>
						</tr >
						<tr class="table-active">
                            <td><b>{{ __('lang.onlinemarket')}}</b></td>
                            <td>{{$storedata->onlineMarket}}</td>
						</tr >
						<tr >
							<td><b>{{ __('lang.loyaltyoptions')}}</b></td>
							<td>{{$storedata->loyaltyOptions}}</td>
						</tr>
						<tr class="table-active">
                            <td><b>{{ __('lang.autoglobalitems')}}</b></td>
                            <td>{{$storedata->autoGlobalItems}}</td>
						</tr>

						<tr >
							<td><b>{{ __('lang.chatbot')}}</b></td>
							<td>{{$storedata->chatbot}}</td>
						</tr>
						
						<tr class="table-active">
                            <td><b>{{ __('lang.latitude')}}</b></td>
                            <td>{{$storedata->latitude}}</td>
						</tr>
						
						<tr >
                            <td><b>{{ __('lang.longitude')}}</b></td>
                            <td>{{$storedata->longitude}}</td>
						</tr>
						
                        
						<tr class="table-active">
                            <td><b>{{ __('lang.storeaddress')}}</b></td>
                            <td>{{$storedata->address}}</td>
						</tr>
						
						<tr >
                            <td><b>{{ __('lang.storeaddar')}}</b></td>
                            <td>{{$storedata->printAddAr}}</td>
						</tr>
						
						
						<tr class="table-active">
                            <td><b>{{ __('lang.ecrappvr')}}</b></td>
                            <td>{{$storedata->appVersion}}</td>
						</tr>
						
						<tr>
                            <td><b>{{ __('lang.subscriptionExpiry')}}</b></td>
                            <td>{{$storedata->subscriptionExpiryDate}}</td>
						</tr>
						
						<tr class="table-active">
                            <td><b>{{ __('lang.storeCreatedOn')}}</b></td>
                            <td>{{$storedata->storeCreatedOn}}</td>
						</tr>
                                   
                                                  
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