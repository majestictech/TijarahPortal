@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('store_manage','store_add');
?>
 <style>
     .error_msg{
	color:red;
	font-size:18px;
	
}
 </style>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboards')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/store')}}"><i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">{{ __('lang.addstore')}}</li>
			</ol>
		</nav>
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<div class="card border-top border-0 border-4 border-secondary">
			<div class="card-body p-5 ">
				<div class="card-title d-flex align-items-center">
					<div><i class="bx bx-store-alt me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">{{ __('lang.addstore')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" method="post" action="{{route('store.store')}}" data-toggle="validator">
				@if($errors->any())
				    <h4 class="error_msg">{{$errors->first()}}</h4>
				@endif
				@csrf
					<div class="col-md-6 ">
						<label for="storename" class="form-label">{{ __('lang.storename')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
							<input type="text" value="{{ old('storeName') }}" autofocus="autofocus" name="storeName" class="form-control border-start-0" id="storename" placeholder="{{ __('lang.storename')}}" required>
						</div>
					</div>
					
					<div class="col-md-6 ">
						<label for="printStoreNameAr" class="form-label">{{ __('lang.storenamear')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
							<input type="text" value="{{ old('printStoreNameAr') }}" name="printStoreNameAr" class="form-control border-start-0" id="printStoreNameAr" placeholder="{{ __('lang.storenamear')}}" required>
						</div>
					</div>
					
					<!--<div class="col-md-12">
						<label for="tagline" class="form-label">{{ __('lang.tagline')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" name="tagLine" class="form-control border-start-0" id="tagline" placeholder="{{ __('lang.tagline')}}" />
						</div>
					</div>-->
					<div class="col-6">
						<label for="storeType" class="form-label">{{ __('lang.storetype')}} *</label>
						<div class="input-group">
							<button class="btn btn-outline-secondary" type="button"><i class='bx bx-store'></i>
							</button>
							<select name="storeType" class="form-select single-select" id="storeType" aria-label="Example select with button addon" required>
								<option value="">{{ __('lang.selectstoretype')}}</option>
									@foreach($storetype as $key=>$storetype)
										<option value="{{$storetype->id}}" >{{$storetype->name}} </option>
									@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<label for="devicetype" class="form-label">{{ __('lang.devicetype')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" value="{{ old('deviceType') }}" name="deviceType" class="form-control border-start-0" id="devicetype" placeholder="{{ __('lang.devicetype')}}" />
						</div>
					</div>
					<div class="col-md-4">
						<label for="apptype" class="form-label">{{ __('lang.apptype')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" value="{{ old('appType') }}" name="appType" class="form-control border-start-0" id="apptype" placeholder="{{ __('lang.apptype')}}" />
						</div>
					</div>
					<div class="col-md-4">
						<label for="shopsize" class="form-label">{{ __('lang.shopsize')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" value="{{ old('shopSize') }}" name="shopSize" class="form-control border-start-0" id="shopSize" placeholder="{{ __('lang.shopsize')}}" />
						</div>
					</div>
					<div class="col-md-4">
						<label for="vatnumber" class="form-label">{{ __('lang.vatnumber')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" value="{{ old('vatNumber') }}" name="vatNumber" class="form-control border-start-0" id="vatNumber" placeholder="{{ __('lang.vatnumber')}}" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="registrationnumber" class="form-label">{{ __('lang.registrationnumber')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-file'></i></span>
							<input type="text" value="{{ old('regNo') }}" name="regNo" class="form-control border-start-0" id="registrationnumber" placeholder="{{ __('lang.registrationnumber')}}" required />
						</div>
					</div>
					
					
					<div class="col-md-6">
						<label for="inputEmailAddress" class="form-label">{{ __('lang.email')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
							<input type="email" value="{{ old('email') }}" name="email" class="form-control border-start-0" id="inputEmailAddress" placeholder="{{ __('lang.email')}}" required />
						</div>
					</div>
					

					
					<div class="col-md-6">
						<label for="contactname" class="form-label">{{ __('lang.ownername')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
							<input type="text" value="{{ old('contactName') }}" name="contactName" class="form-control border-start-0" id="contactname" placeholder="{{ __('lang.ownername')}}" required />
						</div>
					</div>
					<div class="col-md-6">
						<label for="contactnumber" class="form-label">{{ __('lang.mobilenumber')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent">+966</span>
							<input type="number" value="{{ old('contactNumber') }}" name="contactNumber" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="9" class="form-control border-start-0" id="contactnumber" placeholder="{{ __('lang.mobilenumber')}}" required>
						</div>
					</div>
					<div class="col-md-6">
						<label for="latitude" class="form-label">{{ __('lang.latitude')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-navigation'></i></span>
							<input type="text" value="{{ old('latitude') }}" name="latitude" class="form-control border-start-0" id="latitude" placeholder="{{ __('lang.latitude')}}" >
						</div>
					</div>
					<div class="col-md-6">
						<label for="longitude" class="form-label">{{ __('lang.longitude')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-navigation' ></i></span>
							<input type="text" value="{{ old('longitude') }}" name="longitude" class="form-control border-start-0" id="longitude" placeholder="{{ __('lang.longitude')}}" >
						</div>
					</div>
					<div class="col-6">
						<label for="inputChoosePassword" class="form-label">{{ __('lang.choosepassword')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-lock-open' ></i></span>
							<input type="password" name="password" class="form-control border-start-0" id="inputChoosePassword" placeholder="Choose Password" required>
						</div>
					</div>
					<div class="col-6">
						<label for="inputConfirmPassword" class="form-label">{{ __('lang.confirmpassword')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-lock' ></i></span>
							<input type="password" name="passwordConfirmation" class="form-control border-start-0" id="inputConfirmPassword" placeholder="Confirm Password" required>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group ">
							  <label>{{ __('lang.inventorylink')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="yes" value="yes" name="inventoryLink" checked>
							  <label class="mb-0" for="yes">
								{{ __('lang.yes')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="no" id="no" name="inventoryLink">
							  <label class="mb-0" for="no">
								{{ __('lang.no')}}
							  </label>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group ">
							  <label>{{ __('lang.smsalert')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="on" value="on" name="smsAlert" checked>
							  <label class="mb-0" for="on">
								{{ __('lang.on')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="off" id="off" name="smsAlert">
							  <label class="mb-0" for="off">
								{{ __('lang.off')}}
							  </label>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group ">
							  <label>{{ __('lang.autoglobalcat')}} *</label><br/>
    							  <input class="radio-input mr-2" type="radio" id="yes" value="yes" name="autoGlobalCat" checked>
							  <label class="mb-0" for="yes">
								{{ __('lang.yes')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="no" id="no" name="autoGlobalCat">
							  <label class="mb-0" for="no">
								{{ __('lang.no')}}
							  </label>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group ">
							  <label>{{ __('lang.onlinemarket')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="on" value="on" name="onlineMarket" checked>
							  <label class="mb-0" for="on">
								{{ __('lang.on')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="off" id="off" name="onlineMarket">
							  <label class="mb-0" for="off">
								{{ __('lang.off')}}
							  </label>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group ">
							  <label>{{ __('lang.loyaltyoptions')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="on" value="on" name="loyaltyOptions" checked>
							  <label class="mb-0" for="on">
								{{ __('lang.on')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="off" id="off" name="loyaltyOptions">
							  <label class="mb-0" for="off">
								{{ __('lang.off')}}
							  </label>
						</div>
					</div>
					
					
				
					
					<div class="col-md-6">
						<div class="form-group ">
							  <label>{{ __('lang.autoglobalitems')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="yes" value="yes" name="autoGlobalItems" onclick="return confirm('Are you sure you want to upload Global Products to this Store?');" >
							  <label class="mb-0" for="yes">
								{{ __('lang.yes')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="no" id="no" name="autoGlobalItems" checked>
							  <label class="mb-0" for="no">
								{{ __('lang.no')}}
							  </label>
						</div>
					</div>
					
					
					<div class="col-md-6">
						<div class="form-group ">
							  <label>{{ __('lang.chatbot')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="on" value="on" name="chatbot" checked>
							  <label class="mb-0" for="on">
								{{ __('lang.on')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="off" id="off" name="chatbot">
							  <label class="mb-0" for="off">
								{{ __('lang.off')}}
							  </label>
						</div>
					</div>	
					
					
					
					
					
					<div class="col-12">
						<label for="enterstoreaddress" class="form-label">{{ __('lang.storeaddress')}} *</label>
						<textarea class="form-control" value="{{ old('address') }}" name="address" id="enterstoreaddress" placeholder="{{ __('lang.enterstoreaddress')}}" rows="3" required></textarea>
					</div>
					
					<div class="col-12">
						<label for="printAddAr" class="form-label">{{ __('lang.storeaddar')}} *</label>
						<textarea class="form-control" value="{{ old('printAddAr') }}" name="printAddAr" id="printAddAr" placeholder="{{ __('lang.storeaddar')}}" rows="3" required></textarea>
					</div>					
					
					<div class="col-6">
						<label for="inputConfirmPassword" class="form-label">{{ __('lang.country')}} *</label>
						<div class="input-group">
							<button class="btn btn-outline-secondary" type="button"><i class='bx bx-map'></i>
							</button>
							<select name="country" class="form-select single-select" id="country" aria-label="Example select with button addon" data-error="{{ __('lang.req_country')}}" required>
								<option value="">{{ __('lang.selectcountry')}}</option>
							@foreach($country as $key=>$countryData)
								<option value="{{$countryData->id}}" >{{$countryData->nicename}}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="col-6">
						<label for="state" class="form-label">{{ __('lang.state')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-map' ></i></span>
							<input type="text" value="{{ old('state') }}" name="state" class="form-control border-start-0" id="state" placeholder="{{ __('lang.enterstate')}}" >
						</div>
					</div>
					<div class="col-6">
						<label for="city" name="city" class="form-label">{{ __('lang.city')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map' ></i></span>
							<input type="text" value="{{ old('city') }}" name="city" class="form-control border-start-0" id="city" placeholder="{{ __('lang.entercityname')}}">
						</div>
					</div>
					<div class="col-6">
						<label for="zippostalcode" class="form-label">{{ __('lang.zippostalcode')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map' ></i></span>
							<input type="text" value="{{ old('postalCode') }}" name="postalCode" class="form-control border-start-0" maxlength="10" id="zippostalcode" placeholder="{{ __('lang.enterzippostalcode')}}">
						</div>
					</div>
					<div class="col-12">
						<label for="shoptime" class="form-label">{{ __('lang.shoptime')}}</label>
					</div>
					<div class="col-6">
						<label for="shopopen" class="form-label">{{ __('lang.shopopen')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-time' ></i></span>
							<input type="time" value="{{ old('openintime') }}" name="openintime" class="form-control border-start-0" id="shopopen" placeholder="{{ __('lang.enteropentime')}}" />
						</div>
					</div>
					<div class="col-6">
						<label for="shopclose" class="form-label">{{ __('lang.shopclose')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-time' ></i></span>
							<input type="time"  value="{{ old('openclosetime') }}" name="openclosetime" class="form-control border-start-0" id="shopclose" placeholder="{{ __('lang.enterclosesize')}}" />
						</div>
					</div>
					
					
					<div class="col-6">
						<label for="printFooterEn" class="form-label">{{ __('lang.footeren')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag' ></i></span>
							<input type="text" value="{{ old('printFooterEn') }}" name="printFooterEn" class="form-control border-start-0" id="printFooterEn" placeholder="{{ __('lang.footeren')}}">
						</div>
					</div>
					
					<div class="col-6">
						<label for="printFooterAr" class="form-label">{{ __('lang.footerar')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag' ></i></span>
							<input type="text" value="{{ old('printFooterAr') }}" name="printFooterAr" class="form-control border-start-0" id="printFooterAr" placeholder="{{ __('lang.footerar')}}">
						</div>
					</div>
					
					<div class="col-md-6">
					    <label class="form-label">{{ __('lang.status')}}</label><br/>
						<div class="form-group pt-2">
							  <input class="radio-input mr-2" type="radio" id="active" value="Active" name="status" checked>
							  <label class="mb-0" for="active">
								{{ __('lang.active')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="Suspended" id="suspended" name="status">
							  <label class="mb-0" for="suspended">
								{{ __('lang.suspended')}}
							  </label>
						</div>
					</div>
					
					<div class="col-6">
						<label for="subscriptionExpiry" class="form-label">{{ __('lang.subscriptionExpiry')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag' ></i></span>
							<input type="date" required value="{{ old('subscriptionExpiry') }}" name="subscriptionExpiry" class="form-control border-start-0" id="subscriptionExpiry" value="2024-01-01" placeholder="{{ __('lang.subscriptionExpiry')}}">
						</div>
					</div>
					
					<div class="col-6">
						<label for="storeType" class="form-label">{{ __('lang.appVersion')}} *</label>
						<div class="input-group">
							<button class="btn btn-outline-secondary" type="button"><i class='bx bx-store'></i>
							</button>
							<select name="appVersionUpdate" class="form-select single-select" id="appVersionUpdate" aria-label="Example select with button addon">
								<option value="">Select App Version</option>
									@foreach($appupdate as $key=>$app)
										<option value="{{$app->id}}" >{{$app->appVer}} ({{$app->appType}}) </option>
									@endforeach
							</select>
						</div>
					</div>
					
					
					
					<div class="col-12">
						<button type="submit" class="btn btn-secondary px-5">{{ __('lang.addstore')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->
@include('admin.layout.footer')    
 