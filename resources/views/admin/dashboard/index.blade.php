<?php
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Helpers\AppHelper as Helper;

$Roles = config('app.Roles');
?>

@include('admin.layout.header')
@if(Auth::user()->roleId != 4)

<script type="text/javascript" src="/js/themes/gray.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="{{ URL::asset('public/assets/js/jquery.min.js') }}"></script>

  
    <form action="" method="GET" id ="filter_results">
      <div class="row">
        <div class="col-md-3 mb-3">
          <label for="storeFilter" class="form-label">{{ __('lang.filterby')}}</label>

          <div class="input-group">
               <button class="btn btn-outline-secondary" type="button">
                  <i class='bx bx-store'></i>
               </button>
                <select name="storeFilter" class="form-select single-select" id="storeFilter" onChange="this.form.submit();">
                  <option value="" @if(empty($allStores)) selected="selected" @endif>{{ __('lang.store')}}</option>
                  @foreach($allStores as $key=>$allStore)
                    <option value="{{ $allStore->id }}" @if($allStore->id==$storeFilter) selected="selected" @endif >{{ $allStore->storeName }} </option>
                  @endforeach
                </select>
          </div>
         </div>
        <div class="col-md-4">

        </div>
        <div class="col-md-2">
            <div class="input-group mt-4">
              <input type="date" class="form-control " name="startDate" id="startDate" value="{{$startDate}}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group mt-4">
              <input type="date" class="form-control " name="endDate" id="endDate" value="{{$endDate}}">
            </div>
        </div>
        <div class="col-md-1">
            <div class="input-group mt-4">
            <label for="" class="form-label"></label>
							<button type="submit" class="btn btn-primary px-2">ok</button>
            </div>
        </div>
         
        </div>
      </div>
    </form>
    

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
    	<!-- <div class="col">
    		<div class="card radius-10 overflow-hidden">
    			<div class="card-body">
    				<div class="d-flex align-items-center">
    					<div>
    						<p class="mb-0 text-secondary font-14"><a href="#">{{ __('lang.todaysorders')}}</a></p>
    						<h5 class="my-0"></h5>
    					</div>
    					<div class="text-primary ms-auto font-30"><i class='bx bx-cart-alt'></i>
    					</div>
    				</div>
    			 </div>
    			<div class="mt-1" id="chart1"></div>
    		</div>
    	</div> -->
    	<div class="col">
    		<div class="card radius-10 overflow-hidden">
    			<div class="card-body">
    				<div class="d-flex align-items-center">
    					<div>
    						<p class="mb-0 text-secondary font-14"><a href="{{url('/admin/order')}}"> {{__('lang.totalorders')}} </a></p>
    						<h5 class="my-0">{{$allorderCount ?? ''}}</h5>
    					</div>
    					<div class="text-info ms-auto font-30"><i class='bx bx-cart'></i>
    					</div>
    				</div>
    			</div>
    			<div class="mt-1" id="chart5"></div>
    		</div>
    	</div>
    	
    	<div class="col">
    		<div class="card radius-10 overflow-hidden">
    			<div class="card-body">
    				<div class="d-flex align-items-center">
    					<div>
    						<p class="mb-0 text-secondary font-14"><a href="#">{{ __('lang.totalrevenue')}}</a></p>
    						<h5 class="my-0">SAR {{ $revenues->totalAmount ?? ''}}</h5>
    					</div>
    					<div class="text-danger ms-auto font-30">ريال
    					</div>
    				</div>
    			</div>
    			<div class="mt-1" id="chart2"></div>
    		</div>
    	</div>
    	<div class="col">
    		<div class="card radius-10 overflow-hidden">
    			<div class="card-body">
    				<div class="d-flex align-items-center">
    					<div>
    						<p class="mb-0 text-secondary font-14">
                  <a href="#">{{ __('lang.customers')}}</a>
                </p>
    						<h5 class="my-0">{{$allcustomer ?? ''}}</h5>
    					</div>
    					<div class="text-success ms-auto font-30"><i class='bx bx-group'></i>
    					</div>
    				</div>
    			</div>
    			<div class="mt-1" id="chart3"></div>
    		</div>
    	</div>
    	<div class="col">
    		<div class="card radius-10 overflow-hidden">
    			<div class="card-body">
    				<div class="d-flex align-items-center">
    					<div>
    						<p class="mb-0 text-secondary font-14"><a href="{{url('/admin/store')}}">{{ __('lang.activestores')}}</a></p>
    						<h5 class="my-0">{{$activestores ?? ''}}</h5>
    					</div>
    					<div class="text-warning ms-auto font-30"><i class='bx bx-store-alt'></i>
    					</div>
    				</div>
    			</div>
    			<div class="mt-1" id="chart4"></div>
    		</div>
    	</div>
    	
    </div><!--end row-->
    
    
    <div class="row">
