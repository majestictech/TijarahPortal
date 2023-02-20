@include('admin.layout.header')							
<?php
use App\Helpers\AppHelper as Helper;
?>

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="ps-1">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a class="text-primary" href="{{url('admin')}}"><i class="bx bx-home-alt"></i> {{ __('lang.dashboard')}}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><i class="bx bx-store-alt"></i> User Roles</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		
	</div>
</div>
<!--end breadcrumb-->
<div class="row">
	<div class="col-xl-12 mx-auto">
		<h6 class="mb-0 text-uppercase">All Roles & Capabilities</h6>
		<hr/>

		
		
		<div class="card">
			<div class="card-body">
			   <form action="" method="GET" id ="filter_results">
    			    <select name="roleFilter" class="form-select single-select" id="roleFilter" onChange="this.form.submit();">
    					<option value="" @if(empty($roleFilter)) selected="selected" @endif>Select User Role</option>
    						@foreach($userRoles as $key=>$userrole)
    						    <option value="{{ $userrole->id }}" @if($userrole->id==$roleFilter) selected="selected" @endif >{{ $userrole->name }}</option>
    						@endforeach
    				</select>
			    </form>
			   
			    @if(!empty($roleFilter))
			    <form class="row g-3 pt-3" method="post" action="{{route('userroles.store')}}" data-toggle="validator">
			        @csrf
                    <input type="hidden" name="roleFilterVal" value="{{$roleFilter}}">
					
					<!-- App Features Start -->

					@if($roleFilter != 1 && $roleFilter != 2)
					<div class="col-md-3 border-right-0">
    			        <p class="mt-2"><b>{{ __('lang.appfeatures')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_dashboard" <?php if(in_array('app_manage_dashboard', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_dashboard" class="form-label">{{ __('lang.app_manage_dashboard')}}</label><br>
    					</div> <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_inventory" <?php if(in_array('app_manage_inventory', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_inventory" class="form-label">{{ __('lang.app_manage_inventory')}}</label><br>
    					</div>
    			        
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_inventory_add" <?php if(in_array('app_inventory_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_inventory_add" class="form-label">{{ __('lang.app_inventory_add')}}</label><br>
    					</div>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_inventory_edit" <?php if(in_array('app_inventory_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_inventory_edit" class="form-label">{{ __('lang.app_inventory_edit')}}</label><br>
    					</div>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_inventory_update" <?php if(in_array('app_inventory_update', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_inventory_update" class="form-label">{{ __('lang.app_inventory_update')}}</label><br>
    					</div>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_inventory_move" <?php if(in_array('app_inventory_move', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_inventory_move" class="form-label">{{ __('lang.app_inventory_move')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_reports" <?php if(in_array('app_manage_reports', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_reports" class="form-label">{{ __('lang.app_manage_reports')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_orders" <?php if(in_array('app_manage_orders', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_orders" class="form-label">{{ __('lang.app_manage_orders')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_rufund" <?php if(in_array('app_manage_rufund', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_rufund" class="form-label">{{ __('lang.app_manage_rufund')}}</label><br>
    					</div>
    				</div>
					<div class="col-md-3 pt-5 border-left-0 border-right-0 ">
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_purchase" <?php if(in_array('app_manage_purchase', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_purchase" class="form-label">{{ __('lang.app_manage_purchase')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_invoice" <?php if(in_array('app_manage_invoice', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_invoice" class="form-label">{{ __('lang.app_manage_invoice')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_invoice_add" <?php if(in_array('app_invoice_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_invoice_add" class="form-label">{{ __('lang.app_invoice_add')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_invoice_edit" <?php if(in_array('app_invoice_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_invoice_edit" class="form-label">{{ __('lang.app_invoice_edit')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_vendors" <?php if(in_array('app_manage_vendors', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_vendors" class="form-label">{{ __('lang.app_manage_vendors')}}123</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_vendorslist" <?php if(in_array('app_manage_vendorslist', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_vendorslist" class="form-label">{{ __('lang.app_manage_vendorslist')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_vendor_add" <?php if(in_array('app_vendor_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_vendor_add" class="form-label">{{ __('lang.app_vendor_add')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_vendor_edit" <?php if(in_array('app_vendor_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_vendor_edit" class="form-label">{{ __('lang.app_vendor_edit')}}</label><br>
    					</div>
					</div>
					<div class="col-md-3 pt-5 border-left-0 border-right-0 ">		
						<div class="col-md-12 ">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_customers" <?php if(in_array('app_manage_customers', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_customers" class="form-label">{{ __('lang.app_manage_customers')}}</label><br>
    					</div>
						<div class="col-md-12 ">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_loyalty" <?php if(in_array('app_manage_loyalty', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_loyalty" class="form-label">{{ __('lang.app_manage_loyalty')}}</label><br>
    					</div>
						<div class="col-md-12 ">
                            <input type="checkbox" name="catPermissions[]" value="app_customer_managepayment" <?php if(in_array('app_customer_managepayment', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_customer_managepayment" class="form-label">{{ __('lang.app_customer_managepayment')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_store_users" <?php if(in_array('app_manage_store_users', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_store_users" class="form-label">{{ __('lang.app_manage_store_users')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_store_users_add" <?php if(in_array('app_store_users_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_store_users_add" class="form-label">{{ __('lang.app_store_users_add')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_store_users_edit" <?php if(in_array('app_store_users_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_store_users_edit" class="form-label">{{ __('lang.app_store_users_edit')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_discount" <?php if(in_array('app_manage_discount', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_inventory" class="form-label">{{ __('lang.app_manage_discount')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_manage_settings" <?php if(in_array('app_manage_settings', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage_settings" class="form-label">{{ __('lang.app_manage_settings')}}</label><br>
    					</div>
    				</div>

					<div class="col-md-3 pt-5 border-left-0">
						
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_settings_account" <?php if(in_array('app_settings_account', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_settings_account" class="form-label">{{ __('lang.app_settings_account')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_settings_shopdetails" <?php if(in_array('app_settings_shopdetails', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_settings_shopdetails" class="form-label">{{ __('lang.app_settings_shopdetails')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_settings_shopsettings" <?php if(in_array('app_settings_shopsettings', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_settings_shopsettings" class="form-label">{{ __('lang.app_settings_shopsettings')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_settings_onlinesettings" <?php if(in_array('app_settings_onlinesettings', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_settings_onlinesettings" class="form-label">{{ __('lang.app_settings_onlinesettings')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_settings_loyalty" <?php if(in_array('app_settings_loyalty', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_settings_loyalty" class="form-label">{{ __('lang.app_settings_loyalty')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_settings_printer" <?php if(in_array('app_settings_printer', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_settings_printer" class="form-label">{{ __('lang.app_settings_printer')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_settings_printersettings" <?php if(in_array('app_settings_printersettings', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_settings_printersettings" class="form-label">{{ __('lang.app_settings_printersettings')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_settings_barcode" <?php if(in_array('app_settings_barcode', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_settings_barcode" class="form-label">{{ __('lang.app_settings_barcode')}}</label><br>
    					</div>
					</div>
					
					
					
					
					@endif
					<!-- App Features End -->
					
					
					
					
					@if($roleFilter != 4)
			        <div class="col-md-3 border-right-0">
    			        <p class="mt-2"><b>{{ __('lang.storemanagement')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_manage" <?php if(in_array('store_manage', $permissionArray)){ { echo "checked=''"; } } ?> onclick="checkValue('store_manage',this)">
                            <label for="store_manage" class="form-label">{{ __('lang.store_manage')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_add" id="store_add" <?php if(in_array('store_add', $permissionArray)){ { echo "checked=''"; } } ?> onclick="addDay(this)">
                            <label for="store_add" class="form-label">{{ __('lang.store_add')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_edit"  id="store_edit" <?php if(in_array('store_edit', $permissionArray)){ { echo "checked=''"; } } ?> onclick="addDay(this)">
                            <label for="store_edit" class="form-label">{{ __('lang.store_edit')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_del" id="store_del" <?php if(in_array('store_del', $permissionArray)){ { echo "checked=''"; } } ?> onclick="addDay(this)">
                            <label for="store_del" class="form-label">{{ __('lang.store_del')}}</label><br>
    					</div>
    				</div>
			        
			        <div class="col-md-3 pt-5 border-left-0 border-right-0">
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_disable" <?php if(in_array('store_disable', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_disable" class="form-label">{{ __('lang.store_disable')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_manageusers" <?php if(in_array('store_manageusers', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_manageusers" class="form-label">{{ __('lang.store_manageusers')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_customers" <?php if(in_array('store_customers', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_customers" class="form-label">{{ __('lang.store_customers')}}</label><br>
    					</div>
    					<!-- <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_lowinventoryemail" <?php if(in_array('store_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_lowinventoryemail" class="form-label">{{ __('lang.store_lowinventoryemail')}}</label><br>
    					</div> -->
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_inventory" <?php if(in_array('store_inventory', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_inventory" class="form-label">{{ __('lang.store_inventory')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_customerscreenslider" <?php if(in_array('store_customerscreenslider', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_customerscreenslider" class="form-label">{{ __('lang.store_customerscreenslider')}}</label><br>
    					</div>
    					
    				</div>
    				
    				<div class="col-md-3 pt-5 border-right-0 border-left-0">
					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_storereports" <?php if(in_array('store_storereports', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_storereports" class="form-label">{{ __('lang.store_storereports')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_zeroinventory" <?php if(in_array('store_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_zeroinventory" class="form-label">{{ __('lang.store_zeroinventory')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_emptyinventory" <?php if(in_array('store_del', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_emptyinventory" class="form-label">{{ __('lang.store_emptyinventory')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_bills" <?php if(in_array('store_bills', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_bills" class="form-label">{{ __('lang.store_bills')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_sales" <?php if(in_array('store_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_sales" class="form-label">{{ __('lang.store_sales')}}</label><br>
    					</div>
    				</div>
			        
			        <div class="col-md-3 pt-5 border-left-0">
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_shifts" <?php if(in_array('store_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_shifts" class="form-label">{{ __('lang.store_shifts')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_vendors" <?php if(in_array('store_del', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_vendors" class="form-label">{{ __('lang.store_vendors')}}</label><br>
    					</div>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_invioces" <?php if(in_array('store_invioces', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_invioces" class="form-label">{{ __('lang.store_invioces')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="store_purchaseorders" <?php if(in_array('store_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="store_purchaseorders" class="form-label">{{ __('lang.store_purchaseorders')}}</label><br>
    					</div>
    				 </div>
					 
			        <div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.category_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="cat_manage" <?php if(in_array('cat_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="cat_manage" class="form-label">{{ __('lang.cat_manage')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="cat_add" <?php if(in_array('cat_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="cat_add" class="form-label">{{ __('lang.cat_add')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="cat_edit" <?php if(in_array('cat_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="cat_edit" class="form-label">{{ __('lang.cat_edit')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="cat_del" <?php if(in_array('cat_del', $permissionArray)){ echo "checked=''"; } ?>>
                            <label for="cat_del" class="form-label">{{ __('lang.cat_del')}}</label><br>
    					</div>
    				</div>
    				
    				<div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.global_products')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="globalproducts_manage" <?php if(in_array('globalproducts_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="globalproducts_manage" class="form-label">{{ __('lang.globalproducts_manage')}}</label><br>
    					</div>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="globalproducts_import" <?php if(in_array('globalproducts_import', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="globalproducts_import" class="form-label">{{ __('lang.globalproducts_import')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="globalproducts_edit" <?php if(in_array('globalproducts_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="globalproducts_edit" class="form-label">{{ __('lang.globalproducts_edit')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="globalproducts_del" <?php if(in_array('globalproducts_del', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="globalproducts_del" class="form-label">{{ __('lang.globalproducts_del')}}</label><br>
    					</div>
    				</div>
    				
    				<div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.brand_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="brand_manage" <?php if(in_array('brand_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="brand_manage" class="form-label">{{ __('lang.brand_manage')}}</label><br>
    					</div>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="brand_add" <?php if(in_array('brand_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="brand_add" class="form-label">{{ __('lang.brand_add')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="brand_edit" <?php if(in_array('brand_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="brand_edit" class="form-label">{{ __('lang.brand_edit')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="brand_del" <?php if(in_array('brand_del', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="brand_del" class="form-label">{{ __('lang.brand_del')}}</label><br>
    					</div>
    				</div>
    				
    				
    				<div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.vat_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="vat_manage" <?php if(in_array('vat_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="vat_manage" class="form-label">{{ __('lang.vat_manage')}}</label><br>
    					</div>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="vat_add" <?php if(in_array('vat_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="vat_add" class="form-label">{{ __('lang.vat_add')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="vat_edit" <?php if(in_array('vat_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="vat_edit" class="form-label">{{ __('lang.vat_edit')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="vat_del" <?php if(in_array('vat_del', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="vat_del" class="form-label">{{ __('lang.vat_del')}}</label><br>
    					</div>
    				</div>
					@endif
					
    				@if($roleFilter == 4 || $roleFilter == 7)
    			    <div class="col-md-3">
    			        <p class="mt-2"><b>Inventory Management</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="inventory_manage" <?php if(in_array('inventory_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="inventory_manage" class="form-label">Inventory Manage</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="inventory_add" <?php if(in_array('inventory_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="inventory_add" class="form-label">Inventory Add</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="inventory_edit" <?php if(in_array('inventory_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="inventory_edit" class="form-label">Inventory Edit</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="inventory_del" <?php if(in_array('inventory_del', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="inventory_del" class="form-label">Inventory Delete</label><br>
    					</div>
    				</div>
    				@endif
					
    				
    				<div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.consumers_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="consumers_manage" <?php if(in_array('customer_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="customer_manage" class="form-label">{{ __('lang.consumers_manage')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="consumers_view" <?php if(in_array('customer_view', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="customer_view" class="form-label">{{ __('lang.consumers_view')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="consumers_add" <?php if(in_array('customer_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="customer_add" class="form-label">{{ __('lang.consumers_add')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="consumers_edit" <?php if(in_array('customer_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="customer_edit" class="form-label">{{ __('lang.consumers_edit')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="consumers_del" <?php if(in_array('customer_del', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="customer_del" class="form-label">{{ __('lang.consumers_del')}}</label><br>
    					</div>
    				</div>
    				
    				@if($roleFilter == 4 || $roleFilter == 7)
    				<div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.cashier_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="cashier_manage" <?php if(in_array('cashier_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="cashier_manage" class="form-label">{{ __('lang.cashier_manage')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="cashier_view" <?php if(in_array('cashier_view', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="cashier_view" class="form-label">{{ __('lang.cashier_view')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="cashier_add" <?php if(in_array('cashier_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="cashier_add" class="form-label">{{ __('lang.cashier_add')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="cashier_edit" <?php if(in_array('cashier_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="cashier_edit" class="form-label">{{ __('lang.cashier_edit')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="cashier_del" <?php if(in_array('cashier_del', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="cashier_del" class="form-label">{{ __('lang.cashier_del')}}</label><br>
    					</div>
    				</div>
    				@endif
					
    				@if($roleFilter != 4)
    				<div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.subadmin_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="subadmin_manage" <?php if(in_array('subadmin_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="subadmin_manage" class="form-label">{{ __('lang.subadmin_manage')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="subadmin_add" <?php if(in_array('subadmin_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="subadmin_add" class="form-label">{{ __('lang.subadmin_add')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="subadmin_edit" <?php if(in_array('subadmin_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="subadmin_edit" class="form-label">{{ __('lang.subadmin_edit')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="subadmin_del" <?php if(in_array('subadmin_del', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="subadmin_del" class="form-label">{{ __('lang.subadmin_del')}}</label><br>
    					</div>
    				</div>
    				
    				<!-- <div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.faq_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="faq_manage">
                            <label for="faq_manage" class="form-label">{{ __('lang.faq_manage')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="faq_add">
                            <label for="faq_add" class="form-label">{{ __('lang.faq_add')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="faq_edit">
                            <label for="faq_edit" class="form-label">{{ __('lang.faq_edit')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="faq_del">
                            <label for="faq_del" class="form-label">{{ __('lang.faq_del')}}</label><br>
    					</div>
    				</div> -->
    				
    				
    				<div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.storetype_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="storetype_manage" <?php if(in_array('storetype_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="storetype_manage" class="form-label">{{ __('lang.storetype_manage')}}</label><br>
    					</div>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="storetype_add" <?php if(in_array('storetype_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="storetype_add" class="form-label">{{ __('lang.storetype_add')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="storetype_edit" <?php if(in_array('storetype_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="storetype_edit" class="form-label">{{ __('lang.storetype_edit')}}</label><br>
    					</div>
    				</div>
					@endif
					<div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.customerslider_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="customerslider_manage" <?php if(in_array('customerslider_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="customerslider_manage" class="form-label">{{ __('lang.customerslider_manage')}}</label><br>
    					</div>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="customerslider_add" <?php if(in_array('customerslider_add', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="customerslider_add" class="form-label">{{ __('lang.customerslider_add')}}</label><br>
    					</div>
    					<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="customerslider_edit" <?php if(in_array('customerslider_edit', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="customerslider_edit" class="form-label">{{ __('lang.customerslider_edit')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="customerslider_del" <?php if(in_array('customerslider_del', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="customerslider_del" class="form-label">{{ __('lang.customerslider_del')}}</label><br>
    					</div>
    				</div>
					
					<div class="col-md-3 border-right-0">
    			        <p class="mt-2"><b>{{ __('lang.report_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="report_manage" <?php if(in_array('report_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="report_manage" class="form-label">{{ __('lang.report_manage')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="salesreport_manage" <?php if(in_array('salesreport_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="salesreport_manage" class="form-label">{{ __('lang.salesreport_manage')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="vatreport_manage" <?php if(in_array('vatreport_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="vatreport_manage" class="form-label">{{ __('lang.vatreport_manage')}}</label><br>
    					</div>
						<div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="refundreport_manage" <?php if(in_array('refundreport_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="refundreport_manage" class="form-label">{{ __('lang.refundreport_manage')}}</label><br>
    					</div>
					</div>
					<div class="col-md-3 pt-5 border-left-0">
						<div class="col-md-12">
							<input type="checkbox" name="catPermissions[]" value="inventoryreport_manage" <?php if(in_array('inventoryreport_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
							<label for="inventoryreport_manage" class="form-label">{{ __('lang.inventoryreport_manage')}}</label><br>
						</div>
						<div class="col-md-12">
							<input type="checkbox" name="catPermissions[]" value="purchasereport_manage" <?php if(in_array('purchasereport_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
							<label for="purchasereport_manage" class="form-label">{{ __('lang.purchasereport_manage')}}</label><br>
						</div>
						<div class="col-md-12">
							<input type="checkbox" name="catPermissions[]" value="mediareport_manage" <?php if(in_array('mediareport_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
							<label for="mediareport_manage" class="form-label">{{ __('lang.mediareport_manage')}}</label><br>
						</div>
						<div class="col-md-12">
							<input type="checkbox" name="catPermissions[]" value="cashierreport_manage" <?php if(in_array('cashierreport_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
							<label for="cashierreport_manage" class="form-label">{{ __('lang.cashierreport_manage')}}</label><br>
						</div>
					</div>
    				
    				<div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.ordersbills_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="orders_manage" <?php if(in_array('orders_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="orders_manage" class="form-label">{{ __('lang.orders_manage')}}</label><br>
    					</div>
    				</div>
    				
    				<!-- <div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.loyaltypoints_history')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="loyaltypoint_manage">
                            <label for="loyaltypoint_manage" class="form-label">{{ __('lang.loyaltypoint_manage')}}</label><br>
    					</div>
    				</div> -->
    				<div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.subscriptionplanmanagement')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="subscription_manage" <?php if(in_array('subscription_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="subscription_manage" class="form-label">{{ __('lang.subscriptionplanmanagement')}}</label><br>
    					</div>
    				</div>
    				@if($roleFilter != 4)
    				<div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.log_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="log_manage" <?php if(in_array('log_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="log_manage" class="form-label">{{ __('lang.log_manage')}}</label><br>
    					</div>
    				</div>
    				@endif
    			
					@if($roleFilter != 4)
    				<div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.app_update_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="app_manage" <?php if(in_array('app_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="app_manage" class="form-label">{{ __('lang.app_manage')}}</label><br>
    					</div>
    				</div>
    				
    				<!-- <div class="col-md-3">
    			        <p class="mt-2"><b>{{ __('lang.notification_management')}}</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="notifications_manage">
                            <label for="notifications_manage" class="form-label">{{ __('lang.notifications_manage')}}</label><br>
    					</div>
    				</div> -->
					
					<!--<div class="col-md-3">
    			        <p class="mt-2"><b>App Features</b></p>
    			        <div class="col-md-12">
                            <input type="checkbox" name="catPermissions[]" value="notifications_manage" <?php if(in_array('notifications_manage', $permissionArray)){ { echo "checked=''"; } } ?>>
                            <label for="notifications_manage" class="form-label">Manage Reports</label><br>
                            <input type="checkbox" name="catPermissions[]" value="notifications_manage" <?php if(in_array('notifications_manage', $permissionArray)){ { echo "checked=''"; } } ?>> <label for="notifications_manage" class="form-label"> Manage Users</label><br>
                            <input type="checkbox" name="catPermissions[]" value="notifications_manage" <?php if(in_array('notifications_manage', $permissionArray)){ { echo "checked=''"; } } ?>> <label for="notifications_manage" class="form-label"> Purchases</label><br>
    					</div>
    				</div> -->
    				@endif
    				
					<div class="col-12">
				        <button type="submit" class="btn btn-primary px-5">Update Permissions</button>
					</div>
			    </form>
			   
                @endif
			</div>
		</div>
	</div>
</div>
<!--end row-->


@include('admin.layout.footer')
<!--<script src=//code.jquery.com/jquery-3.5.1.slim.min.js integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin=anonymous></script>-->
<script>

    function addDay(e)
    {
        return;
        document.getElementById(e.value).style.display = e.checked ? "initial" : "none";
        console.log(document.getElementById(e.value));
        console.log(document.querySelector('#store_add').checked);
    }

    function checkValue(section, checkbox) {
        return;
        var masterCheckbox = document.getElementById(section + " ::first-child").value;
        var childClickedCheckbox = checkbox.value;
        
        if(childClickedCheckbox == true) {
            
            masterCheckbox = checked;
        }
    }
    
    /*
	//var chk1 = $('input[name="yes"]:checked');
	var chk1 = $("input[type='checkbox'][value='store_manage']");
	var chk2 = $("input[type='checkbox'][value='store_add']");
	var chk3 = $("input[type='checkbox'][value='store_edit']");
	var chk4 = $("input[type='checkbox'][value='store_del']");
	
	var checkedVal2 = $('#store_add').is(":checked");
	var checkedVal3 = $('#store_edit').is(":checked");
	var checkedVal4 = $('#store_del').is(":checked");
	
	chk2.change(function(){
		checkedVal2 = $('#store_add').is(":checked");
		checkedVal3 = $('#store_edit').is(":checked");
		checkedVal4 = $('#store_del').is(":checked");
		
		//alert(checkedVal2 + ' - ' + checkedVal3 + ' - ' + checkedVal4)
		
		if(checkedVal2 == false) {
			if(checkedVal3 == false && checkedVal4 == false) {
				chk1.prop('checked',this.checked);
			}
		}
		else {
			chk1.prop('checked',this.checked);
		}	
	});
	chk3.change(function(){
		checkedVal2 = $('#store_add').is(":checked");
		checkedVal3 = $('#store_edit').is(":checked");
		checkedVal4 = $('#store_del').is(":checked");
		
		//alert(checkedVal2 + ' - ' + checkedVal3 + ' - ' + checkedVal4)
		
		if(checkedVal3 == false) {
			if(checkedVal2 == false && checkedVal4 == false) {
				chk1.prop('checked',this.checked);
			}
		}
		else {
			chk1.prop('checked',this.checked);
		}
	});
	chk4.change(function(){
		checkedVal2 = $('#store_add').is(":checked");
		checkedVal3 = $('#store_edit').is(":checked");
		checkedVal4 = $('#store_del').is(":checked");
		
		//alert(checkedVal2 + ' - ' + checkedVal3 + ' - ' + checkedVal4)
		
		if(checkedVal4 == false) {
			if(checkedVal2 == false && checkedVal3 == false) {
				chk1.prop('checked',this.checked);
			}
		}
		else {
			chk1.prop('checked',this.checked);
		}
	});			
    */
    
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
    .col-md-3 {border:1px solid;}
    .border-right-0 {border-right:0px !important;}
    .border-left-0 {border-left:0px !important;}
</style>