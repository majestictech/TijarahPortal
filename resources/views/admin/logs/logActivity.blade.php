@include('admin.layout.header')							
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('log_manage','');
?>


<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">Activity Log</h6>
		<hr/>

		
		
		<div class="card">
			<div class="card-body">
			    
			    <form action="" method="GET" id ="filter_results">
			       <div class="row"> 
						<div class="col-md-4 mb-3 ">
							<label for="search" class="form-label">Search</label>
							 <input type="text" name="search" class="form-control form-control-sm" value="{{$search}}"/>
						   
						</div>
						<div class="col-md-4 mb-3 pt-4">
							  <label for="" class="form-label"></label>
							 <button type="submit" class="btn btn-primary px-5">Search</button>
						</div>
    				
    				
                    </div>
			    </form>
			    
			    
			    
			    
				<table class="table mb-0 table-striped table-bordered" id="myTable">
            		<tr>
            			<th>No</th>
            			<th>Subject</th>
            			<th>User Name</th>
            		</tr>
            		@if($logs->count())
            			@foreach($logs as $key => $log)
            			<tr>
            				<td>{{ ++$key + $countPerPage * ($page - 1) }}</td>
            				<td>{!! $log->subject !!} on {{ $log->created_at }}</td>
            				<td>{{ $log->firstName }} {{$log->lastName}}</td>
            			</tr>
            			@endforeach
            		@endif
            	</table>
            	
            	@if ($logcount > 0)
    			<div class="pagination_links">
    				{{$logs->appends(array('search' => $search))->links()}}
    			</div>
				@endif
            	
            	
            	
			</div>
		</div>
	</div>
</div>
<!--end row-->


@include('admin.layout.footer')
<!--<script src=//code.jquery.com/jquery-3.5.1.slim.min.js integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin=anonymous></script>-->
<script>
 
var table = $('#myTable').DataTable({
   "aaSorting": [],
              'columnDefs': [{
                    "targets": [0,1,2,3,4,5],
                    "orderable": false
                }]
          });
</script>


<style>
    .dataTables_filter,.dataTables_info,.dataTables_paginate {display:none;}
    
</style>