<!--     	<div class="col-12 col-lg-6">
    		<div class="card radius-10" style="padding-top: 15px;">
    			<div class="card-body">
    			   <div class="d-flex align-items-center">
    				   <div>
    					   <h6 class="mb-0">{{ __('lang.revenue')}}</h6>
    				   </div>
    				   <div class="dropdown ms-auto">
    					   <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
    					   </a>
    					   <ul class="dropdown-menu">
    						   <li><a class="dropdown-item" href="javascript:;">{{ __('lang.action')}}</a>
    						   </li>
    						   <li><a class="dropdown-item" href="javascript:;">{{ __('lang.anotheraction')}}</a>
    						   </li>
    						   <li>
    							   <hr class="dropdown-divider">
    						   </li>
    						   <li><a class="dropdown-item" href="javascript:;">{{ __('lang.somethingelsehere')}}</a>
    						   </li>
    					   </ul>
    				   </div>
    			   	</div>
					<div class="chart-container-0">
						<canvas id="chart7"></canvas>
					</div>
    			</div>
    		</div>
    	</div> -->
      <div class="col-12 col-lg-6">
        <div class="card radius-10">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div>
                <h6 class="mb-0">{{ __('lang.revenue')}}</h6>
              </div>
              <!-- <div class="dropdown ms-auto">
    						<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
    						</a>
    						<ul class="dropdown-menu">
    							<li><a class="dropdown-item" href="javascript:;">{{ __('lang.action')}}</a>
    							</li>
    							<li><a class="dropdown-item" href="javascript:;">{{ __('lang.anotheraction')}}</a>
    							</li>
    							<li>
    								<hr class="dropdown-divider">
    							</li>
    							<li>
                    <a class="dropdown-item" href="javascript:;">
                      {{ __('lang.somethingelsehere')}}
                    </a>
    							</li>
    						</ul>
    					</div> -->
    				</div>
          
            <div class="chart-container-0">
    					<!-- <canvas id="chart-order-status"></canvas> -->
              <figure class="highcharts-figure">
                <div id="chart-h1"></div>
              </figure>
    				</div>
          </div>
        </div>
      </div>
    	<div class="col-12 col-lg-6">
    		<div class="card radius-10">
    			<div class="card-body">
    				<div class="d-flex align-items-center">
    					<div>
    						<h6 class="mb-0">{{ __('lang.number_of_bills')}}</h6>
    					</div>
    					<div class="dropdown ms-auto">
    						<a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
    						</a>
    						<ul class="dropdown-menu">
    							<li><a class="dropdown-item" href="javascript:;">{{ __('lang.action')}}</a>
    							</li>
    							<li><a class="dropdown-item" href="javascript:;">{{ __('lang.anotheraction')}}</a>
    							</li>
    							<li>
    								<hr class="dropdown-divider">
    							</li>
    							<li><a class="dropdown-item" href="javascript:;">{{ __('lang.somethingelsehere')}}</a>
    							</li>
    						</ul>
    					</div>
    				</div>
    				<div class="chart-container-0">
    					<!-- <canvas id="chart-order-status"></canvas> -->
              <figure class="highcharts-figure">
                <div id="chart-10"></div>
              </figure>
    				</div>
    			</div>
    		</div>
    	</div>
    </div><!--end row-->

	<div class="row">
		
		
		<div class="col-12 col-lg-6">
			<div class="card radius-10" style="height: 300px;">
				<div class="">
					<div style="background-color: #fff; align-items: center;">
            <h4 class="p-2" style="text-align:left; color: #272727; margin-bottom: 0; font-size: 1rem; margin-top: 13px;">
            {{ __('lang.top_selling_items')}}
            </h4>
          </div>
					<table class="table" style="border: 1px solid #e9ecef; border-left: 0; border-right: 0px;" >
						<thead style="background-color: #157d4c; color: #fff;">
							<tr style="border-bottom: 1px solid #ccc;">
								<th style="font-weight: 100;">{{ __('lang.name')}}</th>
								<!-- <th style="font-weight: 100;">{{ __('lang.brand')}}</th> -->
								<th style="font-weight: 100;">{{ __('lang.sellingprice')}}</th>
                <th style="font-weight: 100;">{{ __('lang.quantity')}}</th>
							</tr>
						</thead>
						<tbody style="">
              @foreach($topSellingData as $key =>$topSellingDatas)
                  <tr> 
                    <td>{{$topSellingDatas->productName}}</td>
                    <td>{{$topSellingDatas->price}}</td>
                    <td>{{$topSellingDatas->totalQty}}</td>
                 </tr>
              @endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-6">
			<!--<div class="card" style="height: px;">
				<div class="card-body">
					<canvas id="myChart" style="width:100%;max-width:600px"></canvas>
				</div>
			</div>-->
      <div class="card radius-10">
        <div class="card-body">
          <h4 class="p-2" style="text-align:left; color: #272727; margin-bottom: 0; font-size: 1rem;">{{ __('lang.inventory')}}</h4>
          <div class="row">
            <div class="col-4" style="margin-top: 38px;"> 
              <div class="card-body" style="padding: 0;">
                  <!--<canvas id="myChart" class="card" width="200" height="200"></canvas>-->
                  <div class="card" id="chart" ></div>
                  <div class="card" style="padding: 5px; color: #157d4c;; font-weight: 600; margin-top: -22px; text-align: center; background: #eff3f6;">{{ __('lang.available')}}</div>
              </div>
            </div>
            <div class="col-4" style="margin-top: 38px;">
              <div class="card-body" style="padding: 0;">
                <!--<canvas id="myChart1" class="card" width="150" height="150"></canvas>-->
                <div class="card" id="chart-1" ></div>
                <div class="card" style="padding: 5px; color: #ff616d;; font-weight: 600; margin-top: -22px; text-align: center; background: #eff3f6;">{{ __('lang.out_of_stock')}}</div>
              </div>
            </div>
            <div class="col-4" style="margin-top: 38px;">
              <div class="card-body" style="padding: 0;">
                  <!--<canvas id="myChart2" class="card" width="150" height="150"></canvas>-->
                  <div class="card" id="chart-2" ></div>
                  <div class="card" style="padding: 5px; color: #f58634;; font-weight: 600; margin-top: -22px; text-align: center; background: #eff3f6;">{{ __('lang.low_inventory')}}</div>
              </div>
            </div>
          </div>
        </div>		
      </div>

	

