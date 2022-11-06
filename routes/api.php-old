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
Route::get('/faqs', 'api\ApiController@faq');

//Route::get('/auth/login', 'api\ApiController@getProduct');
Route::post('/address', 'api\ApiController@addAddress');
Route::get('/getdefaultaddress', 'api\ApiController@getDefaultAddress');
Route::get('/address', 'api\ApiController@address');
Route::get('/stores', 'api\ApiController@stores');
Route::get('/stores/{id}', 'api\ApiController@getStore');
Route::get('/vendors', 'api\ApiController@vendorSelect');


Route::get('/deliveryslot', 'api\ApiController@deliverySlot');

Route::any('/placeorder', 'api\ApiController@placeOrder');
#Route::post('/placeorder', 'api\ApiController@placeOrder');
Route::get('/brands', 'api\ApiController@brands');
Route::get('/brands/{id}', 'api\ApiController@getBrandsProduct');
Route::any('/ajax_login', 'api\ApiController@ajax_login');
Route::any('/user_token', 'api\ApiController@user_token');

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





Route::get('/storeselect', 'api\ApiController@storeSelect');

