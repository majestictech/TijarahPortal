<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/categories', 'api\ApiController@categories');
Route::get('/categories/{id}', 'api\ApiController@getSubCategories');
Route::get('/picad', 'api\ApiController@picad');
Route::get('/drivers', 'api\ApiController@drivers');

Route::get('/banners', 'api\ApiController@banners');
Route::get('/banners/{id}', 'api\ApiController@getBanner');
Route::get('/getcat', 'api\ApiController@getcategories');
Route::get('/products/{catid}', 'api\ApiController@products');
Route::get('/topsavers/{catid}', 'api\ApiController@topSavers');

Route::get('/productdetail/{id}', 'api\ApiController@getProduct');
Route::get('/orders', 'api\ApiController@orders');
Route::get('/orders/{id}', 'api\ApiController@getOrder');
Route::get('/driverorder/{id}', 'api\ApiController@driverorders');
Route::get('/userorders/{id}', 'api\ApiController@userorders');
Route::get('/faqs', 'api\ApiController@faq');

//Route::get('/auth/login', 'api\ApiController@getProduct');
Route::post('/address', 'api\ApiController@addAddress');
Route::get('/getdefaultaddress', 'api\ApiController@getDefaultAddress');
Route::get('/address', 'api\ApiController@address');
Route::get('/stores', 'api\ApiController@stores');
Route::get('/stores/{id}', 'api\ApiController@getStore');

Route::get('/status', 'api\ApiController@status');


Route::get('/deliveryslot', 'api\ApiController@deliverySlot');

Route::any('/placeorder', 'api\ApiController@placeOrder');
#Route::post('/placeorder', 'api\ApiController@placeOrder');
Route::get('/brands', 'api\ApiController@brands');
Route::get('/brands/{id}', 'api\ApiController@getBrandsProduct');
Route::any('/ajax_login', 'api\ApiController@ajax_login');
Route::any('/ajax_loginecr', 'api\ApiController@ajax_loginecr');
Route::any('/ajax_loginecrsetup', 'api\ApiController@ajax_loginecrsetup');
Route::any('/user_token', 'api\ApiController@user_token');


Route::get('/users/{id}', 'api\ApiController@users');
Route::get('/orderspos/{id}', 'api\ApiController@orderspos');
Route::get('/orderspossearch/{id}/{searchtext}', 'api\ApiController@orderspossearch');
Route::get('/categoriespos', 'api\ApiController@categoriespos');
Route::get('/categoriesname', 'api\ApiController@categoriesName');
Route::get('/categoriesstorepos/{id}', 'api\ApiController@categoriesStorepos');
Route::get('/categoriespos/{id}/{storeid}', 'api\ApiController@getSubCategoriespos');
Route::get('/posproduct/{id}', 'api\ApiController@posproduct');

Route::post('/addproduct', 'api\ApiController@addproduct');
Route::post('/addcustomer', 'api\ApiController@addcustomer');
Route::any('/editproduct/{id}', 'api\ApiController@editproduct');
Route::post('/updateproduct', 'api\ApiController@updateproduct');
Route::any('/editcustomer/{id}', 'api\ApiController@editcustomer');
Route::any('/updatecustomer', 'api\ApiController@updatecustomer');
Route::get('/customercredit/{id}', 'api\ApiController@customerCredit');

/* Manage Payment  */
Route::post('/usermanagepayment', 'api\ApiController@userManagePayment');
/* Update Stock Batch */
Route::post('/updatestock', 'api\ApiController@updateStock');
Route::post('/movestock', 'api\ApiController@moveStock');
	

/*
Route::get('/productslist/{id}', 'api\ApiController@productslist');
Route::get('/productslistsearch/{id}/{searchtext}', 'api\ApiController@productslistsearch');
*/
Route::get('/productpossearch/{id}', 'api\ApiController@productpossearch');

Route::get('/inventorylist/{id}', 'api\ApiController@inventorylist');

/* Reports Starts */
Route::get('/salesreport/{id}', 'api\ApiController@salesreport');

Route::get('/mediareport/{id}', 'api\ApiController@mediareport');
Route::get('/cashierreport/{id}', 'api\ApiController@cashierreport');

Route::get('/shiftreports/{storeId}', 'api\ApiController@shiftReports');
Route::get('/shiftdayreports/{storeId}', 'api\ApiController@shiftdayReports');
Route::get('/shiftreportview', 'api\ApiController@shiftReportView');



Route::get('/salesreportemail', 'api\ApiController@salesreportemail');
Route::get('/vatreportemail', 'api\ApiController@vatReportEmail');
Route::get('/refundreportemail', 'api\ApiController@refundReportEmail');
Route::get('/mediareportemail', 'api\ApiController@mediaReportemail');
Route::get('/cashierreportemail', 'api\ApiController@cashierReportemail');
Route::get('/profitlossreportemail', 'api\ApiController@profitLossReportemail');
Route::get('/shiftreportemail', 'api\ApiController@shiftReportemail');