@endif    

@if(Auth::user()->roleId == 4)
<?php $idUser = Auth::user()->id;
//echo $idUser;
?>
   <div class="content-page">
     <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        @if($storedata->printStoreNameAr != null)
                            <h4 class="mb-3">{{$storedata->storeName}} &nbsp; ({{$storedata->printStoreNameAr}})</h4>
                        @else
                            <h4 class="mb-3">{{$storedata->storeName}} &nbsp;</h4>
                        @endif
                        <h5 style="margin-top: 2px;">
                          (Store ID-{{$storedata->id}}) &nbsp; (User ID-{{$storedata->userId}})
                        </h5>
                    </div>
                </div>
            </div>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            		<!--
                <div class="col-3">
                 <div class="card radius-10 overflow-hidden">
            			<div class="card-body">
            				<div class="d-flex align-items-center">
            					<div>
            						<p class="mb-0 text-secondary font-14"><a href="{{url('/admin/order/'.$storedata->id)}}">{{ __('lang.todaysorders')}}</a></p>
            						<h5 class="my-0">{{$todayorderCount ?? ''}}</h5>
            					</div>
            					<div class="text-primary ms-auto font-30"><i class='bx bx-cart-alt'></i>
            					</div>
            				</div>
            			 </div>
            			<div class="mt-1" id="chart1"></div>
            		</div> 
                -->
            	</div>
            	<div class="col-3">
            		<div class="card radius-10 overflow-hidden">
            			<div class="card-body">
            				<div class="d-flex align-items-center">
            					<div>
            						<p class="mb-0 text-secondary font-14"><a href="{{url('/admin/order/'.$storedata->id)}}">{{ __('lang.totalorders')}}</a></p>
            						<h5 class="my-0">{{$allorderCount ?? ''}}</h5>
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
            						<h5 class="my-0">SAR {{$revenues->totalAmount ?? ''}}</h5>
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
            						<h5 class="my-0">{{$allcustomer ?? ''}}</h5>
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
                        
					    <!-- <tr class="table-active">
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
						
						<tr >
                            <td><b>{{ __('lang.latitude')}}</b></td>
                            <td>{{$storedata->latitude}}</td>
						</tr>
						
						<tr class="table-active">
                            <td><b>{{ __('lang.longitude')}}</b></td>
                            <td>{{$storedata->longitude}}</td>
						</tr>
						
                        
						<tr >
                            <td><b>{{ __('lang.storeaddress')}}</b></td>
                            <td>{{$storedata->address}}</td>
						</tr>
						
						<tr class="table-active">
                            <td><b>{{ __('lang.storeaddar')}}</b></td>
                            <td>{{$storedata->printAddAr}}</td>
						</tr>
						
						
						<tr>
                            <td><b>{{ __('lang.ecrappvr')}}</b></td>
                            <td>{{$storedata->appVersion}}</td>
						</tr>
                                   
                                                  
                    </tbody>
                </table>
                </div>
      
            </div>
			
			
        </div>
        <!-- Page end  -->
    </div>
    
      </div>

