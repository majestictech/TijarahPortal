<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('admin.user.signIn');
// });

//Route::group(['middleware' => 'auth.admin'], function () {   

/*Route::get('/admin', function() {
	return view('admin.dashboard.index');
});
*/


Route::get('admin', 'Admin\AdminIndexController@index');
Route::get('/', 'Admin\AdminIndexController@index');



Auth::routes();

//Route::group(['middleware' => ['auth']], function() {
	Route::group(['middleware' => 'auth.admin'], function () {
    // your routes

	Route::get('admin/settings', 'Admin\SettingsController@index')->name('settings.index');
	Route::get('admin/settings/{id}/edit', 'Admin\SettingsController@edit')->name('settings.edit');
	Route::post('admin/settings/update','Admin\SettingsController@update')->name('settings.update');
	
	//-------------- Users-Management---------------
	Route::get('admin/usersmanagement','Admin\UsersManagementController@index')->name('usersmanagement.index');
	Route::get('admin/usersmanagement/create','Admin\UsersManagementController@create')->name('usersmanagement.create');
	Route::post('admin/usersmanagement/create','Admin\UsersManagementController@store')->name('usersmanagement.store');
	Route::get('admin/usersmanagement/{id}/edit','Admin\UsersManagementController@edit')->name('usersmanagement.edit');
	Route::post('admin/usersmanagement/update','Admin\UsersManagementController@update')->name('usersmanagement.update');
	

	//-------------- Admin-Management---------------
	Route::get('admin/adminmanagement', 'Admin\AdminManagementController@index')->name('adminmanagement.index');
	Route::get('admin/adminmanagement/create','Admin\AdminManagementController@create')->name('adminmanagement.create');
	Route::post('admin/adminmanagement/create','Admin\AdminManagementController@store')->name('adminmanagement.store');
	Route::get('admin/adminmanagement/{id}/edit','Admin\AdminManagementController@edit')->name('adminmanagement.edit');
	Route::post('admin/adminmanagement/update','Admin\AdminManagementController@update')->name('adminmanagement.update');
	
	//-------------- Sub-Admin ---------------
	
	Route::get('admin/subadmin', 'Admin\SubAdminController@index')->name('subadmin.index');
	Route::get('admin/subadmin/{id}/edit','Admin\SubAdminController@edit')->name('subadmin.edit');
	Route::get('admin/subadmin/{id}/delete','Admin\SubAdminController@destroy')->name('subadmin.destroy');
	Route::get('admin/subadmin/create','Admin\SubAdminController@create')->name('subadmin.create');
	Route::post('admin/subadmin/create','Admin\SubAdminController@store')->name('subadmin.store');
	Route::post('admin/subadmin/update','Admin\SubAdminController@update')->name('subadmin.update');
	Route::get('admin/subadmin/{id}/view','Admin\SubAdminController@view')->name('subadmin.view');
	

	
		//-------------- Associate ---------------
	
	Route::get('admin/associate', 'Admin\AssociateController@index')->name('associate.index');
	Route::get('admin/associate/{id}/edit','Admin\AssociateController@edit')->name('associate.edit');
	Route::get('admin/associate/{id}/delete','Admin\AssociateController@destroy')->name('associate.destroy');
	Route::get('admin/associate/create','Admin\AssociateController@create')->name('associate.create');
	Route::post('admin/associate/create','Admin\AssociateController@store')->name('associate.store');
	Route::post('admin/associate/update','Admin\AssociateController@update')->name('associate.update');
	//Route::get('admin/associate/{id}/view','Admin\AssociateController@view')->name('associate.view');
	

		//-------------- Permission ---------------
	
	Route::get('admin/permission', 'Admin\PermissionController@index')->name('permission.index');
	Route::get('admin/permission/{id}/edit','Admin\PermissionController@edit')->name('permission.edit');
	Route::get('admin/permission/{id}/delete','Admin\PermissionController@destroy')->name('permission.destroy');
	Route::get('admin/permission/create','Admin\PermissionController@create')->name('permission.create');
	Route::post('admin/permission/create','Admin\PermissionController@store')->name('permission.store');
	Route::post('admin/permission/update','Admin\PermissionController@update')->name('permission.update');

	
		//-------------- VAT ---------------
	
	Route::get('admin/vat', 'Admin\VatController@index')->name('vat.index');
	Route::get('admin/vat/{id}/edit','Admin\VatController@edit')->name('vat.edit');
	Route::get('admin/vat/{id}/delete','Admin\VatController@destroy')->name('vat.destroy');
	Route::get('admin/vat/create','Admin\VatController@create')->name('vat.create');
	Route::post('admin/vat/create','Admin\VatController@store')->name('vat.store');
	Route::post('admin/vat/update','Admin\VatController@update')->name('vat.update');
	
	
	
	//----------- Stores-------------------------------------
	
	Route::get('admin/store/', 'Admin\StoreController@index')->name('store.index');
	Route::get('admin/store/{id}/edit','Admin\StoreController@edit')->name('store.edit');
	Route::get('admin/store/{id}/delete','Admin\StoreController@destroy')->name('store.destroy');
	Route::get('admin/store/create','Admin\StoreController@create')->name('store.create');
	Route::post('admin/store/create','Admin\StoreController@store')->name('store.store');
	Route::post('admin/store/update','Admin\StoreController@update')->name('store.update');
	Route::get('admin/store/{id}/view','Admin\StoreController@view')->name('store.view');
	Route::get('admin/store/{id}/zeroinventory','Admin\StoreController@zeroInventory');
	Route::get('admin/store/{id}/emptyinventory','Admin\StoreController@emptyInventory');
	Route::get('admin/store/{id}/disable','Admin\StoreController@disableStore');
	Route::get('admin/store/{id}/enable','Admin\StoreController@enableStore');
	Route::get('admin/store/lowinventoryemail', 'Admin\StoreController@lowinventoryemail');
	
	
		//----------- StoreType-------------------------------------
	
	Route::get('admin/storetype', 'Admin\StoreTypeController@index')->name('storetype.index');
	Route::get('admin/storetype/{id}/edit','Admin\StoreTypeController@edit')->name('storetype.edit');
	Route::get('admin/storetype/{id}/delete','Admin\StoreTypeController@destroy')->name('storetype.destroy');
	Route::get('admin/storetype/create','Admin\StoreTypeController@create')->name('storetype.create');
	Route::post('admin/storetype/create','Admin\StoreTypeController@store')->name('storetype.store');
	Route::post('admin/storetype/update','Admin\StoreTypeController@update')->name('storetype.update');


	//----------- Subscription -------------------------------------
		
	Route::get('admin/subscription', 'Admin\SubscriptionController@index')->name('subscription.index');
	Route::get('admin/subscription/create','Admin\SubscriptionController@create')->name('subscription.create');
	Route::post('admin/subscription/store', 'Admin\SubscriptionController@store')->name('subscription.store');
	Route::get('admin/subscription/{id}/edit','Admin\SubscriptionController@edit')->name('subscription.edit');
	Route::post('admin/subscription/update', 'Admin\SubscriptionController@update')->name('subscription.update');
	Route::get('admin/subscription/{id}/delete','Admin\SubscriptionController@destroy')->name('subscription.destroy');


	//----------------Category----------------------------------

	Route::get('admin/category/create', 'Admin\CategoryController@create');
	Route::get('admin/category', 'Admin\CategoryController@index');
	
	//---------------Category Store--------------------
	//Route::get('admin/category/create', 'Admin\CategoryController@create');
	Route::get('admin/category/{id}', 'Admin\StoreController@categories');
	Route::get('admin/category/create/{id}', 'Admin\StoreController@createcat');
	Route::post('admin/category/create/{id}', 'Admin\StoreController@storecat')->name('category.storecat');
	
	
	
		//----------------Shift For Store Login ---------------------//
	Route::get('admin/shift', 'Admin\ShiftController@index')->name('shift.index');
	
	
	//----------------Shift----------------------------------

	Route::get('admin/shift/create/{id}', 'Admin\ShiftController@create')->name('shift.create');
	Route::post('admin/shift/create','Admin\ShiftController@store')->name('shift.store');
	Route::get('admin/shift', 'Admin\ShiftController@index')->name('shift.index');
	
	
	Route::get('admin/shift/{id}/edit','Admin\ShiftController@edit')->name('shift.edit');
	Route::post('admin/shift/update','Admin\ShiftController@update')->name('shift.update');

	
	
	//------------------Shift Store-----------------------------
	Route::get('admin/shift/{id}', 'Admin\StoreController@shifts');

	

	/*	//------------------Shift Store-----------------------------
	Route::get('admin/configemail/create/{id}', 'Admin\StoreController@configemails');


		//----------------ConfigEmail For Store Login ---------------------//
	Route::get('admin/configemail', 'Admin\ShiftController@index')->name('configemail.index');
	
	
	//----------------ConfigEmail----------------------------------

	Route::get('admin/configemail/create/{id}', 'Admin\ConfigEmailController@create')->name('configemail.create');
	Route::post('admin/configemail/create','Admin\ConfigEmailController@store')->name('configemail.store');
	Route::get('admin/configemail', 'Admin\ConfigEmailController@index')->name('configemail.index');
	
	
	Route::get('admin/configemail/{id}/edit','Admin\ConfigEmailController@edit')->name('configemail.edit');
	Route::post('admin/configemail/update','Admin\ConfigEmailController@update')->name('configemail.update');

	
	
	//------------------ConfigEmail Store-----------------------------
	Route::get('admin/shift/{id}', 'Admin\StoreController@shifts');	
	*/
	Route::get('admin/configemail/{id}/edit','Admin\ConfigEmailController@edit')->name('configemail.edit');
	Route::post('admin/configemail/update','Admin\ConfigEmailController@update')->name('configemail.update');
	
	//----------------PO For Store Login ---------------------//
	Route::get('admin/purchaseorder', 'Admin\PurchaseOrderController@index')->name('purchaseorder.index');
	
	
	//----------------PO----------------------------------

	Route::get('admin/purchaseorder/create/{id}', 'Admin\PurchaseOrderController@create')->name('purchaseorder.create');
	Route::post('admin/purchaseorder/create','Admin\PurchaseOrderController@store')->name('purchaseorder.store');
	Route::get('admin/purchaseorder', 'Admin\PurchaseOrderController@index')->name('purchaseorder.index');
	
	
	Route::get('admin/purchaseorder/{id}/edit','Admin\PurchaseOrderController@edit')->name('purchaseorder.edit');
	Route::post('admin/purchaseorder/update','Admin\PurchaseOrderController@update')->name('purchaseorder.update');

	
	
	//------------------PO Store-----------------------------
	Route::get('admin/purchaseorder/{id}', 'Admin\StoreController@purchaseorders');
	
	//------------------Vendor Store-----------------------------
	Route::get('admin/vendor/{id}', 'Admin\StoreController@vendors');	
	
		//----------------Invoice For Store Login ---------------------//
	Route::get('admin/invoice', 'Admin\InvoiceController@index')->name('invoice.index');
	
	
	//----------------Invoice----------------------------------

	Route::get('admin/invoice/create/{id}', 'Admin\InvoiceController@create')->name('invoice.create');
	Route::post('admin/invoice/create','Admin\InvoiceController@store')->name('invoice.store');
	Route::get('admin/invoice', 'Admin\InvoiceController@index')->name('invoice.index');
	
	
	Route::get('admin/invoice/{id}/edit','Admin\InvoiceController@edit')->name('invoice.edit');
	Route::post('admin/invoice/update','Admin\InvoiceController@update')->name('invoice.update');

	
	
	//------------------Invoice Store-----------------------------
	Route::get('admin/invoice/{id}', 'Admin\StoreController@invoices');
	
	
	//----------------Product For Store Login ---------------------//
	Route::get('admin/product', 'Admin\ProductController@index')->name('product.index');
	
	
	//----------------Product----------------------------------

	Route::get('admin/product/create/{id}', 'Admin\ProductController@create')->name('product.create');
	Route::post('admin/product/create','Admin\ProductController@store')->name('product.store');
	Route::get('admin/product', 'Admin\ProductController@index')->name('product.index');
	
	
	Route::get('admin/product/{id}/edit','Admin\ProductController@edit')->name('product.edit');
	Route::post('admin/product/update','Admin\ProductController@update')->name('product.update');
	Route::get('admin/product/{id}/view','Admin\ProductController@view')->name('product.view');
	Route::get('admin/product/import', 'Admin\ProductController@import')->name('product.import');
	Route::post('admin/product/import', 'Admin\ProductController@import')->name('product.import');
    Route::get('admin/product/{id}/delete','Admin\ProductController@destroy')->name('product.destroy');
	
	Route::get('admin/product/expirydate/{id}/edit','Admin\ProductController@editInventory')->name('product.expirydate.edit');
	Route::get('admin/product/expirydate','Admin\ProductController@expirydate')->name('product.expirydate');
	Route::post('admin/product/expirydate/update','Admin\ProductController@updateInventory')->name('expirydate.update');	
    //----------------Global Product----------------------------------
    
  // Route::get('admin/globalproducts/globalimport', 'Admin\GlobalProductsController@globalimport')->name('product.globalimport');
	Route::post('admin/globalproducts/globalimport', 'Admin\GlobalProductsController@globalimport')->name('globalproducts.globalimport');
	Route::get('admin/globalproducts', 'Admin\GlobalProductsController@index')->name('globalproducts.index');
	Route::get('admin/globalproducts/{id}/edit','Admin\GlobalProductsController@edit')->name('globalproducts.edit');
	Route::post('admin/globalproducts/update','Admin\GlobalProductsController@update')->name('globalproducts.update');    
    Route::get('admin/globalproducts/{id}/delete','Admin\GlobalProductsController@destroy')->name('globalproducts.destroy');
    
//	Route::get('admin/product/destroyProduct','Admin\ProductController@destroyProduct')->name('product.destroyProduct');
	
	//------------------Product Store-----------------------------
	Route::get('admin/product/{id}', 'Admin\StoreController@products');
	/*Route::get('admin/product/create/{id}', 'Admin\ProductController@create')->name('product.create');
	Route::post('admin/product/create','Admin\ProductController@store')->name('product.store');*/
	
	
	
	//------------------ Customerscreen -------------//
	
	Route::get('admin/customerscreen/{id}', 'Admin\CustomerScreenController@index');
	Route::get('admin/customerscreen/create/{id}', 'Admin\CustomerScreenController@create');
	Route::get('admin/customerscreen/{id}/edit','Admin\CustomerScreenController@edit');
	Route::post('admin/customerscreen/create','Admin\CustomerScreenController@store')->name('customerscreen.store');
	Route::post('admin/customerscreen/update','Admin\CustomerScreenController@update')->name('customerscreen.update');
	
	
	//-------------- Manage Users ---------------
	/*
	Route::get('admin/manageusers', 'Admin\ManageUsersController@index')->name('manageusers.index');
	Route::get('admin/manageusers/create','Admin\ManageUsersController@create')->name('manageusers.create');
	Route::get('admin/manageusers/{id}/edit','Admin\ManageUsersController@edit')->name('manageusers.edit');
	Route::post('admin/manageusers/store','Admin\ManageUsersController@store')->name('manageusers.store');
	Route::post('admin/manageusers/update','Admin\ManageUsersController@update')->name('manageusers.update');
	*/

	

	//--------------   Cashier---------------
	
	Route::get('admin/cashier', 'Admin\CashierController@index')->name('cashier.index');
	Route::get('admin/cashier/{id}/edit','Admin\CashierController@edit')->name('cashier.edit');
	Route::get('admin/cashier/{id}/delete','Admin\CashierController@destroy')->name('cashier.destroy');
	Route::get('admin/cashier/create/{id}','Admin\CashierController@create')->name('cashier.create');
	Route::post('admin/cashier/create','Admin\CashierController@store')->name('cashier.store');
	Route::post('admin/cashier/update','Admin\CashierController@update')->name('cashier.update');
	Route::get('admin/cashier/{id}/view','Admin\CashierController@view')->name('cashier.view');
	
		//------------------Cashier Store-----------------------------
	Route::get('admin/cashier/{id}', 'Admin\StoreController@cashiers');
	Route::get('admin/manageusers/{id}', 'Admin\CashierController@storeindex');

	//-------------- Manage Users ---------------
	
	Route::get('admin/manageusers', 'Admin\CashierController@index')->name('cashier.index');
	Route::get('admin/manageusers/{id}/edit','Admin\CashierController@edit')->name('cashier.edit');
	Route::get('admin/manageusers/{id}/delete','Admin\CashierController@destroy')->name('cashier.destroy');
	Route::get('admin/manageusers/create/{id}','Admin\CashierController@create')->name('cashier.create');
	Route::post('admin/manageusers/create','Admin\CashierController@store')->name('cashier.store');
	Route::post('admin/manageusers/update','Admin\CashierController@update')->name('cashier.update');
	Route::get('admin/manageusers/{id}/view','Admin\CashierController@view')->name('cashier.view');
	

		//-------------- Cashier ---------------
	
	Route::get('admin/device', 'Admin\DeviceController@index')->name('device.index');
	Route::get('admin/device/{id}/delete','Admin\DeviceController@destroy')->name('device.destroy');
	

		//------------------Cashier Store-----------------------------
	Route::get('admin/device/{id}', 'Admin\StoreController@devices');
	
   //----------------Customer----------------------------------

	Route::get('admin/customer/create/{id}', 'Admin\CustomerController@create')->name('cashier.create');
	Route::get('admin/customer', 'Admin\CustomerController@index')->name('cashier.index');
	Route::post('admin/customer/create','Admin\CustomerController@store')->name('customer.store');
	Route::post('admin/customer/update','Admin\CustomerController@update')->name('customer.update');
	Route::get('admin/customer/{id}/view','Admin\CustomerController@view')->name('customer.view');
	Route::get('admin/customer/{id}/edit','Admin\CustomerController@edit')->name('customer.edit');
	Route::get('admin/customer/{id}/delete','Admin\CustomerController@destroy')->name('customer.destroy');
	
	Route::get('admin/customer/loyaltyview/{id}','Admin\CustomerController@loyalty')->name('customer.loyalty');
	
		//------------------Customer Store-----------------------------
	Route::get('admin/customer/{id}', 'Admin\StoreController@customers');	
	

	//----------------Vendor----------------------------------

	//Route::get('admin/vendor/create', 'Admin\VendorController@create');
	//Route::get('admin/vendor', 'Admin\VendorController@index');

	Route::get('admin/vendor', 'Admin\VendorController@index')->name('vendor.index');
	Route::get('admin/vendor/{id}/edit','Admin\VendorController@edit')->name('vendor.edit');
	Route::get('admin/vendor/{id}/delete','Admin\VendorController@destroy')->name('vendor.destroy');
	Route::get('admin/vendor/create','Admin\VendorController@create')->name('vendor.create');
	Route::post('admin/vendor/create','Admin\VendorController@store')->name('vendor.store');
	Route::post('admin/vendor/update','Admin\VendorController@update')->name('vendor.update');
	Route::get('admin/vendor/{id}/view','Admin\VendorController@view')->name('vendor.view');


	//----------------Driver----------------------------------


	Route::get('admin/driver', 'Admin\DriverController@index')->name('driver.index');
	Route::get('admin/driver/{id}/edit','Admin\DriverController@edit')->name('driver.edit');
	Route::get('admin/driver/{id}/delete','Admin\DriverController@destroy')->name('driver.destroy');
	Route::get('admin/driver/create','Admin\DriverController@create')->name('driver.create');
	Route::post('admin/driver/create','Admin\DriverController@store')->name('driver.store');
	Route::post('admin/driver/update','Admin\DriverController@update')->name('driver.update');
	Route::get('admin/driver/{id}/view','Admin\DriverController@view')->name('driver.view');



//----------------Delivery Slot----------------------------------


	Route::get('admin/deliveryslot', 'Admin\DeliveryController@index')->name('deliveryslot.index');
	Route::get('admin/deliveryslot/{id}/edit','Admin\DeliveryController@edit')->name('deliveryslot.edit');
	Route::get('admin/deliveryslot/{id}/delete','Admin\DeliveryController@destroy')->name('deliveryslot.destroy');
	Route::get('admin/deliveryslot/create','Admin\DeliveryController@create')->name('deliveryslot.create');
	Route::post('admin/deliveryslot/create','Admin\DeliveryController@store')->name('deliveryslot.store');
	Route::post('admin/deliveryslot/update','Admin\DeliveryController@update')->name('deliveryslot.update');
	Route::get('admin/deliveryslot/{id}/view','Admin\DeliveryController@view')->name('deliveryslot.view');

	
	//---------Orders for stores---------------//
	
	Route::get('admin/order/{storeid}', 'Admin\OrderController@storeindex');
	
	
	
	
	//---------Products for stores-------------//
	
    Route::get('admin/product/{storeid}', 'Admin\ProductController@storeindex')->name('product.index');
    
	//---------Shifts for stores-------------//
	
    Route::get('admin/shift/{storeid}', 'Admin\ShiftController@storeindex')->name('shift.index');
    
    //---------------------Customers for store------------
    Route::get('admin/customer/{id}', 'Admin\CustomerController@storeindex');
    
    //----------------Cashier for Store-----------------
    Route::get('admin/cashier/{id}', 'Admin\CashierController@storeindex');
    
	//----------------Order----------------------------------

	Route::get('admin/order/create', 'Admin\OrderController@create');
	Route::get('admin/order/', 'Admin\OrderController@index')->name('order.index');
	Route::get('admin/order/{id}/view','Admin\OrderController@view')->name('order.view');
	Route::post('admin/order/update','Admin\OrderController@update')->name('order.update');

	//----------------Return----------------------------------

	Route::get('admin/return', 'Admin\ReturnController@index')->name('return.index');
	Route::get('admin/return/{id}/edit','Admin\ReturnController@edit')->name('return.edit');
	Route::get('admin/return/{id}/delete','Admin\ReturnController@destroy')->name('return.destroy');
	Route::get('admin/return/create','Admin\ReturnController@create')->name('return.create');
	Route::post('admin/return/create','Admin\ReturnController@store')->name('return.store');
	Route::post('admin/return/update','Admin\ReturnController@update')->name('return.update');
	Route::get('admin/return/{id}/view','Admin\ReturnController@view')->name('return.view');


	//----------------Invoice----------------------------------

	Route::get('admin/invoice', 'Admin\InvoiceController@index')->name('invoice.index');
	Route::get('admin/invoice/{id}/edit','Admin\InvoiceController@edit')->name('invoice.edit');
	Route::get('admin/invoice/{id}/delete','Admin\InvoiceController@destroy')->name('invoice.destroy');
	Route::get('admin/invoice/create','Admin\InvoiceController@create')->name('invoice.create');
	Route::post('admin/invoice/create','Admin\InvoiceController@store')->name('invoice.store');
	Route::post('admin/invoice/update','Admin\InvoiceController@update')->name('invoice.update');
	
	
	
	
	//----------------Brands----------------------------------

	Route::get('admin/brand', 'Admin\BrandController@index')->name('brand.index');
	
	Route::get('admin/brand/create','Admin\BrandController@create')->name('brand.create');
	
	Route::post('admin/brand/create','Admin\BrandController@store')->name('brand.store');
	
	Route::get('admin/brand/{id}/delete','Admin\BrandController@destroy')->name('brand.destroy');
	Route::get('admin/brand/{id}/edit','Admin\BrandController@edit')->name('brand.edit');
	Route::get('admin/brand/{id}/delete','Admin\BrandController@destroy')->name('brand.destroy');
	
	Route::post('admin/brand/update','Admin\BrandController@update')->name('brand.update');

	//----------------Promocode----------------------------------

	Route::get('admin/promocode/create', 'Admin\PromocodeController@create');
	Route::get('admin/promocode', 'Admin\PromocodeController@index');


	//----------------Report----------------------------------

	//Route::get('admin/report/{type}/{id}', 'Admin\ReportController@index');
	
	Route::get('admin/storereports/{id}', 'Admin\StoreReportsController@index');
	Route::get('admin/storereports/salesreports/{id}', 'Admin\StoreReportsController@salesreports');
	Route::get('admin/storereports/vatreports/{id}', 'Admin\StoreReportsController@vatreports');
	Route::get('admin/storereports/refundreports/{id}', 'Admin\StoreReportsController@refundreports');
	Route::get('admin/storereports/inventoryreports/{id}', 'Admin\StoreReportsController@inventoryreports');
	Route::get('admin/storereports/purchasereports/{id}', 'Admin\StoreReportsController@purchasereports');
	Route::get('admin/storereports/mediareports/{id}', 'Admin\StoreReportsController@mediareports');
	Route::get('admin/storereports/cashierreports/{id}', 'Admin\StoreReportsController@cashierreports');

	//----------------Report----------------------------------


	Route::get('admin/loyaltyhistory', 'Admin\LoyaltyController@index');


	//----------------PushNotification----------------------------------


	Route::get('admin/pushnotification/create', 'Admin\PushNotificationController@create');


	//----------------FAQ----------------------------------

	Route::get('admin/faq', 'Admin\FaqController@index')->name('faq.index');
	Route::get('admin/faq/{id}/edit','Admin\FaqController@edit')->name('faq.edit');
	Route::get('admin/faq/{id}/delete','Admin\FaqController@destroy')->name('faq.destroy');
	Route::get('admin/faq/create','Admin\FaqController@create')->name('faq.create');
	Route::post('admin/faq/create','Admin\FaqController@store')->name('faq.store');
	Route::post('admin/faq/update','Admin\FaqController@update')->name('faq.update');
	Route::get('admin/salesman', 'Admin\SalesmanController@index')->name('salesman.index');
	Route::get('admin/salesman/{id}/edit','Admin\SalesmanController@edit')->name('salesman.edit');
	Route::get('admin/salesman/{id}/delete','Admin\SalesmanController@destroy')->name('salesman.destroy');
	Route::get('admin/salesman/create','Admin\SalesmanController@create')->name('salesman.create');
	Route::post('admin/salesman/create','Admin\SalesmanController@store')->name('salesman.store');
	Route::post('admin/salesman/update','Admin\SalesmanController@update')->name('salesman.update');
	Route::get('admin/salesman/{id}/view','Admin\SalesmanController@view')->name('salesman.view');

	//---------------- Promocode-----------------
	Route::get('admin/promocode', 'Admin\PromocodeController@index')->name('promocode.index');
	Route::get('admin/promocode/{id}/edit','Admin\PromocodeController@edit')->name('promocode.edit');
	Route::get('admin/promocode/{id}/delete','Admin\PromocodeController@destroy')->name('promocode.destroy');
	Route::get('admin/promocode/create','Admin\PromocodeController@create')->name('promocode.create');
	Route::post('admin/promocode/create','Admin\PromocodeController@store')->name('promocode.store');
	Route::post('admin/promocode/update','Admin\PromocodeController@update')->name('promocode.update');
	Route::get('admin/promocode/{id}/view','Admin\PromocodeController@view')->name('promocode.view');

	//---------------- Category-----------------
	Route::get('admin/category', 'Admin\CategoryController@index')->name('category.index');
	Route::get('admin/category/{id}/edit','Admin\CategoryController@edit')->name('category.edit');
	Route::get('admin/category/{id}/delete','Admin\CategoryController@destroy')->name('category.destroy');
	Route::get('admin/category/create','Admin\CategoryController@create')->name('category.create');
	Route::post('admin/category/create','Admin\CategoryController@store')->name('category.store');
	Route::post('admin/category/update','Admin\CategoryController@update')->name('category.update');
	Route::get('admin/category/{id}/view','Admin\CategoryController@view')->name('category.view');
	
	
	
	//-------------------User Logs-------------------------------------
	Route::get('admin/add-to-log', 'Admin\AdminIndexController@myTestAddToLog');
    Route::get('admin/logActivity', 'Admin\AdminIndexController@logActivity');
    
    //-------------------User Roles-------------------------------------
    Route::get('admin/userroles', 'Admin\UserRoleController@index')->name('userroles.index');
    Route::post('admin/userroles', 'Admin\UserRoleController@store')->name('userroles.store');
    //Route::post('admin/userroles','Admin\CategoryController@store')->name('category.store');
    Route::get('admin/restricted', 'Admin\UserRoleController@restricted');

    
    //--------------------- Order Range Export-------------------------
    Route::post('admin/order/export', 'Admin\OrderController@exportOrder')->name('order.export');
	
	
});

