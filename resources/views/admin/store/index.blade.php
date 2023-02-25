@include('admin.layout.header')							
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('store_manage','');

?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
	@if(helper::checkUserRights('store_manage','store_add'))
		<div class="btn-group">
			<a href="{{url('/admin/store/create')}}" class="btn btn-primary"><i class="fadeIn animated bx bx-list-plus"></i> {{ __('lang.addstore')}}</a>
		</div>
		@endif
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">All Stores</h6>
		<hr/>
		
		<div class="card">
			<div class="card-body">
			    <form action="" method="GET" id ="filter_results">
			       <div class="row"> 
						@if(Auth::user()->roleId != 11 )
						<div class="col-md-3 mb-3">
							<label for="storeFilter" class="form-label">{{ __('lang.filterby')}}</label>
							
							
							<div class="input-group">
								<button class="btn btn-outline-secondary" type="button"><i class='bx bx-store'></i>
								</button>
								<select name="storeFilter" class="form-select single-select" id="storeFilter" onChange="this.form.submit();">
									<option value="" @if(empty($storeFilter)) selected="selected" @endif>{{ __('lang.storetype')}}</option>
										@foreach($storetype as $key=>$storetypes)
											<option value="{{ $storetypes->id }}" @if($storetypes->id==$storeFilter) selected="selected" @endif >{{ $storetypes->name }} ({!! App\Helpers\AppHelper::storeTypeStores($storetypes->id) !!})</option>
										@endforeach
								</select>
							</div>
							
						</div>
						@endif
						
						<div class="col-md-3 mb-3 ">
							<label for="search" class="form-label">Store Id</label>
							<input type="text" name="storeId" class="form-control form-control-sm" value="{{$storeId}}"/>                     
						</div>
						
						<div class="col-md-3 mb-3 ">
							<label for="search" class="form-label">Search</label>
							<input type="text" name="search" class="form-control form-control-sm" value="{{$search}}"/>                      
						</div>
						<div class="col-md-3 mb-3 pt-4">
							<label for="" class="form-label"></label>
							<button type="submit" class="btn btn-primary px-5">Search</button>
						</div>     
                    </div>
			    </form>
			   
			    
				<table class="table mb-0 table-striped table-bordered">
					<thead>
						<tr>
							<th scope="col" width="25%">{{ __('lang.storename')}}</th>
							<th scope="col">{{ __('lang.appVersion')}}</th>
							<th scope="col">{{ __('lang.appType')}}/{{ __('lang.deviceType')}}</th>
							<th scope="col">{{ __('lang.mobilenumber')}}</th>
							<th scope="col">{{ __('lang.lastbill')}}</th>
							<th scope="col">{{ __('lang.totalorders')}}</th>
							<th scope="col">{{ __('lang.todaysorders')}}</th>
							<th scope="col" width="8%">{{ __('lang.subscriptionExpiry')}}</th>
							<th scope="col" width="8%">{{ __('lang.action')}}</th>
						</tr>
					</thead>
					<tbody>
					@foreach($stores as $key =>$StoreData)
					    @if($StoreData->status==='Active')
						<tr style="vertical-align: middle !important;">
                            <td>{{$StoreData->storeName}}</td>
                            <td>{{$StoreData->appVersion}}</td>
                            <td>{{$StoreData->appType}} {{$StoreData->deviceType}}</td>
                            <td>{{$StoreData->contactNumber}}</td>
                            <td>{!! App\Helpers\AppHelper::lastStoreBilled($StoreData->id) !!}</td>
							<td><a href="{{url('/admin/order?storeId='.$StoreData->id)}}">{!! App\Helpers\AppHelper::totalStoreOrders($StoreData->id) !!}</a></td>
							<!--<td>{!! App\Helpers\AppHelper::totalStoreOrders($StoreData->id) !!}</td>-->
                            <td>{!! App\Helpers\AppHelper::todayStoreOrders($StoreData->id) !!}</td>
                            <td>{{$StoreData->subscriptionExpiry}}</td>
                            <td>
								<div class="btn-group store-dropdown">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" style="/*height: 73vh*/">
										<!--<a class="dropdown-item" href="{{url('/admin/category/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-spreadsheet"></i> Categories</a>-->
										@if(helper::checkUserRights('store_manage','store_edit'))
										<a class="dropdown-item" href="{{url('/admin/store/'.$StoreData->id.'/edit')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>

										<!-- <a class="dropdown-item" href="{{url('/admin/test/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.test')}}</a> -->
										<!-- <a class="dropdown-item" href="{{url('/admin/errororderlog')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.test')}}</a> -->
										@endif

										@if($StoreData->status==='Active')
											@if(helper::checkUserRights('store_manage','store_disable'))
    										<a class="dropdown-item" href="{{url('/admin/store/'.$StoreData->id.'/disable')}}" onclick="return confirm('Are you sure you want to disable the store?');"><i class="fadeIn animated bx bx-notification-off"></i> {{ __('lang.disable')}}</a>
											@endif
    									@else
											@if(helper::checkUserRights('store_manage','store_enable'))
    										<a class="dropdown-item" href="{{url('/admin/store/'.$StoreData->id.'/enable')}}" onclick="return confirm('Are you sure you want to enable the store?');"><i class="fadeIn animated bx bx-notification"></i> {{ __('lang.enable')}}</a>
											@endif
    									@endif
										
										<!--<a class="dropdown-item" href="{{url('/admin/cashier/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-group"></i> {{ __('lang.cashier')}}</a>-->

										@if(helper::checkUserRights('store_manage','store_manageusers'))
										<a class="dropdown-item" href="{{url('/admin/manageusers/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-group"></i> {{ __('lang.manageusers')}}</a>
										@endif
										@if(helper::checkUserRights('store_manage','store_customers'))
										<a class="dropdown-item" href="{{url('/admin/customer/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-group"></i> {{ __('lang.storecustomers')}}</a>
										@endif
										<!-- <a class="dropdown-item" href="{{url('/admin/store/lowinventoryemail?storeId='.$StoreData->id)}}"><i class="bx bx-book-content"></i> {{ __('lang.lowinventory')}}</a>-->
										
										@if(helper::checkUserRights('store_manage','store_configemail'))
										<a class="dropdown-item" href="{{url('/admin/configemail/'.$StoreData->id.'/edit')}}"><i class="fadeIn animated bx bx-mail-send"></i> {{ __('lang.configemail')}}</a> 
										@endif
										@if(helper::checkUserRights('store_manage','store_inventory'))
										<a class="dropdown-item" href="{{url('/admin/product/'.$StoreData->id)}}"><i class="bx bx-book-content"></i> {{ __('lang.inventory')}}</a>
										@endif
										<a class="dropdown-item" href="{{url('/admin/store/'.$StoreData->id.'/view')}}"><i class="bx bx-show"></i> {{ __('lang.view')}}</a>
										
										@if(helper::checkUserRights('store_manage','store_customerscreenslider'))
										<a class="dropdown-item" href="{{url('/admin/customerscreen/'.$StoreData->id)}}"><i class="bx bx-book-content"></i> {{ __('lang.customerscreenslider')}}</a>
										@endif
										
										@if(helper::checkUserRights('store_manage','store_storereports'))
										<a class="dropdown-item" href="{{url('/admin/storereports/'.$StoreData->id)}}"><i class="bx bx-book-content"></i> {{ __('lang.storereports')}}</a>
										@endif
										
										@if(helper::checkUserRights('store_manage','store_inventory'))
										<a class="dropdown-item" href="{{url('/admin/store/'.$StoreData->id.'/zeroinventory')}}" onclick="return confirm('Are you sure you want to zero the inventory?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.zeroinventory')}}</a>
										<a class="dropdown-item" href="{{url('/admin/store/'.$StoreData->id.'/emptyinventory')}}"  onclick="return confirm('Are you sure you want to empty the inventory?');"><i class="fadeIn animated bx bx-trash-alt"></i> {{ __('lang.emptyinventory')}}</a>
										@endif
										@if(helper::checkUserRights('store_manage','store_bills'))
										<a class="dropdown-item" href="{{url('/admin/order/?storeId='.$StoreData->id)}}"><i class="fadeIn animated bx bx-receipt"></i> {{ __('lang.bills')}}</a>
										@endif
										<!-- <a class="dropdown-item" href="{{url('/admin/report/sales/'.$StoreData->id)}}"><i class="fadeIn animated bx bxs-offer"></i> {{ __('lang.sales')}}</a>
										<a class="dropdown-item" href="{{url('/admin/shift/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-time"></i> {{ __('lang.shifts')}}</a> -->
										@if(helper::checkUserRights('store_manage','store_vendors'))
										<a class="dropdown-item" href="{{url('/admin/vendor/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-purchase-tag"></i> {{ __('lang.vendors')}}</a>
										@endif
										@if(helper::checkUserRights('store_manage','store_invioces'))
										<a class="dropdown-item" href="{{url('/admin/invoice/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-detail"></i> {{ __('lang.invoices')}}</a>
										@endif
										<!-- <a class="dropdown-item" href="{{url('/admin/purchaseorder/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-basket"></i> {{ __('lang.purchaseorderpo')}}</a> -->
										<?php if(Auth::user()->roleId == 1){?>
										    <a class="dropdown-item disabled" href="#" >
												<i class="fadeIn animated bx bx-trash-alt"></i> {{ __('lang.delete')}} 
											</a>
								    	   <!-- <a class="dropdown-item" href="{{route('store.destroy',['id'=>$StoreData->id])}}"  onclick="return confirm('Are you sure you want to delete the Store?\nNote: This will also delete all the data associated with the Store!');"><i class="fadeIn animated bx bx-trash-alt"></i> {{ __('lang.delete')}}</a>-->
										<?php } ?>
										<!--
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="#"><i class="fadeIn animated bx bx-log-in"></i> Login As</a>
										-->
										
										
									</div>
								</div>
							</td>
						</tr>
						
						<div class="modal fade" id="exampleModal{{$StoreData->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        Delete Store
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                
                      <div class="col-md-offset-2">
                        <div class="form-group">
                            <label for="name">Are you sure you want to delete this store? If Yes, Please type "DELETE" in the input box.</label>
                        </div>
                         <div class="form-group mt-3 mb-3">
                            <input type="text" value="" autofocus="autofocus" name="" class="form-control" placeholder="DELETE" id="keywordSearch1">
                            <input type="hidden" value="{{$StoreData->id}}" id="deleteStoreId">
                            <!--<button onclick="onClick(this)" class="btn btn-success mt-3" id="btSubmit" disabled="disabled">OK</button>-->
                            <button onclick="onClick(this)" class="btn btn-success mt-3" id="btSubmit">OK</button>
                        </div>
                
                        <form id='deleteStore_{{$StoreData->id}}' action="{{route('store.destroy',['id'=>$StoreData->id])}}" method="get">
                            <input type="hidden" name="delete" value="true" />
                        </form>
                        <!--<div class="items">
                          <input type="text" name="" id="keywordSearch1" placeholder="">
                          <button onclick="onClick(this)">button1</button>
                        </div>-->
                        
                
                    </div>
                
                                </div>
                
                            </div>
                        </div>
                    </div>
						
						@else
						<tr style="background-color:#ffcccc; vertical-align: middle !important;">
                            <td>{{$StoreData->storeName}}</td>
                            <td>{{$StoreData->appVersion}}</td>
							<td>{{$StoreData->appType}} {{$StoreData->deviceType}}</td>
                            <td>{{$StoreData->contactNumber}}</td>
                            <td>{!! App\Helpers\AppHelper::lastStoreBilled($StoreData->id) !!}</td>
							<td><a href="{{url('/admin/order/'.$StoreData->id)}}">{!! App\Helpers\AppHelper::totalStoreOrders($StoreData->id) !!}</a></td>
							<!--<td>{!! App\Helpers\AppHelper::totalStoreOrders($StoreData->id) !!}</td>-->
                            <td>{!! App\Helpers\AppHelper::todayStoreOrders($StoreData->id) !!}</td>
                            <td>{{$StoreData->subscriptionExpiry}}</td>
                            <td>
								<div class="btn-group">
									<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><i class="bx bx-show"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
										<!--<a class="dropdown-item" href="{{url('/admin/category/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-spreadsheet"></i> Categories</a>-->
										<a class="dropdown-item" href="{{url('/admin/store/'.$StoreData->id.'/edit')}}"><i class="fadeIn animated bx bx-edit"></i> {{ __('lang.edit')}}</a>
							
										@if($StoreData->status==='Active')
    										<a class="dropdown-item" href="{{url('/admin/store/'.$StoreData->id.'/disable')}}" onclick="return confirm('Are you sure you want to disable the store?');"><i class="fadeIn animated bx bx-notification-off"></i> {{ __('lang.disable')}}</a>
    									@else
    										<a class="dropdown-item" href="{{url('/admin/store/'.$StoreData->id.'/enable')}}" onclick="return confirm('Are you sure you want to enable the store?');"><i class="fadeIn animated bx bx-notification"></i> {{ __('lang.enable')}}</a>
    									@endif
										<a class="dropdown-item" href="{{url('/admin/cashier/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-group"></i> {{ __('lang.storecashiers')}}</a>
										<a class="dropdown-item" href="#"><i class="bx bx-book-content"></i> {{ __('lang.lowinventory')}}</a>
										<a class="dropdown-item" href="{{url('/admin/configemail/'.$StoreData->id.'/edit')}}"><i class="fadeIn animated bx bx-mail-send"></i> {{ __('lang.configemail')}}</a>
										<a class="dropdown-item" href="{{url('/admin/product/'.$StoreData->id)}}"><i class="bx bx-book-content"></i> {{ __('lang.inventory')}}</a>
										<a class="dropdown-item" href="{{url('/admin/store/'.$StoreData->id.'/view')}}"><i class="bx bx-show"></i> {{ __('lang.view')}}</a>
										<a class="dropdown-item" href="{{url('/admin/customerscreen/'.$StoreData->id)}}"><i class="bx bx-book-content"></i> {{ __('lang.customerscreenslider')}}</a>
										<a class="dropdown-item" href="{{url('/admin/report/'.$StoreData->id)}}"><i class="bx bx-book-content"></i> {{ __('lang.reports')}}</a>
										<a class="dropdown-item" href="{{url('/admin/store/'.$StoreData->id.'/zeroinventory')}}" onclick="return confirm('Are you sure you want to zero the inventory?');"><i class="fadeIn animated bx bx-trash"></i> {{ __('lang.zeroinventory')}}</a>
										<a class="dropdown-item" href="{{url('/admin/store/'.$StoreData->id.'/emptyinventory')}}"  onclick="return confirm('Are you sure you want to empty the inventory?');"><i class="fadeIn animated bx bx-trash-alt"></i> {{ __('lang.emptyinventory')}}</a>
										<a class="dropdown-item" href="{{url('/admin/order/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-receipt"></i> {{ __('lang.bills')}}</a>
										<a class="dropdown-item" href="{{url('/admin/report/sales/'.$StoreData->id)}}"><i class="fadeIn animated bx bxs-offer"></i> {{ __('lang.sales')}}</a>
										<a class="dropdown-item" href="{{url('/admin/shift/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-time"></i> {{ __('lang.shifts')}}</a>
										<a class="dropdown-item" href="{{url('/admin/vendor/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-purchase-tag"></i> {{ __('lang.vendors')}}</a>
										<a class="dropdown-item" href="{{url('/admin/invoice/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-detail"></i> {{ __('lang.invoices')}}</a>
										<!-- <a class="dropdown-item" href="{{url('/admin/purchaseorder/'.$StoreData->id)}}"><i class="fadeIn animated bx bx-basket"></i> {{ __('lang.purchaseorderpo')}}</a> -->
										<?php if(Auth::user()->roleId == 1){?>
										    <a class="dropdown-item disable" href="{{route('store.destroy',['id'=>$StoreData->id])}}"  onclick="return confirm('Are you sure you want to delete the Store?');"><i class="fadeIn animated bx bx-trash-alt"></i> {{ __('lang.delete')}}</a>
										<?php } ?>
										<!--
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="#"><i class="fadeIn animated bx bx-log-in"></i> Login As</a>
										-->
									</div>
								</div>
							</td>
						</tr>
						@endif
						
						
						
					@endforeach
					</tbody>
				</table>
                @if ($storecount > 0)
    				<div class="pagination_links">
    				{{$stores->appends(array('search' => $search,'storeFilter'=>$storeFilter))->links()}}
    				</div>
				@endif
			</div>
		</div>
	</div>
</div>







<!--end row-->


@include('admin.layout.footer')
<!--<script src=//code.jquery.com/jquery-3.5.1.slim.min.js integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin=anonymous></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->

<script>

function onClick(elem) {
  var $this = $(elem); //< -- wrap the element in a jQuery wrapper-->
  var val = $this.siblings('input[type=text]').val();
   var storeId = $this.siblings('input[type=hidden]').val();
  //var bt = document.getElementById('btSubmit');
    if (val == '' || val!= 'DELETE') 
    {
        alert('Please enter "DELETE" in the input box');
    } 
    else 
    {
    //alert(val);
        if(val == 'DELETE')
        {
            //alert('yes');
            document.getElementById("deleteStore_"+storeId).submit();
            
            document.getElementById("deleteStoreBtn").click();
        }
    }
}

/*
function changeInput(txt)
{
    var bt = document.getElementById('btSubmit');
    alert(txt.value);
    if (txt.value = 'DELETE') {
        bt.disabled = false;
    }
    else
    {
        bt.disabled = true;
    }
}

*/





<style>
    .dataTables_filter {display:none;}
    .dropdown-menu,.dropdown-menu.dropdown-menu-right.dropdown-menu-lg-end {  margin-bottom: 20px !important;}
</style>