@endif    

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script> -->

<!-- <script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
<script src="assets/plugins/apexcharts-bundle/js/apex-custom.js"></script> -->

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script type="text/javascript" src="/js/themes/gray.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Set up the chart container





/*
// Activate the sliders
document.querySelectorAll('#sliders input').forEach(input => input.addEventListener('input', e => {
  chart.options.chart.options3d[e.target.id] = parseFloat(e.target.value);
  showValues();
  chart.redraw(false);
}));

showValues();
*/
// Set up the chart for container 1
const container1 = new Highcharts.Chart({
  chart: {

    renderTo: 'container1',
    type: 'column',
	margin: [0, 0, 0, 0],
        spacingTop: 0,
        spacingBottom: 0,
        spacingLeft: 0,
        spacingRight: 0
    options3d: {
      enabled: true,
      alpha: 5,
      beta: 15,
      depth: 100,
      viewDistance: 5
    }
  },
  xAxis: {
    categories: [23, 22, 20, 18, 17, 15]
  },
  yAxis: {
    title: {
      enabled: false
    }
  },
  tooltip: {
    headerFormat: '<b>{point.key}</b><br>',
    pointFormat: 'Cars sold: {point.y}'
  },
  title: {
    text: '{{ __('lang.total_bills')}}'
  },
  subtitle: {
    text: 'Source: '
  },
  legend: {
    enabled: true
  },
  plotOptions: {
    column: {
      depth: 13
    }
  },
  series: [{
	name: 'Total Bills',
    data: [6, 5, 3, 4, 2, 1, 0],
    colorByPoint: true

  }]
});

