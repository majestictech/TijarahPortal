@include('admin.layout.header')
<?php
use App\Helpers\AppHelper as Helper;
helper::checkUserURLAccess('store_manage','store_edit');
?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('/admin/store')}}"><i class="bx bx-store-alt"></i> {{ __('lang.stores')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">{{ __('lang.editstore')}}</li>
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
					<h5 class="mb-0 text-primary">{{ __('lang.editstore')}}</h5>
				</div>
				<hr>
				<form class="row g-3 pt-3" method="post" action="{{route('store.update')}}" data-toggle="validator">
				@if($errors->any())
				<h4 class="error_msg">{{$errors->first()}}</h4>
				@endif
				@csrf
					<div class="col-md-6 ">
						<label for="storename" class="form-label">{{ __('lang.storename')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
							<input type="text" autofocus="autofocus" onfocus="this.setSelectionRange(this.value.length,this.value.length);" name="storeName" class="form-control border-start-0" id="storename" placeholder="{{ __('lang.storename')}}" value="{{$stores->storeName}}" required>
						</div>
					</div>

					<div class="col-md-6 ">
						<label for="printStoreNameAr" class="form-label">{{ __('lang.storenamear')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-store'></i></span>
							<input type="text"  name="printStoreNameAr" value="{{$stores->printStoreNameAr}}" class="form-control border-start-0" id="printStoreNameAr" placeholder="{{ __('lang.storenamear')}}" required>
						</div>
					</div>
					
					<!--<div class="col-md-12">
						<label for="tagline" class="form-label">{{ __('lang.tagline')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" name="tagLine" class="form-control border-start-0" id="tagline" placeholder="{{ __('lang.tagline')}}" value="{{$stores->tagLine}}"/>
						</div>
					</div>-->
					
		            <div class="col-6">
						<label for="storeType" class="form-label">{{ __('lang.storetype')}} *</label>
						<div class="input-group">
							<button class="btn btn-outline-secondary" type="button"><i class='bx bx-store'></i>
							</button>
							<select name="storeType" class="form-select single-select" id="storeType" aria-label="Example select with button addon" required>
								<option value="">{{ __('lang.selectstoretype')}}</option>
									@foreach($storetype as $key=>$value)
										<option value="{{$value->id}}" {{ ( $value->id == $stores->storeType) ? 'selected' : '' }} >{{$value->name}}</option>
									@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<label for="devicetype" class="form-label">{{ __('lang.devicetype')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" name="deviceType" class="form-control border-start-0" id="devicetype" value="{{$stores->deviceType}}" placeholder="{{ __('lang.devicetype')}}" />
						</div>
					</div>
					<div class="col-md-4">
						<label for="apptype" class="form-label">{{ __('lang.apptype')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" name="appType" class="form-control border-start-0" id="apptype" value="{{$stores->appType}}" placeholder="{{ __('lang.apptype')}}" />
						</div>
					</div>
					<div class="col-md-4">
						<label for="shopsize" class="form-label">{{ __('lang.shopsize')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" name="shopSize" class="form-control border-start-0" id="shopsize" value="{{$stores->shopSize}}" placeholder="{{ __('lang.shopsize')}}" />
						</div>
					</div>
					<div class="col-md-4">
						<label for="vatnumber" class="form-label">{{ __('lang.vatnumber')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag'></i></span>
							<input type="text" name="vatNumber" class="form-control border-start-0" id="vatnumber" value="{{$stores->vatNumber}}" placeholder="{{ __('lang.vatnumber')}}" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="registrationnumber" class="form-label">{{ __('lang.registrationnumber')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-file'></i></span>
							<input type="text" name="regNo" class="form-control border-start-0" id="registrationnumber" placeholder="{{ __('lang.registrationnumber')}}" value="{{$stores->regNo}}" required>
						</div>
					</div>
					<div class="col-md-6">
						<label for="inputEmailAddress" class="form-label">{{ __('lang.email')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
							<input type="text" name="email" class="form-control border-start-0" id="inputEmailAddress" placeholder="{{ __('lang.email')}}" value="{{$stores->email}}" required  />
						</div>
					</div>
					
					<div class="col-md-6">
						<label for="contactname" class="form-label">{{ __('lang.ownername')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
							<input type="text" class="form-control border-start-0" name="contactName" id="contactname" placeholder="{{ __('lang.ownername')}}" value="{{$stores->contactName}}" required>
						</div>
					</div>
					<div class="col-md-6">
						<label for="contactnumber" class="form-label">{{ __('lang.mobilenumber')}} *</label>
						<div class="input-group"> <span class="input-group-text bg-transparent">+966</span>
							<input type="text"  name="contactNumber" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="9" disabled class="form-control border-start-0" id="contactnumber" placeholder="{{ __('lang.mobilenumber')}}" value="{{$stores->contactNumber}}" required>
						</div>
					</div>
					<div class="col-md-6">
						<label for="latitude" class="form-label">{{ __('lang.latitude')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-navigation'></i></span>
							<input type="text" class="form-control border-start-0" name="latitude" id="latitude" placeholder="{{ __('lang.latitude')}}" value="{{$stores->latitude}}"  />
						</div>
					</div>
					<div class="col-md-6">
						<label for="longitude" class="form-label">{{ __('lang.longitude')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-navigation' ></i></span>
							<input type="text" name="longitude" class="form-control border-start-0" id="longitude" placeholder="{{ __('lang.longitude')}}" value="{{$stores->longitude}}"  />
						</div>
					</div>
					<div class="col-6">
						<label for="inputChoosePassword" class="form-label">{{ __('lang.choosepassword')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-lock-open' ></i></span>
							<input type="password" name="password" class="form-control border-start-0" id="inputChoosePassword" placeholder="Choose Password" />
						</div>
					</div>
					<div class="col-6">
						<label for="inputConfirmPassword" class="form-label">{{ __('lang.confirmpassword')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-lock' ></i></span>
							<input type="password" name="passwordConfirmation" class="form-control border-start-0" id="inputConfirmPassword" placeholder="Confirm Password" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group ">
							  <label>{{ __('lang.inventorylink')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="yes" value="yes" name="inventoryLink" <?php if(isset($stores)){ if('Yes' == $stores->manageInventory){ echo "checked"; } } ?>>
							  <label class="mb-0" for="yes">
								{{ __('lang.yes')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="no" id="no" name="inventoryLink" <?php if(isset($stores)){ if('No' == $stores->manageInventory){ echo "checked"; } } ?>>
							  <label class="mb-0" for="off">
								{{ __('lang.no')}}
							  </label>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group ">
							  <label>{{ __('lang.smsalert')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="on" value="on" name="smsAlert" <?php if(isset($stores)){ if('On' == $stores->smsAlert){ echo "checked"; } } ?>>
							  <label class="mb-0" for="on">
								{{ __('lang.on')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="off" id="off" name="smsAlert" <?php if(isset($stores)){ if('Off' == $stores->smsAlert){ echo "checked"; } } ?>>
							  <label class="mb-0" for="off">
								{{ __('lang.off')}}
							  </label>
						</div>
					</div>
					
					@if(Auth::user()->roleId != '11')
					<div class="col-md-6" >
						<div class="form-group ">
							  <label>{{ __('lang.autoglobalcat')}} *</label><br/>
							  <input class="radio-input mr-2" type="radio" id="yes" value="yes" name="autoGlobalCat" <?php if(isset($stores)){ if('Yes' == $stores->autoGlobalCat){ echo "checked"; } } ?>>
							  <label class="mb-0" for="yes">
								{{ __('lang.yes')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="no" id="no" name="autoGlobalCat" <?php if(isset($stores)){ if('No' == $stores->autoGlobalCat){ echo "checked"; } } ?>>
							  <label class="mb-0" for="no">
								{{ __('lang.no')}}
							  </label>
						</div>
					</div>
					@else
						<input type="hidden" name="autoGlobalCat" value="yes" />
					@endif

					<div class="col-md-6">
						<div class="form-group ">
							  <label>{{ __('lang.onlinemarket')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="on" value="on" name="onlineMarket" <?php if(isset($stores)){ if('On' == $stores->onlineMarket){ echo "checked"; } } ?>>
							  <label class="mb-0" for="on">
								{{ __('lang.on')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="off" id="off" name="onlineMarket" <?php if(isset($stores)){ if('Off' == $stores->onlineMarket){ echo "checked"; } } ?>>
							  <label class="mb-0" for="off">
								{{ __('lang.off')}}
							  </label>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group ">
							  <label>{{ __('lang.loyaltyoptions')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="on" value="on" name="loyaltyOptions" <?php if(isset($stores)){ if('On' == $stores->loyaltyOptions){ echo "checked"; } } ?>>
							  <label class="mb-0" for="on">
								{{ __('lang.on')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="off" id="off" name="loyaltyOptions" <?php if(isset($stores)){ if('Off' == $stores->loyaltyOptions){ echo "checked"; } } ?>>
							  <label class="mb-0" for="off">
								{{ __('lang.off')}}
							  </label>
						</div>
					</div>
					
					
				

					@if($stores->autoGlobalItems==='Yes' )
				
    					<div class="col-md-6">
    						<div class="form-group ">
    							  <label>{{ __('lang.autoglobalitems')}}</label><br/>
                                    <p style="color: #157D4C;"><b>{{ __('lang.alreadyuploaded')}}</b></p>
    						</div>
    					</div>
    				@else
						@if(Auth::user()->roleId != '11')
     					<div class="col-md-6">
    						<div class="form-group ">
    							  <label>{{ __('lang.autoglobalitems')}}</label><br/>
    							  <input class="radio-input mr-2" type="radio" id="yes" value="yes" name="autoGlobalItems" onclick="return confirm('Are you sure you want to upload Global Products to this Store?');">
    							  <label class="mb-0" for="yes">
    								{{ __('lang.yes')}}
    							  </label>
    							
    							  <input class="checkbox-input mr-2 ml-2" type="radio" value="no" id="no" name="autoGlobalItems" <?php if(isset($stores)){ if('No' == $stores->autoGlobalItems){ echo "checked"; } } ?>>
    							  <label class="mb-0" for="no">
    								{{ __('lang.no')}}
    							  </label>
    						</div>
    					</div>
						@else
						<input type="hidden" name="autoGlobalItems" value="no" />
						@endif
						
    				@endif
					
					
					
					
					<div class="col-md-6">
						<div class="form-group ">
							  <label>{{ __('lang.chatbot')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="on" value="on" name="chatbot" <?php if(isset($stores)){ if('On' == $stores->chatbot){ echo "checked"; } } ?>>
							  <label class="mb-0" for="on">
								{{ __('lang.on')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="off" id="off" name="chatbot" <?php if(isset($stores)){ if('Off' == $stores->chatbot){ echo "checked"; } } ?>>
							  <label class="mb-0" for="off">
								{{ __('lang.off')}}
							  </label>
						</div>
					</div>	
					<div class="col-12">
						<label for="inputAddress3" class="form-label">{{ __('lang.storeaddress')}} *</label>
						<textarea name="address" class="form-control" id="inputAddress3" placeholder="{{ __('lang.storeaddress')}}" rows="3" required>{{$stores->address}}</textarea>
					</div>
					
					<div class="col-12">
						<label for="printAddAr" class="form-label">{{ __('lang.storeaddar')}} *</label>
						<textarea class="form-control" name="printAddAr" id="printAddAr" placeholder="{{ __('lang.storeaddar')}}" rows="3" required>{{$stores->printAddAr}}</textarea>
					</div>	
					
					
					<div class="col-6">
						<label for="inputConfirmPassword" class="form-label">{{ __('lang.country')}} *</label>
						<div class="input-group">
							<button class="btn btn-outline-secondary" type="button"><i class='bx bx-map'></i>
							</button>
							<select name="country" class="form-select single-select" id="country" data-error="{{ __('lang.req_country')}}" required>
								<option value="">{{ __('lang.selectcountry')}}</option>
								@foreach ($country as $key => $value)
												<option value="{{ $value->id }}" {{ ( $value->id == $stores->countryId) ? 'selected' : '' }}> 
													{{ $value->nicename }} 
												</option>
											  @endforeach  
							</select>
						</div>
					</div>
					<div class="col-6">
						<label for="state" class="form-label">{{ __('lang.state')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-map' ></i></span>
							<input type="text" name="state" class="form-control border-start-0" id="state" value="{{$stores->state}}" placeholder="{{ __('lang.enterstate')}}" />
						</div>
					</div>
					<div class="col-6">
						<label for="city" class="form-label">{{ __('lang.city')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map' ></i></span>
							<input type="text" name="city" class="form-control border-start-0" id="city" value="{{$stores->city}}" placeholder="{{ __('lang.entercityname')}}" />
						</div>
					</div>
					<div class="col-6">
						<label for="zippostalcode" class="form-label">{{ __('lang.zippostalcode')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map' ></i></span>
							<input type="text"  name="postalCode" class="form-control border-start-0"  maxlength="10" id="zippostalcode" value="{{$stores->postalCode}}" placeholder="{{ __('lang.enterzippostalcode')}}" />
						</div>
					</div>
					<div class="col-12">
						<label for="shoptime" class="form-label">{{ __('lang.shoptime')}}</label>
					</div>
					<div class="col-6">
						<label for="shopopen" class="form-label">{{ __('lang.shopopen')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-time' ></i></span>
							<input type="time" name="openintime" class="form-control border-start-0" id="shopopen" value="{{$stores->openintime}}" placeholder="{{ __('lang.enteropentime')}}" />
						</div>
					</div>
					<div class="col-6">
						<label for="shopclose" class="form-label">{{ __('lang.shopclose')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-time' ></i></span>
							<input type="time" name="openclosetime" class="form-control border-start-0" id="shopclose" value="{{$stores->openclosetime}}" placeholder="{{ __('lang.enterclosesize')}}" />
						</div>
					</div>
					
					<div class="col-6">
						<label for="printFooterEn" class="form-label">{{ __('lang.footeren')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag' ></i></span>
							<input type="text" name="printFooterEn" class="form-control border-start-0" id="printFooterEn" value="{{$stores->printFooterEn}}" placeholder="{{ __('lang.footeren')}}">
						</div>
					</div>
					
					<div class="col-6">
						<label for="printFooterAr" class="form-label">{{ __('lang.footerar')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag' ></i></span>
							<input type="text" name="printFooterAr" class="form-control border-start-0" id="printFooterAr" value="{{$stores->printFooterAr}}" placeholder="{{ __('lang.footerar')}}">
						</div>
					</div>
					
					
					
					<!--
					<div class="col-md-6">
						<div class="form-group">
							  <label>{{ __('lang.status')}}</label><br/>
							  <input class="radio-input mr-2" type="radio" id="active" value="Active" name="status" <?php if(isset($stores)){ if('Active' == $stores->status){ echo "checked"; } } ?>>
							  <label class="mb-0" for="active">
								{{ __('lang.active')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="Suspended" id="suspended" name="status" <?php if(isset($stores)){ if('Suspended' == $stores->status){ echo "checked"; } } ?>>
							  <label class="mb-0" for="suspended">
								{{ __('lang.suspended')}}
							  </label>
						</div>
					</div>
					-->
					
					
					@if(Auth::user()->roleId != '11')
					<div class="col-md-6">
						<label for="stores" class="form-label">{{ __('lang.subscriptionplansduration')}} *</label>
						<select name="subscriptionPlanId" class="form-control" id="subscriptionPlanId" >
							<option value="">{{ __('lang.selectplan')}}</option>
								@foreach($subscriptionPlans as $key=>$value)
									<option value="{{$value->id}}" {{ ( $value->id == $stores->subscriptionPlanId) ? 'selected' : '' }}> {{$value->plan}} - {{$value->duration}}</option>
								@endforeach	
						</select>
					</div> 
					@else
						<input type="hidden" name="subscriptionPlanId" value="{{$subscriptionPlanId}}" />
					@endif
					
					@if(Auth::user()->roleId != '11')
					<div class="col-6">
						<label for="subscriptionExpiry" class="form-label">{{ __('lang.subscriptionExpiry')}}</label>
						<div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bx-tag' ></i></span>
							<input type="date" required name="subscriptionExpiry" class="form-control border-start-0" id="subscriptionExpiry" value="{{$stores->subscriptionExpiry}}" placeholder="{{ __('lang.subscriptionExpiry')}}">
						</div>
					</div>
					@else
						<input type="hidden" name="subscriptionExpiry" value="{{$subscriptionExpiryDate}}" />
					@endif
					
					 <div class="col-6">
						<label for="storeType" class="form-label">{{ __('lang.appVersion')}} *</label>
						<div class="input-group">
							<button class="btn btn-outline-secondary" type="button"><i class='bx bx-store'></i>
							</button>
							<select name="appVersionUpdate" class="form-select single-select" id="appVersionUpdate" aria-label="Example select with button addon" required>
								<option value="">Select App Version</option>
									@foreach($appupdate as $key=>$value)
										<option value="{{$value->id}}" {{ ( $value->id == $stores->appVersionUpdate) ? 'selected' : '' }} >{{$value->appVer}} ({{$value->appType}}) </option>
									@endforeach
							</select>
						</div>
					</div>
					

					<div class="col-6">
					    <label class="form-label">{{ __('lang.status')}}</label><br/>
					    <div class="form-group pt-2">
							  <input class="radio-input mr-2" type="radio" id="active" value="Active" name="status" <?php if(isset($stores)){ if('Active' == $stores->status){ echo "checked"; } } ?>>
							  <label class="mb-0" for="active">
								{{ __('lang.active')}}
							  </label>
							
							  <input class="checkbox-input mr-2 ml-2" type="radio" value="Suspended" id="suspended" name="status" <?php if(isset($stores)){ if('Suspended' == $stores->status){ echo "checked"; } } ?>>
							  <label class="mb-0" for="suspended">
								{{ __('lang.suspended')}}
							  </label>
						</div>
					</div>

					<div class="col-12">
						<input type="hidden" name="id" value = "{{$stores->id}}">
						<button type="submit" class="btn btn-primary px-5">{{ __('lang.editstore')}}</button>
					</div>
				</form>
			</div>
		</div>		
	</div>
</div>
<!--end row-->
@include('admin.layout.footer')    
 