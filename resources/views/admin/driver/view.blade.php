
@include('admin.layout.header')
   <div class="content-page">
     <div class="container-fluid">
                <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{ __('lang.driverdetails')}}</h4>
                        <p class="mb-0">{{ __('lang.driverdetailsdesc')}}</p>
                    </div>
                    <a href="{{url('/admin/driver')}}" class="btn btn-primary add-list">{{ __('lang.driverlist')}}</a>
                </div>
            </div>
		
			
			<div class="col-lg-9">
			  <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0 tbl-server-info">
                 
                    <tbody >
					
                        <tr class="table-active">
                            <td><b>Full Name</b></td> 
                            <td>{{$driverdata->firstName}} {{$driverdata->lastName}}</td> 
							
						</tr>
						<tr>
                            <td><b>Gender</b></td>
                            <td>{{$Gender[$driverdata->gender]}}</td>
						</tr>
						<tr class="table-active">
                            <td><b>Phone Number</b></td>
                            <td>{{$driverdata->contactNumber}}</td>
						</tr>
						<tr>
                            <td><b>Type of Vehicle</b></td>
                            <td>{{$driverdata->typeofvehicle}}</td>
						</tr >
						<tr class="table-active">
							<td><b>Vehicle Number</b></td>
							<td>{{$driverdata->vehicleNumber}}</td>
						</tr>
						<tr>
                            <td><b>Hours of Service</b></td>
                            <td>{{$driverdata->hoursOfServiceFrom}} - {{$driverdata->hoursOfServiceTo}}</td>
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