// Set up the chart for container 2
const container2 = new Highcharts.Chart({
  chart: {
    renderTo: 'container2',
    type: 'column',
    options3d: {
      enabled: true,
      alpha: 5,
      beta: 15,
      depth: 100,
      viewDistance: 5
    }
  },
  xAxis: {
    categories: [23, 22, 20, 18, 17, 15]
  },
  yAxis: {
    title: {
      enabled: false
    }
  },
  tooltip: {
    headerFormat: '<b>{point.key}</b><br>',
    pointFormat: 'Cars sold: {point.y}'
  },
  title: {
    text: '{{ __('lang.average_basket')}}'
  },
  subtitle: {
    text: 'Source: '
  },
  legend: {
    enabled: true
  },
  plotOptions: {
    column: {
      depth: 13
    }
  },
  series: [{
	name: 'Average Basket',
    data: [6, 5, 3, 4, 2, 1, 0],
    colorByPoint: true

  }]
});


Highcharts.chart('chart-1', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45
        }
    },
    title: {
        text: ''
    },
    plotOptions: {
        pie: {
			dataLabels: {
				enabled: false
			},
            innerSize: 80,
            depth: 50
        }
    },
    series: [{
        name: 'Medals',
        data: [
            ['Norway', 16],
            ['Japan', 3]

        ]
    }]
});


/*// chart 2
$(function() {
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'chart-2',
            type: 'pie'
        },
        plotOptions: {
            pie: {
                innerSize: '85%'
            }
        },
        title: {
            text: ''
        },
        series: [{
            data: [
                ['Firefox', 2262],
                ['IE7', 3800],
                ['IE6', 1000],
                ['Chrome', 1986]
                ]}]
    },
                                     
    function(chart) { // on complete
        var textX = chart.plotLeft + (chart.series[0].center[0]);
        var textY = chart.plotTop  + (chart.series[0].center[1]);

        var span = '<span id="pieChartInfoText" style="position:absolute; text-align:center;">';
        span += '<span style="font-size: 32px">Upper</span><br>';
        span += '<span style="font-size: 16px">Lower</span>';
        span += '</span>';

        $("#addText").append(span);
        span = $('#pieChartInfoText');
        span.css('left', textX + (span.width() * -0.5));
        span.css('top', textY + (span.height() * -0.5));
    });
});
*/
</script>
<script>

/*	var options = {
          series: [70],
          chart: {
          height: 350,
          type: 'radialBar',
        },
        plotOptions: {
          radialBar: {
            hollow: {
              size: '50%',
            }
          },
        },
        labels: ['Cricket'],
        };

        var chart = new ApexCharts(document.querySelector("#myChart"), options);
        chart.render();
*/
 
/* var options = {
          series: [75],
          chart: {
          height: 350,
          type: 'radialBar',
          toolbar: {
            show: true
          }
        },
        plotOptions: {
          radialBar: {
            startAngle: -135,
            endAngle: 225,
             hollow: {
              margin: 0,
              size: '70%',
              background: '#fff',
              image: undefined,
              imageOffsetX: 0,
              imageOffsetY: 0,
              position: 'front',
              dropShadow: {
                enabled: true,
                top: 3,
                left: 0,
                blur: 4,
                opacity: 0.24
              }
            },
            track: {
              background: '#fff',
              strokeWidth: '67%',
              margin: 0, // margin is in pixels
              dropShadow: {
                enabled: true,
                top: -3,
                left: 0,
                blur: 4,
                opacity: 0.35
              }
            },
        
            dataLabels: {
              show: true,
              name: {
                offsetY: -10,
                show: true,
                color: '#888',
                fontSize: '17px'
              },
              value: {
                formatter: function(val) {
                  return parseInt(val);
                },
                color: '#111',
                fontSize: '36px',
                show: true,
              }
            }
          }
        },
        fill: {
          type: 'gradient',
          gradient: {
            shade: 'dark',
            type: 'horizontal',
            shadeIntensity: 0.5,
            gradientToColors: ['#ABE5A1'],
            inverseColors: true,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 100]
          }
        },
        stroke: {
          lineCap: 'round'
        },
        labels: ['Percent'],
        };

        var chart = new ApexCharts(document.querySelector("#myChart"), options);
        chart.render();*/