Route::get('admin/logout', 'Admin\AuthenticateController@logout');

Route::get('admin/resetpass','Admin\AuthenticateController@resetpass');

//Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/', 'UserController@login');
//Route::get('/', 'UserController@login');

//Route::post('/users/login', 'UserController@userlogin');
Route::get('admin/users/logout', 'UserController@logout');

//Route::post('ajax_login', 'Auth\LoginuserController');



//----------------Stores---------------------------------

/*Route::get('admin/store/create', 'Admin\StoreController@create')->name('store.create');
Route::post('admin/store/create', 'Admin\StoreController@store')->name('store.store');
Route::get('admin/store', 'Admin\StoreController@index')->name('store.index');
Route::get('admin/store/{id}/edit','Admin\StoreController@edit')->name('store.edit');
Route::post('admin/store/update/{id}','Admin\StoreController@update')->name('store.update');
Route::get('admin/store/{id}/delete','Admin\StoreController@destroy')->name('store.destroy');
*/

//Route::get('admin/store', 'Admin\StoreController@index')->name('store.index');
//-----------------Authentication----------------------------
	Route::get('admin/login', 'Admin\AuthenticateController@login');
	Route::post('admin/login','Admin\AuthenticateController@userlogin');
	Route::get('admin/register', 'Admin\AuthenticateController@register');
	Route::get('admin/recoverPassword', 'Admin\AuthenticateController@recoverPassword');
	Route::get('admin/confirmMail', 'Admin\AuthenticateController@confirmMail');
	