Route::get('/customerpossearch/{id}', 'api\ApiController@customerpossearch');

Route::get('/getorderpos/{id}', 'api\ApiController@getOrderpos');
Route::get('/homedatagraph/{id}', 'api\ApiController@homedatagraph');
Route::get('/inventorygraph/{id}', 'api\ApiController@inventoryGraphData');
Route::get('/customerposdetail/{id}', 'api\ApiController@customerposdetail');

Route::post('/placeorderpos', 'api\ApiController@placeorderpos');
Route::get('/hsndatagraph/{id}', 'api\ApiController@hsndatagraph');
Route::get('/dailydatagraph/{id}', 'api\ApiController@dailydatagraph');
Route::get('/stockdatagraph/{id}', 'api\ApiController@stockdatagraph');
Route::get('/salesdatagraph/{id}', 'api\ApiController@salesdatagraph');
Route::get('/customerorders/{id}', 'api\ApiController@customerOrders');
Route::get('/country', 'api\ApiController@country');
Route::get('/vendors/{id}', 'api\ApiController@vendorSelect');
Route::get('/invoices/{id}', 'api\ApiController@invoices');
Route::get('/purchaseorders/{id}', 'api\ApiController@purchaseorders');

Route::get('/ordervendors/{storeid}', 'api\ApiController@ordervendors');
//Route::get('/merchantpurchasereport/{storeid}', 'api\ApiController@merchantPurchaseReport');

Route::post('/addinvoice', 'api\ApiController@addinvoice');

Route::post('/addpurchase', 'api\ApiController@addpo');
Route::post('/addvendor', 'api\ApiController@addvendor');

Route::any('/editinvoice/{id}', 'api\ApiController@editinvoice');
Route::post('/updateinvoice', 'api\ApiController@updateinvoice');

Route::any('/editpo/{id}', 'api\ApiController@editpo');
Route::post('/updatepo', 'api\ApiController@updatepo');
Route::get('/vatlist', 'api\ApiController@vatlist');

Route::get('/brandlist', 'api\ApiController@brandlist');



Route::any('/placeorderecr', 'api\ApiController@placeorderecr');
Route::any('/placevendorinvoice', 'api\ApiController@placevendorinvoice');
//Route::get('/refund/{id}/{type}', 'api\ApiController@cancelRefundOrder');
Route::get('/podetail/{id}', 'api\ApiController@poDetail');
Route::get('/invoicedetail/{id}', 'api\ApiController@invoiceDetail');

Route::post('/cancelrefund', 'api\ApiController@cancelRefundOrder');

Route::post('/processrefund', 'api\ApiController@processRefund');

Route::get('/productsearchcashier/{id}', 'api\ApiController@productSearchCashier');

Route::any('/editvendor/{id}', 'api\ApiController@editvendor');
Route::post('/updatevendor', 'api\ApiController@updatevendor');


// Local Update URL
Route::get('/productsfetch/{id}', 'api\ApiController@productsFetch');
Route::get('/customersfetch/{id}', 'api\ApiController@customersFetch');
Route::get('/ordersfetch/{id}', 'api\ApiController@ordersFetch');
Route::get('/categoriesfetch/{id}', 'api\ApiController@categoriesFetch');
Route::get('/statusfetch', 'api\ApiController@statusFetch');
Route::get('/taxclassfetch', 'api\ApiController@taxClassFetch');
Route::get('/updatesync/{id}', 'api\ApiController@updateSync');
Route::get('/customervisit/{id}', 'api\ApiController@customerVisit');
Route::get('/averagepurchase/{id}', 'api\ApiController@averagePurchase');
Route::get('/storeprintfetch/{id}', 'api\ApiController@storePrintFetch');
//Route::get('ajax_login', function(){
	
	 /*$email = $request->input('email');
     $password = $request->input('password');

     $user = User::where('email', '=', $email)->first();
     if (!$user) {
        return response()->json(['success'=>false, 'message' => 'Login Fail, please check email id']);
     }
     if (!Hash::check($password, $user->password)) {
        return response()->json(['success'=>false, 'message' => 'Login Fail, pls check password']);
     }
        return response()->json(['success'=>true,'message'=>'success', 'data' => $user]);
	*/
	//echo "Arvind";
//});



Route::post('/recoverdata', 'api\ApiController@recoverData');

Route::get('/recoverdatatest', 'api\ApiController@recoverData');


Route::get('/storeselect', 'api\ApiController@storeSelect');
Route::get('/updatestore', 'api\ApiController@updateStore');
Route::any('/editstore/{id}', 'api\ApiController@editStore');
Route::post('/updatestore', 'api\ApiController@updateStore');
Route::post('/updateshop', 'api\ApiController@updateShop');


// ECR PLUS functions
Route::any('/placeorderplus', 'api\ApiController@placeorderplus');
Route::get('/ordersplus/{id}', 'api\ApiController@ordersplus');