/*var xValues = [];
var yValues = [70, 30];
var barColors = [
  "#157d4c",
  "#e5e9ec"
]; 

/*new Chart("myChart", {
  type: "doughnut",
  width: "10%", 
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
 
options: {
	series: [70],
	cutout: '10%',
    title: {
      display: true,
      text: "16,317",
	  alpha: "40"
    }
  }
  
  
});*/

/*var xValues = ["Low Inventory","Out of Stock"];*/
/*var yValues = [40, 60];
var barColors = [
  "#ff616d",
  "#e5e9ec"
];

new Chart("myChart1", {
  type: "doughnut",
  width: "10%", 
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
 
options: {
	series: [40],
	cutout: '10%',
    title: {
      display: true,
      text: "2,089",
	  alpha: "40"
    }
  }
  
  
});

/*var xValues = ["Low Inventory","Out of Stock"];*/
/*var yValues = [25, 75];
var barColors = [
  "#f58634",
  "#e5e9ec"
];

new Chart("myChart2", {
  type: "doughnut",
  width: "10%", 
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
 
options: {
	series: [70],
	cutout: '10%',
    title: {
      display: true,
      text: "978",
	  alpha: "40"
    }
  }
  
  
});*/
/*  -------Available Chart Strat------*/

var allProducts = {{$allProducts}};
if(allProducts <= 0)
   allProducts = 1;

var productAvailable = {{$productAvailable}};
if (allProducts> 0)
productAvailablePercentage = (productAvailable/allProducts)*100;
productAvailablePercentage =productAvailablePercentage.toFixed(2);
if (allProducts <= 0)
productAvailablePercentage =0;
var options = {
  chart: {
    height: 160,
    type: "radialBar",
  },

  series: [productAvailablePercentage],
  colors: ["#157d4c"],
  plotOptions: {
    radialBar: {
      hollow: {
        margin: 0,
        size: "60%",
      },
      track: {
        dropShadow: {
          enabled: true,
          top: 2,
          left: 0,
          blur: 4,
          opacity: 0.15
        }
      },
      dataLabels: {
        name: {
          offsetY: -10,
          color: "#000",
          fontSize: "13px"
        },
        value: {
          color: "#000",
          fontSize: "0",
          show: true
        }
      }
    }
  },
  fill: {
    type: "gradient",
    gradient: {
      shade: "dark",
      type: "vertical",
      gradientToColors: ["#157d4c"],
      stops: [0, 100]
    }
  },
  stroke: {
    lineCap: "round"
  },
  labels: [{{$productAvailable}}]
};

var chart = new ApexCharts(document.querySelector("#chart"), options);

chart.render();
/*  -------Available Chart End------*/



/*  -------Out Of Stock Chart Start------*/
var outOfStock = {{$outOfStock}};
var outOfStockPercentage = (outOfStock/allProducts)*100;
outOfStockPercentage = outOfStockPercentage.toFixed(2);
var options = {
  chart: {
    height: 160,
    type: "radialBar",
  },

  series: [outOfStockPercentage],
  colors: ["#ff616d"],
  plotOptions: {
    radialBar: {
      hollow: {
        margin: 0,
        size: "60%",
      },
      track: {
        dropShadow: {
          enabled: true,
          top: 2,
          left: 0,
          blur: 4,
          opacity: 0.15
        }
      },
      dataLabels: {
        name: {
          offsetY: -10,
          color: "#000",
          fontSize: "13px"
        },
        value: {
          color: "#000",
          fontSize: "0",
          show: true
        }
      }
    }
  },
  fill: {
    type: "gradient",
    gradient: {
      shade: "dark",
      type: "vertical",
      gradientToColors: ["#ff616d"],
      stops: [0, 100]
    }
  },
  stroke: {
    lineCap: "round"
  },
  labels: [{{$outOfStock}}]
};

