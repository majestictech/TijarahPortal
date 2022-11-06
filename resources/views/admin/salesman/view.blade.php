
@include('admin.layout.header')
   <div class="content-page">
     <div class="container-fluid">
                <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{ __('lang.salesmandetails')}}</h4>
                        <p class="mb-0">{{ __('lang.salesmandetailsdesc')}}</p>
                    </div>
                    <a href="{{url('/admin/salesman')}}" class="btn btn-primary add-list">{{ __('lang.salesmanlist')}}</a>
                </div>
            </div>
				<div class="col-lg-9">
			  <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0 tbl-server-info">
                 
                    <tbody >
					
                        <tr class="table-active">
                            <td><b>Full Name</b></td> 
                            <td>{{$salesmandata->firstName}} {{$salesmandata->lastName}}</td> 
							
						</tr>
						<tr>
                            <td><b>Gender</b></td>
                            <td>{{$Gender[$salesmandata->gender]}}</td>
						</tr>
						<tr class="table-active">
                            <td><b>Phone Number</b></td>
                            <td>{{$salesmandata->contactNumber}}</td>
						</tr>
						<tr>
                            <td><b>Type of Vehicle</b></td>
                            <td>{{$salesmandata->typeofvehicle}}</td>
						</tr >
						<tr class="table-active">
							<td><b>Vehicle Number</b></td>
							<td>{{$salesmandata->vehicleNumber}}</td>
						</tr>
						<tr>
                            <td><b>Hours of Service</b></td>
                            <td>{{$salesmandata->hoursOfServiceFrom}} - {{$salesmandata->hoursOfServiceTo}}</td>
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