// Export Excel
Route::get('/export', 'api\ApiController@export');

//PDF Mail Order Detail
Route::get('/emailorderdetail/{id}', 'api\ApiController@emailOrderDetail');


Route::get('/vatreport/{id}', 'api\ApiController@vatreport');
Route::get('/catvatreport/{id}', 'api\ApiController@catvatreport');
Route::get('/profitreport/{id}', 'api\ApiController@profitreport');
Route::get('/profitlossreports/{id}', 'api\ApiController@profitlossreports');
Route::get('/productreport/{id}', 'api\ApiController@productreport');
Route::get('/refundreport/{id}', 'api\ApiController@refundreport');
Route::get('/inventoryreport/{id}', 'api\ApiController@inventoryreport');
Route::get('/updatesetting/{id}', 'api\ApiController@updateSetting');
Route::any('/settings', 'api\ApiController@settings');

Route::any('/ordervendorlist/{id}', 'api\ApiController@orderVendorList');
/* Get Vendors */
Route::any('/storeVendor/{id}', 'api\ApiController@getStoreVendor');


Route::get('/categoriesnamepos/{id}', 'api\ApiController@categoriesNamePos');

Route::get('/getappver/{id}', 'api\ApiController@getAppVersion');
Route::get('/getsubscriptiondate/{id}', 'api\ApiController@getSubscriptionDate');
Route::get('/readfile', 'api\ApiController@readFile');
Route::get('/appupdate/{type}', 'api\ApiController@appUpdate');

Route::get('/paymenttransaction/{id}', 'api\ApiController@paymentTransaction');
Route::get('/summarysales/{id}', 'api\ApiController@summarySales');

Route::get('/appupdates/{id}', 'api\ApiController@appUpdates');

Route::post('/updateversion', 'api\ApiController@updateVersion');

// Cashier Listing, Add, Edit
Route::get('/listcashier/{id}', 'api\ApiController@listCashier');
Route::any('/editcashier/{id}', 'api\ApiController@editCashier');
Route::post('/updatecashier', 'api\ApiController@updateCashier');
Route::post('/addcashier', 'api\ApiController@addCashier');

// Store Users Listing, Add, Edit
Route::get('/liststoreusers/{id}', 'api\ApiController@listStoreUsers');
Route::any('/editstoreuser/{id}', 'api\ApiController@editStoreUser');
Route::post('/updatestoreuser', 'api\ApiController@updateStoreUser');
Route::post('/addstoreuser', 'api\ApiController@addStoreUser');

// Delivery Person Listing
Route::get('/listdeliveryperson/{id}', 'api\ApiController@getDeliveryPerson');

Route::get('/productexpiry/{id}', 'api\ApiController@getExpiryDate');
Route::get('/getupdatestockexpiry/{id}', 'api\ApiController@getupdatestockExpiryDate');
Route::get('/getmovefromexpirydate/{id}', 'api\ApiController@getMoveFromExpiryDate');

//Route::get('/expirylist/{id}', 'api\ApiController@expiryList');
//Product Inventory Batch Expiry Date 
//Route::get('/expirylist/{id}', 'api\ApiController@expiryList');

// Master Stock Reason
Route::get('/liststockreason','api\ApiController@getStockReason'); 
Route::get('/listproducts','api\ApiController@getProducts'); 
//Route::get('/listproducts','api\ApiController@getProducts'); 

// Get Shift Start and End Reason
Route::get('/shiftendreason','api\ApiController@shiftEndReason'); 
Route::get('/shiftinreason','api\ApiController@shiftInReason'); 

// Get Shift Start and End Reason
Route::post('/refundbalace','api\ApiController@refundBalace'); 

// Slider Images
Route::get('/sliderimages', 'api\ApiController@sliderImages');
Route::get('/batchdelete/{id}', 'api\ApiController@batchDelete');

//Route::get('/sliderimages','');

// Inventory Batch Listing & Update
// {id} is the product id
Route::get('/inventorybatch/{id}', 'api\ApiController@listInventoryBatch');
Route::post('/updateinventorybatch', 'api\ApiController@updateInventoryBatch');

// Get shifts
Route::get('/shifts/{id}', 'api\ApiController@shifts');
Route::get('/userroles', 'api\ApiController@userroles');

// Store Devices Update Bal
Route::get('/getstoredevices', 'api\ApiController@getStoreDevice');
Route::post('/storedevicesbal', 'api\ApiController@storeDevicesBal');

// Get User Shift

Route::get('/getusershift/{id}', 'api\ApiController@getUserShift');

// Enter User Shift
Route::post('/usersshiftin', 'api\ApiController@usersShiftIn');
Route::post('/usersshiftend', 'api\ApiController@usersShiftEnd');
Route::post('/updatecashdrawerbal', 'api\ApiController@updateCashdrawerBal');




Route::get('/getbatches/{id}', 'api\ApiController@productbatch');


Route::get('/getStrTime', 'api\ApiController@getStrTime');