var chart = new ApexCharts(document.querySelector("#chart-1"), options);

chart.render();
/*  -------Out Of Stock Chart End------*/


/*  -------Low inventory Chart Start------*/
var lowInventory = {{$lowInventory}};
var lowInventoryPercentage = (lowInventory/allProducts)*100;
lowInventoryPercentage = lowInventoryPercentage.toFixed(2);
var options = {
  chart: {
    height: 160,
    type: "radialBar",
  },

  series: [lowInventoryPercentage],
  colors: ["#f58634"],
  plotOptions: {
    radialBar: {
      hollow: {
        margin: 0,
        size: "60%",
      },
      track: {
        dropShadow: {
          enabled: true,
          top: 2,
          left: 0,
          blur: 4,
          opacity: 0.15
        }
      },
      dataLabels: {
        name: {
          offsetY: -10,
          color: "#000",
          fontSize: "13px"
        },
        value: {
          color: "#000",
          fontSize: "0",
          show: true
        }
      }
    }
  },
  fill: {
    type: "gradient",
    gradient: {
      shade: "dark",
      type: "vertical",
      gradientToColors: ["#f58634"],
      stops: [0, 100]
    }
  },
  stroke: {
    lineCap: "round"
  },
  labels: [{{$lowInventory}}]
};

var chart = new ApexCharts(document.querySelector("#chart-2"), options);

chart.render();
/*  -------Low inventory Chart End------*/



/*
$("#chart22").sparkline([3,5,3,7,5,10,3,6,5,0], {
            type: 'line',
            width: '150',
            height: '45',
            lineWidth: '2',
            lineColor: '#1769ff',
            fillColor: 'rgba(23, 105, 255, 0.5)',
            spotColor: '#1769ff',
    });
	*/

 /* Number of Bills Chart Start */
 // Set up the chart
 Highcharts.chart('chart-10', {
  chart: {
    type: 'column',
    options3d: {
      enabled: true,
      alpha: 1,
      beta: 5,
      depth: 45
    }
  },
  title: {
    text: ''
  },
  /* subtitle: {
    text: 'Source: ' +
      '<a href="https://www.ssb.no/en/statbank/table/08804/"' +
      'target="_blank">SSB</a>'
  }, */
  plotOptions: {
    column: {
      depth: 15,
      color: '#157d4c'
    }
  },
  xAxis: {
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
  },
  yAxis: {
    title: {
      text: 'NOk (million)',
      margin: 20
    }
  },
  tooltip: {
    valueSuffix: ''
  },
  series: [{
    name: 'Sales',
    data: [16076, 112688, 39452, 58545, 94352,
      30495]
  }]
});
	
/* Number of Bills Chart End */
</script>
<script>

/* Revenue Chart Start */
console.log(132);
let graphDayCount = 10; //call graphDayCount variale 
console.log(graphDayCount);
for (let i=0; i<graphDayCount; i++){
  $i =i;
  let revenueLabel = 0;
  let revenueData = 0;
  console.log(revenueLabel);
  console.log(revenueData);

  Highcharts.chart('chart-h1', {
      chart: {
          type: 'area'
      },
      xAxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] 
          
      },

      plotOptions: {
        line: {
          dataLabels: {
            enabled: true
          },
          enableMouseTracking: false,

        }
      },
      
      series: [{
          type: undefined,
          data: [40, 30, 20 , 10, 50, 100],
          colors: ['#000000', '#006C35'],
            colorByPoint: true
      }]
  });
}
/* Revenue Chart End */


</script>
<style>
	.table>:not(:last-child)>:last-child>* {
    border-bottom-color: #ccc;
}
.highcharts-root {
  height: 335px;
}
rect.highcharts-point {
  fill: #157d4c;
}
</style>



@include('admin.layout.footer')