@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('app_manage','');
?>
<style>
.error_msg{
	color:red;
	font-size:16px;
	
}

</style>
 <div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
		<div class="col-xl-12 mx-auto">
		<!--<h6 class="mb-0 text-uppercase">Form with icons</h6> -->
		<hr/>
		
		<div class="card border-top border-0 border-4 border-secondary">
			<div class="card-body p-5 ">
				<div class="card-title d-flex align-items-center">
					<div><i class="bx bx-notification me-1 font-22 text-secondary"></i>
					</div>
					<h5 class="mb-0 text-secondary">{{ __('lang.pushapp')}}</h5>
				</div>
				<hr>
			    
			    
		
				
				<form class="row g-3 pt-3 mb-3" method="post" action="{{route('appupdate.store')}}" data-toggle="validator" enctype="multipart/form-data"> 
				@csrf
					<div class="mb-3 col-md-6">
						<label class="form-label">{{ __('lang.sendto')}} *</label>
						<div class="input-group">
							<button class="btn btn-outline-secondary" type="button"><i class='bx bx-search'></i>
							</button>
							
							<select name="appType" class="form-select single-select" id="inputGroupSelect03" aria-label="Example select with button addon" data-error="{{ __('lang.req_sendto')}}" required>
								<option value="MINI">{{ __('lang.miniapp')}}</option>
								<option value="PLUS">{{ __('lang.plusapp')}}</option>
							</select>
							
						</div>
					</div>
					<div class="col-6">
						<label class="form-label">{{ __('lang.appVersion')}} *</label>
							<input type="text" name="appVer" class="form-control" placeholder="{{ __('lang.appVersion')}}" required>
					</div>
					<div class="col-4">
						<label class="form-label">{{ __('lang.appcode')}} *</label>	
						    <input type="text" name="appCode" class="form-control" placeholder="{{ __('lang.appcode')}}" required>
					</div>
					<div class="col-4">
						<label class="form-label">{{ __('lang.uploadapk')}} *</label>	
						    <input type="file" name="appfile" class="form-control border-start-0" id="file" required>
					</div>
					<!--<div class="col-4">
						<label class="form-label">Push app to all stores *</label><br/>	
						    <input class="radio-input mr-2" type="checkbox" id="yes" value="Yes" name="push" checked>
						    <label class="mb-0" for="yes">
					            Yes
							</label>
					</div>-->
					<div class="col-12">
						<button type="submit" class="btn btn-secondary px-5">{{ __('lang.pushapp')}}</button>
					</div>
				</form>
				
				
				<table class="table mb-0 mt-4 table-striped table-bordered" id="myTable">
					<thead>
						<tr>
							<th scope="col">App Type</th>
							<th scope="col">{{ __('lang.appVersion')}}</th>
							<th scope="col">App Code</th>
						</tr>
					</thead>
					<tbody>
					@foreach($appupdate as $key =>$AppUpdateData)
						<tr style="vertical-align: middle !important;">
                            <td>{{$AppUpdateData->appType}}</td>
                            <td>{{$AppUpdateData->appVer}}</td>
                            <td>{{$AppUpdateData->appCode}}</td>
						</tr>
						
						
					@endforeach
					</tbody>
				</table>
				
				
				
				
			</div>
		</div>		
	</div>
	</div>
</div>
<!--end row-->
      </div>
    </div>
@include('admin.layout.footer')