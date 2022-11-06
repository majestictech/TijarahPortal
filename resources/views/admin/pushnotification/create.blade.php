@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('notification_manage','');
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
					<h5 class="mb-0 text-secondary">{{ __('lang.sendnotification')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" method="post" action="{{route('store.store')}}" data-toggle="validator">
				@csrf
					<div class="mb-3">
										<label class="form-label">{{ __('lang.sendto')}} *</label>
										<div class="input-group">
											<button class="btn btn-outline-secondary" type="button"><i class='bx bx-search'></i>
											</button>
											<select class="form-select single-select" id="inputGroupSelect03" aria-label="Example select with button addon" data-error="{{ __('lang.req_sendto')}}" required>
												<option selected>{{ __('lang.all')}}</option>
												<option value="1">{{ __('lang.vendors')}}</option>
												<option value="2">{{ __('lang.stores')}}</option>
												<option value="3">{{ __('lang.drivers')}}</option>
												<option value="3">{{ __('lang.storesnotloggedin')}}</option>
											</select>
										</div>
					</div>
					<div class="col-12">
						<label class="form-label">{{ __('lang.notification')}} *</label>
							<textarea rows="6" type="text" data-error="{{ __('lang.req_notification')}}" class="form-control" placeholder="{{ __('lang.enternotificationhere')}}" required ></textarea>
					</div>
					<div class="col-12">
						<button type="submit" class="btn btn-secondary px-5">{{ __('lang.sendnotification')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
	</div>
</div>
<!--end row-->
      </div>
    </div>
@include('admin.layout.footer')