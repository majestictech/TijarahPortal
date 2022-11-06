
@include('admin.layout.header')
   <div class="content-page">
     <div class="container-fluid">
                <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">{{ __('lang.loyaltypointstrans')}}</h4>
               
                </div>
            </div>
			
		    <div class="col-lg-9">
			  <div class="table-responsive rounded mb-3">
                <table class="data-table table mb-0 tbl-server-info">
                 
                    <tbody >
					
                        <tr class="table-active">
                            <td><b>{{ __('lang.storename')}}</b></td> 
                            <td>{{$loyaltytransactions->storeName}}</td> 
							
						</tr>
						<tr>
                            <td><b>{{ __('lang.loyaltypoints')}}</b></td>
                            <td>{{$loyaltytransactions->points}}</td>
						</tr>
				
						<tr class="table-active">
                            <td><b>{{ __('lang.type')}}</b></td>
                            <td>{{$loyaltytransactions->type}}</td>
						</tr >
						
						<tr>
                            <td><b>{{ __('lang.crdr_at')}}</b></td>
                            <td>{{$loyaltytransactions->created_at}}</td>
						</tr>
					


						
                                   
                                                  
                    </tbody>
                </table>
                </div>
      
            </div>
			

    </div>
    
      </div>
    </div>
    </div>
	
    <!-- Wrapper End-->
@include('admin.layout.footer')