//-----------------Route for Language Change -----------
Route::get('/{lang}', function ($lang) {
    App::setlocale($lang);
    return view('admin.dashboard.index');
});



Route::get('setlocale/{locale}',function($lang){
       \Session::put('locale',$lang);
       return redirect()->back();   
});
Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});





//---------------- Salesman-----------------



Route::get('/clear-cache', function() {

    $configCache = Artisan::call('config:cache');
    $clearCache = Artisan::call('cache:clear');
    // return what you want
});



//---------------- Product-----------------


//Route::get('product/export/', 'Admin\ProductController@export');

Route::post('admin/product/export', 'Admin\ProductController@export')->name('product.export');
Route::get('generate-pdf','Admin\ProductController@generatePDF');



Route::post('admin/store/export', 'Admin\StoreController@exportReport')->name('report.export');

Route::post('downloadPDF', 'Admin\ProductController@downloadPDF')->name('product.downloadPDF');



//----------------APP Update Push Notification----------------------------------


Route::get('admin/appupdate/create','Admin\AppUpdateController@create')->name('appupdate.create');
Route::post('admin/appupdate/create','Admin\AppUpdateController@store')->name('appupdate.store');

//Route::any ( 'admin/store/search','Admin\StoreController@search')->name('store.index');


//Route::get('admin/store/search', 'Admin\StoreController@getSearch');
//Route::get('admin/store/search/', 'Admin\StoreController@search')->name('search');

Route::get('admin/customerdisplay', 'UserController@customerdisplay');
Route::get('admin/sliderimages', 'UserController@sliderImages');
