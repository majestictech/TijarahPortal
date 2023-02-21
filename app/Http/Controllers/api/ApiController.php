<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Driver;
use App\Banner;
use App\Orders;
use App\OrdersPos;
use App\Brand;
use App\Store;
use App\StoreVendor;
use App\User;
use App\Reports;
use App\Exports\ReportExportEmail;
use App\Product;
use App\Product_AR;
use App\ProductInventoryBatch;
use App\ProductLogs;

use App\Customer;
use App\CustomersCredit;
use App\OrdersData;
use App\VendorInvoice;
use App\VendorPurchase;
use App\Cashier;
use Mail;
use App\Settings;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;

use App\RecoverData;
use App\RecoverOrdersData;
use App\RecoverProductsData;

use Illuminate\Support\Facades\Storage;
/*
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
*/
use App\Faq;

use App\Http\Controllers\Controller;
use DB;
use Hash;
use Image;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once "vendor/autoload.php";
use Carbon\Carbon;
use PDF;

use App\BatchInventory;
use App\InventoryLogs;

use App\StoreDevices;
use App\UserShifts;
use App\MultiplePayment;
class ApiController extends Controller
{
	public function homeData()
	{
		/*
		$results = array();
		
		$results['banners'] = banners(true);
		$results['categories'] = categories(true);
		$results['topSavers'] = banners(true);
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
		*/
	}
	
    public function categories()
	{
		$results = DB::Table('categories as C1')->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C1.id')->leftJoin('categories AS C2', 'C2.id', '=', 'CR.parentCategoryId')->select('C1.id','C1.name','C1.catImage')->orWhereNull('C2.name')
		->orderBy('C1.id', 'ASC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function getSubCategories($catId)
	{
		// If $id has value it will fetch sub categories else if $id has "False" or "TopSavers" It will fetch all root categories
		$results = array();
		$count = 0;
		
		if($catId == "false" || $catId == "False" || $catId == "TopSavers" || $catId == "Top Savers") {			
			$categories = DB::Table('categories as C')->select('C.id','C.name','C.catImage')
			->whereNotIn('id',function($query) {
				$query->select('categoryId')->from('catrelation');
			})->orderBy('C.id', 'DESC')->get();
			
			/* Create 2D Array */
			foreach($categories as $category) {
				$now = date('Y-m-d');
				
				$products = DB::Table('products as P')->leftJoin('mas_weightclass', 'mas_weightclass.id', '=', 'P.weightClassId')
				->leftJoin('categories AS C', 'C.id', '=', 'P.categoryId')
				->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C.id')
				->select('P.id','P.name','P.minOrderQty','P.price','P.splPrice','P.weight','mas_weightclass.name AS weightClass','P.productImage', 
				DB::raw("ROUND((((P.price - P.splPrice)/(P.price + P.splPrice)) * 100),0) AS discount"))
				->where('CR.parentCategoryId','=',$category->id)
				->where('P.splPrice','!=','NULL')
				->where('P.splPriceFrom', '<=', $now)
				->where('P.splPriceTo', '>=', $now)
				->where('P.status', '=', 'Available')
				->orderBy('P.id', 'DESC')->get();
				
				$results[$count]['categoryId'] = $category->id;
				$results[$count]['categoryName'] = $category->name;
				$results[$count]['categoryImage'] = $category->catImage;
				$results[$count]['products'] = $products;
				
				$count++;
			}
		}
		else {
			$categories = DB::Table('categories as C')->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C.id')->select('C.id','C.name','C.catImage')->Where('CR.parentCategoryId','=',$catId)
			->orderBy('C.id', 'ASC')->get();
			
			/* Create 2D Array */
			foreach($categories as $category) {
				$products = DB::Table('products as P')->leftJoin('mas_weightclass', 'mas_weightclass.id', '=', 'P.weightClassId')
				->select('P.id','P.name','P.minOrderQty','P.price','P.splPrice','P.weight','mas_weightclass.name AS weightClass','P.productImage', 
				DB::raw("ROUND((((P.price - P.splPrice)/(P.price + P.splPrice)) * 100),0) AS discount"))
				->where('P.categoryId','=',$category->id)
				->where('P.status', '=', 'Available')
				->orderBy('P.id', 'DESC')->get();
				
				//array_push($results,)
				
				$results[$count]['categoryId'] = $category->id;
				$results[$count]['categoryName'] = $category->name;
				$results[$count]['categoryImage'] = $category->catImage;
				$results[$count]['products'] = $products;
				
				$count++;
			}
		}
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	function address()
	{
		$results = DB::Table('addresses as A')->leftJoin('users', 'users.id', '=', 'A.user_id')
		->select('A.id','A.address','A.name','A.phone','A.city','A.state','A.pinCode','A.defaultAddress')
		->orderBy('A.defaultAddress', 'ASC')
		->orderBy('A.id', 'DESC')->get();
		//echo $data;
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	function getAppVersion()
	{
	    $results = 10709;
	    return $results;
	}
	
	function getDefaultAddress()
	{
		$results = DB::Table('addresses as A')->leftJoin('users', 'users.id', '=', 'A.user_id')
		->select('A.id','A.address','A.name','A.phone','A.city','A.state','A.pinCode','A.defaultAddress')->where('A.defaultAddress','=','Yes')
		->orderBy('A.id', 'DESC')->get();
		//echo $data;
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function banners(){
		$now = date('Y-m-d');

		$results = DB::Table('banners as B')->leftJoin('categories', 'categories.id', '=', 'B.categoryId')
		->select('B.id','B.bannerImage','B.categoryId','categories.name AS categoryName')->where('B.bannerFrom', '<=', $now)->where('B.bannerTo', '>=', $now)
		->orderBy('B.id', 'DESC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function getBanner($id)
	{
		$results = Banner::find($id);
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function products($catId)
	{
		$results = DB::Table('products as P')->leftJoin('mas_weightclass', 'mas_weightclass.id', '=', 'P.weightClassId')
		->select('P.id','P.name','P.minOrderQty','P.price','P.splPrice','P.weight','mas_weightclass.name AS weightClass','P.productImage', 
		DB::raw("ROUND((((P.price - P.splPrice)/(P.price + P.splPrice)) * 100),0) AS discount"))
		->where('P.categoryId','=',$catId)
		->where('P.status', '=', 'Available')
		->orderBy('P.id', 'DESC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function topSavers($catId){
		// This function is for home page only.
		$now = date('Y-m-d');
		//echo $now.'<br>';
		
		if($catId == "false" || $catId == "False" || $catId == "TopSavers" || $catId == "Top Savers") {
			$results = DB::Table('products as P')->leftJoin('mas_weightclass', 'mas_weightclass.id', '=', 'P.weightClassId')
			->leftJoin('categories AS C', 'C.id', '=', 'P.categoryId')
			->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C.id')
			->select('P.id','P.name','P.minOrderQty','P.price','P.splPrice','P.weight','mas_weightclass.name AS weightClass','P.productImage')
			->where('P.splPrice','!=','NULL')
			->where('P.splPriceFrom', '<=', $now)
			->where('P.splPriceTo', '>=', $now)
			->where('P.status', '=', 'Available')
			->orderBy('P.id', 'DESC')->get();
		}
		else {
			$results = DB::Table('products as P')->leftJoin('mas_weightclass', 'mas_weightclass.id', '=', 'P.weightClassId')
			->leftJoin('categories AS C', 'C.id', '=', 'P.categoryId')
			->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C.id')
			->select('P.id','P.name','P.price','P.minOrderQty','P.splPrice','P.weight','mas_weightclass.name AS weightClass','P.productImage')
			->where('CR.parentCategoryId','=',$catId)
			->where('P.splPrice','!=','NULL')
			->where('P.splPriceFrom', '<=', $now)
			->where('P.splPriceTo', '>=', $now)
			->where('P.status', '=', 'Available')
			->orderBy('P.id', 'DESC')->get();
		}
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function addAddress(){
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function faq()
	{
		$results = DB::Table('faqs as F')
		->select('F.id','F.question','F.answer')
		->orderBy('F.id', 'DESC')->get();
		//echo $data;
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function getStore($id)
	{
		$results = DB::Table('stores as S')
		->select('S.id','S.storeName','S.address','S.postalCode','S.city','S.state', 'S.latitude', 'S.longitude')
		->where('S.id', $id)->first();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function stores()
	{
		$results = DB::Table('stores as S')->leftJoin('addresses','addresses.user_id','=','S.userId')
		->select('S.id','S.storeName','addresses.pinCode','addresses.defaultAddress')
		->orderBy('S.id', 'DESC','S.userId')->get();
		//echo $data;
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function status()
	{
	    $results = DB::Table('mas_statustype as MS')
		->select('MS.statusName','MS.id')
		->orderBy('MS.id', 'ASC')->get();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
public function orders()
	{
		$results = DB::Table('orders as O')->leftJoin('users','users.id','=','O.userId')
	
		->leftJoin('mas_statustype AS MS','MS.id','=','O.orderStatus')
		->select(DB::raw('DATE_FORMAT(O.created_at, "%d %M, %Y %h:%i %p") as placed'),'O.id','O.orderId','O.orderDetail','O.totalAmount','O.paymentStatus','O.deliveryDetails','O.driverId','users.firstName','users.lastName','MS.statusName')
		->where('O.driverId', '=', $id)
		->orderBy('O.id', 'DESC')->get();
		//echo $results;
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function driverorders($id)
	{
		$results = DB::Table('orders as O')->leftJoin('users','users.id','=','O.userId')
		->leftJoin('mas_statustype AS MS','MS.id','=','O.orderStatus')
		->select(DB::raw('DATE_FORMAT(O.created_at, "%d %M, %Y %h:%i %p") as placed'),'O.id','O.orderId','O.orderDetail','O.totalAmount','O.paymentStatus','O.deliveryDetails','O.driverId','users.firstName','users.lastName','MS.statusName')
		->where('O.driverId','=',  $id)
		->orderBy('O.id', 'DESC')->get();
		//echo $results;
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function userorders($id)
	{
		$results = DB::Table('orders as O')->leftJoin('users','users.id','=','O.userId')
		->leftJoin('mas_statustype AS MS','MS.id','=','O.orderStatus')
		->select(DB::raw('DATE_FORMAT(O.created_at, "%d %M, %Y %h:%i %p") as placed'),'O.id','O.orderId','O.orderDetail','O.totalAmount','O.paymentStatus','O.deliveryDetails','O.driverId','users.firstName','users.lastName','MS.statusName')
		->where('O.userId','=',  $id)
		->orderBy('O.id', 'DESC')->get();
		//echo $results;
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function getOrder($id)
	{
		
		$results =DB::Table('orders as O')->leftJoin('users','users.id','=','O.userId')
		->leftJoin('mas_statustype AS MS','MS.id','=','O.orderStatus')
		->select(DB::raw('DATE_FORMAT(O.created_at, "%d %M, %Y %h:%i %p") as placed'),'O.id','O.orderId','O.orderDetail','O.orderStatus','O.driverId','O.totalAmount','O.paymentStatus','O.deliveryDetails','users.firstName','users.lastName','MS.statusName')
		->where('O.id', $id)->first();
		
		$orderStatusId = $results->orderStatus;

		$status = DB::Table('mas_statustype as MS')->select('MS.id','MS.statusName')->where('MS.id', '<=', $orderStatusId)->orderBy('MS.id', 'ASC')->get();
		//print_r($status);
		//die;
		//$results = Orders::find($id)->first();
		
		return response()->json(compact('results','status'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function placeOrder(Request $request)
	{
		header("Access-Control-Allow-Origin: *");
		 // ALLOW OPTIONS METHOD
        $headers = [
            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'=> 'Content-Type, X-Auth-Token, Origin'
        ];
        if($request->getMethod() == "OPTIONS") {
            // The client-side application can set only headers allowed in Access-Control-Allow-Headers
            return Response::make('OK', 200, $headers);
        }
        
    
        $response = $next($request);
        foreach($headers as $key => $value)
            $response->header($key, $value);
        return $response;
		//$data_row 		= 	file_get_contents("php://input");
		//$decoded 	    = 	json_decode($data_row, true);
		//$banner = new OrdersData;
		
		//$banner->id = "1";
		//$banner->orderdata = "Test";
		//$banner->save();
		//echo "Record inserted successfully.<br/>";
		
		$results = ["status"=>"success"];
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function brands()
	{
		$results = DB::Table('brands as B')
		->select('B.id','B.brandName','B.brandImage','B.brandFeaturedImage')
		->where('B.status','=','Available')
		->orderBy('B.id', 'DESC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function getBrandsProduct($id)
	{
		$results = DB::Table('products AS P')->select('P.id','P.minOrderQty','P.name','P.price','P.splPrice','P.weight','P.productImage','P.brandId')->where('P.brandId','=',$id)->get();
		
		
		$brandFeatured = DB::Table('brands as B')
		->select('B.id','B.brandName','B.brandImage','B.brandFeaturedImage')
		->where('B.id','=',$id)->get();
		
		return response()->json(compact('results', 'brandFeatured'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function cors(){
// Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
}
	public function ajax_login(Request $request){
		//$this->cors();
		//echo "I am called...";
		$email = $request->input('email');
		//$email = "admin@tijarah.com";
		$password = $request->input('password');
		//$password = "admin#2021";
		$user = DB::Table('users')
		->select('id','firstName','lastName','email','contactNumber','password')
		->where('email',$email)->first();
		//$user = User::where('email', '=', $email)->first();
		//return response()->json(['success'=>true,'message'=>'success', 'data' => $user]);
		
		if (!$user) {
		return response()->json(['success'=>false, 'message' => 'Login Fail, please check email id'])->header("Access-Control-Allow-Origin",  "*");
		}
		if (!Hash::check($password, $user->password)) {
		return response()->json(['success'=>false, 'message' => 'Login Fail, pls check password'])->header("Access-Control-Allow-Origin",  "*");
		}
		//unset($user["password"]);
		$user = (array) $user;
		unset($user["password"]);
		return response()->json(['success'=>true,'message'=>'success', 'data' => $user])->header("Access-Control-Allow-Origin",  "*");
		/*
		{"success":true,"message":"success","data":{"id":1,"firstName":"Tijarah","lastName":"Admin","email":"admin@tijarah.com","contactNumber":"","email_verified_at":null,"password":"$2y$12$0QU0UDT353chhkf1cr.61eWavhwWEHL6k3V4G1KiOI.1vMFKb3Fzu","remember_token":"qmBJ9CwxJy2ZbHKczheHa3r06ZOhvokP42FaVHBihRvVyYqPA1AsiAiT3TBz","roleId":1,"created_at":"2021-03-09 07:56:49","updated_at":"2021-03-09 07:56:49"}}
		*/
		//echo "Arvind";
		
	}
	function user_token(Request $request){
		$contactNumber = $request->input('contactNumber');
		$device_type = $request->input('device_type');
		$device_token = $request->input('device_token');
		
		$user = DB::Table('users')
		->select('id','firstName','lastName','email','contactNumber','password')
		->where('contactNumber',$contactNumber)->first();
		if($user){
			// update user token//
			DB::table('users')
          ->where('contactNumber', $contactNumber)
          ->update(['device_type' => $device_type,
		  'device_token' => $device_token
		  ]);
		  return response()->json(['success'=>true,'message'=>'success'])->header("Access-Control-Allow-Origin",  "*");
		}else{
			return response()->json(['success'=>true,'message'=>'User not found.'])->header("Access-Control-Allow-Origin",  "*");
		}
		/*
		/retailb2b/api/user_token
		{
		  "contactNumber": "9414790019",
		  "device_type": "android",
		  "device_token": "some device token"
		}
		*/
	}
	
	
	public function storeSelect()
	{
		$results = DB::Table('stores as S')
		->select('S.id','S.storeName','S.address')
		->orderBy('S.id', 'DESC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function deliverySlot()
	{
		$results = DB::Table('deliveryslot as D')
		->select(DB::raw('DATE_FORMAT(D.startingTime, "%h:%i %p") as startingTime'),DB::raw('DATE_FORMAT(D.endingTime, "%h:%i %p") as endingTime'), 'D.id','D.slotName')
		->orderBy('D.startingTime', 'ASC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	/* POS App Functions */
	
	public function appUpdate($type)
	{
	    $json = file_get_contents('https://majestictechnosoft.com/posadmin/storage/app/data_mini.json');
        // Decode the JSON file
        
        //print_r($json);
        $json_data = json_decode($json,true);
        // Display data
        //print_r($json_data[0]['app_ver']);
        $results = $json_data[0]['app_ver'];
        return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	
	
	
	public function getSubscriptionDate($id)
	{
	    $results = DB::Table('stores')
		->select('stores.id','stores.subscriptionExpiry')
		->where('stores.id',$id)
		->first();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	
	public function users($id)
	{
	    $results = DB::Table('customers as U')
		->select('U.id','U.customerName','U.email','U.contactNumber')
		->where('U.storeName',$id)
		->orderBy('U.id', 'ASC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function orderspos($storeid)
	{
	    /*
	    $results = DB::Table('orders_pos as O')
	    ->leftJoin('customers','customers.id','O.customerId')
		->select('customers.customerName','O.id','O.orderStatus','O.orderId','O.totalAmount',DB::raw('DATE_FORMAT(O.created_at, "%h:%i %p %m/%d/%Y") as orderDateTime'))
		->where('O.storeId',$id)
		->orderBy('O.id', 'DESC')->paginate(10);
		*/
		
		$results = DB::Table('orders_pos as O')
		->select('O.id','O.orderStatus','O.orderId','O.totalAmount','O.created_at as orderDateTime')
		->where('O.storeId',$storeid)
		->orderBy('O.id', 'DESC')->paginate(10);
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function orderspossearch($storeid,$searchtext)
	{
	    /*
	    $results = DB::Table('orders_pos as O')
	    ->leftJoin('customers','customers.id','O.customerId')
		->select('customers.customerName','O.id','O.orderStatus','O.orderId','O.totalAmount',DB::raw('DATE_FORMAT(O.created_at, "%h:%i %p %m/%d/%Y") as orderDateTime'))
		->where('O.storeId',$id)
		->orderBy('O.id', 'DESC')->paginate(10);
		*/
		
		//echo $searchtext;
		
		//die;
		
		
		$results = DB::Table('orders_pos as O')
		->select('O.id','O.orderStatus','O.orderId','O.totalAmount','O.created_at as orderDateTime')
		->where('O.storeId',$storeid)
		->where('O.orderId', 'LIKE', '%'.$searchtext.'%')
		->orderBy('O.id', 'DESC')->paginate(10);
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function categoriespos()
	{
	    /*
		$results = DB::Table('categories as C1')->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C1.id')->leftJoin('categories AS C2', 'C2.id', '=', 'CR.parentCategoryId')->select('C1.id','C1.name','C1.catImage')->orWhereNull('C2.name')
		->orderBy('C1.id', 'ASC')->get();
		*/
		
		$results = DB::Table('categories as C')->select('C.id','C.name','C.catImage','C.catImgBase64')->where('C.typeadmin','=',"pos")->orderBy('C.id', 'ASC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function categoriesName()
	{
		$results = DB::Table('categories as C1')->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C1.id')
		->leftJoin('categories AS C2', 'C2.id', '=', 'CR.parentCategoryId')
		->select('C1.id','C1.name')->orWhereNull('C2.name')
		->where('C1.typeadmin','=',"pos")
		->orderBy('C1.name', 'ASC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function categoriesNamePos($storeId)
	{
	    $results = DB::Table('categories as C')
		->leftJoin('stores as S','S.storeType','=','C.storeType')
		->select('C.id','C.name')
		->where('C.typeadmin','=',"pos")
		->where('S.id','=', $storeId)
		->orderBy('C.name', 'ASC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function categoriesStorepos($storeId)
	{
		
		$results = DB::Table('categories as C1')->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C1.id')
		->leftJoin('categories AS C2', 'C2.id', '=', 'CR.parentCategoryId')
		->leftJoin('stores as S','S.storeType','=','C1.storeType')
		->select('C1.id','C1.name','C1.catImage','C1.catImgBase64')->orWhereNull('C2.name')
		->where('C1.typeadmin','=',"pos")
		->where('S.id','=', $storeId)
		->orderBy('C1.name', 'ASC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function getSubCategoriespos($catId,$id)
	{
		// If $id has value it will fetch sub categories else if $id has "False" or "TopSavers" It will fetch all root categories
		$results = array();
		$count = 0;
		
		$categories = DB::Table('categories as C')->select('C.id','C.name','C.catImage')->Where('C.id','=',$catId)
		
		->orderBy('C.id', 'ASC')->get();
		
		/* Create 2D Array */
		foreach($categories as $category) {
			$products = DB::Table('products as P')->leftJoin('mas_weightclass', 'mas_weightclass.id', '=', 'P.weightClassId')
			->leftJoin('mas_taxclass', 'mas_taxclass.id', '=', 'P.taxClassId')
			->select('P.description','P.id','P.name','P.minOrderQty','P.price','P.splPrice','P.weight','P.inventory','P.productImgBase64','mas_weightclass.name AS weightClass','P.productImage', 'mas_taxclass.value AS tax', 
			DB::raw("ROUND((((P.price - P.splPrice)/(P.price + P.splPrice)) * 100),0) AS discount"))
			->where('P.categoryId','=',$catId)
			->where('P.status', '=', 'Available')
			->where('P.storeId','=',$id)
			
			->orderBy('P.id', 'DESC')->get();
			
			//array_push($results,)
			
			$results[$count]['categoryId'] = $category->id;
			$results[$count]['categoryName'] = $category->name;
			$results[$count]['categoryImage'] = $category->catImage;
			$results[$count]['products'] = $products;
			
			$count++;
		}
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function inventorylist($storeid)
	{
	    $catId = $_GET['catId'];
	    $searchtext = $_GET['searchtext'];
		
	    
	    $inventorytype = $_GET['inventorytype'];
	    
	    //echo "CatId:: " . $catId . " Searchtext:: " . $searchtext . "<br>";
	    //die;
	    
	    /*$categories = DB::Table('categories as C1')->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C1.id')
		->leftJoin('categories AS C2', 'C2.id', '=', 'CR.parentCategoryId')
		->select('C1.id','C1.name')->orWhereNull('C2.name')
		->where('C1.typeadmin','=',"pos")
		->orderBy('C1.name', 'ASC')->get();*/
		
		$categories = DB::Table('categories as C1')->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C1.id')
		->leftJoin('categories AS C2', 'C2.id', '=', 'CR.parentCategoryId')
		->leftJoin('stores as S','S.storeType','=','C1.storeType')
		->select('C1.id','C1.name','C1.catImgBase64')->orWhereNull('C2.name')
		->where('C1.typeadmin','=',"pos")
		->where('S.id','=', $storeid)
		->orderBy('C1.name', 'ASC')->get();
		
	    $products = DB::Table('products as P')
		->select('P.id','P.name','PAR.name as name_ar','P.sellingPrice','P.barCode','P.categoryId','P.price','P.productImage','P.productImgBase64','P.inventory','P.minInventory','MTC.value as tax','P.storeId','P.status')
		->leftJoin('products_ar as PAR','PAR.productId','=','P.id')
		->leftJoin('mas_taxclass as MTC','MTC.id','=','P.taxClassId')
		->where('P.storeId','=',  $storeid)
		->orderBy('P.updated_at', 'DESC');
		
	    if(!empty($catId) && $catId != 0) {
    	    $products = $products->where('P.categoryId','=',  $catId);
	    }
	    
	    if(!empty($searchtext)) {
	        /*
	        $products = $products->where('P.name', 'LIKE', '%'.$searchtext.'%')
    		->orwhere('PAR.name', 'LIKE', '%'.$searchtext.'%')
    		->orwhere('P.barCode', 'LIKE', $searchtext.'%');
    		*/
    		
    		 $products = $products->where(function($query) use ($searchtext) {
                            $query->orwhere('P.name', 'LIKE', $searchtext.'%')
                        		->orwhere('PAR.name', 'LIKE', $searchtext.'%')
                        		->orwhere('P.name', 'LIKE', '% '.$searchtext.'%')
                        		->orwhere('PAR.name', 'LIKE', '% '.$searchtext.'%')
                        		->orwhere('P.barCode', 'LIKE', $searchtext);
                        });
	    }
	    
	    if($inventorytype == 'outofstock') {
	        $products = $products->where('P.inventory','<=',  0);
	    }
	    
	    if($inventorytype == 'available') {
	        $products = $products->where('P.inventory','>',  'P.minInventory');
	    }
	    
	    if($inventorytype == 'lowinventory') {
	        $products = $products->where('P.inventory','>',  0)->where('P.inventory','<=',  'P.minInventory');
	    }
		
		$products = $products->orderBy('P.id', 'DESC')->paginate(10);

		//print_r($products);
		//die;
		
		$results['categories'] = $categories;
		$results['products'] = $products;
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function productslist($storeid)
	{
	    
		$results = DB::Table('products as P')
		->select('P.id','P.name','PAR.name as name_ar','P.barCode','P.categoryId','P.price','P.productImage','P.productImgBase64','P.inventory','P.minInventory','MTC.value as tax')
		->leftJoin('products_ar as PAR','PAR.productId','=','P.id')
		->leftJoin('mas_taxclass as MTC','MTC.id','=','P.taxClassId')
		->where('P.storeId','=',  $storeid)
		->orderBy('P.id', 'DESC')->paginate(10);
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function productslistsearch($storeid,$searchtext)
	{
		$results = DB::Table('products as P')
		->select('P.id','P.name','PAR.name as name_ar','P.barCode','P.categoryId','P.price','P.productImage','P.productImgBase64','P.inventory','P.minInventory','MTC.value as tax')
		->leftJoin('products_ar as PAR','PAR.productId','=','P.id')
		->leftJoin('mas_taxclass as MTC','MTC.id','=','P.taxClassId')
		->where('P.name', 'LIKE', '%'.$searchtext.'%')
		->orwhere('PAR.name', 'LIKE', '%'.$searchtext.'%')
		->orwhere('P.barCode', 'LIKE', $searchtext.'%')
		->where('P.storeId','=',  $storeid)
		->orderBy('P.id', 'DESC')->paginate(10);
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function productpossearch($id)
	{
	    /*
		$results = DB::Table('products as P')->leftJoin('mas_weightclass', 'mas_weightclass.id', '=', 'P.weightClassId')
		->select('P.id','P.name','P.categoryId','P.minOrderQty','P.inventory','P.minInventory','P.price','P.splPrice','P.weight','mas_weightclass.name AS weightClass','P.productImage', 
		DB::raw("ROUND((((P.price - P.splPrice)/(P.price + P.splPrice)) * 100),0) AS discount"))
		->where('P.status', '=', 'Available')
		->where('P.storeId','=',  $id)
		->orderBy('P.id', 'DESC')->get();
		*/
		
		$results = DB::Table('products as P')
		->select('P.id','P.name','PAR.name as name_ar','P.barCode','P.categoryId','P.price','P.sellingPrice','P.productImage','P.productImgBase64','P.inventory','P.minInventory','MTC.value as tax')
		->leftJoin('products_ar as PAR','PAR.productId','=','P.id')
		->leftJoin('mas_taxclass as MTC','MTC.id','=','P.taxClassId')
		->where('P.storeId','=',  $id)
		->orderBy('P.id', 'DESC')->get();
	
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function country()
	{
		$results = DB::Table('mas_country as C')
		->select('C.id','C.phonecode')
		->orderBy('C.id', 'ASC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function customerpossearch($id)
	{
		$results =DB::Table('customers as C')
		->select('C.id','C.customerName','C.contactNumber','C.email')
		->where('C.storeName','=',  $id)
		->orderBy('C.id', 'DESC')->get();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function customerposdetail($id)
	{
		$results =DB::Table('customers as C')
		->select('C.id','C.customerName','C.contactNumber')
		->where('C.id','=',  $id)
		->orderBy('C.id', 'DESC')->first();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function posproduct($id)
	{
	    
	    $results =  DB::Table('products as P')->leftJoin('mas_weightclass', 'mas_weightclass.id', '=', 'P.weightClassId')
	    ->leftJoin('products_ar as PAR','PAR.productId','=','P.id')
	    ->leftJoin('stores','stores.id','=','P.storeId')
		->select('P.id','P.name','PAR.name as name_ar','P.minOrderQty','P.price','P.splPrice','P.inventory','P.minInventory','P.weight','mas_weightclass.name AS weightClass','P.productImage', 
		DB::raw("ROUND((((P.price - P.splPrice)/(P.price + P.splPrice)) * 100),0) AS discount"))
		->where('P.status', '=', 'Available')
		->where('P.storeId','=',  $id)
		->orderBy('P.id', 'DESC')->get();
	    return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	    
	}
	
	public function addproduct(Request $request){
	    $productImage = "";
	    $productImgBase64 = "";
	    
	    $results = DB::Table('products as P')
	    ->where('P.storeId',$request->storeId)
		->where( 'P.name',$request->name_en);
		
		if(!empty($request->barCode))
	        $results = $results->where( 'P.barCode',$request->barCode);
		
		$results = $results->first();
		
		$response = [
	        'name_en'=>$request->name_en,
	        'name_ar'=>$request->name_ar,
	        'code'=>$request->barCode,
	        'boxBarCode'=>$request->boxBarCode,
	        'piecesPerBox'=>$request->piecesPerBox,
	        'price'=>$request->price,
	        'vatId'=>$request->vatId,
	        'brandId'=>$request->brandId,
	        'availablity'=>$request->availablity,
	        //'inventory'=>$request->inventory,
	        'inventory'=>0,
	        'minInventory'=>$request->minInventory,
	        'categoryId'=>$request->categoryId,
	        'storeId'=>$request->storeId,
	        'costPrice'=>$request->costPrice,
	        'sellingPrice'=>$request->sellingPrice,
	        'file'=>$productImage
	        ];
		
		/*
		$results = DB::table('products')->where([
            ['name', '=', $request->name_en],
            ['barCode', '=', $request->barCode],
        ])->get();
		*/
		
		if(!empty($results)) {
    	    $status = 'duplicate';
    	    return response()->json(compact('status','results','response'))->header("Access-Control-Allow-Origin",  "*");
		}
	    
	    
	    //print($results);
	    
	    //die;
	    
	    
	    if($_FILES && isset($_FILES['file'])) {
	        $destinationPath = "/home/majtechnosoft/public_html/posadmin/public/products/";
	        $imgPre = time();
 
            $productImage = $imgPre.basename( $_FILES['file']['name']);
            
            //$target_path = $destinationPath . $imgPre.basename( $_FILES['file']['name']);

            $main_img = Image::make($_FILES['file']['tmp_name'])->fit(200, 200);
            $main_img->save($destinationPath.$productImage,100);
            
            $thumb_img = Image::make($_FILES['file']['tmp_name'])->fit(50, 50);
            $thumb_img = Image::make($_FILES['file']['tmp_name']);
			$thumb_img->save($destinationPath.'50x50/'.$productImage,100);
			
			$data = file_get_contents($destinationPath.'50x50/'.$productImage);
			$productImgBase64 = base64_encode($data);
			
			//$thumb_img->move($destinationPath.'125x125/', $filename);
            //move_uploaded_file($_FILES['file']['tmp_name'], $target_path);
            
            /*
            if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
                //header('Content-type: application/json');
                //$data = ['success' => true, 'message' => 'Upload and move success'];
                //echo json_encode( $data );
                
                $productImage = $filename;
            } else{
                //header('Content-type: application/json');
                //$data = ['success' => false, 'message' => 'There was an error uploading the file, please try again!'];
               // echo json_encode( $data );
            }
            */
	    }
	    
	    
	    //return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	    
	    $response = [
	        'name_en'=>$request->name_en,
	        'name_ar'=>$request->name_ar,
	        'code'=>$request->barCode,
	        'boxBarCode'=>$request->boxBarCode,
	        'piecesPerBox'=>$request->piecesPerBox,
	        'price'=>$request->price,
	        'vatId'=>$request->vatId,
	        'brandId'=>$request->brandId,
	        'availablity'=>$request->availablity,
	        //'inventory'=>$request->inventory,
	        'inventory'=>0,
	        'minInventory'=>$request->minInventory,
	        'categoryId'=>$request->categoryId,
	        'storeId'=>$request->storeId,
	        'costPrice'=>$request->costPrice,
	        'sellingPrice'=>$request->sellingPrice,
	        'file'=>$productImage
	        ];
	        
	   
	    $product = new Product;
        $product_ar = new Product_AR;
	   
        $product->name = $request->name_en;
		
        $product->barCode = $request->barCode;
        if($request->boxBarCode != 'undefined' && $request->boxBarCode != 'null')
			$product->boxBarCode = $request->boxBarCode;
		if($request->piecesPerBox != 'undefined' && $request->piecesPerBox != 'null')
			$product->piecesPerBox = $request->piecesPerBox;
        $product->price = $request->price;
        $product->taxClassId = $request->vatId;
        $product->brandId = $request->brandId;
        $product->status = $request->availablity;
       // $product->inventory = $request->inventory;
        $product->inventory = 0;
        if($request->minInventory != 'undefined' && $request->minInventory != 'null')
            $product->minInventory = $request->minInventory;
        $product->categoryId = $request->categoryId;
        $product->storeId = $request->storeId;
        $product->productImage = $productImage;
        $product->productImgBase64 = $productImgBase64;
        $product->costPrice = $request->costPrice;
        $product->sellingPrice = $request->sellingPrice;
        
        if(isset($request->loose))
            $product->looseItem = $request->loose;
        
        if(isset($request->expirydate))
            $product->expiryDate = $request->expirydate;
            
        $product->save();
        
        $productId = $product->id;
        $product_ar->productId = $productId;
        
        
	    if($request->name_ar != 'undefined')
	        $product_ar->name = $request->name_ar;
		$product_ar->save();
        
        $status = 'success';
        return response()->json(compact('status','productId'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function editproduct($id)
	{
		
		$results = DB::Table('products as P')
		->leftJoin('mas_taxclass', 'mas_taxclass.id', '=', 'P.taxClassId')
		->leftJoin('productinventorybatch as PIB', 'PIB.productId', '=', 'P.id')
		//->leftJion('productinventorybatch as PIB', 'PIB.productId', '=','P.id')
		->select('P.id','P.name', 'P_AR.name as name_ar','mas_taxclass.value as taxClassValue','P.barCode','P.boxBarCode','P.piecesPerBox','P.brandId','P.sellingPrice','P.status as availableStatus','P.categoryId','P.taxClassId','P.price','P.inventory','P.minInventory','P.productImgBase64', 'P.productImage','P.costPrice','P.looseItem','P.expiryDate', DB::raw('SUM(PIB.costPrice * PIB.inventory) AS stockValue'))
		->leftJoin('products_ar as P_AR','P_AR.productId','=','P.id')
		->where( 'P.id',$id)
		->first();
		
		/*
		$results = DB::Table('products as P')
		->leftJoin('mas_taxclass', 'mas_taxclass.id', '=', 'P.taxClassId')
		->select('P.id','P.name', 'P_AR.name as name_ar','mas_taxclass.value as taxClassValue','P.barCode','P.brandId','P.sellingPrice','P.status as availableStatus','P.categoryId','P.taxClassId','P.price','P.inventory','P.minInventory','P.productImgBase64', 'P.productImage','P.costPrice')
		->leftJoin('products_ar as P_AR','P_AR.productId','=','P.id')
		->where( 'P.id',$id)
		->first();*/
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function updateproduct(Request $request)
	{
	    $product = new Product;
	    $productImage = "";
	    $productImgBase64 = "";
	    $product = Product::find($request->id);
		
        $results = DB::Table('products as P')
	    ->where('P.storeId',$request->storeId)
		->where( 'P.name',$request->name_en)
		->where('P.id','!=', $request->id);
		
		if(!empty($request->barCode))
	        $results = $results->where( 'P.barCode',$request->barCode);
		
		$results = $results->first();
		
		$response = [
		    'id'=>$request->id,
	        'name_en'=>$request->name_en,
	        'name_ar'=>$request->name_ar,
	        'barCode'=>$request->barCode,
	        'price'=>$request->price,
	        'vatId'=>$request->vatId,
	        'brandId'=>$request->brandId,
	        'availablity'=>$request->availablity,
	        'inventory'=>$request->inventory,
	        'minInventory'=>$request->minInventory,
	        'categoryId'=>$request->categoryId,
	        'storeId'=>$request->storeId,
	        'costPrice'=>$request->costPrice,
	        'sellingPrice'=>$request->sellingPrice,
	        'file'=>$productImage
	        ];
	        
	        
		//return response()->json(compact('results','response'))->header("Access-Control-Allow-Origin",  "*");
		

		
	        
		if(!empty($results)) {
    	    $status = 'duplicate';
    	    return response()->json(compact('status','results','response'))->header("Access-Control-Allow-Origin",  "*");
		}
        
       
        if(!empty($request->name_en))
            $product->name = $request->name_en;
            
        if(!empty($request->barCode))
            $product->barCode = $request->barCode;
		
		if(!empty($request->boxBarCode))
            $product->boxBarCode = $request->boxBarCode;
		
		if(!empty($request->piecesPerBox))
            $product->piecesPerBox = $request->piecesPerBox;
        
        if(!empty($request->vatId))
            $product->taxClassId = $request->vatId;
            
        if(!empty($request->brandId))
            $product->brandId = $request->brandId;
            
        if(!empty($request->availablity))
            $product->status = $request->availablity;
        
        if(!empty($request->looseItem))
           $product->looseItem = $request->looseItem;

        if(!empty($request->inventory)) {	
			if($product->inventory != $request->inventory){		
				$productLog = new ProductLogs;
		
				$productLog->userId = '';
				$productLog->productId = $request->id;
				$productLog->previousStock = $product->inventory;
				$productLog->newStock = $request->inventory;
				$productLog->save(); 
			}
			
			$product->inventory = $request->inventory;
		}
		
        if(!empty($request->minInventory))
            $product->minInventory = $request->minInventory;

        if(!empty($request->categoryId))
            $product->categoryId = $request->categoryId;
        
        if(!empty($request->costPrice))
            $product->costPrice = $request->costPrice;
            
        if(!empty($request->price) && $request->price != 'NaN')
            $product->price = $request->price;
            
        if(!empty($request->sellingPrice))
            $product->sellingPrice = $request->sellingPrice;
            
        //if(isset($request->expirydate))
        $product->expiryDate = $request->expiryDate;
        
       if($_FILES && isset($_FILES['file'])) {
	        $destinationPath = "/home/majtechnosoft/public_html/posadmin/public/products/";
	        $imgPre = time();
 
            $productImage = $imgPre.basename( $_FILES['file']['name']);
            
            $main_img = Image::make($_FILES['file']['tmp_name'])->fit(100, 100);
            $main_img->save($destinationPath.$productImage,100);
            
            $thumb_img = Image::make($_FILES['file']['tmp_name'])->fit(100, 100);
			$thumb_img->save($destinationPath.'50x50/'.$productImage,100);
			
			$data = file_get_contents($destinationPath.'50x50/'.$productImage);
			$productImgBase64 = base64_encode($data);
			
			$product->productImage = $productImage;
            $product->productImgBase64 = $productImgBase64;
	    }
	    
        $product->save();
        
        $product_ar = Product_AR::select('id')->where('productID', $request->input('id'))->first();
		if($product_ar == null)
		    $product_ar = new Product_AR;
		
	    $product_ar->productId = $product->id;
	    if($request->name_ar != 'undefined')
	        $product_ar->name = $request->name_ar;
	    $product_ar->save();
		
		$response = [
	        'name_en'=>$request->name_en,
	        'name_ar'=>$request->name_ar,
	        'code'=>$request->barCode,
	        'boxBarCode'=>$request->boxBarCode,
	        'piecesPerBox'=>$request->piecesPerBox,
	        'price'=>$request->price,
	        'vatId'=>$request->vatId,
	        'availablity'=>$request->availablity,
	        'brandId'=>$request->brandId,
	        'inventory'=>$request->inventory,
	        'minInventory'=>$request->minInventory,
	        'categoryId'=>$request->categoryId,
	        'storeId'=>$request->storeId,
	        'costPrice'=>$request->costPrice,
	        'sellingPrice'=>$request->sellingPrice,
	        //'file'=>$_FILES['file'],
	        'id'=>$request->id,
	        'product'=>$product
	        ];
		
		return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	}

	public function addcustomer(Request $request){
	    
	    $results = DB::Table('customers as C')
	    ->where('C.storeName',$request->storeName)
		->where( 'C.contactNumber',$request->contactNumber);
		
		$results = $results->first();
	    
	    
	    $response = [
	        'customerName'=>$request->customerName,
	        'email'=>$request->email,
			'customerVat'=>$request->customerVat,
	        'storeName'=>$request->storeName,
	        'contactNumber'=>$request->contactNumber,
	        'dob'=>$request->dob,
	        'doa'=>$request->doa,
	        'address'=>$request->address
	        
	        ];
	   if(!empty($results)) {
    	    $status = 'duplicate';
    	    return response()->json(compact('status','results','response'))->header("Access-Control-Allow-Origin",  "*");
		}
		
		 $response = [
	        'customerName'=>$request->customerName,
	        'email'=>$request->email,
			'customerVat'=>$request->customerVat,
	        'storeName'=>$request->storeName,
	        'contactNumber'=>$request->contactNumber,
	        'dob'=>$request->dob,
	        'doa'=>$request->doa,
	        'address'=>$request->address
	        
	        ];
		
	   $customer = new Customer;
	   
	   
        if(!empty($request->customerName))
            $customer->customerName = $request->customerName;
            
        if(!empty($request->email))
            $customer->email = $request->email;

		if(!empty($request->customerVat))
            $customer->customerVat = $request->customerVat;

        if(!empty($request->contactNumber))
            $customer->contactNumber = $request->contactNumber;

        if(!empty($request->dob))
            $customer->dob = $request->dob;

        if(!empty($request->address))
            $customer->address = $request->address;

        if(!empty($request->storeName))
            $customer->storeName = $request->storeName;
        
        $customer->save();
	   
	   
	    return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function editcustomer($id)
	{
		$results = DB::Table('customers as C')
		->select('C.id','C.customerName', 'C.email','C.customerVat','C.contactNumber','C.address','C.dob')
		->where( 'C.id',$id)
		->first();
	    
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function updatecustomer(Request $request)
	{
	    
	    $customer = new Customer;
	    $customer = Customer::find($request->id);
	    
	    $results = DB::Table('customers as C')
	    ->where('C.storeName',$customer->storeName)
		->where( 'C.contactNumber',$request->contactNumber)
		->where('C.id','!=', $request->id);
	    
		$results = $results->first();
	    
	    
	    $response = [
	        'customerName'=>$request->customerName,
	        'email'=>$request->email,
			'customerVat'=>$request->customerVat,
	        'storeName'=>$request->storeName,
	        'contactNumber'=>$request->contactNumber,
	        'dob'=>$request->dob,
	        'doa'=>$request->doa,
	        'address'=>$request->address
	        
	        ];
	   if(!empty($results)) {
    	    $status = 'duplicate';
    	    return response()->json(compact('status','results','response'))->header("Access-Control-Allow-Origin",  "*");
		}

	    if(!empty($request->customerName))
            $customer->customerName = $request->customerName;

        if(!empty($request->contactNumber))
            $customer->contactNumber = $request->contactNumber;

		if(!empty($request->email))
            $customer->email = $request->email; 

		if(!empty($request->customerVat))
            $customer->customerVat = $request->customerVat;

        if(!empty($request->dob))
            $customer->dob = $request->dob;

        if(!empty($request->address))
            $customer->address = $request->address;

        if(!empty($request->storeName))
            $customer->storeName = $request->storeName;
        
       /*  $customer->dob = $request->dob;
        $customer->email = $request->email;
        $customer->address = $request->address;
	   */
	   $customer->save();
	   $response = [
	        'customerName'=>$request->customerName,
	        'email'=>$request->email,
			'customerVat'=>$request->customerVat,
	        'storeName'=>$request->storeName,
	        'contactNumber'=>$request->contactNumber,
	        'dob'=>$request->dob,
	        'doa'=>$request->doa,
	        'address'=>$request->address
	        
	        ];
	  return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function customerCredit($id) {
		$results = DB::table('customers_credit')
                //select('orderNumber, balance, type, created_at')
				 ->select('orderNumber','orderId', 'balance', 'type', 'created_at')
                ->where('customerId',$id)
                ->orderBy('created_at', 'DESC')
                ->get();
				
		return response()->json(compact('results','id'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	
	public function getOrderpos($id)
	{
		
		$results =DB::Table('orders_pos as O')->leftJoin('users','users.id','=','O.userId')
		->leftJoin('customers','customers.id','=','O.customerId')
		->leftJoin('mas_statustype AS MS','MS.id','=','O.orderStatus')
		->select(DB::raw('DATE_FORMAT(O.created_at, "%d %M, %Y %h:%i %p") as placed'),'O.id','O.orderId','O.orderDetail','O.refundDetail','O.orderStatus','O.totalAmount','O.paymentStatus','MS.statusName','customers.customerName','customers.contactNumber')
		->where('O.id', $id)->first();
		
		$orderStatusId = $results->orderStatus;

		$status = DB::Table('mas_statustype as MS')->select('MS.id','MS.statusName')->where('MS.id', '<=', $orderStatusId)->orderBy('MS.id', 'ASC')->get();
		//print_r($status);
		//die;
		//$results = Orders::find($id)->first();
		
		return response()->json(compact('results','status'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function homedatagraph($storeId)
	{
        $totalProducts = DB::table('products')
                ->select('id')
                ->where('storeId',$storeId)
                ->count();
                
        $outOfStock = DB::table('products')
                ->select('id')
                ->where('inventory','<=','0')
                ->where('storeId',$storeId)
                ->count();
                
        $available = DB::table('products')
                ->select('id')
                ->where('inventory','>','0')
                ->where('storeId',$storeId)
                ->count();
        
        $lowInventory = DB::table('products')
                ->select('id')
                ->where('inventory','<=','20')
                ->where('inventory','>',0)
                ->where('storeId',$storeId)
                ->count();
        //print_r($lowInventory);
        //die;
        
        $graphdata['inventory']['totalProducts'] = $totalProducts;
		$graphdata['inventory']['outOfStock'] = $outOfStock;
		$graphdata['inventory']['available'] = $available;
		$graphdata['inventory']['lowInventory'] = $lowInventory;
		
		//$graphdata['revenue']['labels'] = ["Mon", "Tue", "Wed", "Thur", "Fri", "Sat", "Sun"]; 
	    //$graphdata['revenue']['data'] = [87,27,45,65,74,20,49];
	    
	    //$graphdata['avgBasket']['labels'] = ["Mon", "Tue", "Wed", "Thur", "Fri", "Sat", "Sun"]; 
		//$graphdata['avgBasket']['data'] = [82,50,78,70,47,20,49];
		
		//$graphdata['bills']['labels'] = ["Mon", "Tue", "Wed", "Thur", "Fri", "Sat", "Sun"]; 
		//$graphdata['bills']['data'] = [45,27,45,87,74,55,49];
	    
	    $tillDate = Carbon::now()->subDays(9)->toDateString();
	    
        $revenueData = DB::table('orders_pos')
                ->select(DB::raw('SUM(totalAmount - refundTotalAmount) as totalAmount'),DB::raw('SUM(totalAmount - refundTotalAmount)/COUNT(totalAmount) as averageAmount'),DB::raw('COUNT(totalAmount) as billCount'),DB::raw('Date(created_at) as date'))
                ->where('storeId',$storeId)
                ->where(DB::raw('Date(created_at)'),'>=',$tillDate)
                ->groupBy(DB::raw('Date(created_at)'))
                ->orderBy(DB::raw('Date(created_at)'),'DESC')
                ->get();
				
		$totalorderBill = DB::table('orders_pos')
                ->select(DB::raw('COUNT(totalAmount) as billCount'))
                ->where('storeId',$storeId)
                ->get();
				
		$totalbilling0 = DB::table('orders_pos')
                ->select(DB::raw('SUM(totalAmount - refundTotalAmount) as totalAmount'))
                ->where('storeId',$storeId)
                ->get();		
				
		$topSellingData = DB::table('reports')
                ->select('productName', 'productNameAr','price', DB::raw('SUM(quantity) as totalQty'))
                ->where('storeId',$storeId)
                ->where(DB::raw('Date(created_at)'),'>=',$tillDate)
                ->groupBy('productName')
                ->orderBy('totalQty','DESC')
				->limit(5)
                ->get();
        
        $count = 0;
        $graphRevenue = [];
        $graphRevenueSearch = [];
        foreach($revenueData as $revenue) {
            $graphRevenueSearch[$count] = $revenue->date;
            $graphRevenue[$count]['date'] = $revenue->date;
            $graphRevenue[$count]['totalAmount'] = $revenue->totalAmount;
            $graphRevenue[$count]['averageAmount'] = $revenue->averageAmount;
            $graphRevenue[$count]['billCount'] = $revenue->billCount;
           
            $count++;
        }
        
        for($i=0; $i<7; $i++) {
            $day = Carbon::now()->subDays($i);
            $checkDate = $day->toDateString();
            //$dateDay = $day->format('D');
            $dateDay = $day->format('jS M');
            
            // Search For Revenue, Average Basket and Bills used common as in both totalAmount is used
            $position = array_search($checkDate, $graphRevenueSearch);
            
            if($position !== false) {
                $graphdata['revenue']['labels'][] = $dateDay;
                $graphdata['revenue']['data'][] = round($graphRevenue[$position]['totalAmount'],0);
                
                $graphdata['avgBasket']['labels'][] = $dateDay;
                $graphdata['avgBasket']['data'][] = round($graphRevenue[$position]['averageAmount'],0);
                
                $graphdata['bills']['labels'][] = $dateDay;
                $graphdata['bills']['data'][] = round($graphRevenue[$position]['billCount'],0);
            }
            else {
                $graphdata['revenue']['labels'][] = $dateDay;
                $graphdata['revenue']['data'][] = 0;
                
                $graphdata['avgBasket']['labels'][] = $dateDay;
                $graphdata['avgBasket']['data'][] = 0;
                
                $graphdata['bills']['labels'][] = $dateDay;
                $graphdata['bills']['data'][] = 0;
            }
        }
		
	
		$graphdata['topSellingData'] = $topSellingData;
		$graphdata['totalAmount0'] = $totalbilling0[0]->totalAmount;
		$graphdata['totalorderBill'] = $totalorderBill[0]->billCount;
		
		return response()->json(compact('graphdata'))->header("Access-Control-Allow-Origin",  "*");
	}

	public function inventoryGraphData($storeId){

		$outOfStock = DB::table('products')
		->select('id')
		->where('inventory','<=','0')
		->where('storeId',$storeId)
		->count();
		
		$available = DB::table('products')
		->select('id')
		->where('inventory','>','0')
		->where('storeId',$storeId)
		->count();



		$graphdata['inventory']['outOfStock'] = $outOfStock;
		$graphdata['inventory']['available'] = $available;

		return response()->json(compact('graphdata'))->header("Access-Control-Allow-Origin",  "*");
	}

	
	public function salesreport($storeId)
	{
	    $type = $_GET['type'];
	    $customStartDate = $_GET['start'];
	    $customEndDate = $_GET['end'];
	    $totalVat = 0;
	    $totalSumAmount = 0;
	    
	  /*   $fromDate = 0;
        $toDate = 0; */
	    $fromDate =  Carbon::now()->toDateString();
        $toDate =  Carbon::now()->toDateString();
        
	    //echo "Date Is: " . date("Y-m-d H:i:s", '1630070473813');
	    
	    
	    if($type == 'dayend') {
            $checkDate = Carbon::now()->toDateString();
            
            $queryData = DB::table('orders_pos')
                ->select('orderId',DB::raw('(vat - refundVat) as vat'), DB::raw('(totalAmount - refundTotalAmount) as totalAmount'),'created_at')
                ->where('storeId',$storeId)
                ->where(DB::raw('Date(created_at)'),'=',$checkDate)
                ->orderBy(DB::raw('Date(created_at)'),'DESC');
                
            $fromDate = $checkDate;
            $toDate = $checkDate;
	    }
	    else {
	    
    	    // Reference
    	    /*
    	    ->select(DB::raw('SUM(totalAmount) as totalAmount'),DB::raw('SUM(totalAmount)/COUNT(totalAmount) as averageAmount'),DB::raw('COUNT(totalAmount) as billCount'),DB::raw('Date(created_at) as date'))
                    ->where('storeId',$storeId)
                    ->where(DB::raw('Date(created_at)'),'>=',$tillDate)
                    ->groupBy(DB::raw('Date(created_at)'))
                    ->orderBy(DB::raw('Date(created_at)'),'DESC')
            */
            //DB::raw("ROUND((((P.price - P.splPrice)/(P.price + P.splPrice))),0) AS discount"))
            
            if($type == 'today' || $type == 'yesterday' || $type == 'thismonth' || $type == 'lastsixmonths' || $type == 'custom') {
                $queryData = DB::table('orders_pos')
                    ->select('orderId',DB::raw('COUNT(id) as totalBill'), DB::raw('ROUND((SUM(vat) - SUM(refundVat)),2) as vat'), DB::raw('(SUM(totalAmount) - SUM(refundTotalAmount)) as totalAmount'), DB::raw('Date(created_at) as created_at'))
                    ->where('storeId',$storeId);
                
            }
            else {
                $queryData = DB::table('orders_pos')
                    ->select('orderId',DB::raw('(vat - refundVat) as vat'), DB::raw('(totalAmount - refundTotalAmount) as totalAmount'),'created_at')
                    ->where('storeId',$storeId);
            }
    
            if($type == 'today' || $type == 'billwisetoday') {
    	        $checkDate = Carbon::now()->toDateString();
    	        
    	        $queryData = $queryData->where(DB::raw('Date(created_at)'),'=',$checkDate);
    	        
    	        $fromDate = $checkDate;
                $toDate = $checkDate;
    	    }
    	    else if($type == 'yesterday') {
    	        $checkDate = Carbon::now()->subDays(1)->toDateString();
    	        
    	        $queryData = $queryData->where(DB::raw('Date(created_at)'),'=',$checkDate);
    	        
    	        $fromDate = $checkDate;
                $toDate = $checkDate;
    	    }
    	    else if($type == 'thismonth' || $type == 'billwisethismonth') {
    	        $checkDate = Carbon::now()->month;
    	        
    	        $queryData = $queryData->where(DB::raw('Month(created_at)'),'=',$checkDate);
    	        
    	        // This needs to fixed as start date will be first date of the month
    	        //$fromDate = $checkDate;
    	        $fromDate = Carbon::now()->startOfMonth()->toDateString();
                $toDate = Carbon::now()->toDateString();
    	    }
    	    else if($type == 'quartely') {
    	        $checkDate = new \Carbon\Carbon('-3 months'); // for the last quarter requirement
    	        $startDate = $checkDate->startOfQuarter()->toDateString();
    	        $endDate = $checkDate->endOfQuarter()->toDateString();
    	        
    	        $queryData = $queryData->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate]);
    	        
    	        $fromDate = $startDate;
                $toDate = $endDate;
    	    }
    	    else if($type == 'lastsixmonths') {
    	        $checkDate = Carbon::now()->subMonths(6)->toDateString();
    	        
    	        $queryData = $queryData->where(DB::raw('Date(created_at)'),'>=',$checkDate);
    	        
    	        $fromDate = $checkDate;
                $toDate = Carbon::now()->toDateString();
    	    }
    	    else if($type == 'custom' || $type == 'billwisecustom') {
    	        //$checkDate = Carbon::now()->subMonths(6)->toDateString();
    	        
    	        $queryData = $queryData->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate]);
    	        //print_r($queryData);
    	        
    	        $fromDate = $customStartDate;
                $toDate = $customEndDate;
    	    }
    	    

            if($type == 'today' || $type == 'yesterday' || $type == 'thismonth' || $type == 'custom') {
                $queryData = $queryData->groupBy(DB::raw('Date(created_at)'))
                    ->orderBy('created_at','DESC');
            }
            else if($type == 'lastsixmonths') {
            //else if($type == 'lastsixmonths' || $type == 'custom') {
                $queryData = $queryData->groupBy(DB::raw('Month(created_at)'))
                    ->orderBy('created_at','DESC');
            }
            else {
                $queryData = $queryData->orderBy('created_at','DESC');
            }
            
            
            //$queryData = $queryData->orderBy('created_at','DESC');
	    }
	    
        $completeData = $queryData->get();
        
        foreach($completeData as $data) {
            $totalVat += $data->vat;
            $totalSumAmount += $data->totalAmount;
        }

        $queryData = $queryData->paginate(10);

        /*
        echo "<br><br>" . $checkDate . " - " . $customStartDate . " - " . $customEndDate;
        echo "<br><br><hr><br>";

        print_r($queryData);
        
        echo "<br><br>";
        */
        
        $results['bills'] = $queryData;
        $results['totalVat'] = round($totalVat,2);
        $results['totalSumAmount'] = round($totalSumAmount,2);
        $results['totalBills'] = count($completeData);

        $results['fromDate'] = $fromDate;
        $results['toDate'] = $toDate;
        
        return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function catvatreport($storeId)
	{
	    $type = $_GET['type'];
	    $customStartDate = $_GET['start'];
	    $customEndDate = $_GET['end'];
	    $totalSumAmount = 0;
        
        $fromDate = 0;
        $toDate = 0;
        
        /*
        $queryData = DB::table('reports')
                ->select('categoryName',DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'), DB::raw('ROUND((SUM(total) - SUM(refundTotal) - SUM(total*(discPer/100))),2) as totalAmount'))
                ->where('storeId',$storeId);
        */
        
        $queryData = DB::table('reports')
                ->select(DB::raw("(CASE WHEN `categoryName` IS NULL THEN 'Custom' ELSE `categoryName` END) as categoryName"), DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'), DB::raw('ROUND((SUM(total) - SUM(refundTotal) - SUM(total*(discPer/100))),2) as totalAmount'))
                ->where('storeId',$storeId);
        
        if($type == 'catwisetoday') {

            $checkDate = Carbon::now()->toDateString();
           
    	   $queryData = $queryData->where(DB::raw('Date(created_at)'),'=',$checkDate);
                
        }
        
        if($type == 'catwiseyesterday') {
        
           $checkDate = Carbon::now()->subDays(1)->toDateString();
           
           
    	   $queryData = $queryData->where(DB::raw('Date(created_at)'),'=',$checkDate);
    	   
        }
        
        if($type == 'catwisethismonth') 
        {
            $checkDate = Carbon::now()->month;
    	    $queryData = $queryData->where(DB::raw('Month(created_at)'),'=',$checkDate);
    	   
    	    $fromDate = Carbon::now()->startOfMonth()->toDateString();
    	    
    	    //echo $fromDate;
    	    //die;
    	    
            $toDate = Carbon::now()->toDateString();
        }
        
        if($type == 'catwisecustom') {
           //$checkDate = Carbon::now()->subDays(1)->toDateString();
    	    $queryData = $queryData->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate]);
    	     
    	/*    $queryData = DB::table('reports')
                ->select('categoryName',DB::raw('COUNT(id) as qty'),DB::raw('SUM(total) as totalAmount'))
                ->where('storeId',$storeId)
                ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
                ->groupBy('categoryName')
                ->groupBy(DB::raw('Date(created_at)'))
                ->orderBy('created_at','DESC')->paginate(10); */
				
        }
        
        
        
        if($type == 'catwisetoday' || $type == 'catwiseyesterday') 
        {
            $queryData = $queryData->groupBy('categoryName')
                    ->orderBy('created_at','DESC');


            $fromDate = $checkDate;
            $toDate = $checkDate;
        }
        
        if($type == 'catwisethismonth')
        {
            $queryData = $queryData->groupBy('categoryName')
                    ->orderBy('created_at','DESC');
            
        }
        
         if($type == 'catwisecustom' ) 
        {
            $queryData = $queryData->groupBy('categoryName')
                    ->orderBy('created_at','DESC');
                    
            $fromDate = $customStartDate;
            $toDate = $customEndDate;
        }
        
        
            

         
        $completeData = $queryData->get();
        
        foreach($completeData as $data) {
            $totalSumAmount += $data->totalAmount;
        }

        $queryData = $queryData->paginate(10);
        
        $results['catdata'] = $queryData;
        $results['totalSumAmount'] = round($totalSumAmount,2);
        $results['fromDate'] = $fromDate;
        $results['toDate'] = $toDate;
        return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
        
	    
	}
	
	
	
	public function vatreport($storeId)
	{
	    $type = $_GET['type'];
	    $customStartDate = $_GET['start'];
	    $customEndDate = $_GET['end'];
	    $totalSumAmount = 0;
        $totalVatAmount = 0;
        $fromDate = 0;
        $toDate = 0;
       
            $checkDate = Carbon::now()->toDateString();
			$queryPurchaseData = DB::table('vendorInvoice')->select(DB::raw('ROUND((SUM(vatAmount)),2) as totalVat'))->where('status','Complete')->where('storeId',$storeId);

			$queryData = DB::table('reports')
			->select('productName',DB::raw('ROUND((SUM(price) - SUM(refundPrice)),2) as sellingPrice'),DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'),DB::raw('ROUND(SUM(total) - SUM(refundTotal),2) as totalAmount'),
			DB::raw('ROUND((SUM(vat) - SUM(refundVat)),2) as vat'),DB::raw('ROUND((SUM(costPrice*quantity) - SUM(costPrice*refundQuantity)),2) as totalcostPrice'), DB::raw('ROUND((SUM(vat/quantity)*100)/(SUM(price) - SUM(vat/quantity)),2) as vatPer'))
			->where('storeId',$storeId);
                
            $fromDate = $checkDate;
            $toDate = $checkDate;
	    
	
        if(empty($customStartDate))
	        $customStartDate = new Carbon('first day of January 2021');
	        
	    if(empty($customEndDate))
	        $customEndDate = Carbon::now()->toDateString();
	        
        $queryPurchaseData = DB::table('vendorInvoice')->select(DB::raw('ROUND((SUM(vatAmount)),2) as totalVat'))->where('status','Complete')->where('storeId',$storeId);
        
        /*
        $queryData = DB::table('reports')
                ->select('productName',DB::raw('ROUND((SUM(price) - SUM(vat)),2) as sellingPrice'),DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'),DB::raw('ROUND((SUM(total) - SUM(refundTotal) - SUM(total*(discPer/100))),2) as totalAmount'),
                DB::raw('ROUND((SUM(vat) - SUM(refundVat)),2) as vat'),DB::raw('ROUND((price - (price*(discPer/100))),2) as totalPrice'),DB::raw('ROUND((SUM(costPrice)),2) as totalcostPrice'), DB::raw('ROUND((SUM(vat)*100 / ((SUM(price) - SUM(vat)))),2) as vatPer'))
                ->where('storeId',$storeId);
        */
        
        
        $queryData = DB::table('reports')
                ->select('productName',DB::raw('ROUND((SUM(price) - SUM(refundPrice)),2) as sellingPrice'),DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'),DB::raw('ROUND(SUM(total) - SUM(refundTotal),2) as totalAmount'),
                DB::raw('ROUND((SUM(vat) - SUM(refundVat)),2) as vat'),DB::raw('ROUND((SUM(costPrice*quantity) - SUM(costPrice*refundQuantity)),2) as totalcostPrice'), DB::raw('ROUND((SUM(vat/quantity)*100)/(SUM(price) - SUM(vat/quantity)),2) as vatPer'))
                ->where('storeId',$storeId);
        
        
        /*
        // Sample Query to Calculate vatPer
        $queryData = DB::table('reports')
                ->select('productName',DB::raw('SUM(vat)/SUM(quantity) as vat'),DB::raw('SUM(price) as price'),DB::raw('SUM(price) - SUM(vat)/SUM(quantity) as diff'),DB::raw('ROUND((SUM(vat)/SUM(quantity)*100)/(SUM(price) - SUM(vat)/SUM(quantity)),2) as vatPer'),DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'))
                ->where('storeId',$storeId)
                ->where('productName','Afia Oil');
        */
        
        if($type== 'vattoday')
        {
            $checkDate = Carbon::now()->toDateString();
    	    $queryData = $queryData->where(DB::raw('Date(created_at)'),'=',$checkDate);
    	    
    	    $queryPurchaseData = $queryPurchaseData->where(DB::raw('Date(created_at)'),'=',$checkDate);
        }
        
        if($type == 'vatyesterday') {
        
           $checkDate = Carbon::now()->subDays(1)->toDateString();
    	   $queryData = $queryData->where(DB::raw('Date(created_at)'),'=',$checkDate);
    	   
    	   $queryPurchaseData = $queryPurchaseData->where(DB::raw('Date(created_at)'),'=',$checkDate);
    	   
        }
        
        if($type == 'vatthismonth') 
        {
            $checkDate = Carbon::now()->month;
    	    $queryData = $queryData->where(DB::raw('Month(created_at)'),'=',$checkDate);
    	   
    	    $fromDate = Carbon::now()->startOfMonth()->toDateString();
    	    
    	    //echo $fromDate;
    	    //die;
    	    
            $toDate = Carbon::now()->toDateString();
        }
        
        
        
        
        
        if($type == 'vatlastsixmonths') {

            $checkDate = Carbon::now()->subMonths(6)->toDateString();
    	    $queryData = $queryData->where(DB::raw('Date(created_at)'),'>=',$checkDate);
    	    
    	    $queryPurchaseData = $queryPurchaseData->where(DB::raw('Date(created_at)'),'>=',$checkDate);
                
        }
         
        if($type == 'vatcustom') {
           $queryData = $queryData->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate]);
           $queryPurchaseData = $queryPurchaseData->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate]);
           $fromDate = $customStartDate;
           $toDate = $customEndDate;
        }
        
        
        if($type == 'vattoday' || $type == 'vatyesterday') 
        {
            $fromDate = $checkDate;
            $toDate = $checkDate;
        }
	  
        $queryData = $queryData->groupBy('productName','price')->orderBy('qty','DESC');
        
        $completeData = $queryData->get();
        $completePurchaseData = $queryPurchaseData->first();

        $totalVatPurchase = $completePurchaseData->totalVat;
        
        
        //print_r($completeData);
        //die;
        
        
        foreach($completeData as $data) {
            $totalSumAmount += $data->totalAmount;
            $totalVatAmount += $data->vat;
        }

        $queryData = $queryData->paginate(10);
        
        $results['vatdata'] = $queryData;
        $results['totalSumAmount'] = round($totalSumAmount,2);
        $results['totalVatAmount'] = round($totalVatAmount,2);
        $results['fromDate'] = $fromDate;
        $results['toDate'] = $toDate;
        $results['totalVatPurchase'] = $totalVatPurchase;
        return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
        
	    
	}
	
	
	public function profitreport($storeId)
	{
	    //$type = $_GET['type'];
	    $type ='';
	   // $customStartDate = $_GET['start'];
	    $customStartDate = '';
	   // $customEndDate = $_GET['end'];
	    $customEndDate ='';
	    $totalSumAmount = 0;
        $totalVatAmount = 0;
        $fromDate = 0;
        $toDate = 0;
        $queryData = DB::table('reports')
                ->select('productName',DB::raw('ROUND(((SUM(price) - SUM(costPrice))) - vat/quantity,2) as margin'),DB::raw('ROUND(((SUM(total) - SUM(vat) - (costPrice * quantity)) ),2) as profit'), DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'),DB::raw('ROUND((SUM(total) - SUM(refundTotal)),2) as totalAmount'),DB::raw('ROUND((SUM(vat) - SUM(refundVat)),2) as vat'),DB::raw('ROUND((SUM(price)),2) as totalPrice'),'created_at')
				->groupBy('productName','price','costPrice')
                ->where('storeId',$storeId);
        
        if($type == 'profitlastsixmonths') {

            $checkDate = Carbon::now()->subMonths(6)->toDateString();
    	    $queryData = $queryData->where(DB::raw('Date(created_at)'),'>=',$checkDate);
                
        }
         
        if($type == 'profitcustom') {
           $queryData = $queryData->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate]);
            $fromDate = $customStartDate;
            $toDate = $customEndDate;
        }
        
        if($type == 'profitlastsixmonths' || $type == 'profitcustom') 
        {
            $queryData = $queryData->orderBy('created_at','DESC');
        }
        
        $completeData = $queryData->get();
        
        foreach($completeData as $data) {
            $totalSumAmount += $data->totalAmount;
            $totalVatAmount += $data->vat;
        }

        $queryData = $queryData->orderBy('id','DESC')->paginate(10);
        
        $results['profitdata'] = $queryData;
        $results['totalSumAmount'] = round($totalSumAmount,2);
        $results['totalVatAmount'] = round($totalVatAmount,2);
        $results['fromDate'] = $fromDate;
        $results['toDate'] = $toDate;

		


        return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
        
	    
	}

	public function profitlossreports($storeId)
	{
	  // die;
		$storeId = $storeId;
		

		/* if(isset($_GET['search']))
			$search = $_GET['search'];
		else
			$search = ''; */

		if(!empty($_GET['start']))
			$startDate = $_GET['start'];
		else
			$startDate = Carbon::now()->toDateString();
		
		if(!empty($_GET['end']))
			$endDate = $_GET['end'];
		else
			$endDate = Carbon::now()->toDateString();


		$queryData = DB::table('reports')
		->select('productName','price','costPrice', 'storeId', DB::raw('SUM(quantity) as qty'), DB::raw('(price - costPrice) as margin'),   DB::raw('((price - costPrice) * (SUM(quantity))) as totalMargin'), DB::raw('ROUND((((price - costPrice)/costPrice) * 100),2) as percentprofit'))
		->whereBetween(DB::raw('Date(created_at)'),[$startDate,$endDate])
		->where('storeId', $storeId);

		/* if(!empty($search)) {
			$queryData = $queryData->where('productName','LIKE', '%' . $search . '%');
		} */
		
		$queryData = $queryData->groupBy('productName')->get();


		$results['profitdata'] = $queryData;
        $results['startDate'] = $startDate;
        $results['endDate'] = $endDate;

        return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
        
	    
	}
	
	public function productreport($storeId)
	{
	    $type = $_GET['type'];
	    $customStartDate = $_GET['start'];
	    $customEndDate = $_GET['end'];
	    $totalSumAmount = 0;

        $queryData = DB::table('reports')
                ->select('productName','price',DB::raw('ROUND((SUM(price)*quantity),2) as totalamount'), DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'),DB::raw('ROUND((SUM(total) - SUM(refundTotal)),2) as totalAmount'),DB::raw('ROUND((SUM(price)),2) as totalPrice'))
                ->where('storeId',$storeId);
        
        if($type == 'producttoday') {

            $checkDate = Carbon::now()->toDateString();
    	    $queryData = $queryData->where(DB::raw('Date(created_at)'),'=',$checkDate);
                
        }
        if($type == 'productthismonth') {

            $checkDate = Carbon::now()->month;
    	    $queryData = $queryData->where(DB::raw('Month(created_at)'),'=',$checkDate);
                
        }
         
        if($type == 'productcustom') {
           $queryData = $queryData->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate]);
        }
        if($type == 'producttoday') 
        {
            $queryData = $queryData->groupBy('productName','price','costPrice')->orderBy('created_at','DESC');
            $fromDate = $checkDate;
            $toDate = $checkDate;
        }
        
        if($type == 'productthismonth')
        {
            $queryData = $queryData->groupBy('productName','price','costPrice')->orderBy('created_at','DESC');
            $fromDate = Carbon::now()->startOfMonth()->toDateString();
            $toDate = Carbon::now()->toDateString();
        }
        
        if($type == 'productcustom') 
        {
            $queryData = $queryData->groupBy('productName','price','costPrice')->orderBy('created_at','DESC');
            $fromDate = $customStartDate;
            $toDate = $customEndDate;
        }
        
        $completeData = $queryData->get();
        
        foreach($completeData as $data) 
        {
            $totalSumAmount += $data->totalAmount;
        }

        $queryData = $queryData->paginate(10);
        
        $results['productdata'] = $queryData;
        $results['fromDate'] = $fromDate;
        $results['toDate'] = $toDate;
        $results['totalSumAmount'] = round($totalSumAmount,2);
        
        return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
        
	    
	}
	
	
	
    public function refundreport($storeId)
	{
	    $type = $_GET['type'];
	    $customStartDate = $_GET['start'];
	    $customEndDate = $_GET['end'];
	    $totalSumAmount = 0;
        
        $fromDate = 0;
        $toDate = 0;
        
        $queryData = DB::table('reports')
                ->leftJoin('orders_pos','orders_pos.orderId','=','reports.orderNumber')
                ->leftJoin('customers','customers.id','=','orders_pos.customerId')
                ->select('reports.created_at','reports.orderNumber',DB::raw('SUM(reports.quantity) as qty'),DB::raw('SUM(reports.refundQuantity) as refundQty'),DB::raw('SUM(reports.refundTotal) as totalAmount'),'customers.customerName')
                ->where('reports.refundQuantity','>',0)
                ->groupBy('reports.orderNumber')
                ->where('reports.storeId',$storeId);
        
        if($type == 'refundtoday') {

          $checkDate = Carbon::now()->toDateString();
          $queryData = $queryData->where(DB::raw('Date(reports.updated_at)'),'=',$checkDate);
        }
        
        if($type == 'refundyesterday') {
        
           $checkDate = Carbon::now()->subDays(1)->toDateString();
    	   $queryData = $queryData->where(DB::raw('Date(reports.updated_at)'),'=',$checkDate);
        }
         
        if($type == 'refundcustom') {
           //$checkDate = Carbon::now()->subDays(1)->toDateString();
    	    $queryData = $queryData->whereBetween(DB::raw('Date(reports.updated_at)'), [$customStartDate, $customEndDate]);
        }
        
        if($type == 'refundtoday' || $type == 'refundyesterday') 
        {
            $queryData = $queryData->groupBy('reports.orderId')
                    ->orderBy('reports.updated_at','DESC');
            
            $fromDate = $checkDate;
            $toDate = $checkDate;
        }
        
        if($type == 'refundcustom' ) 
        {
            $queryData = $queryData->groupBy('reports.orderId')
                    ->orderBy('reports.updated_at','DESC');
                    
            $fromDate = $customStartDate;
            $toDate = $customEndDate;
        }
        
        $completeData = $queryData->get();
        
        foreach($completeData as $data) {
            $totalSumAmount += $data->totalAmount;
        }

        $queryData = $queryData->paginate(10);
        
        $results['refunddata'] = $queryData;
        $results['totalSumAmount'] = round($totalSumAmount,2);
        $results['fromDate'] = $fromDate;
        $results['toDate'] = $toDate;
        
        return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
        
	    
	}
	
	
	
	public function inventoryreport($storeId)
	{
		$results ='';
		die;
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
        /* 
	    $type = $_GET['type'];
	    
        $queryData = DB::table('reports')
                ->select(DB::raw('SUM(quantity)-SUM(refundQuantity) as qty'),'productName as name','productNameAr as name_ar')
                ->where('storeId',$storeId)->groupBy('productName');
        $products = DB::Table('products as P')
        		->select('P.id','P.name','PAR.name as name_ar','P.barCode','P.categoryId','P.price','P.productImage','P.productImgBase64','P.inventory','P.minInventory','MTC.value as tax','P.storeId','P.status')
        		->leftJoin('products_ar as PAR','PAR.productId','=','P.id')
        		->leftJoin('mas_taxclass as MTC','MTC.id','=','P.taxClassId')
        		->where('P.storeId','=',  $storeId)
        		->orderBy('P.updated_at', 'DESC');
		
	    
	    if($type == 'all') 
	    {
	        $products = $products;
	    }
	    
	    if($type == 'outofstock') {
	        $products = $products->where('P.inventory','<=',  0);
	    }
	    
	    if($type == 'available') {
	        $products = $products->where('P.inventory','>',  'P.minInventory');
	    }
	    
	    if($type == 'lowinventory') {
	        $products = $products->where('P.inventory','>',  0)->where('P.inventory','<=',  'P.minInventory');
	    }
        
        
        if($type == 'fastmoving')
        {
            $queryData = $queryData->orderBy('reports.quantity','DESC');
        }
        
        if($type == 'slowmoving')
        {
            $queryData = $queryData->orderBy('reports.quantity','ASC');
        }
       
        
         
       
       
        
        
        $completeData = $products->get();
        
        $movingData =  $queryData->get();
        
        
        

        $products = $products->paginate(10);
        $queryData = $queryData->paginate(10);
        
        
        if($type == 'outofstock' || $type == 'available' || $type == 'lowinventory' || $type == 'all') 
        {
            $results['inventoryreportdata'] = $products;
        }
        
        if($type == 'fastmoving' || $type == 'slowmoving') 
        {
            $results['inventoryreportdata'] = $queryData;
        }
       
        
        return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
         */
	    
	}
	
	public function mediareport($storeId) {
	    $customStartDate = $_GET['start'];
	    $customEndDate = $_GET['end'];
	    
	    if(empty($customStartDate)) {
	        //$customStartDate = new Carbon('first day of January 2021');
			$customStartDate = Carbon::now()->toDateString();
		}
	        
	    if(empty($customEndDate)) {
	        $customEndDate = Carbon::now()->toDateString();
		}
	    
	    $resultsCash = DB::table('orders_pos')->select(DB::raw('Count(id) as cashCount'), DB::raw('SUM(totalAmount - refundTotalAmount) as cashAmount'), DB::raw('SUM(refundTotalAmount) as refundTotalAmount'))
        ->where('paymentStatus', '=', 'Cash')
        ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
        ->where('storeId','=',$storeId)->first();
		
		$resultsCashRefund = DB::table('orders_pos')->select(DB::raw('Count(id) as cashRefundCount'))
		->where('refundTotalAmount','>','0')
		->where(function($query) {
			$query->orwhere('paymentStatus', '=', 'Cash')
				->orwhere('paymentStatus', '=', 'Multiple');
		})
        ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
        ->where('storeId','=',$storeId)->first();
		
        $resultsCard = DB::table('orders_pos')->select(DB::raw('Count(id) as cardCount'), DB::raw('SUM(totalAmount - refundTotalAmount) as cardAmount'), DB::raw('SUM(refundTotalAmount) as refundTotalAmount'))
        ->where('paymentStatus', '=', 'Card')
        ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
        ->where('storeId','=',$storeId)->first();
        
		$resultsCardRefund = DB::table('orders_pos')->select(DB::raw('Count(id) as cardRefundCount'))
		->where('refundTotalAmount','>','0')
		->where('paymentStatus', '=', 'Card')
        ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
        ->where('storeId','=',$storeId)->first();
		
        $resultsOther = DB::table('orders_pos')->select(DB::raw('Count(id) as otherCount'), DB::raw('SUM(totalAmount - refundTotalAmount) as otherAmount'))
        ->where('paymentStatus', '=', 'Credit')
        ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
        ->where('storeId','=',$storeId)->first();
		
		$resultsCreditRefund = DB::table('orders_pos')->select(DB::raw('Count(id) as creditRefundCount'))
		->where('refundTotalAmount','>','0')
		->where('paymentStatus', '=', 'Credit')
        ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
        ->where('storeId','=',$storeId)->first();
		
		// Calculate Sum of refund in case of multiple. This will be subtracted from Cash amount
		$resultsMultipleRefund = DB::table('orders_pos')->select(DB::raw('SUM(refundTotalAmount) as multipleRefundAmount'))
        ->where('paymentStatus', '=', 'Multiple')
        ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
        ->where('storeId','=',$storeId)->first();
		
		$resultsMultipleCash = DB::table('multiplepayment')->select('paymentMode', DB::raw('Count(id) as multipleCount'), DB::raw('SUM(amount) as totalAmount'))
        ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
		//->where(TJ8082301031981)
        ->where('storeId','=',$storeId)->groupBy('paymentMode')->get();
		
		$refundedAmount = DB::table('orders_pos')->select(DB::raw('SUM(refundTotalAmount) as RefundAmount'),DB::raw('Count(id) as refundCount'))
		 ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
		->where('storeId','=',$storeId)
		->where('refundTotalAmount','>','0')
		->first();
		
		$salesCount =  DB::table('orders_pos')->select(DB::raw('Count(id) as salesCount'))
		 ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
		->where('storeId','=',$storeId)
		->first();
        
		
		//print_r($salesCount);
		
		//die;
		
	    $results['cashCount'] = $resultsCash->cashCount;
	    $results['cardCount'] = $resultsCard->cardCount;
	    $results['otherCount'] = $resultsOther->otherCount;
	    $results['refundCount'] = $refundedAmount->refundCount;
	    $results['salesCount'] = $salesCount->salesCount;
	    
		$results['multipleCount'] = 0;
		
	    if($results['cashCount'] == 0)
	        $results['cashAmount'] = 0;
	    else {
			// Need to subtract here only and not above as cashCount if condition logic breaks if subtracted above
			$results['cashCount'] = $results['cashCount'] - $resultsCashRefund->cashRefundCount;
	        $results['cashAmount'] = $resultsCash->cashAmount - $resultsMultipleRefund->multipleRefundAmount;
		}
	        
	    if($results['cardCount'] == 0)
	        $results['cardAmount'] = 0;
	    else {
			// Need to subtract here only and not above as cardCount if condition logic breaks if subtracted above
			$results['cardCount'] = $results['cardCount'] - $resultsCardRefund->cardRefundCount;
	        $results['cardAmount'] = $resultsCard->cardAmount;
		}
	        
	    if($results['otherCount'] == 0)
	        $results['otherAmount'] = 0;
	    else {
			// Need to subtract here only and not above as otherCount if condition logic breaks if subtracted above
			$results['otherCount'] = $results['otherCount'] - $resultsCreditRefund->creditRefundCount;
	        $results['otherAmount'] = $resultsOther->otherAmount;
		}
		
		//$results['refundAmount'] = $resultsCash->refundTotalAmount + $resultsCard->refundTotalAmount;
		$results['refundAmount'] = $refundedAmount->RefundAmount;
		
		
		//print_r($results);
		
		
		foreach($resultsMultipleCash as $result) {
			if($result->paymentMode == 'CASH') {
				$results['cashAmount'] = $results['cashAmount'] + $result->totalAmount;
				$results['cashCount'] += $results['multipleCount'] + $result->multipleCount;
				
				
			}
			else if($result->paymentMode == 'CARD') {
				$results['cardAmount'] = $results['cardAmount'] + $result->totalAmount;
				$results['cardCount'] += $results['multipleCount'] + $result->multipleCount;
			}
			
			//$results['multipleCount'] = $results['multipleCount'] + $result->multipleCount;
		}
		$results['fromDate'] = $customStartDate;
		$results['toDate'] = $customEndDate;
		
		//print_r($results);
		
	    return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function cashierreport($storeId) {
	    //$resultsCash = DB::table('orders_pos')->select(DB::raw('Count(id) as cashCount'), DB::raw('SUM(totalAmount) as cashAmount'))->where('paymentStatus', '=', 'Cash')->where('storeId','=',$storeId)->first();
	    
	    $customStartDate = $_GET['start'];
	    $customEndDate = $_GET['end'];
	    
	    $search = $_GET['search'];
		    
	    if(empty($customStartDate)) {
	       $customStartDate = new Carbon('first day of January 2021');
		   $customStartDate =  $customStartDate->toDateString();;
			//$customStartDate = Carbon::now()->toDateString();
		}
	        
	    if(empty($customEndDate))
	        $customEndDate = Carbon::now()->toDateString();

	    $cashier = DB::table('cashier as C')
		->leftJoin('users as U','U.id','=','C.userId')
	    ->leftJoin('orders_pos as O','O.userId','=','C.userId')
	    ->leftJoin('orders_pos as O','O.userId','=','C.userId')
		->select(DB::raw('CONCAT(U.firstName, " ", U.lastName) AS Name'), 'U.id as userId', 'U.contactNumber', 'U.email', DB::raw('Count(O.id) as billCount'), DB::raw('SUM(O.totalAmount - O.refundTotalAmount) as totalSales'))
	    ->whereBetween(DB::raw('Date(O.created_at)'), [$customStartDate, $customEndDate])
	    ->where('C.storeId','=',$storeId);
	    
	    
	    if(!empty($search)) {
			
		   // $cashier = $cashier->where('U.firstName', 'LIKE', $search.'%');
			$cashier = $cashier->where(function($query) use ($search) {
				$name = explode(" ", $search);

				$firstName = $name[0];
				$firstName = trim($firstName);

				if(count($name) > 1) { 
					$lastName = $name[1];
					$lastName = trim($lastName);
				}
				else {
					$lastName = $firstName;
				}

				$query->orWhere('U.firstName', 'LIKE', $firstName . '%' )
				->orWhere ('U.lastName', 'LIKE',  $lastName . '%' );
			});
		}
	    
	    $cashier = $cashier->groupBy('O.userId')->get();

		$results['results'] = $cashier;
		$results['fromDate'] = $customStartDate;
		$results['toDate'] = $customEndDate;
	    
	    //print_r($results);
	    
	    return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function ajax_loginecr(Request $request){
		$contactNumber = $request->input('contactNumber');
		$password = $request->input('password');
		
		$user = DB::Table('users')
		->leftJoin('mas_role','mas_role.id','=','users.roleId')
		->select('users.firstName','users.lastName','users.roleId','mas_role.name AS userrole','mas_role.userRights AS userRights','users.id as userId','users.email','users.contactNumber','users.password', 'users.status as userStatus')
		->where('users.contactNumber',$contactNumber)->first();
		
		if (!$user) {
		    return response()->json(['success'=>false, 'message' => 'Login Failed, please check email id'])->header("Access-Control-Allow-Origin",  "*");
		}
		if (!Hash::check($password, $user->password)) {
		    return response()->json(['success'=>false, 'message' => 'Login Failed, pls check password', 'password'=>$password])->header("Access-Control-Allow-Origin",  "*");
		}
		/*
		if ($user["roleId"] != '4' && $user["roleId"] != '7' && $user["roleId"] != '9' && $user["roleId"] != '10') {
		    return response()->json(['success'=>false, 'message' => 'Login Failed. You are not authorized to login on app'])->header("Access-Control-Allow-Origin",  "*");
		}*/
		$user = (array) $user;
		if ( $user["roleId"] == '11' ) {
		    return response()->json(['success'=>false, 'message' => 'Login Failed, please check email id'])->header("Access-Control-Allow-Origin",  "*");
		}

		
		unset($user["password"]);

        if($user["roleId"] == '4') {
            // Store Owner
            $stores = DB::Table('stores')
    		->select('stores.id as storeId','stores.printType','stores.cardPayment','stores.allowNegativeBill','stores.manageInventory','stores.storeName','stores.storeType','stores.status as storeStatus','stores.address','stores.vatNumber','stores.printStoreNameEn','stores.printStoreNameAr','stores.printVat','stores.printAddEn','stores.printAddAr','stores.printPh','stores.printFooterEn','stores.printFooterAr','stores.printBillCount','stores.cashDrawerBalance','stores.subscriptionExpiry','stores.defaultLang')
    		->where('stores.userId',$user["userId"])->first();
        }
        else if($user["roleId"] == '7' || $user["roleId"] == '9' || $user["roleId"] == '10') {
            // Cashier
            $stores = DB::Table('cashier')
            ->leftJoin('stores','stores.id','=','cashier.storeId')
    		->select('stores.id as storeId','stores.printType','stores.cardPayment','stores.allowNegativeBill','stores.manageInventory','stores.storeName','stores.storeType','stores.status as storeStatus','stores.address','stores.vatNumber','stores.printStoreNameEn','stores.printStoreNameAr','stores.printVat','stores.printAddEn','stores.printAddAr','stores.printPh','stores.printFooterEn','stores.printFooterAr','stores.cashDrawerBalance','stores.subscriptionExpiry','stores.defaultLang')
    		->where('cashier.userId',$user["userId"])->first();
        }
        
		return response()->json(['success'=>true,'message'=>'success', 'logindata' => $user, 'stores' => $stores])->header("Access-Control-Allow-Origin",  "*");
	}
	
	/*
	public function ajax_loginecr(Request $request){
		//$this->cors();
		//echo "I am called...";
		//$email = $request->input('email');
		//$contactNumber = $request->input('email');
		$contactNumber = $request->input('contactNumber');
		//$email = "admin@tijarah.com";
		$password = $request->input('password');
		//$password = "admin#2021";
		
		$user = DB::Table('stores')
		->leftJoin('users','users.id','=','stores.userId')
		->leftJoin('mas_role','mas_role.id','=','users.roleId')
		->select('stores.id','users.firstName','users.lastName','mas_role.name AS userrole','users.id as userId','users.email','stores.manageInventory','stores.storeName','users.contactNumber','users.password')
		->where('users.contactNumber',$contactNumber)->first();
		//$user = User::where('email', '=', $email)->first();
		//return response()->json(['success'=>true,'message'=>'success', 'data' => $user]);
		
		if (!$user) {
		return response()->json(['success'=>false, 'message' => 'Login Fail, please check email id'])->header("Access-Control-Allow-Origin",  "*");
		}
		if (!Hash::check($password, $user->password)) {
		return response()->json(['success'=>false, 'message' => 'Login Fail, pls check password'])->header("Access-Control-Allow-Origin",  "*");
		}
		//unset($user["password"]);
		$user = (array) $user;
		unset($user["password"]);
		
		return response()->json(['success'=>true,'message'=>'success', 'logindata' => $user])->header("Access-Control-Allow-Origin",  "*");
	}
	*/
	
	public function ajax_loginecrsetup(Request $request){
		$contactNumber = $request->input('contactNumber');
		$password = $request->input('password');

		$user = DB::Table('stores')
		->leftJoin('users','users.id','=','stores.userId')
		->leftJoin('mas_role','mas_role.id','=','users.roleId')
		->select('stores.id as storeId', 'stores.printType', 'stores.cardPayment', 'stores.allowNegativeBill', 'users.firstName', 'users.lastName', 'mas_role.name AS userrole', 'mas_role.userRights AS userRights', 'users.id as userId', 'users.roleId as roleId', 'users.email', 'stores.manageInventory', 'stores.storeType', 'stores.storeName', 'users.contactNumber', 'users.password','stores.status as storeStatus', 'stores.address', 'stores.vatNumber', 'stores.printStoreNameEn', 'stores.printStoreNameAr', 'stores.printVat', 'stores.printAddEn', 'stores.printAddAr', 'stores.printPh', 'stores.printFooterEn', 'stores.printFooterAr', 'stores.printBillCount', 'stores.city', 'stores.shopSize', 'users.email', 'stores.cashDrawerBalance', 'stores.subscriptionExpiry', 'stores.defaultLang', 'stores.printerBarcode')
		->where('users.contactNumber',$contactNumber)->first();

		if ( $user->roleId == '11' ) {
    		return response()->json(['success'=>false, 'message' => 'Login Fail, you are not authorized person'])->header("Access-Control-Allow-Origin",  "*");
		}
		if (!$user) {
    		return response()->json(['success'=>false, 'message' => 'Login Fail, please check email id'])->header("Access-Control-Allow-Origin",  "*");
		}
		if (!Hash::check($password, $user->password)) {
	    	return response()->json(['success'=>false, 'message' => 'Login Fail, pls check password'])->header("Access-Control-Allow-Origin",  "*");
		}

		$user = (array) $user;
		unset($user["password"]);

        // Fetch data of all other tables
		return response()->json(['success'=>true,'message'=>'success', 'logindata' => $user])->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function taxClassFetch()
	{
	    $results =  DB::Table('mas_taxclass as MT')
		->select('MT.id','MT.name','MT.value')
		->orderBy('MT.id', 'ASC')->get();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	
	public function customersFetch($storeId)
	{
	    $lastSync = '';
	    $customerId = '';
	    
	    if(isset($_REQUEST['lastSync']))
	        $lastSync = $_REQUEST['lastSync'];
	       
	    if(isset($_REQUEST['customerId']))
	        $customerId = $_REQUEST['customerId'];
		
		$results =  DB::Table('customers as C')
		->select('C.id','C.customerName','C.email','C.contactNumber','C.address','C.customerVat','C.balanceDue','C.totalPurchase','C.noOfBills','C.avgItemPerBill','C.lastVisit','C.loyaltyPoints')
		->where('C.storeName','=',$storeId);
		
		if(!empty($lastSync) && $lastSync != '0000-00-00 00:00:00')
		{
    		$results = $results->where(function($query) use ($lastSync) {
                    $query->orwhere('C.created_at', '>', $lastSync)
                		->orwhere('C.updated_at', '>', $lastSync);
                });
		}
		if(!empty($customerId))
		{
		    $results = $results->where('C.id','=',  $customerId);
		}
		$results = $results->orderBy('C.customerName', 'ASC')->get();
		
		$lastSync = date('Y-m-d H:i:s');
		
		return response()->json(compact('results','lastSync'))->header("Access-Control-Allow-Origin",  "*");
		
		
	}
	
	public function ordersFetch($storeId)
	{
	    // Fetching last 10 days data only
	    $tillDate = Carbon::now()->subDays(10)->toDateString();
	    
	    $results =  DB::Table('orders_pos as O')
		->select('O.id','O.orderId','O.userId','O.customerId','O.orderDetail','O.cashBalance','O.refundDetail','O.orderStatus','O.vat','O.totalAmount','O.totalDiscount','O.paymentStatus','O.orderType','O.created_at','O.updated_at')
		->where('O.storeId','=',$storeId)
		->where(DB::raw('Date(created_at)'),'>=',$tillDate)
		->orderBy('O.id', 'DESC')
		->get();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function statusFetch()
	{
	    $results = DB::Table('mas_statustype as MS')
		->select('MS.id','MS.statusName')
		->orderBy('C1.name', 'ASC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function storePrintFetch($storeId)
	{
	    $results =  DB::Table('stores as S')
	    ->leftJoin('users', 'users.id', '=', 'S.userId')
		->select('S.id','users.contactNumber','users.firstName','users.lastName','S.printType','S.paperSize','S.cardPayment','S.allowNegativeBill','S.printerName','users.email','S.created_at','S.updated_at','S.printStoreNameEn','S.printStoreNameAr','S.printVat','S.printAddEn','S.storeName','S.printPh','S.vatNumber','S.address','S.city','S.shopSize','S.printAddAr','S.printFooterEn','S.printFooterAr','S.printBillCount','S.defaultLang','S.storeLogo','S.printerBarcode')
		->where('S.id','=',$storeId)
		->get();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function categoriesFetch($storeId)
	{
	    $lastSync = '';
	    
	    if(isset($_REQUEST['lastSync']))
	        $lastSync = $_REQUEST['lastSync'];
	    
	    $results = DB::Table('categories as C1')->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C1.id')
		->leftJoin('categories_ar AS CAR', 'CAR.categoryId', '=', 'C1.id')
		->leftJoin('categories AS C2', 'C2.id', '=', 'CR.parentCategoryId')
		->leftJoin('stores as S','S.storeType','=','C1.storeType')
		->select('C1.id','C1.name','CAR.name as name_ar','C1.catImgBase64')->orWhereNull('C2.name')
		->where('C1.typeadmin','=',"pos")
		->where('S.id','=', $storeId);
		
		if(!empty($lastSync) && $lastSync != '0000-00-00 00:00:00')
		{
    		$results = $results->where(function($query) use ($lastSync) {
                    $query->orwhere('C1.created_at', '>', $lastSync)
                		->orwhere('C1.updated_at', '>', $lastSync);
                });
		}
		
		$results = $results->orderBy('C1.id', 'ASC')->get();
		
		$lastSync = date('Y-m-d H:i:s');
		
		return response()->json(compact('results','lastSync'))->header("Access-Control-Allow-Origin",  "*");
	}

    public function productsFetch($storeId)
	{
	    $lastSync = '';
	    $productId = '';
		
		if(isset($_REQUEST['lastSync']))
	        $lastSync = $_REQUEST['lastSync'];
	        
	    if(isset($_REQUEST['productId']))
	        $productId = $_REQUEST['productId'];
	
	    $results = DB::Table('products as P')
	    ->leftJoin('products_ar as PAR','PAR.productId','=','P.id')
	    ->leftJoin('mas_taxclass as MT','MT.id','=','P.taxClassId')
		->select('P.id','P.name','PAR.name as name_ar','P.barCode','P.boxBarCode','P.piecesPerBox','P.categoryId','P.status as productStatus','P.sellingPrice', 'P.price','P.taxClassId','MT.value as taxClassValue','P.costPrice','P.productImgBase64','P.inventory','P.minInventory', DB::raw('unix_timestamp(P.updated_at) as updated_at'),'P.looseItem', DB::raw('unix_timestamp(P.expiryDate) as expiryDate'),'P.noOfDays',DB::raw('unix_timestamp(P.expiryDateNotify) as expiryDateNotify'))
		//->REPLACE('PAR.name as name_ar',"'",'')
		->where('P.storeId','=',  $storeId);
		
	    if(!empty($lastSync) && $lastSync != '0000-00-00 00:00:00')
		{
    		$results = $results->where(function($query) use ($lastSync) {
                    $query->orwhere('P.created_at', '>', $lastSync)
                		->orwhere('P.updated_at', '>', $lastSync);
                });
		}
		
		if(!empty($productId))
		{
		    $results = $results->where('P.id','=',  $productId);
		}
		
		$results = $results->orderBy('P.name', 'ASC')->get();
		//$results = $results->orderBy('P.name', 'ASC')->limit(5)->get();
		
		$lastSync = date('Y-m-d H:i:s');
		
		return response()->json(compact('results','lastSync'))->header("Access-Control-Allow-Origin",  "*");
		
		
		//return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function updateSync($storeId) {
	    // Update store table lastSync value to current time.
	    
	    $syncType = '';
	    $lastSyncValue = '';
	    
	    if(isset($_REQUEST['syncType']))
	        $syncType = $_REQUEST['syncType'];
	        
        if(isset($_REQUEST['lastSyncValue']))
	        $lastSyncValue = $_REQUEST['lastSyncValue'];
	    
	    $updateStore = new Store;
        $updateStore = Store::find($storeId);
        //echo $syncType;
        if(empty($lastSyncValue))
            $lastSync = date('Y-m-d H:i:s');
        else
            $lastSync = $lastSyncValue;
        
        if(empty($syncType))
            $updateStore->lastSync = $lastSync;
        else if($syncType == 'CAT_SYNC')
            $updateStore->catSync = $lastSync;
        else if($syncType == 'PRODUCTS_SYNC')
            $updateStore->productsSync = $lastSync;
        else if($syncType == 'CUST_SYNC')
            $updateStore->custSync = $lastSync;
		else if($syncType == 'SHIFT_SYNC')
            $updateStore->shiftSync = $lastSync;
        
        $updatedStore = $updateStore->save();
        
        return response()->json(compact('lastSync','syncType'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function placeorderpos(Request $request)
	{
		$response = [
	        'orderDetail'=>$request->orderDetail,
	        'storeId'=>$request->storeId
	        ];
	        
	       $order = new Orders;
	   
    	   $order->orderDetail = $request->orderDetail;
    	   $order->storeId = $request->storeId;
    	   $order->save();
    	   
	       // echo $request->name;
	       
	    return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	
	///**** Graph Data function ***//////
	public function hsndatagraph($storeId)
	{
	    $results = DB::Table('products as P')
		->select('P.id','P.minInventory','P.inventory','P.storeId')
	
		->where('P.inventory','<=', '0')
		//->where( 'P.storeId',$storeId)
		->get();
		
		$labels = ["Mon", "Tue", "Wed", "Thur", "Fri", "Sat", "Sun"]; 
		$datavar = [87,27,45,65,74,20,49];
		return response()->json(compact('labels','datavar'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function dailydatagraph($storeId)
	{
	    $results = DB::Table('products as P')
		->select('P.id','P.minInventory','P.inventory','P.storeId')
	
		->where('P.inventory','<=', '0')
		//->where( 'P.storeId',$storeId)
		->get();
		
		$labels = ["Mon", "Tue", "Wed", "Thur", "Fri", "Sat", "Sun"]; 
		$datavar = [50,10,89,65,38,50,45];
		return response()->json(compact('labels','datavar'))->header("Access-Control-Allow-Origin",  "*");
		
		//return response()->json(compact('labels , datavar'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function salesdatagraph($storeId)
	{
	    $results = DB::Table('products as P')
		->select('P.id','P.minInventory','P.inventory','P.storeId')
	
		->where('P.inventory','<=', '0')
		//->where( 'P.storeId',$storeId)
		->get();
		
		$labels = ["Mon", "Tue", "Wed", "Thur", "Fri", "Sat", "Sun"]; 
		$datavar = [10,20,50,65,89,78,58];
		return response()->json(compact('labels','datavar'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function stockdatagraph($storeId)
	{
	    $results = DB::Table('products as P')
		->select('P.id','P.minInventory','P.inventory','P.storeId')
	
		->where('P.inventory','<=', '0')
		//->where( 'P.storeId',$storeId)
		->get();
		
		
	
		$labels = ["Mon", "Tue", "Wed", "Thur", "Fri", "Sat", "Sun"]; 
		$datavar = [48,20,87,14,28,78,45];
		return response()->json(compact('labels','datavar'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function customerOrders($id)
	{
	    $results = DB::Table('orders_pos as O')
	    ->leftJoin('customers','customers.id','O.customerId')
		->select('customers.customerName','O.id','O.orderId','O.totalAmount',DB::raw('DATE_FORMAT(O.created_at, "%h:%i %p %m/%d/%Y") as orderDateTime'))
		->where('O.customerId',$id)
		->orderBy('O.id', 'ASC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function vendorSelect($id)
	{
	    $search = "";
        if(isset($_GET['search']))
            $search = $_GET['search'];
	    
		$results = DB::Table('storeVendors as V')
		->select('V.id','V.vendorName','V.contactNumber','V.VatNumber')->where('V.storeId',$id);
		
		if(!empty($search))
		    $results = $results->where('V.vendorName', 'LIKE', $search.'%');
		    
		$results = $results->orderBy('V.id', 'DESC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	/* Get Vendor Start */
	public function getStoreVendor($id)
	{
		$results = DB::Table('storeVendors as V')
		->select('V.id','V.vendorName')->where('V.storeId',$id)
		->orderBy('V.id', 'DESC')->get();

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}

	/* Get Vendor End */
	public function invoices($id)
	{
		
		$type = $_GET['type'];
		$customStartDate = $_GET['start'];
	    $customEndDate = $_GET['end'];
	    $fromDate = 0;
        $toDate = 0;
        $totalSumAmount = 0;
        $totalVatAmount = 0;
        
        $search = "";
        if(isset($_GET['search']))
            $search = $_GET['search'];
        
         $queryData = DB::table('vendorInvoice as VI')
            ->leftJoin('storeVendors','storeVendors.id','=','VI.vendorId')
            ->select('VI.id','storeVendors.vendorName','VI.status','storeVendors.contactNumber','VI.totalAmount','VI.vatAmount','VI.invoiceId','VI.invoiceNumber','VI.invoiceDate','VI.created_at')
            ->where('VI.storeId',$id);
            
		if(!empty($search)) {
		    //$queryData = $queryData->where('VI.invoiceNumber', 'LIKE', $search.'%');
		    
		    
		    $queryData = $queryData->where(function($query) use ($search) {
                    $query->orwhere('VI.invoiceNumber', 'LIKE', $search.'%')
                		->orwhere('storeVendors.vendorName', 'LIKE', $search.'%');
                });
            
            /*
            $queryData = $queryData->where(function($query) use ($search) {
                    $query->orwhere('VI.invoiceNumber', 'LIKE', $search.'%')
                		->orwhereJsonContains('VI.vendorDetail->vendorName', 'LIKE', $search.'%');
                });
            */
		}
        /*
		if(authuserd == 'CASHIER' && $type == 'All'){
			$queryData = $queryData->where('VI.status','=','Pending')->where(date,=,'todydate');
			$queryData = $queryData->where('VI.status','=','Complete');
		}
		*/
		
        if($type == 'All')
        {
            $queryData = $queryData;
        }
        else if($type == 'Pending') 
        {
            $queryData = $queryData->where('VI.status','=','Pending');
        }
        else if($type == 'Complete') 
        {
            $queryData = $queryData->where('VI.status','=','Complete');
        }
        
        if(!empty($customStartDate) && !empty($customEndDate)) 
		{
            $queryData = $queryData->whereBetween(DB::raw('Date(VI.invoiceDate)'), [$customStartDate, $customEndDate])->where('VI.status','!=','');
            $fromDate = $customStartDate;
            $toDate = $customEndDate;
    	}
    	
    	$queryData = $queryData->orderBy('VI.invoiceDate', 'DESC')->orderBy('VI.created_at', 'DESC');
    	
    	$completeData = $queryData->get();
    	foreach($completeData as $data) {
            $totalSumAmount += $data->totalAmount;
            $totalVatAmount += $data->vatAmount;
        }
        
    	
    	//$completeData = $queryData->get();
        //print_r($completeData);
        
        //$results['invoicedata'] = $queryData;
        //$results['totalSumAmount'] = round($totalSumAmount,2);
        //$results['totalVat'] = round($totalVatAmount,2);
        //$results['fromDate'] = $fromDate;
        //$results['toDate'] = $toDate;
    	
    	
        $queryData = $queryData->paginate(10);
        $results['invoicedata'] = $queryData;
        $results['totalSumAmount'] = round($totalSumAmount,2);
        $results['totalVat'] = round($totalVatAmount,2);
        $results['fromDate'] = $fromDate;
        $results['toDate'] = $toDate;
        
        
	    return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function purchaseorders($id)
	{
		$type = $_GET['type'];
		$customStartDate = $_GET['start'];
	    $customEndDate = $_GET['end'];
	    $fromDate = 0;
        $toDate = 0;
        $totalSumAmount = 0;
        $totalVatAmount = 0;
		$results = DB::Table('vendorPurchase as VP')
		->leftJoin('storeVendors','storeVendors.id','=','VP.vendorId')
		->select('VP.id','storeVendors.vendorName','VP.status','storeVendors.contactNumber')
		->where('VP.storeId',$id)
		->orderBy('VP.id', 'DESC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}

	/* public function merchantPurchaseReport($storeId){
		$results = DB::Table('vendorInvoice as VI')
		->join('storeVendors','storeVendors.id','=','VI.vendorId')
		->select(DB::raw('SUM(VI.totalAmount) as totalAmount'),DB::raw('SUM(VI.vatAmount) as vatAmount'),'VI.id','storeVendors.vendorName','VI.status','storeVendors.contactNumber','VI.storeId','VI.vendorId')
		->where('VI.storeId',$storeId)
		->where('VI.status','=','Complete');
	

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	} */


    public function ordervendors($storeId)
	{
		$type = $_GET['type'];
		$customStartDate = $_GET['start'];
	    $customEndDate = $_GET['end'];
	    $fromDate = 0;
        $toDate = 0;
        $totalSumAmount = 0;
        $totalVatAmount = 0;
		
		
		$queryData = DB::Table('vendorInvoice as VI')
		->join('storeVendors','storeVendors.id','=','VI.vendorId')
		->select(DB::raw('SUM(VI.totalAmount) as totalAmount'),DB::raw('SUM(VI.vatAmount) as vatAmount'),'VI.id','storeVendors.vendorName','VI.status','storeVendors.contactNumber','VI.storeId','VI.vendorId')
		->where('VI.storeId',$storeId)
		->where('VI.status','=','Complete')
		->groupBy('VI.vendorId');
		
		
		if($type == 'purchasecustom') 
		{
            $queryData = $queryData->whereBetween(DB::raw('Date(VI.created_at)'), [$customStartDate, $customEndDate]);
            $fromDate = $customStartDate;
            $toDate = $customEndDate;
    	}
		
		
		$completeData = $queryData->get();
		
		foreach($completeData as $data) {
            $totalSumAmount += $data->totalAmount;
            $totalVatAmount += $data->vatAmount;
        }
		
		$results['purchasedata']['data'] = $completeData;
		$queryData = $queryData->paginate(10);
		$results['fromDate'] = $fromDate;
        $results['toDate'] = $toDate;
        $results['totalSumAmount'] = round($totalSumAmount,2);
        $results['totalVat'] = round($totalVatAmount,2);
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function orderVendorList($storeId)
	{
		$type = $_GET['type'];
		$customStartDate = $_GET['start'];
	    $customEndDate = $_GET['end'];
	    $fromDate = 0;
        $toDate = 0;
        $totalSumAmount = 0;
        $totalVatAmount = 0;
        
        $search = "";
        if(isset($_GET['search']))
            $search = $_GET['search'];
		
        /*
		$queryData =  DB::Table('vendorPurchase as VP')
		->join('storeVendors','storeVendors.id','=','VP.vendorId')
		->select(DB::raw('SUM(VP.totalAmount) as totalAmount'),DB::raw('SUM(VP.vatAmount) as vatAmount'),'VP.id','storeVendors.vendorName','VP.status','storeVendors.contactNumber','VP.storeId','VP.vendorId')
		->where('VP.storeId',$storeId)
		->where('VP.status','=','Complete')
		->groupBy('VP.vendorId');
	    */
	    $queryData =  DB::Table('vendorInvoice as VP')
		->join('storeVendors','storeVendors.id','=','VP.vendorId')
		->select('VP.created_at',DB::raw('SUM(VP.totalAmount) as totalAmount'),DB::raw('SUM(VP.vatAmount) as vatAmount'),'VP.id','storeVendors.vendorName','VP.status','storeVendors.contactNumber','VP.storeId','VP.vendorId')
		->where('VP.storeId',$storeId)
		->where('VP.status','=','Complete');
		
		if(!empty($search))
		    $queryData = $queryData->where('storeVendors.vendorName', 'LIKE', $search.'%');
		    
		$queryData = $queryData->groupBy('VP.vendorId');
	    
	    
	    //$completeData = $queryData->get();
	    //print_r($completeData);
		
		if($type == 'vendorwisecustom') 
		{
            $queryData = $queryData->whereBetween(DB::raw('Date(VP.created_at)'), [$customStartDate, $customEndDate]);
            $fromDate = $customStartDate;
            $toDate = $customEndDate;
    	}
		$completeData = $queryData->get();

        //print_r($completeData);
        
        foreach($completeData as $data) {
            $totalSumAmount += $data->totalAmount;
            $totalVatAmount += $data->vatAmount;
        }
        
        $results['vendordata']['data'] = $completeData;
        $results['totalSumAmount'] = round($totalSumAmount,2);
        $results['totalVat'] = round($totalVatAmount,2);
        $results['totalBills'] = count($completeData);

        $results['fromDate'] = $fromDate;
        $results['toDate'] = $toDate;
		
		return response()->json(compact('results','type'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	

    public function addinvoice(Request $request){
	    $response = [
	        'vendorId'=>$request->vendorId,
	        'invoiceNumber'=>$request->invoiceNumber,
	        'invoiceDate'=>$request->invoiceDate,
	        'status'=>$request->status,
	        'storeId'=>$request->storeId,
	        'totalAmount'=>$request->totalAmount,
	        'vatAmount'=>$request->vatAmount,
	        'paymentMode'=>$request->paymentMode,
	        'refundDetail'=>json_encode($request->refundDetail),
	        'orderDetail'=>json_encode($request->orderDetail)
	        ];
	        

        $vendorData = DB::Table('storeVendors')->select('vendorName','contactNumber','VatNumber')->where('id',$request->vendorId)->first();
	   
        $vendorInvoice = new VendorInvoice;

        $vendorInvoice->vendorId = $request->vendorId;
        $vendorInvoice->vendorDetail = json_encode($vendorData);
        $vendorInvoice->invoiceNumber = $request->invoiceNumber;
        $vendorInvoice->invoiceDate = $request->invoiceDate;
        $vendorInvoice->status = $request->status;
        $vendorInvoice->storeId = $request->storeId;
        $vendorInvoice->totalAmount = $request->totalAmount;
        $vendorInvoice->vatAmount = $request->vatAmount;
        $vendorInvoice->paymentMode = $request->paymentMode;
        //$vendorInvoice->orderDetail = 'Test';

        $vendorInvoice->orderDetail = json_encode($request->orderDetail);
        $vendorInvoice->refundDetail = json_encode($request->refundDetail);
        
		$vendorInvoice->save();

		$vendorInvoiceId = $vendorInvoice->id;

        /* Update Stock Code Starts */
        $products = $request->orderDetail;
		if($request->status == 'Complete'){
			foreach($products as $product) 
			{
				
				// Save Vendor Invocie Id
				
				//$updateProduct = new Product;
				$updateProductInventoryBatch = new ProductInventoryBatch;
				
				$updateProduct = Product::find($product['id']);

				$updateProduct->inventory = $updateProduct->inventory + $product['quantity'];
				
				// (No of days - expiry date) - Have to Do
				
				
				//Carbon::now()->subDays($updateProduct->noOfDays)
				
				$dt=$product['expiryDate'];
				$no_days = $updateProduct->noOfDays;
				$bdate = strtotime("-".$no_days." days", strtotime($dt));
				$updateProduct->expiryDateNotify = date("Y-m-d", $bdate);
				
				//echo 'Before 40 days : '.date("Y-m-d", $bdate)."\n";
				
				//$date = $product['expiryDate'];
				//$expiryDate = date('Y-m-d', strtotime(str_replace('-','/', $date)));
				//echo date('Y-m-d', strtotime(str_replace('-','/', $date))); // => 2013-02-16
				
				
				
				//$updateProduct->inventory = $expiryDate;
				
				if(isset($product['expiryDate'])){
					if($updateProduct->expiryDate >  $product['expiryDate'])
					{
						$updateProduct->expiryDate = $product['expiryDate'];
					}
				}
				//$updateProduct->inventory = $updateProduct->inventory + $product['quantity'];
				$updateProduct->save();
				
				
				$updateProductInventoryBatch->vendorInvoiceId = $vendorInvoiceId;
				$updateProductInventoryBatch->productId = $product['id'];
				$updateProductInventoryBatch->inventory = $product['quantity'];
				if(isset($product['expiryDate']))
					$updateProductInventoryBatch->expiryDate = $product['expiryDate'];
				
				$updateProductInventoryBatch->save();
				
				
			}
        }
        /* Update Stock Code Ends */
        
        
		//$product_ar->save();
        
        // echo $request->name;
        return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function editinvoice($id){
	   $results = DB::Table('vendorInvoice as VI')
		->select('VI.id','VI.invoiceId', 'VI.invoiceDate','VI.vendorId','storeVendors.vendorName', 'VI.status','VI.invoiceNumber','VI.orderDetail','VI.totalAmount','VI.vatAmount','VI.refundDetail', 'VI.paymentMode')
		->leftJoin('storeVendors','storeVendors.id','=','VI.vendorId')
		->where( 'VI.id',$id)
		->first();
	    
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function updateinvoice(Request $request)
	{
	    $vendorInvoice = new VendorInvoice;
	    $vendorInvoice = VendorInvoice::find($request->id);
		
        if(!empty($request->vendorId))
            $vendorInvoice->vendorId = $request->vendorId;
            
        if(!empty($request->invoiceId))
            $vendorInvoice->invoiceId = $request->invoiceId;
            
        if(!empty($request->invoiceDate))
            $vendorInvoice->invoiceDate = $request->invoiceDate;
        
        $vendorInvoice->orderDetail = json_encode($request->orderDetail);
        $vendorInvoice->refundDetail = json_encode($request->refundDetail);
        $vendorInvoice->status = $request->status;
        $vendorInvoice->totalAmount = $request->totalAmount;
        $vendorInvoice->vatAmount = $request->vatAmount;
        $vendorInvoice->paymentMode = $request->paymentMode;
        $vendorInvoice->save();

		 /* Update Stock Code Starts */
		 $products = $request->orderDetail;
		 if($request->status == 'Complete'){
			 foreach($products as $product) 
			 {
				 //$updateProduct = new Product;
				 $updateProductInventoryBatch = new ProductInventoryBatch;
				 
				 $updateProduct = Product::find($product['id']);
 
				 $updateProduct->inventory = $updateProduct->inventory + $product['quantity'];
				 $updateProduct->save();
				 
				 // Save Vendor Invocie Id
				 
					 $updateProductInventoryBatch->vendorInvoiceId = $request->vendorId;
					 $updateProductInventoryBatch->productId = $product['id'];
					 $updateProductInventoryBatch->inventory = $product['quantity'];
					 if(isset($product['expiryDate']))
						 $updateProductInventoryBatch->expiryDate = $product['expiryDate'];
					 
					 $updateProductInventoryBatch->save();
			 }
		 }
		 /* Update Stock Code Ends */
	}
	
	public function editvendor($id){
	   
	   $results = DB::Table('storeVendors')
		->select('storeVendors.id','storeVendors.vendorName','storeVendors.contactNumber','storeVendors.VatNumber')
		->where( 'storeVendors.id',$id)
		->first();
	    
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function updatevendor(Request $request)
	{
	   
	   
	   $storeVendor = new StoreVendor;
	   $storeVendor = StoreVendor::find($request->id);
	   
	   /*
	   $results = DB::Table('storeVendors as SV')
	    ->where('SV.storeId',$request->storeId)
		->where( 'SV.VatNumber',$request->VatNumber)	
		->where('SV.id','!=', $request->id);
		*/
		
		$results = DB::Table('storeVendors as SV')
	    ->where('SV.storeId',$request->storeId)
	    ->where('SV.id','!=',$request->id);
		
		if(!empty($request->vendorName))
	        $results = $results->where( 'SV.vendorName',$request->vendorName);
	        
		if(!empty($request->contactNumber))
	        $results = $results->where( 'SV.contactNumber',$request->contactNumber);
		
		$results = $results->first();
	    
	    
	     $response = [	        
	    'vendorName'=>$request->vendorName,
	    'VatNumber'=>$request->VatNumber,
	    'contactNumber'=>$request->contactNumber,
	    'storeId'=>$request->storeId  ];
	    
	    
	   if(!empty($results)) {
    	    $status = 'duplicate';
    	    return response()->json(compact('status','results','response'))->header("Access-Control-Allow-Origin",  "*");
		}
	   
	  
	   
	    
		
        if(!empty($request->vendorName))
            $storeVendor->vendorName = $request->vendorName;
            
        if(!empty($request->contactNumber))
            $storeVendor->contactNumber = $request->contactNumber;
            
        if(!empty($request->VatNumber))
            $storeVendor->VatNumber = $request->VatNumber;
        
        $storeVendor->save();
         $response = [	        
	    'vendorName'=>$request->vendorName,
	    'VatNumber'=>$request->VatNumber,
	    'contactNumber'=>$request->contactNumber,
	    'storeId'=>$request->storeId  ];
	    return response()->json(compact('status','results','response'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	
	public function addpo(Request $request){
	    $response = [
	        'vendorId'=>$request->vendorId,
	        'poDate'=>$request->poDate,
	        'deliveryDate'=>$request->deliveryDate,
	        'status'=>$request->status,
	        'storeId'=>$request->storeId,
	        'totalAmount'=>$request->totalAmount
	        
	        ];
	   
        $vendorPurchase = new VendorPurchase;

        $vendorPurchase->vendorId = $request->vendorId;
        $vendorPurchase->poDate = $request->poDate;
        $vendorPurchase->deliveryDate = $request->deliveryDate;
        $vendorPurchase->orderDetail = json_encode($request->orderDetail);
        $vendorPurchase->status = $request->status;
        $vendorPurchase->storeId = $request->storeId;
        $vendorPurchase->totalAmount = $request->totalAmount;
        $vendorPurchase->save();
		//$product_ar->save();
        
        // echo $request->name;
        return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	}		
	public function addvendor(Request $request)
	{	    
	    /*
	    $results = DB::Table('storeVendors as SV')
	    ->where('SV.storeId',$request->storeId)
		->where( 'SV.VatNumber',$request->VatNumber);
		*/
		
		$results = DB::Table('storeVendors as SV')
	    ->where('SV.storeId',$request->storeId);
		
		if(!empty($request->vendorName))
	        $results = $results->where( 'SV.vendorName',$request->vendorName);
	        
		if(!empty($request->contactNumber))
	        $results = $results->where( 'SV.contactNumber',$request->contactNumber);
		
		$results = $results->first();
	    
	    
	     $response = [	        
	    'vendorName'=>$request->vendorName,
	    'VatNumber'=>$request->VatNumber,
	    'contactNumber'=>$request->contactNumber,
	    'storeId'=>$request->storeId  ];
	    
	    
	   if(!empty($results)) {
    	    $status = 'duplicate';
    	    return response()->json(compact('status','results','response'))->header("Access-Control-Allow-Origin",  "*");
		}
	    
	   
	    $response = 
	    [	        
    	    'vendorName'=>$request->vendorName,
    	    'VatNumber'=>$request->VatNumber,
    	    'contactNumber'=>$request->contactNumber,
    	    'storeId'=>$request->storeId  
	    ];
	    
	   
	    
	    $vendorstore = new StoreVendor;
	    $vendorstore->vendorName = $request->vendorName;
	    //$vendorstore->email = $request->email;
	    $vendorstore->contactNumber = $request->contactNumber;
	    $vendorstore->VatNumber = $request->VatNumber;
	    $vendorstore->storeId = $request->storeId;
	    $vendorstore->save();	
	    return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");	
	    
	}		
	public function editpo($id){
	    
	   
	    $results = DB::Table('vendorPurchase as VP')
		->select('VP.id','VP.poDate', 'VP.deliveryDate','VP.vendorId','VP.orderDetail','VP.totalAmount','VP.vatAmount','storeVendors.vendorName')
	    ->leftJoin('storeVendors','storeVendors.id','=','VP.vendorId')
		->where( 'VP.id',$id)
		->first();
	    
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function updatepo(Request $request)
	{
	    $vendorPurchase = new VendorPurchase;
	    $vendorPurchase = VendorPurchase::find($request->id);
		
        if(!empty($request->deliveryDate))
            $vendorPurchase->deliveryDate = $request->deliveryDate;
            
        if(!empty($request->invoiceId))
            $vendorPurchase->poDate = $request->poDate;
            
        if(!empty($request->invoiceDate))
            $vendorPurchase->invoiceDate = $request->invoiceDate;
        if(!empty($request->vendorId))
             $vendorPurchase->vendorId = $request->vendorId;
        $vendorPurchase->orderDetail = json_encode($request->orderDetail);
        $vendorPurchase->status = $request->status;
        $vendorPurchase->totalAmount = $request->totalAmount;
        $vendorPurchase->vatAmount = $request->vatAmount;
        $vendorPurchase->save();
    
        //$vendorPurchase->save();
	}
	public function poDetail($id)
	{
	    $results = DB::Table('vendorPurchase as VP')
		->select('VP.id','VP.poDate', 'VP.deliveryDate','VP.vendorId','VP.orderDetail','VP.totalAmount','VP.vatAmount')
		->where( 'VP.id',$id)
		->first();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function invoiceDetail($id)
	{
	    /*
	    $results = DB::Table('vendorInvoice as VI')
	    ->leftJoin('storeVendors','storeVendors.id','=','VI.vendorId')
		->select('VI.id','VI.invoiceDate', 'VI.invoiceId','storeVendors.vendorName','VI.orderDetail','VI.totalAmount','VI.vatAmount')
		->where( 'VI.id',$id)
		->first();
		*/
		
		$results = DB::Table('vendorInvoice')
		->select('id','invoiceDate', 'invoiceId','vendorDetail','orderDetail','totalAmount','vatAmount')
		->where( 'id',$id)
		->first();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function vatlist()
	{
	    $results = DB::Table('mas_taxclass as MV')
		->select('MV.id','MV.name', 'MV.value')
		->get();
	    
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function brandlist()
	{
	    $results = DB::Table('brands as BR')
		->select('BR.id','BR.brandName')
		->get();
	    
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function placeorderplus(Request $request)
	{
        $orderDetails = array();
        $orderDetails["products"] = $request->orderDetail;
        $cart = json_encode($orderDetails);
        
	    $response = [
	        'userId'=>$request->userId,
	        'orderId'=>$request->orderId,
	        'storeId'=>$request->storeId,
	        'customerId'=>$request->customerId,
	        'orderDetail'=>$request->orderDetails,
	        'totalAmount'=>$request->totalAmount
	        ];
	    
	    
	    $ordersPos = new OrdersPos;
	    $ordersPos->userId = $request->userId;

	    $ordersPos->storeId = $request->storeId;
	    $ordersPos->customerId = $request->customerId;
	    $ordersPos->orderDetail = $cart;
	    $ordersPos->paymentStatus = $request->paymentStatus;
	    $ordersPos->totalAmount = $request->totalAmount;
	    $ordersPos->vat = $request->vat;
	    $ordersPos->appType = $request->appType;
	    $ordersPos->orderId = $request->orderId;
	    
	    // Date will come from local but as it is not coming properly so we commented this.
	    //$ordersPos->refundDetail = $request->created_at;
	    //$ordersPos->created_at = $request->created_at;
	    
        // Convert JSON string to Array
        
        //$products = json_encode($cart);
        $products = json_decode($cart);
        /*
        foreach($products->products as $product) 
        {
            $updateProduct = new Product;
	        $updateProduct = Product::find($product->id);

	        $updateProduct->inventory = $updateProduct->inventory - $product->amount;
            $updateProduct->save();
        }*/

	    $ordersPos->save();
	    
	    
	    $orderId = 'TIJ' . ($ordersPos->id);
	    $created_at = $ordersPos->created_at;
	    $ordersPos->orderId = $orderId;
	    //$ordersPos->orderId =$request->orderId;
	    $ordersPos->save();
	   
	    
        return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function ordersplus($storeid)
	{
		$results = DB::Table('orders_pos as O')
		->select('O.id','O.orderStatus','O.orderId','O.totalAmount','O.created_at as orderDateTime')
		->where('O.storeId',$storeid)
		->where('O.appType','=','plus')
		->orderBy('O.id', 'DESC')->paginate(10);
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	
	public function batchDelete($productid)
	{
	    
	   $getBatchProduct = DB::Table('productInventoryBatch')
        ->leftJoin('products','products.id','=','productInventoryBatch.productId')
        ->select('productInventoryBatch.expiryDate','productInventoryBatch.productID','productInventoryBatch.inventory','productInventoryBatch.id')
        ->where('productInventoryBatch.productId',$productid)
        ->orderBy('productInventoryBatch.expiryDate', 'ASC')
        //->groupBy('productInventoryBatch.productID')
        ->get();
        
        print_r(count($getBatchProduct));
        //die;
        
        
        
        $loopset = true;
        $i=0;
        $productamount = 2023;
        $newInv = 0;
       
        //echo $i.'<br>';
        $deleteStatus = false;
        $tableInv = false;
        
        
        //echo '<pre>';
        if(count($getBatchProduct) && $getBatchProduct->isNotEmpty()) {
        
        while ($loopset == true){
            $inventory = $getBatchProduct[$i]->inventory;
            
            echo $productamount.'<br>';
            echo $inventory.'<br>';
           // $batchInventoryLeft = $getBatchProduct[i]->inventory - $productamount;
           if($inventory >= $productamount)
           {
               $productamountleft = $inventory - $productamount;
               //$productamount = $productamountleft;
               //$loopset = false;
               echo $productamountleft.'Invntory to be mainatied in table<br>';
               $tableInv = true;
               //echo $inventoryLeft.'Inventory Left<br>';
               
               $newInv = $productamountleft;
           }
           else{
               $productamountleft = $productamount - $inventory;
               //$productamount = $productamountleft;
               echo $productamountleft.'Produc Amt else<br>';
               //echo $productamount.'<br>';
               
               $newInv = 0;
               
           }
           $loopset = true;
           $productamount =  $productamountleft;
           $deleteStatus = false;
           if(($productamount == 0 || $tableInv == true) && $loopset = false){
               echo $productamount.'asdasdsd<br>';
               $loopset = false;
               $deleteStatus = true;
           }
           if($productamountleft > 0){
               $getBatchProduct[$i]->inventory = 0;
           }
           
            //print_r($getBatchProduct[$i]->id);
             $id = $getBatchProduct[$i]->id;
              $batchInventoryUpdate = ProductInventoryBatch::find($id);
            print_r($productamount);
            //print_r($deleteStatus);
            //print_r($productamount);
            $batchInventoryUpdate->inventory = $newInv;
            //  $batchInventoryUpdate->deleteStatus = $deleteStatus;
            // // //print_r($batchInventoryUpdate);
              $batchInventoryUpdate->save();
            $i++;
            
            //$batchInventoryUpdate = $getBatchProduct[i]->inventory;
            //$id = $getBatchProduct[$i]->id;
           
            
            //print_r($id);
            //$batchInventoryUpdate = ProductInventoryBatch::find($id);
           //print_r($batchInventoryUpdate);
        }
        }
        

		
       
				
		//return response()->json(compact('getBatchProduct'))->header("Access-Control-Allow-Origin",  "*");
	}
	/* Update Stock Start */
 	public function updateStock(Request $request) { 
		$response = [
	        'expiryId'=>$request->expiryId,
	        'reduceQuantity'=>$request->reduceQuantity,
			'addValue'=>$request->addValue,
	        'productId'=>$request->productId
	    ];

		$inventoryLogs = new InventoryLogs;

		$updateProductInventory = Product::find($request->productId);

		$updateStocksBatch = ProductInventoryBatch::find($request->expiryId);
		
		/* Update Stock in the Table ProductInventoryBatch  */
		$updateStocksBatch->inventory = $updateStocksBatch->inventory - $request->reduceQuantity; 
		$updateStocksBatch->save();

		/* Update Stock in the Table Product  */
		$updateProductInventory->inventory = $updateProductInventory->inventory - $request->reduceQuantity; 
		$previousStock = $updateProductInventory->inventory;
		$updateProductInventory->save();
		/* Reason of Update Stock in the Table InventoryLogs */
		$inventoryLogs->productId = $request->productId;
		$inventoryLogs->expiryId = $request->expiryId;
		$inventoryLogs->reduceQuantity = $request->reduceQuantity;
		$inventoryLogs->reasonId = $request->reasonId;

		//$updateStock->inventory = $request->reason; 

		/*Product Logs Start*/
		$productLog = new ProductLogs;
	   
		//$productLog->userId = $request->userId;
		$productLog->userId = '';
		$productLog->productId = $request->productId;
		$productLog->previousStock = $previousStock;
		$productLog->newStock = $updateProductInventory->inventory - $request->reduceQuantity;
		$productLog->save(); 
		/*Product Logs End*/


		//$product->inventory = $request->inventory;


		
		$inventoryLogs->save();


		

		$status = 'success'; 
		return response()->json(compact('status','response'))->header("Access-Control-Allow-Origin",  "*");
	}
 

	public function moveStock(Request $request) { 
		$response = [
	        'fromexpiryId'=>$request->fromexpiryId,
	        'inexpiryId'=>$request->inexpiryId,
	        'reduceQuantity'=>$request->reduceQuantity,
	        'productId'=>$request->productId
	    ];
		$inventoryLogs = new InventoryLogs;
		
		//$updateProductInventory = Product::find($request->productId);
/* From In */
		$moveFromBatch = ProductInventoryBatch::find($request->fromexpiryId);
		$moveFromBatch->inventory = $moveFromBatch->inventory - $request->reduceQuantity; 
		$moveFromBatch->save();

		$moveFromBatch = ProductInventoryBatch::find($request->fromexpiryId);


		$moveInBatch = ProductInventoryBatch::find($request->inexpiryId);
		$moveInBatch->inventory = $moveInBatch->inventory + $request->reduceQuantity; 
		$moveInBatch->save();

		$moveInBatch = ProductInventoryBatch::find($request->inexpiryId);




		$status = 'success'; 
		return response()->json(compact('status','response'))->header("Access-Control-Allow-Origin",  "*");
	}




	/* Update Stock End */
	/* Manage Payment Start */
	public function userManagePayment(Request $request){

		$customerBal = Customer::find($request->customerId);

		$customersCredit = new CustomersCredit;
		//$customers_credit->customerId = $request->customerId;
		//$customers_credit->orderId = $request->orderId;
		//$customerBal->balanceDue = $request->balanceDue;
		$response = [
	        'customerId'=>$request->customerId,
	        'amountType'=>$request->amountType,
	        'paymentType'=>$request->paymentType,
	        'customerBalanceDue'=>$request->customerBalanceDue,
	        'requestAmount'=>$request->requestAmount
	        ];

		$paymentReturnType = $request->amountType ; //Amount is [(Added = DEBIT)(Mean Customer Pay to Store )] or [(Widthdraw = CREDIT)  (Mean Customer Debt to Store )  ]
		
		
		/* if($request->amountType == "withdrawal") {
			$customerBal->balanceDue = $request->customerBalanceDue + $request->requestAmount;
		
			 $customersCredit->type = 'Credit';
			
		} */
		if($request->amountType == "addamount") {
			$customerBal->balanceDue = -((-$customerBal->balanceDue) - $request->requestAmount);
			//$customerBal->balanceDue = $customerBal->balanceDue + $request->requestAmount;

			$customersCredit->type = 'CREDIT';
			
		}
		
		$customersCredit->orderNumber = "Amount Added"; 
		$customersCredit->balance = $request->requestAmount; 
		$customersCredit->customerId = $request->customerId; 
		$customerBal->save();

		//$customersCredit->orderNumber = $request->amountType;
		$customersCredit->save();
		
		$status = 'updated';
		


        return response()->json(compact('status','response','paymentReturnType'))->header("Access-Control-Allow-Origin",  "*");
		
	} 
	/* Manage Payment End */
	
	public function placeorderecr(Request $request)
	{
		// From App it will come as InStore or OutForDelivery
		$orderType = isset($request->orderType) ? $request->orderType : 'InStore';
		
	    $response = [
	        'userId'=>$request->userId,
	        'orderId'=>$request->orderId,
	        'storeId'=>$request->storeId,
	        'customerId'=>$request->customerId,
	        'orderDetail'=>$request->orderDetails,
	        'totalAmount'=>$request->totalAmount,
	        'balanceDue'=>$request->balanceDue,
			'orderType'=>$orderType,
			'paymentReturnType'=>$request->paymentReturnType,
			'paymentStatus'=>$request->paymentStatus
			//'paymentModes'=>$request->paymentModes,
			//'Count'=>count($request->paymentModes)
	        ];
	        
	    $checkOrderId = $request->orderId;
	    $checkResults = DB::table('orders_pos')
	    ->where('orderId','=',$checkOrderId)
	    ->where('storeId','=',$request->storeId)->get();
	    
	    $status = 'save';
	    
	    if(count($checkResults)>0) {
	        $status = 'duplicate';
			
	        return response()->json(compact('response','status'))->header("Access-Control-Allow-Origin",  "*");
	    }
        
        $cart = $request->orderDetail;
        
	    $ordersPos = new OrdersPos;
	    $ordersPos->userId = $request->userId;
	    //$ordersPos->userId = '87';
	    
	    $ordersPos->storeId = $request->storeId;
	    $ordersPos->customerId = $request->customerId;
	    $ordersPos->orderDetail = $cart;
	    $ordersPos->paymentStatus = $request->paymentStatus;
	    $ordersPos->totalAmount = $request->totalAmount;
	    $ordersPos->totalDiscount = $request->totalDiscount;
	    $ordersPos->cashBalance = $request->cashBalance;
	    $ordersPos->vat = $request->vat;
	    
	    $ordersPos->orderId = $request->orderId;
		
		// ORder Type: InStore, OutForDelivery
	    $ordersPos->orderType = $orderType;
	    
	    // Date will come from local but as it is not coming properly so we commented this.
	    //$ordersPos->refundDetail = $request->created_at;
	    $ordersPos->created_at = $request->created_at;
	    //$ordersPos->paymentModes = $request->paymentModes;
		//'paymentModes'=>$request->paymentModes
	    
	    
	    $ordersPos->save();
		
		$products = json_decode($cart);
		// Multiple Payment starts
		$multiplePayments = isset($request->paymentModes) ? $request->paymentModes : '';
		//$multiplePayments = $request->paymentModes;
		//$arrlen = count($multiplePayments);
		$paystatus = "MULTIPLE";
		$storeId = $request->storeId;
		if($request->paymentStatus == $paystatus){
		if(isset($request->paymentModes)){
			foreach($multiplePayments as $item) {
				//echo $item['filename'];
				//echo $item['filepath'];
				$updatePayment = new MultiplePayment;
				$updatePayment->orderId = $checkOrderId;
				$updatePayment->amount = $item['paymentValue'];
				$updatePayment->paymentMode = $item['paymentMode'];
				$updatePayment->storeId = $storeId;
				$updatePayment->save();
				//echo ($checkOrderId);
				// To know what's in $item
				//echo '<pre>'; var_dump($item);
			}
		}}
		
		
		// Multiple Payment End
		
		//echo count($products->products);
		$productsCount = count($products->products);
		
	    //$customerBal = new Customer;
	    //if(isset($request->customerId) && $request->customerId != '') {
	    if(isset($request->customerId) && $request->customerId != '') {
    	    $customerBal = Customer::find($request->customerId);
			
    	    //$customerBal->balanceDue = $request->balanceDue;
			if($request->paymentStatus == 'CREDIT') {
				$customerBal->balanceDue = $customerBal->balanceDue + ($request->totalAmount*-1); // Doing -1 multiplication as cashBalance is coming in negative
			}
			else if($request->paymentStatus == 'CASH' || $request->paymentStatus == 'CARD' ) {
				// Check Cash Balance and adjust as per paymentReturnType
				// if paymentReturnType is Credit then settle customerCredit amount else do nothing
				
				if(isset($request->paymentReturnType)) {
					if($request->paymentReturnType == 'customerCredit') {
						$customerBal->balanceDue = $customerBal->balanceDue + $request->cashBalance; // Doing addition as cashBalance is coming in negative
					}
				}
			}
			
			$customerBal->totalPurchase = $customerBal->totalPurchase + $request->totalAmount;
			
			// If per bill items are 10, 15, 35 then average is 20. Now new bill comes with 40 items so (20*3 + 40)/4 = 25
			$customerBal->avgItemPerBill = (($customerBal->avgItemPerBill * $customerBal->noOfBills) + $productsCount)/($customerBal->noOfBills+1);
			
			$customerBal->noOfBills = $customerBal->noOfBills + 1;
			$customerBal->lastVisit = $request->created_at;

    	    $customerBal->save();

			if($request->paymentStatus == 'CREDIT' || $request->paymentReturnType == 'customerCredit') {
				$customers_credit = new CustomersCredit;
				$customers_credit->customerId = $request->customerId;
				$customers_credit->orderNumber = $request->orderId;
				//$customers_credit->orderId = 'A10';
				
				
				//if($request->paymentReturnType == 'CREDIT') {
				if($request->paymentStatus == 'CREDIT') {
					$customers_credit->type = 'DEBIT';
					//$customers_credit->balance = $request->cashBalance;
					$customers_credit->balance = $request->totalAmount * -1; // Doing -1 multiplication as cashBalance is coming in negative
					//$customers_credit->balance = 10;
				}
				
				if($request->paymentStatus == 'customerCredit') {
					$customers_credit->type = 'CREDIT';
					$customers_credit->balance = $request->cashBalance; 
				}
				
				$customers_credit->save();
			}
			
			
			// Check paymentReturnType
	    }
	    
        // Convert JSON string to Array
        
        
        
        $discountedPrice = 0;
        $productVat = 0;
        foreach($products->products as $product) 
        {
           
            if(substr($product->id,0,1) != 'C') {
                $updateProduct = new Product;
    	        $updateProduct = Product::find($product->id);
    	        $productid = $updateProduct->id;
    	        
    	        $catId = $updateProduct->categoryId;
    
    	        $updateProduct->inventory = $updateProduct->inventory - $product->amount;
                $updateProduct->save();
                
				
				
				// Update Inventory in Batch Code Starts Here
				// Algo Steps:
				// 1) Find products based on Id order by expiry date. There may be more than one batch for same product.
				// 2) Check if batch stock is greater than and equal to order quantity(amount)
				// 3) If true simply reduce this stock.
				// 4) If new stock value is zero then change status of deleteStatus to true
				// 5) If false in step 2 then it means that order product quantity is 10 and batch balance quantity may be 8. then reduce 8 from this batch and 2 from next batch. This may goes longer until stock goes equivalent
			
				
				//$updateProductInventoryBatch = new ProductInventoryBatch;
				
				
				 $getBatchProduct = DB::Table('productInventoryBatch AS PIB')
                ->leftJoin('products','products.id','=','PIB.productId')
                ->select('PIB.expiryDate','PIB.productID','PIB.inventory','PIB.id','PIB.deleteStatus')
                ->where('PIB.productId',$productid)
               ->where('PIB.deleteStatus','!=','Yes')
                ->orderBy('PIB.expiryDate', 'ASC')
                ->get();

				if(count($getBatchProduct) > 0) {
		
					$loopset = true;
					$deleteStatus = false;
					$batchCount=0;
					$productAmount = 30;
					$reduceBatch = 0;
		   
					while ($loopset == true) {
						$batchInventoryUpdate = ProductInventoryBatch::find($getBatchProduct[$batchCount]->id);
						
						$inventory = $getBatchProduct[$batchCount]->inventory; // 20

					//echo '<br>Product Amount:: ' . $productAmount; // 10
					//echo '<br>Inventory in table:: ' . $inventory; // 20
				   
					if($inventory >= $productAmount)
					{
						$batchInventoryUpdate->inventory = $inventory - $productAmount;
						
						// Only set deleteStatus to true as the exact inventory is reduced. Else inventory will be left so no change in deleteStatus
						if($inventory == $productAmount) 
							$batchInventoryUpdate->deleteStatus = true;
							$batchInventoryUpdate->save();
							break;
					}
					else {
							$batchInventoryUpdate->inventory = 0;
							$batchInventoryUpdate->deleteStatus = true;
							$batchInventoryUpdate->save();
							$productAmount = $productAmount - $inventory;
						}
						$batchCount++;
						}
				}				
			
               
    	         
				
				// Update Inventory in Batch Code Ends Here
				
                
                // Make Entry in Reports Table for each product
                
                $updateReport = new Reports;
              /* $results = DB::Table('categories as C')
                ->leftJoin('categories_ar','categories_ar.categoryId','=','C.id')
        		->select('C.id','C.name','categories_ar.name')
        		->where('C.storeId',$request->storeId)
        		->where('C.typeadmin','=','pos')
        		->orderBy('O.id', 'DESC')->paginate(10);
        		*/
        		
        		$results = DB::Table('categories')
                ->leftJoin('categories_ar','categories_ar.categoryId','=','categories.id')
        		->select('categories.id','categories.name','categories_ar.name as nameArCat')
        		->where('categories.id','=',$catId)->first();
                
                
                $updateReport->storeId = $request->storeId;
                $updateReport->orderId = $ordersPos->id;
                $updateReport->orderNumber = $request->orderId;
                
                $updateReport->productName = utf8_encode($product->name);
                
                if(!empty($product->name_ar))
                    $updateReport->productNameAr = $product->name_ar;
                if(empty($product->name_ar))
                    $updateReport->productNameAr = '';
                $updateReport->categoryName = $results->name;
                $updateReport->categoryNameAr = $results->nameArCat;
                $updateReport->price = $product->sellingPrice;
                $updateReport->costPrice = $product->costPrice;
                $updateReport->quantity = $product->amount;
                
                
               $discountedPrice = $product->sellingPrice  - $product->sellingPrice*$product->discPer/100;
    	        
    	       $productVat = ($discountedPrice - ($discountedPrice/ (1+ ($product->tax/100)))) * $product->amount;
                
                
                $updateReport->vat = $productVat;
                $updateReport->discPer = $product->discPer;
                $updateReport->total = $product->total;
                
                $updateReport->barCode = $product->barCode;
                $updateReport->save();
            }
            
            
            if(substr($product->id,0,1) == 'C') {
    
                
                
                // Make Entry in Reports Table for each product
                
                $updateReport = new Reports;
        		
        		/*$results = DB::Table('categories')
                ->leftJoin('categories_ar','categories_ar.categoryId','=','categories.id')
        		->select('categories.id','categories.name','categories_ar.name as nameArCat')
        		->where('categories.id','=',$catId)->first();*/
                
                
                $updateReport->storeId = $request->storeId;
                $updateReport->orderId = $ordersPos->id;
                $updateReport->orderNumber = $request->orderId;
                
                $updateReport->productName = $product->name;
                
                if(!empty($product->name_ar))
                    $updateReport->productNameAr = $product->name_ar;
                if(empty($product->name_ar))
                    $updateReport->productNameAr = '';
                //$updateReport->categoryName = $results->name;
                //$updateReport->categoryNameAr = $results->nameArCat;
                $updateReport->price = $product->sellingPrice;
                $updateReport->costPrice = $product->costPrice;
                $updateReport->quantity = $product->amount;
                
                
                $discountedPrice = $product->sellingPrice  - $product->sellingPrice*$product->discPer/100;
    	        
    	        $productVat = ($discountedPrice - ($discountedPrice/ (1+ ($product->tax/100)))) * $product->amount;
                
                
                $updateReport->vat = $productVat;
                $updateReport->discPer = $product->discPer;
                $updateReport->total = $product->total;
                
                //$updateReport->barCode = $product->barCode;
                $updateReport->save();
            }
            
            
            
        }

        //echo $cart[0]["name"]; // Access Array data
        
        /*
        $someArray = $cart;
        print_r($someArray);
        //die();
        foreach($someArray as $key=>$value) { //foreach element in $arr
            $value->name;
        }
        echo $someArray;        // Dump all data of the Array
        //echo $someArray[0]["name"]; // Access Array data
	    die();
	    */
	    
	    
	    
	    /*
	    $orderId = 'TIJ' . ($ordersPos->id);
	    $created_at = $ordersPos->created_at;
	    $ordersPos->orderId = $orderId;
	    //$ordersPos->orderId =$request->orderId;
	    $ordersPos->save();
	    */
	    /*$created_at = $ordersPos->created_at;
	    $IDorder = $ordersPos->id;
	    
	     $results =  DB::Table('orders_pos')
		->select('created_at')
		->where('id','=',$IDorder)->first();*/
	    
        return response()->json(compact('response','status'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function processRefund(Request $request)
	{
	    $orderId = $request->orderId;
	    $storeId = $request->storeId;
	     
	     $response = [
	        'orderId'=>$orderId,
	        
	        'cart'=>$request->products
	        ];
	        
        $orderDetails = array();
        $orderDetails["products"] = $request->products;
        $cart = json_encode($orderDetails);
        
	    $ordersPos = new OrdersPos;
	    $ordersPos = OrdersPos::where('orderId','=',$orderId)->where('storeId','=',$storeId)->first();
	    
		//$ordersPos = OrdersPos::find($orderId);
	    
	    $ordersPos->refundDetail = $cart;
	    $ordersPos->orderStatus = 5; // Status Id of Refunded
	    
        // Convert JSON string to Array
        
        $products = json_decode($cart);
        
        $productRefundVat = 0;
        $refundVat = 0;
        $productRefundTotalAmount = 0;
        $refundTotalAmount = 0;
        
        foreach($products->products as $product) 
        {
            // Increase invenory of all refunded products
            // C Means custom item's
            if(substr($product->id,0,1) != 'C') {
                $updateProduct = new Product;
    	        $updateProduct = Product::find($product->id);
    	    
    	        $updateProduct->inventory = $updateProduct->inventory + $product->amount;
    	        
    	        $updateProduct->save();
            }
            
	        if(empty($product->discPer))
	            $product->discPer= 0;
	        $discountedPrice = $product->sellingPrice  - ($product->sellingPrice*$product->discPer/100);
	        
	        $productRefundVat = ($discountedPrice - ($discountedPrice/ (1+ ($product->tax/100)))) * $product->amount;
	        
	        $refundVat = $refundVat + $productRefundVat;
	        
	        $productRefundTotalAmount = ($discountedPrice * $product->amount);
	        $refundTotalAmount = $refundTotalAmount + $productRefundTotalAmount;

            

            //$updateReport = new Reports;
            
            
            if(!empty($product->barCode))
            {
                $updateReport = Reports::where('orderNumber',$orderId)
                ->where('productName',$product->name)
                ->where('barCode',$product->barCode)
                ->first();
            }
            if(empty($product->barCode))
            {
                $updateReport = Reports::where('orderNumber',$orderId)
                ->where('productName',$product->name)
                //->where('barCode',$product->barCode)
                ->first();
            }
            
            
            if($updateReport)
            {
                $updateReport->refundPrice = $discountedPrice;
                $updateReport->refundQuantity = $product->amount;
                $updateReport->refundVat = $productRefundVat;
                $updateReport->refundTotal= $productRefundTotalAmount ;
                $updateReport->save();
            }
        }

        $ordersPos->refundVat = round($refundVat,2);
	    $ordersPos->refundTotalAmount = round($refundTotalAmount,2);
	    
	    $ordersPos->save();
	    
	    $refundDetails = $ordersPos->refundDetail;
	    
	    $status = $ordersPos->orderStatus;
	   
	   //$response = 'success';
	    
        return response()->json(compact('refundDetails','status','response'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function refundBalace(Request $request)
	{
		$response = [
	        'customerId'=>$request->customerId,
	        'totalAmount'=>$request->totalAmount
	    ];
	        
		$Customer = Customer::find($request->customerId);
		$Customer->balanceDue = $Customer->balanceDue + $request->totalAmount;
        $Customer->save();

		$customersCredit = new CustomersCredit;
		$customersCredit->balance = $request->totalAmount; 
		$customersCredit->customerId = $request->customerId; 
		$customersCredit->orderNumber = 'R-'.$request->orderId; 
		$customersCredit->type = 'Credit'; 
		$customersCredit->save();

        
        $status = 'success';
		return response()->json(compact('status','response'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function cancelRefundOrder(Request $request)
	{
		$ordersPos = new OrdersPos;
		$ordersPos = OrdersPos::find($request->id);
		
        if($request->type == 'refund')
            $ordersPos->orderStatus = 5;
        else
            $ordersPos->orderStatus = 6;
            
        $products = json_decode($ordersPos->orderDetail);
        foreach($products->products as $product) 
        {
            $updateProduct = new Product;
	        $updateProduct = Product::find($product->id);
	    
	        $updateProduct->inventory = $updateProduct->inventory + $product->amount;
            $updateProduct->save();
        }   
        $ordersPos->save();
        
        $status = 'success';
        return response()->json(compact('ordersPos'))->header("Accesstores-Control-Allow-Origin",  "*");
	}
	
	public function placevendorinvoice(Request $request)
	{
	    $orderDetails = array();
        $orderDetails["products"] = $request->orderDetail;
        $cart = json_encode($orderDetails);
	    $response = [
	        'invoiceId'=>$request->invoiceId,
	        'vendorId'=>$request->vendorId,
	        'storeId'=>$request->storeId,
	        'status'=>$request->status,
	        'paymentMode'=>$request->paymentMode,
	        'totalAmount'=>$request->totalAmount,
	        'invoiceDate'=>$request->invoiceDate,
	        'vatAmount'=>$request->vatAmount
	        ];
	    $vendorInvoice = new VendorInvoice;
	    $vendorInvoice->invoiceId = $request->invoiceId;
	    $vendorInvoice->vendorId = $request->vendorId;
	    $vendorInvoice->storeId = $request->storeId;
	    $vendorInvoice->status = $request->status;
	    $vendorInvoice->paymentMode = $request->paymentMode;
	    $vendorInvoice->totalAmount = $request->totalAmount;
	    $vendorInvoice->invoiceDate = $request->invoiceDate;
	    $vendorInvoice->vatAmount = $request->vatAmount;
	    $vendorInvoice->orderDetail = $cart;
	    $vendorInvoice->save();
	    return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
        
	   
	}
	
	public function customerVisit($id)
	{
	    $checkDate = Carbon::now()->month;
	    $checkDateNow = Carbon::now();
	    $visits = DB::table('orders_pos')
                ->select(DB::raw('COUNT(id) as visits') )
                ->where('customerId',$id)
                ->where(DB::raw('Month(created_at)'),'=',$checkDate)
                ->first();
        $purchases = DB::table('orders_pos')
                ->select(DB::raw('AVG(totalAmount) as purchases'))
                ->where('customerId',$id)
                ->where(DB::raw('Month(created_at)'),'=',$checkDate)
                ->first();
        $lastpaid = DB::table('orders_pos')
                ->select('created_at as lastpaid')
                ->where('customerId',$id)
                ->orderBy('orders_pos.id', 'DESC')
                ->first();
                
        $firstvisit = DB::table('orders_pos')
                ->select('created_at as firstvisit')
                ->where('customerId',$id)
                ->orderBy('orders_pos.id', 'ASC')
                ->first();

        if(!empty($lastpaid)) {
            $purchaseDate = new Carbon($lastpaid->lastpaid);
            $dayslastpaid = $purchaseDate->diffInDays($checkDateNow);
        }
        else {
            $lastpaid = "-";
            $dayslastpaid = "-";
        }
        
         $results['visits'] = $visits;
         $results['purchases'] = $purchases;
         $results['lastpaid'] = $lastpaid;
         $results['dayslastpaid'] = $dayslastpaid;
         $results['firstvisit'] = $firstvisit;
        return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	    
	}
	
	public function editStore($id)
	{
		$results = DB::Table('stores')
		->leftJoin('users', 'users.id', '=', 'stores.userId')
		->select('stores.userId','stores.storeName','stores.address','stores.regNo','stores.address','stores.subscriptionExpiry','stores.defaultLang','stores.city','stores.manageInventory','stores.shopSize','stores.vatNumber','users.email','users.contactNumber','stores.printStoreNameEn','stores.printStoreNameAr','stores.printVat','stores.printAddEn','stores.printAddAr','stores.printPh','stores.printFooterEn','stores.printFooterAr','stores.printType','stores.paperSize','stores.cardPayment','stores.printerName')
		->where( 'stores.id',$id)
		->first();

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	/*public function updateStore(Request $request)
	{
	
	    $store = new Store;
	    $store = Store::find($request->id);
	    
	    
	    $response = [
	        'storeName'=>$request->storeName,
	        'contactNumber'=>$request->contactNumber,
	        'email'=>$request->email
	        
	        ];
		//echo $store;
        
        if(!empty($request->storeName))
            $store->storeName = $request->storeName;
	    
        $store->save();
        
        $user = User::select('id')->where('id', $request->input('id'))->first();
		if(!empty($request->contactNumber))
            $store->contactNumber = $request->contactNumber;
        
        if(!empty($request->email))
            $store->email = $request->email;
	    $user->save();
		
		return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	
	}*/
	public function updateShop(Request $request)
	{
	
	    //$store = new Store;
	    //$user = new User;
	    $store = Store::find($request->id);
	   
	    
        
        if(!empty($request->contactNumber)) {
            $userId = $store->userId;
	        $user= User::find($userId);
            //$user->contactNumber = $request->contactNumber;
            $name = explode(' ',$request->display_name);
            $user->email = $request->email;
            $user->firstName = $name[0];
    		unset($name[0]);
            $user->lastName = implode(' ',$name);
            $user->save(); 
        }
        
	    $response = [
	        'storeName'=>$request->storeName,
	        'address'=>$request->address,
	        'shopSize'=>$request->shopSize,
	        'vatNumber'=>$request->vatNumber,
	        'printPh'=>$request->printPh
	        
	        
	        ];
        
        
        if(!empty($request->storeName))
            $store->storeName = $request->storeName;
            
        /*if(!empty($request->regNo))
            $store->regNo = $request->regNo;
            
        if(!empty($request->manageInventory))
            $store->manageInventory = $request->manageInventory;*/
        
        
        
        if(!empty($request->address))
            $store->address = $request->address;
            
        if(!empty($request->printType))
            $store->printType = $request->printType;

        if(!empty($request->shopSize))
            $store->shopSize = $request->shopSize;
            
        if(!empty($request->city))
            $store->city = $request->city;
        
        if(!empty($request->vatNumber))
            $store->vatNumber = $request->vatNumber;
            
        if(!empty($request->printStoreNameEn))
            $store->printStoreNameEn = $request->printStoreNameEn;
        
        if(!empty($request->printStoreNameAr))
            $store->printStoreNameAr = $request->printStoreNameAr;
        
        if(!empty($request->printVat))
            $store->printVat = $request->printVat;
        
        if(!empty($request->printAddEn))
            $store->printAddEn = $request->printAddEn;
        
        if(!empty($request->printAddAr))
            $store->printAddAr = $request->printAddAr;
        
        if(!empty($request->printPh))
            $store->printPh = $request->printPh;
        
        if(!empty($request->printFooterEn))
            $store->printFooterEn = $request->printFooterEn;
        
        if(!empty($request->printFooterAr))
            $store->printFooterAr = $request->printFooterAr;
            
        if(!empty($request->printBillCount))
            $store->printBillCount = $request->printBillCount;
            
        if(!empty($request->printType))
            $store->printType = $request->printType;
            
        if(!empty($request->cardPayment))
            $store->cardPayment = $request->cardPayment;
		
		if(!empty($request->allowNegativeBill))
            $store->allowNegativeBill = $request->allowNegativeBill;
            
        if(!empty($request->printerName))
            $store->printerName = $request->printerName;
            
        if(!empty($request->paperSize))
            $store->paperSize = $request->paperSize;
            
        if(!empty($request->defaultLang))
            $store->defaultLang = $request->defaultLang;
            
        if(!empty($request->printerBarcode))
            $store->printerBarcode = $request->printerBarcode;
            
        /* Printer Logo */
        $storeLogo = "";
        $storeLogoBase64 = "";
        if($_FILES && isset($_FILES['file'])) 
        {
        	$destinationPath = "/home/majtechnosoft/public_html/posadmin/public/storelogos/";
        	$imgPre = time();
        
        	$storeLogo = $imgPre.basename( $_FILES['file']['name']);
        	
        	//$target_path = $destinationPath . $imgPre.basename( $_FILES['file']['name']);
        
        	$main_img = Image::make($_FILES['file']['tmp_name'])->fit(200, 200);
        	$main_img->save($destinationPath.$storeLogo,100);
        	
        	$thumb_img = Image::make($_FILES['file']['tmp_name'])->fit(50, 50);
        	$thumb_img = Image::make($_FILES['file']['tmp_name']);
        	$thumb_img->save($destinationPath.'50x50/'.$storeLogo,100);
        	
        	$data = file_get_contents($destinationPath.'50x50/'.$storeLogo);
        	$storeLogoBase64 = base64_encode($data);
        }
        
        
        
        
        
        
        
	    
        $store->save();
		
		return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
    
    
    
    public function salesreportemail()
	{
	    $type = $_GET['type'];
	    $storeId = $_REQUEST['storeId'];
		/* print_r($storeId);
		die; */

		/* if(!empty($_GET['start']))
		$startDate = $_GET['start'];
	else
		$startDate = Carbon::now()->toDateString();
	
	if(!empty($_GET['end']))
		$endDate = $_GET['end'];
	else
		$endDate = Carbon::now()->toDateString();
	     */
	    //echo $storeId;
	    
	    $storedata = DB::table('stores as S')
	     ->select('S.allReport','S.dayEndReport','S.storeName')
	     ->where('S.id','=',$storeId)
	     ->get();
	     

		/*  $results['fromDate'] = $startDate;
		 $results['toDate'] = $endDate; */
	     /*$storedata = DB::table('stores as S')
	     ->leftJoin('users','users.id','=','S.userId')
	     ->select('users.email')
	     ->where('S.id','=',$storeId)
	     ->get();*/
	     $storedataall = $storedata[0]->allReport;
	     $storedataday = $storedata[0]->dayEndReport;
	     $storename = $storedata[0]->storeName;
	     //$storedata = $storedata[0]->lowInventory;
	    /*  echo $storedata;
	     echo $storename;
        die; */
        
       
        if($type == 'today' || $type == 'billwisetoday')
        {
            $emailData["title"] = "Tijarah ECR Billwise Daily Sales Report";
             $emailData["email"] = "$storedataday";
        }
		else if($type == 'yesterday'){
			$emailData["title"] = "Tijarah ECR Yesterday Sales Report";
             $emailData["email"] = "$storedataday";
		}
		else if($type == 'thismonth'){
			$emailData["title"] = "Tijarah ECR this Month Sales Report";
             $emailData["email"] = "$storedataday";
		}
        
		else if($type == 'custom'|| $type == 'billwisecustom'){
			$emailData["title"] = "Tijarah ECR Billwise Custome Date Sales Report";
             $emailData["email"] = "$storedataday";
		}
        
        else if($type == 'catwisetoday' ){
		  
        
            $emailData["title"] = "Tijarah ECR Category Daily Sales Report";
             $emailData["email"] = "$storedataday";
        }
		else if( $type == 'catwiseyesterday' ){
			$emailData["title"] = "Tijarah ECR Category Yesterday Sales Report";
			$emailData["email"] = "$storedataday";
		}
		else if( $type == 'catwisecustom'){
			$emailData["title"] = "Tijarah ECR Category Custom Sales Report";
			$emailData["email"] = "$storedataday";
		}
        else if($type == 'catwisethismonth'){
			$emailData["title"] = "Tijarah ECR Category Monthly Sales Report";
			$emailData["email"] = "$storedataday";
		}
        
        else if($type == 'refundtoday' || $type == 'refundyesterday' || $type == 'refundcustom')
        { 
            $emailData["title"] = "Tijarah ECR Refund Report";
             $emailData["email"] = "$storedataday";
        }
        else if($type == 'vatlastsixmonths' || $type == 'vatcustom')
        {
            $emailData["title"] = "Tijarah ECR VAT Report";
             $emailData["email"] = "$storedataday";
        }
        
        else if($type == 'thismonth' || $type == 'billwisethismonth' || $type == 'custom' )
        {
            $emailData["title"] = "Tijarah ECR Billwise Monthly Sales Report";
            $emailData["email"] = "$storedataall";
        }
		else if($type == 'producttoday')
        {
            $emailData["title"] = "Tijarah ECR PrdouctWise Daily Sales Report";
            $emailData["email"] = "$storedataall";
        }
		else if($type == 'productthismonth' )
        {
            $emailData["title"] = "Tijarah ECR PrdouctWise Monthly Sales Report";
            $emailData["email"] = "$storedataall";
        }
		else if($type == 'productcustom')
        {
            $emailData["title"] = "Tijarah ECR PrdouctWise Custom Sales Report";
            $emailData["email"] = "$storedataall";
        }
        else if($type == 'quartely')
        {
            $emailData["title"] = "Tijarah ECR Quarterly Sales Report";
            $emailData["email"] = "$storedataall";
        }
        else if($type == 'lastsixmonths')
        {
            $emailData["title"] = "Tijarah ECR Last Six Months Sales Report";
            $emailData["email"] = "$storedataall";
        }
        $emailData["body"] = "Hello Store Owner<br>As per your request, we are sending you the sales report enclosed with this email.<br><br>Regards<br><br>Team<br>Tijarah ECR";
        //$emailData["email"] = "hemlata@majestictechnologies.net";
        //$emailData["email"] = "$storedata";
        /*
        $files = [
            public_path('files/160031367318.pdf'),
            public_path('files/1599882252.png'),
        ];
        */
        //$fileName = 'inventoryfile_' . $type . '.csv';
        $fileName = $storename.'_' . $type . '.csv';
		
        //$inventoryFile = Excel::store(new ProductExport(), $fileName);
        $inventoryFile =  Excel::store(new ReportExportEmail(), $fileName);
       /*  print_r( $inventoryFile);
		die; */
       // die;
        $files = [
            storage_path('app/' . $fileName)
        ];
  
        Mail::send('admin.emails.myTestMail', array('data' => $emailData), function($message)use($emailData, $files) {
            $message->to($emailData["email"], $emailData["email"])
                    ->subject($emailData["title"]);
 
            foreach ($files as $file){
                $message->attach($file);
            }
        });
          
          
        $status = "sent";
        
        return response()->json(compact('status'))->header("Access-Control-Allow-Origin",  "*");
        
	}

	public function vatReportEmail()
	{
		$type = $_GET['type'];
	    $storeId = $_REQUEST['storeId'];

	   
	    
	    $storedata = DB::table('stores as S')
	     ->select('S.allReport','S.dayEndReport','S.storeName')
	     ->where('S.id','=',$storeId)
	     ->get();
	     
	     $storedataall = $storedata[0]->allReport;
	     $storedataday = $storedata[0]->dayEndReport;
	     $storename = $storedata[0]->storeName;
        
		 if($type == 'vattoday')
		 {
			 $emailData["title"] = "Tijarah ECR Daily Vat Report";
			  $emailData["email"] = "$storedataday";
		 }

		 else if($type == 'vatyesterday')
		 {
			 $emailData["title"] = "Tijarah ECR Yesterday Vat Report";
			 $emailData["email"] = "$storedataall";
		 }
		 else if($type == 'vatthismonth')
		 {
			 $emailData["title"] = "Tijarah ECR Monthly Vat Report";
			 $emailData["email"] = "$storedataall";
		 }
		 else if($type == 'vatcustom')
		 {
			 $emailData["title"] = "Tijarah ECR Custom Date Vat Report";
			 $emailData["email"] = "$storedataall";
		 }
		 
		
		 $emailData["body"] = "Hello Store Owner<br>As per your request, we are sending you the Vat report enclosed with this email.<br><br>Regards<br><br>Team<br>Tijarah ECR";
		 //$emailData["email"] = "hemlata@majestictechnologies.net";
		 //$emailData["email"] = "$storedata";
		 /*
		 $files = [
			 public_path('files/160031367318.pdf'),
			 public_path('files/1599882252.png'),
		 ];
		 */

		 
	   
		 //$fileName = 'inventoryfile_' . $type . '.csv';
		 $fileName = $storename.'_' . $type . '.csv';
		 //$inventoryFile = Excel::store(new ProductExport(), $fileName);
		 $inventoryFile =  Excel::store(new ReportExportEmail(), $fileName);
		 
		// die;
		 $files = [
			 storage_path('app/' . $fileName)
		 ];
   

		 /* Test Code Start */ 
		/* $status = "Stop";
		return response()->json(compact('status'))->header("Access-Control-Allow-Origin",  "*"); */
	   /* Test Code End */ 

		 Mail::send('admin.emails.myTestMail', array('data' => $emailData), function($message)use($emailData, $files) {
			 $message->to($emailData["email"], $emailData["email"])
					 ->subject($emailData["title"]);
  
			 foreach ($files as $file){
				 $message->attach($file);
			 }
		 });
		   
		   
		 $status = "sent";
		 
		 return response()->json(compact('status'))->header("Access-Control-Allow-Origin",  "*");

	}

    public function refundReportEmail(){
		$type = $_GET['type'];
	    $storeId = $_REQUEST['storeId'];
		
	  
	    //echo $storeId;
	    
	    $storedata = DB::table('stores as S')
	     ->select('S.allReport','S.dayEndReport','S.storeName')
	     ->where('S.id','=',$storeId)
		 
		
	     ->get();
	     
	     /*$storedata = DB::table('stores as S')
	     ->leftJoin('users','users.id','=','S.userId')
	     ->select('users.email')
	     ->where('S.id','=',$storeId)
	     ->get();*/
	     $storedataall = $storedata[0]->allReport;
	     $storedataday = $storedata[0]->dayEndReport;
	     $storename = $storedata[0]->storeName;
	     //$storedata = $storedata[0]->lowInventory;
	     //echo $storedata;
        //die;
        
       
        if($type == 'refundtoday')
        {
			
            $emailData["title"] = "Tijarah ECR Daily Refund Report";
             $emailData["email"] = "$storedataday";
        }
          
        else if($type == 'refundyesterday')
        {
            $emailData["title"] = "Tijarah ECR Yesterday Refund Report";
             $emailData["email"] = "$storedataday";
        }
      
        else if($type == 'refundcustom')
        {
            $emailData["title"] = "Tijarah ECR Custom Date Refund Report";
            $emailData["email"] = "$storedataall";
        }
		
       
        $emailData["body"] = "Hello Store Owner<br>As per your request, we are sending you the refund report enclosed with this email.<br><br>Regards<br><br>Team<br>Tijarah ECR";
        //$emailData["email"] = "hemlata@majestictechnologies.net";
        //$emailData["email"] = "$storedata";
        /*
        $files = [
            public_path('files/160031367318.pdf'),
            public_path('files/1599882252.png'),
        ];
        */
        //$fileName = 'inventoryfile_' . $type . '.csv';
        $fileName = $storename.'_' . $type . '.csv';
        //$inventoryFile = Excel::store(new ProductExport(), $fileName);
        $inventoryFile =  Excel::store(new ReportExportEmail(), $fileName);
        
       // die;
        $files = [
            storage_path('app/' . $fileName)
        ];
  
        Mail::send('admin.emails.myTestMail', array('data' => $emailData), function($message)use($emailData, $files) {
            $message->to($emailData["email"], $emailData["email"])
                    ->subject($emailData["title"]);
 
            foreach ($files as $file){
                $message->attach($file);
				
            }
        });
          
          
        $status = "sent";
        
        return response()->json(compact('status'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function mediaReportemail()
	{
	    $type = $_GET['type'];
	    $storeId = $_REQUEST['storeId'];
	    
	    //echo $storeId;
	    
	    $storedata = DB::table('stores as S')
	     ->select('S.allReport','S.dayEndReport','S.storeName')
	     ->where('S.id','=',$storeId)
	     ->get();
	     
	     /*$storedata = DB::table('stores as S')
	     ->leftJoin('users','users.id','=','S.userId')
	     ->select('users.email')
	     ->where('S.id','=',$storeId)
	     ->get();*/
	     $storedataall = $storedata[0]->allReport;
	     $storedataday = $storedata[0]->dayEndReport;
	     $storename = $storedata[0]->storeName;
	     //$storedata = $storedata[0]->lowInventory;
	     //echo $storedata;
        //die;
        
       
        if($type == 'mediatoday')
        {
            $emailData["title"] = "Tijarah ECR Media Report";
             $emailData["email"] = "$storedataday";
        }
               
        else if($type == 'mediacustom')
        {
            $emailData["title"] = "Tijarah ECR Refund Report";
             $emailData["email"] = "$storedataday";
        }
        else if($type == 'medialastsixmonths' || $type == 'mediacustom')
        {
            $emailData["title"] = "Tijarah ECR VAT Report";
             $emailData["email"] = "$storedataday";
        }
        
        else if($type == 'custom' || $type == 'mediacustom')
        {
            $emailData["title"] = "Tijarah ECR Monthly Sales Report";
            $emailData["email"] = "$storedataall";
        }
        else if($type == 'quartely')
        {
            $emailData["title"] = "Tijarah ECR Quarterly Sales Report";
            $emailData["email"] = "$storedataall";
        }
        else if($type == 'lastsixmonths')
        {
            $emailData["title"] = "Tijarah ECR Last Six Months Sales Report";
            $emailData["email"] = "$storedataall";
        }
        $emailData["body"] = "Hello Store Owner<br>As per your request, we are sending you the Media report enclosed with this email.<br><br>Regards<br><br>Team<br>Tijarah ECR";
        //$emailData["email"] = "hemlata@majestictechnologies.net";
        //$emailData["email"] = "$storedata";
        /*
        $files = [
            public_path('files/160031367318.pdf'),
            public_path('files/1599882252.png'),
        ];
        */
        //$fileName = 'inventoryfile_' . $type . '.csv';
        $fileName = $storename.'_' . $type . '.csv';
        //$inventoryFile = Excel::store(new ProductExport(), $fileName);
        $inventoryFile =  Excel::store(new ReportExportEmail(), $fileName);
        
       // die;
        $files = [
            storage_path('app/' . $fileName)
        ];
  
        Mail::send('admin.emails.myTestMail', array('data' => $emailData), function($message)use($emailData, $files) {
            $message->to($emailData["email"], $emailData["email"])
                    ->subject($emailData["title"]);
 
            foreach ($files as $file){
                $message->attach($file);
            }
        });
          
          
        $status = "sent";
        
        return response()->json(compact('status'))->header("Access-Control-Allow-Origin",  "*");
        
	}
	public function cashierReportemail()
	{
	    $type = $_GET['type'];
	    $storeId = $_REQUEST['storeId'];
	    
	    //echo $storeId;
	    
	    $storedata = DB::table('stores as S')
	     ->select('S.allReport','S.dayEndReport','S.storeName')
	     ->where('S.id','=',$storeId)
	     ->get();
	     
	     /*$storedata = DB::table('stores as S')
	     ->leftJoin('users','users.id','=','S.userId')
	     ->select('users.email')
	     ->where('S.id','=',$storeId)
	     ->get();*/
	     $storedataall = $storedata[0]->allReport;
	     $storedataday = $storedata[0]->dayEndReport;
	     $storename = $storedata[0]->storeName;
	     //$storedata = $storedata[0]->lowInventory;
	     //echo $storedata;
        //die;
        
       
        if($type == 'cashiercustom')
        {
            $emailData["title"] = "Tijarah ECR Cashier Report";
             $emailData["email"] = "$storedataday";
        }
               
       
        $emailData["body"] = "Hello Store Owner<br>As per your request, we are sending you the sales report enclosed with this email.<br><br>Regards<br><br>Team<br>Tijarah ECR";
        //$emailData["email"] = "hemlata@majestictechnologies.net";
        //$emailData["email"] = "$storedata";
        /*
        $files = [
            public_path('files/160031367318.pdf'),
            public_path('files/1599882252.png'),
        ];
        */
        //$fileName = 'inventoryfile_' . $type . '.csv';
        $fileName = $storename.'_' . $type . '.csv';
        //$inventoryFile = Excel::store(new ProductExport(), $fileName);
        $inventoryFile =  Excel::store(new ReportExportEmail(), $fileName);
        
       // die;
        $files = [
            storage_path('app/' . $fileName)
        ];
  
        Mail::send('admin.emails.myTestMail', array('data' => $emailData), function($message)use($emailData, $files) {
            $message->to($emailData["email"], $emailData["email"])
                    ->subject($emailData["title"]);
 
            foreach ($files as $file){
                $message->attach($file);
            }
        });
          
          
        $status = "sent";
        
        return response()->json(compact('status'))->header("Access-Control-Allow-Origin",  "*");
        
	}
	public function profitLossReportemail()
	{
	    $type = $_GET['type'];
	    $storeId = $_REQUEST['storeId'];
	    
		
	    //echo $storeId;
	    
	    $storedata = DB::table('stores as S')
	     ->select('S.allReport','S.dayEndReport','S.storeName')
	     ->where('S.id','=',$storeId)
	     ->get();
	     
	     /*$storedata = DB::table('stores as S')
	     ->leftJoin('users','users.id','=','S.userId')
	     ->select('users.email')
	     ->where('S.id','=',$storeId)
	     ->get();*/
	     $storedataall = $storedata[0]->allReport;
	     $storedataday = $storedata[0]->dayEndReport;
	     $storename = $storedata[0]->storeName;
	     //$storedata = $storedata[0]->lowInventory;
	     //echo $storedata;
        //die;
        
       
        if($type == 'profitmargin' || $type == 'profitlosscustom')
        {
            $emailData["title"] = "Tijarah ECR Profit & Loss Report";
            $emailData["email"] = "$storedataday";
        }
        $emailData["body"] = "Hello Store Owner<br>As per your request, we are sending you the profit & loss report enclosed with this email.<br><br>Regards<br><br>Team<br>Tijarah ECR";
        //$emailData["email"] = "hemlata@majestictechnologies.net";
        //$emailData["email"] = "$storedata";
        /*
        $files = [
            public_path('files/160031367318.pdf'),
            public_path('files/1599882252.png'),
        ];
        */
        //$fileName = 'inventoryfile_' . $type . '.csv';
        $fileName = $storename.'_' . $type . '.csv';
        //$inventoryFile = Excel::store(new ProductExport(), $fileName);
        $inventoryFile =  Excel::store(new ReportExportEmail(), $fileName);
        
       // die;
        $files = [
            storage_path('app/' . $fileName)
        ];
  
        Mail::send('admin.emails.myTestMail', array('data' => $emailData), function($message)use($emailData, $files) {
            $message->to($emailData["email"], $emailData["email"])
                    ->subject($emailData["title"]);
 
            foreach ($files as $file){
                $message->attach($file);
            }
        });
          
          
        $status = "sent";
        
        return response()->json(compact('status'))->header("Access-Control-Allow-Origin",  "*");
        
	}
	public function shiftReportemail()
	{
	    $type = $_GET['type'];
	    $storeId = $_REQUEST['storeId'];
	    
	    //echo $storeId;
	    
	    $storedata = DB::table('stores as S')
	     ->select('S.allReport','S.dayEndReport','S.storeName')
	     ->where('S.id','=',$storeId)
	     ->get();
	     
	     /*$storedata = DB::table('stores as S')
	     ->leftJoin('users','users.id','=','S.userId')
	     ->select('users.email')
	     ->where('S.id','=',$storeId)
	     ->get();*/
	     $storedataall = $storedata[0]->allReport;
	     $storedataday = $storedata[0]->dayEndReport;
	     $storename = $storedata[0]->storeName;
	     //$storedata = $storedata[0]->lowInventory;
	     //echo $storedata;
        //die;
        
       
        if($type == 'today')
        {
            $emailData["title"] = "Tijarah ECR Daily Sales Report";
             $emailData["email"] = "$storedataday";
        }
               
        else if($type == 'shiftcustom')
        {
            $emailData["title"] = "Tijarah ECR Refund Report";
             $emailData["email"] = "$storedataday";
        }
        else if($type == 'shiftlastsixmonths' || $type == 'shiftcustom')
        {
            $emailData["title"] = "Tijarah ECR VAT Report";
             $emailData["email"] = "$storedataday";
        }
        
        else if($type == 'custom' || $type == 'shiftcustom')
        {
            $emailData["title"] = "Tijarah ECR Monthly Sales Report";
            $emailData["email"] = "$storedataall";
        }
        else if($type == 'quartely')
        {
            $emailData["title"] = "Tijarah ECR Quarterly Sales Report";
            $emailData["email"] = "$storedataall";
        }
        else if($type == 'lastsixmonths')
        {
            $emailData["title"] = "Tijarah ECR Last Six Months Sales Report";
            $emailData["email"] = "$storedataall";
        }
        $emailData["body"] = "Hello Store Owner<br>As per your request, we are sending you the sales report enclosed with this email.<br><br>Regards<br><br>Team<br>Tijarah ECR";
        //$emailData["email"] = "hemlata@majestictechnologies.net";
        //$emailData["email"] = "$storedata";
        /*
        $files = [
            public_path('files/160031367318.pdf'),
            public_path('files/1599882252.png'),
        ];
        */
        //$fileName = 'inventoryfile_' . $type . '.csv';
        $fileName = $storename.'_' . $type . '.csv';
        //$inventoryFile = Excel::store(new ProductExport(), $fileName);
        $inventoryFile =  Excel::store(new ReportExportEmail(), $fileName);
        
       // die;
        $files = [
            storage_path('app/' . $fileName)
        ];
  
        Mail::send('admin.emails.myTestMail', array('data' => $emailData), function($message)use($emailData, $files) {
            $message->to($emailData["email"], $emailData["email"])
                    ->subject($emailData["title"]);
 
            foreach ($files as $file){
                $message->attach($file);
            }
        });
          
          
        $status = "sent";
        
        return response()->json(compact('status'))->header("Access-Control-Allow-Origin",  "*");
        
	}
    public function emailOrderDetail($id)
    {

        $results =DB::Table('orders_pos as O')->leftJoin('users','users.id','=','O.userId')
		->leftJoin('customers','customers.id','=','O.customerId')
		->leftJoin('mas_statustype AS MS','MS.id','=','O.orderStatus')
		->select('users.email',DB::raw('DATE_FORMAT(O.created_at, "%d %M, %Y %h:%i %p") as placed'),'O.id','O.orderId','O.orderDetail','O.refundDetail','O.orderStatus','O.totalAmount','O.paymentStatus','MS.statusName','customers.customerName','customers.contactNumber')
		->where('O.id', $id)->first();
		
		
		//print_r($results);
		//$results = json_decode($results);
	    //print_r($results->email);
	     
	    //die;
	     /*$storedata = DB::table('stores as S')
	     ->leftJoin('users','users.id','=','S.userId')
	     ->select('users.email')
	     ->where('S.id','=',$storeId)
	     ->get();*/
	     $orderemail = $results->email;
		
		//print_r($results);
		//$results = json_decode($results);

		//var_dump(json_decode($response)->results[0]->geometry->location->lat);
        //$results = $results[0];
		$orderDetail = $results->orderDetail;
		$orderDetail = json_decode($orderDetail);
		//print_r($orderDetail);
		$orderDetail = $orderDetail->products;
		//print_r($orderDetail);
		
		$pdf = PDF::loadView('admin.emails.preview',compact('results','orderDetail'))->setPaper('a4', 'landscape');
        
        //echo storage_path();
        //echo $orderId;
        //die;
        Storage::put('app/' . $id . '.pdf', $pdf->output());
        $files = 
        [
            //Storage::put(public_path('invoice.pdf'), $pdf->output()))
            
            storage_path('app/app/' . $results->id . '.pdf')
            
        ];
	    /*echo $orderId;
	    
		$emailData["body"] = "Hello Store Owner<br>As per your request, we are sending you the refund report enclosed with this email.<br><br>Regards<br><br>Team<br>Tijarah ECR";
        $emailData["email"] = "hemlata@majestictechnologies.net";
        $emailData["title"] = "Order Detail";*/
        
        
        $data["email"] = $orderemail;
        $data["title"] = "Invoice Report";
        $data["body"] = "Hello Customer<br>This is a invoice email for your order.<br><br>Regards<br><br>Team<br>ExxonMobil";
        $files = [
           storage_path('app/app/' . $results->id . '.pdf')
        ];
        
       // $this->emailto = "hemlata@majestictechnologies.net";
	   //$this->nameto = "Hemlata";
		
		
		Mail::send('admin.emails.myTestMail', array('data' => $data), function($message)use($data, $files) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"]);
 
            foreach ($files as $file){
                $message->attach($file);
            }
        });
        
        
        /*
        Mail::send('admin.emails.email', array('data' => $data), function($message)use($data, $files) {
            $message->from('hemlata@majestictechnologies.net');
            $message->to($this->emailto, $this->nameto)
                    ->subject('Tiajarh Invoice Order');
 
            foreach ($files as $file){
                $message->attach($file);
            }
        });*/
        
        
        
		$status = "sent";
        
        return response()->json(compact('status'))->header("Access-Control-Allow-Origin",  "*");
		
        
    }
    
    public function updateSetting($id)
    {
        $results = DB::Table('settings')
		->select('settings.descriptionAr')
		->where( 'settings.id',2)
		->first();

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
    
    
    public function settings(Request $request)
	{
	    $setting = new Settings;
	    $setting = Settings::find(2);

        $setting->descriptionAr = $request->descriptionAr;
        $setting->save();
	    $response = [
	        'descriptionAr'=>$request->descriptionAr
	        ];
	  return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	}
    
    
    public function paymentTransaction($storeId)
    {
        $now = date('Y-m-d');
        $results = DB::Table('orders_pos')
            ->select('paymentStatus', DB::raw("ROUND(SUM(totalAmount) - SUM(refundTotalAmount),2) as totalAmount"))
            ->where('storeId',$storeId)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),$now)
            ->groupBy('paymentStatus')
            ->get();

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
    }
    
    public function summarySales($storeId)
    {
        $now = date('Y-m-d');
        $results = DB::Table('orders_pos')
            ->select(DB::raw("ROUND(SUM(totalAmount) - SUM(refundTotalAmount),2) - (SUM(vat) - SUM(refundVat)) as net"),DB::raw("ROUND(SUM(totalDiscount),2) as discount"),DB::raw("ROUND(SUM(vat) - SUM(refundVat),2) as vat"), DB::raw("ROUND(SUM(totalAmount) - SUM(refundTotalAmount)) - (SUM(vat) +  SUM(refundVat)) + SUM(totalDiscount) + (SUM(vat) +  SUM(refundVat)) as gross"))
            ->where('storeId',$storeId)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),$now)
            //->groupBy('paymentStatus')
            //->groupBy('created_at')
            ->get();

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
    }
    
    public function appUpdates($id)
    {
        $results = DB::Table('stores as S')
       	->leftJoin('app_update as AU','AU.id','=','S.appVersionUpdate')
        ->select('S.recoverOrders','S.recoverProducts','AU.appType','AU.appVer','AU.appCode','AU.appfile as appUpdateFile',DB::raw('CONCAT("http://www.majestictechnosoft.com/posadmin/public/apk/", AU.appfile) AS appUpdateFile'))
        ->where('S.id', $id)->first();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
    }
    
    public function updateVersion(Request $request)
    {
        /*
        $store = Store::find($request->id);
        $store->appVersionUpdate = $request->appVersionUpdate;
        $store->save();*/
        
        $appType = $request->appType;
        
        if($appType == 'miniECR') {
            $appType = 'Mini';
        }
        else if($appType == 'plusECR') {
            $appType = 'Plus';
        }
        
        $store = Store::find($request->id);
        $store->appVersion = $request->appVersion;
        $store->appType = $appType;
        $store->deviceType = $request->appDevice;
        $store->save();
    }
    
    
    public function listCashier($storeId)
    {
        $results = DB::Table('cashier as C')->leftJoin('users', 'users.id', '=', 'C.userId')
		->leftJoin('stores','stores.id','=','C.storeId')
		->select('C.id','users.firstName','users.lastName','users.contactNumber','stores.storeName','users.email','users.status')
		->where('users.roleId','=','7')
		->where('C.storeId',$storeId)
		->orderBy('C.id', 'DESC')->get();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
    }
    
    
    public function editCashier($cashierid)
	{
		
		$results = DB::Table('cashier as C')->leftJoin('users', 'users.id', '=', 'C.userId')
		->select('C.id','users.firstName','users.lastName','users.contactNumber','users.email','users.status')
		->where('C.id',$cashierid)
		->orderBy('C.id', 'DESC')->first();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function updateCashier(Request $request)
	{
	    $user = new User;
        $cashier = new Cashier;
        
        $cashier = Cashier::find($request->id);
    		
    	$userId = $cashier->userId;
    		
		$email = $request->email;
		
		
		$duplicateUser = DB::Table('users')
        ->where('contactNumber',$request->contactNumber)
        ->where(function ($query) use ($email) {
            $query->orwhere('email', $email);
        })
        ->where('id','!=', $userId)->get();
        
        
		$response = [
	        'id'=>$request->id,
	        'userId'=>$userId,
	        'contactNumber'=>$request->contactNumber,
	        'email'=>$request->email,
	        'firstName'=>$request->firstName,
	        'lastName'=>$request->lastName,
	        'passwordValue'=>$request->passwordValue
	        
	        ];
		
		
		if(count($duplicateUser)>0) {
		    $status = 'duplicate';
		}
		else {
    		$user= User::find($userId);
    		
    		if(!empty($request->firstName))
    		    $user->firstName = $request->firstName;
    		    
    		if(!empty($request->lastName))
    		    $user->lastName = $request->lastName;
    		
    		if(!empty($request->email))
    		    $user->email = $request->email;
    		
    		if(!empty($request->contactNumber))
    		    $user->contactNumber = $request->contactNumber;
    		
    		if(!empty($request->passwordValue))
    		    $user->password = Hash::make($request->passwordValue);
    	
    	    //$user->password = Hash::make('abc123');
            $user->save();
            
            $status = 'updated';
		}
        
        return response()->json(compact('status','duplicateUser','response','cashier'))->header("Access-Control-Allow-Origin",  "*");
        
	}
	
	public function addCashier(Request $request)
	{
	    $email = $request->email;
	    
		$duplicateUser = DB::Table('users')
        ->where('contactNumber',$request->contactNumber)
        ->orwhere('email', $email)->get();
		
		if(count($duplicateUser)>0)  {
		    $status = 'duplicate';
		}
		else {
            $user = new User;
            $cashier = new Cashier;
            
            $user->firstName = $request->firstName;
            $user->lastName = $request->lastName;
            $user->password = Hash::make($request->passwordValue);
            $user->email = $request->email;
            $user->contactNumber = $request->contactNumber;
            //$user->status = $request->status;
            $user->roleId = '7';
            $user->save(); 
            
            $userId = $user->id;
            $cashier->userId = $userId;
            
            $cashier->storeId = $request->storeId;
            
            $cashier->save();
            
            $status = 'added';
		}
	   
	   return response()->json(compact('status'))->header("Access-Control-Allow-Origin",  "*");
	}
    
    /* Manage Store Users in App Starts */
    public function listStoreUsers($storeId)
    {
        //7: Cashier
        //8: Store Admin
		//9: Store Manager
		
		$userRole = $_GET['userRole'];
		
		$searchRoles = array();
		if($userRole == 'all') {
		    $searchRoles = [7, 9, 10, 13];
		}
		else if($userRole == 'cashier') {
		    $searchRoles = [7];
		}
		else if($userRole == 'storeadmin') {
		    $searchRoles = [9];
		}
		else if($userRole == 'storemanager') {
		    $searchRoles = [10];
		}
		else if($userRole == 'deliveryperson') {
		    $searchRoles = [13];
		}
			
		$results = DB::Table('cashier as C')->leftJoin('users as U', 'U.id', '=', 'C.userId')
		->leftJoin('mas_role','mas_role.id','=','U.roleId')
		->select('C.id','U.firstName','U.lastName','U.contactNumber','mas_role.name as role','mas_role.name_ar as roleAr','U.email','U.status')
		->whereIn('U.roleId',$searchRoles)
		->where('C.storeId',$storeId)
		->orderBy('C.id', 'DESC')->get();
		
		//die;
		$massRoles = DB::Table('mas_role as MS')->select('MS.id', 'MS.name')->whereIn('MS.id', [7, 9, 10])->get();

		
		//return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
    }
	public function getDeliveryPerson($storeId){
		
		$results = DB::Table('cashier as C')->leftJoin('users as U', 'U.id', '=', 'C.userId')
		->select('U.id', DB::raw('upper(CONCAT(U.firstName, " ", U.lastName)) AS name'))
		->where('U.roleId', 13)
		->where('C.storeId',$storeId)
		->orderBy('name', 'ASC')->get();

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	//getting expirydate for update button******start
	
	public function getExpiryDate($id){
		
		$results = DB::Table('productInventoryBatch as PI')
		->select('PI.id','PI.productId', 'PI.expiryDate', 'PI.inventory')
		//->where('PI.inventory','>' '0')
		->where('PI.productId',$id)
		->orderBy('expiryDate', 'ASC')->get();

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function getupdatestockExpiryDate($id){
		
		$results = DB::Table('productInventoryBatch as PI')
		->select('PI.id','PI.productId', 'PI.expiryDate', 'PI.inventory')
		->where('PI.inventory','>', '0')
		->where('PI.productId',$id)
		->orderBy('expiryDate', 'ASC')->get();

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function getMoveFromExpiryDate($id){
		
		$results = DB::Table('productInventoryBatch as PI')
		->select('PI.id','PI.productId', 'PI.expiryDate', 'PI.inventory')
		->where('PI.inventory','>', '0')
		->where('PI.productId',$id)
		->orderBy('expiryDate', 'ASC')->get();

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	//getting expirydate for update button******end
	
	
	/*Master Table Reason Start  */	
	public function getStockReason(){
		$results = DB::Table('mas_reason')->where('mas_reason.type', '=', 'stock')->get();

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}

/* Get products */
	public function getProducts(){
		$results = DB::Table('products')
		->select('products.id', 'products.name', 'products.barcode')
		->limit(50)
		->get();		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}



	/*Master Table Reason End  */	

	/*Get Shift-Start  Reason Start */	
	public function shiftInReason(){
		$results = DB::Table('mas_reason')->where('mas_reason.type', '=', 'shiftIn')->get();

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	/*Get Shift-Start  Reason End  */	

	/*Get Shift-End  Reason Start */	
	public function shiftEndReason(){
		$results = DB::Table('mas_reason')->where('mas_reason.type', '=', 'shiftEnd')->get();

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	/*Get Shift-End  Reason End  */	

	public function addStoreUser(Request $request)
	{
	    $email = $request->email;
	    
		$duplicateUser = DB::Table('users')
        ->where('contactNumber',$request->contactNumber)
        ->orwhere('email', $email)->get();
		
		if(count($duplicateUser)>0)  {
		    $status = 'duplicate';
		}
		else {
            $user = new User;
            $cashier = new Cashier;
            
            $user->firstName = $request->firstName;
            $user->lastName = $request->lastName;
            $user->password = Hash::make($request->passwordValue);
            $user->email = $request->email;
            $user->contactNumber = $request->contactNumber;
            //$user->status = $request->status;
            $user->roleId = $request->roleId;
            $user->save(); 
            
            // If User is Cashier add details in Cashier table also.
           
			$userId = $user->id;
			$cashier->userId = $userId;
			$cashier->storeId = $request->storeId;
			$cashier->shiftId = $request->shiftId;
			
			$cashier->save();
            
            
            $status = 'added';
		}
	   
	   return response()->json(compact('status'))->header("Access-Control-Allow-Origin",  "*");
	}
    
    public function editStoreUser( $id)
	{
		$results = DB::Table('cashier as C')
         ->leftJoin('users', 'users.id', '=', 'C.userId')
         ->select('users.firstName','users.lastName','users.email','C.id','users.contactNumber','users.status','C.storeId','C.shiftId', 'users.roleId', 'C.storeId')
        ->where('C.id', $id)
		->first();

		/*$results = DB::Table('users as U')->leftJoin('stores as S','S.userId','=','U.id')
		->select('U.id','U.firstName','U.lastName','U.contactNumber','U.email','U.status')
		->whereIN('U.roleId', $searchRoles)
		->where('S.id', $storeId)->first();*/
		
		//$store = Store::orderBy('id', 'DESC')->get();
		//$shift = Shift::orderBy('id', 'DESC')->get();
		/*$shifts = DB::Table('shifts')
		->leftJoin('stores','stores.id','=','shifts.storeId')
		->select('shifts.id','shifts.title')
		->where('shifts.storeId',$id)
		->orderBy('shifts.id', 'DESC')
		->get();*/

		//$results = DB::Table('mas_role as MS')->select('MS.id', 'MS.name')->whereIn('MS.id', [7, 9, 10])->get();


		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function updateStoreUser(Request $request)
	{
	    $user = new User;
        $cashier = new Cashier;
        
        $cashier = Cashier::find($request->id);
    		
    	$userId = $cashier->userId;
    		
		$email = $request->email;
		
		
		$duplicateUser = DB::Table('users')
        ->where('contactNumber',$request->contactNumber)
        ->where(function ($query) use ($email) {
            $query->orwhere('email', $email);
        })
        ->where('id','!=', $userId)->get();
        
        
		$response = [
	        'id'=>$request->id,
	        'userId'=>$userId,
	        'contactNumber'=>$request->contactNumber,
	        'email'=>$request->email,
	        'firstName'=>$request->firstName,
	        'lastName'=>$request->lastName,
	        'passwordValue'=>$request->passwordValue
	        
	        ];
		
		
		if(count($duplicateUser)>0) {
		    $status = 'duplicate';
		}
		else {
    		$user= User::find($userId);
    		
    		if(!empty($request->firstName))
    		    $user->firstName = $request->firstName;
    		    
    		if(!empty($request->lastName))
    		    $user->lastName = $request->lastName;
    		
    		if(!empty($request->email))
    		    $user->email = $request->email;
    		
    		if(!empty($request->contactNumber))
    		    $user->contactNumber = $request->contactNumber;
    		
    		if(!empty($request->passwordValue))
    		    $user->password = Hash::make($request->passwordValue);
    	
    	    //$user->password = Hash::make('abc123');
			//$user->roleId = 7;
			$user->roleId = $request->roleId;
			
            $user->save();
            
            $status = 'updated';
		}
        
        return response()->json(compact('status','duplicateUser','response','cashier'))->header("Access-Control-Allow-Origin",  "*");
        
	}
	
	
    /* Manage Store Users in App Ends */
    
    public function sliderimages(Request $request)
    {
        $storeId = $request->storeId;
        $results = DB::Table('storeSlider')
		->select('storeSlider.id','storeSlider.image','storeSlider.storeId')
		->orderBy('storeSlider.id', 'DESC')->get();
		return view('admin.slider.index');
		//return response()->json(compact('results','storeId'))->header("Access-Control-Allow-Origin",  "*");
    }
    
    public function storeSliderImages(Request $request)
    {
        $storeId = $request->storeId;
        $results = DB::Table('storeSlider')
		->select('storeSlider.id','storeSlider.image','storeSlider.storeId')
		->where('storeSlider.storeId','=', $storeId)
		->orderBy('storeSlider.id', 'DESC')->get();
		return response()->json(compact('results','storeId'))->header("Access-Control-Allow-Origin",  "*");
    }
    
    public function recoverData(Request $request)
    {
        if($request->type == 'settings') {
            $results = RecoverData::select('id')
    		->where('storeId',$request->storeId)
    		->where('type','settings')->first();
    		
    		if(empty($results)) {
    		    $RecoverData = new RecoverData;
    		    
    		    $RecoverData->storeId = $request->storeId;
        	    $RecoverData->type = $request->type;
        	    $RecoverData->dataRecoverStatus = 'DBSettings';
        	    $RecoverData->ordersCount = $request->ordersCount;
        	    $RecoverData->productsCount = $request->productsCount;
        	    $RecoverData->device = $request->device;
        	    $RecoverData->data = $request->data;
        	    
        	    $RecoverData->save();
    		}
        }
        else if($request->type == 'orders') {
            
            $results = RecoverOrdersData::select('id')
    		->where('storeId',$request->storeId)
    		->where('orderId',$request->orderId)->first();
            
            if(empty($results)) {
                $RecoverOrdersData = new RecoverOrdersData;
        		    
    		    $RecoverOrdersData->storeId = $request->storeId;;
        	    $RecoverOrdersData->orderId = $request->orderId;
        	    $RecoverOrdersData->data = $request->data;
        	    
        	    $RecoverOrdersData->save();
            }
        }
        else if($request->type == 'products') {
            
            $results = RecoverProductsData::select('id')
    		->where('storeId',$request->storeId)
    		->where('productId',$request->productId)->first();
            
            if(empty($results)) {
                $RecoverProductsData = new RecoverProductsData;
        		    
    		    $RecoverProductsData->storeId = $request->storeId;;
        	    $RecoverProductsData->productId = $request->productId;
        	    $RecoverProductsData->data = $request->data;
        	    
        	    $RecoverProductsData->save();
            }
        }
        else if($request->type == 'statusUpdate') {
            
            $results = RecoverData::select('id')
    		->where('storeId',$request->storeId)
    		->where('type','settings')->first();
            
            $results->dataRecoverStatus = $request->recoverStatus;
        	    
        	$results->save();
        }
        
        $storeId = $request->storeId;
        return response()->json(compact('storeId'))->header("Access-Control-Allow-Origin",  "*");
    }
    
    public function recoverOrders2DB($storeId) {
        $results = RecoverOrdersData::select('id')
    		->where('storeId',$storeId)->get();
    		
    	print_r($results);
    }
    
    // Inventory Batch Listing & Update
    public function listInventoryBatch($productId)
    {
        $results = DB::Table('productInventoryBatch')
		->select('inventory','expiryDate')
		->where('productId','=',$productId)
		->orderBy('expiryDate', 'ASC')->get();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
    }
    
	public function updateInventoryBatch(Request $request)
	{
        $productInventoryBatch = new ProductInventoryBatch;
        $productInventoryBatch = ProductInventoryBatch::find($request->id);
    		
		$response = [
	        'id'=>$request->id,
	        'inventory'=>$request->inventory
	        ];
		
		$productInventoryBatch->inventory = $request->inventory;
		$productInventoryBatch->save();
		
		$status = 'updated';
		
        return response()->json(compact('status','response'))->header("Access-Control-Allow-Origin",  "*");
        
	}
	
	public function userroles(){
		$results = DB::Table('mas_role')
		->select('id','name', 'name_ar')
		->whereIn('id',[7, 9, 10, 13])
		->get();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	public function shifts($id){
		$results = DB::Table('shifts')
		->select('id','title')
		->where('storeId',$id)
		->get();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}

	
	public function getStoreDevice(){
		$results = DB::Table('storedevices')
		->select('id','deviceId','storeId','cashDrawerBal','lastUsedBy')
		->get();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function getUserShift($storeId){
		// This function is mainly used at the time of store setup so we can only fetch the running shifts for users.
		
		$results = DB::Table('usersshift')
		->select('id','userId','shiftId','shiftInTime','shiftInCDBalance','shiftInBalance','shiftInReason','shiftEndTime','shiftEndCDBalance','shiftEndBalance','shiftEndReason','status','created_at','updated_at')
		//->where('status','Running')
		->where('storeId',$storeId)
		->get();
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}

	
	
	public function storeDevicesBal(Request $request){
		$storeDevices = new StoreDevices;
		
		//where cond store id and device id find
		//$storeDevices = StoreDevices::find($request->id);
		$response = [
	        'id'=>$request->id,
	        'deviceId'=>$request->deviceId,
	        'storeId'=>$request->storeId,
	        'cashDrawerBal'=>$request->cashDrawerBal,
	        'lastUsedBy'=>$request->lastUsedBy
	        ];
		
		//$storeDevices->deviceId = $request->deviceId;
		//$storeDevices->storeId = $request->storeId;
		$storeDevices->cashDrawerBal = '1000';
		$storeDevices->lastUsedBy = 'Tes';
		$storeDevices->save();
		
		$status = 'updated';
		
        return response()->json(compact('status','response'))->header("Access-Control-Allow-Origin",  "*");	
	}
	
	public function usersShiftIn(Request $request){
		$userShift = new UserShifts;
		
		$response = [
	        'id'=>$request->id,
	        'userId'=>$request->userId,
	        'userId'=>$request->shiftId,
	        'shiftInTime'=>$request->shiftDateTime,
	        'shiftInCDBalance'=>$request->shiftInCDBalance,
	        'shiftInBalance'=>$request->shiftInBalance,
	        'shiftInReason'=>$request->shiftInReason	        
	        ];
		
		$userShift->storeId = $request->storeId;
		$userShift->userId = $request->userId;
		$userShift->shiftId = $request->shiftId;
		$userShift->shiftInTime = $request->shiftInTime;
		$userShift->shiftInCDBalance = $request->shiftInCDBalance;
		$userShift->shiftInBalance = $request->shiftInBalance;
		$userShift->shiftInReason = $request->shiftInReason;
		
		$userShift->save();
		
		$updateStore = Store::find($request->storeId);
		$updateStore->cashDrawerBalance = $request->shiftInBalance;
		$updateStore->save();
			
		return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function usersShiftEnd(Request $request){
		$userShift = UserShifts::find($request->userShiftsId);
		
		$response = [
	        'userShiftsId'=>$request->userShiftsId,
	        'shiftEndTime'=>$request->shiftEndTime,
	        'shiftEndCDBalance'=>$request->shiftEndCDBalance,
	        'shiftEndBalance'=>$request->shiftEndBalance,
	        'shiftEndReason'=>$request->shiftEndReason
	        ];
		
		$userShift->shiftEndTime = $request->shiftEndTime;
		$userShift->shiftEndBalance = $request->shiftEndBalance;
		$userShift->shiftEndCDBalance = $request->shiftEndCDBalance;
		$userShift->shiftEndReason = $request->shiftEndReason;
		$userShift->status = 'Closed';
		
		$userShift->save();
		
		$updateStore = Store::find($request->storeId);
		$updateStore->cashDrawerBalance = $request->shiftEndBalance;
		$updateStore->save();
			
		return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	
	public function updateCashdrawerBal(Request $request){	
		$response = [
	        'storeId'=>$request->storeId,
	        'cashDrawerBalance'=>$request->cashDrawerBalance
	            
	        ];	
		$updateStore = Store::find($request->storeId);
		$updateStore->cashDrawerBalance = $request->cashDrawerBalance;
		$updateStore->save();
			
		return response()->json(compact('response'))->header("Access-Control-Allow-Origin",  "*");
	}

	public function shiftReports($storeId) {
		

	/* 	 $startDate = $request->start;
        $endDate = $request->end; 
		 $checkDate = Carbon::now()->toDateString();*/
		
		/* if(isset($_GET['startDate']))
			$startDate = $_GET['startDate'];
		else
			$startDate =  ''; 

		if(isset($_GET['endDate']))
			$endDate = $_GET['endDate'];
		else
			$endDate = '';  
 */
		/*
	    if(isset($_GET['search']))
			$search = $_GET['search'];
		else
			$search = '';
			->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate]); */
	   
		
	    $results = DB::table('usersshift as US')
		->leftJoin('stores as S','S.id','=','US.storeId')
		->select ( DB::raw('SUM(US.shiftEndCDBalance) as shiftEndCDBalance'), DB::raw('SUM(US.shiftEndBalance) as shiftEndBalance'), DB::raw('ROUND((SUM(US.shiftEndBalance) - SUM(US.shiftEndCDBalance)),2) as adjustAmount'), DB::raw('COUNT(US.id) as totalShifts'), DB::raw('Date(US.created_at) as dateCreated'), DB::raw('ROUND(SUM(US.shiftEndBalance),2) as totalAmount'),'US.storeId')
		->where('US.storeId',$storeId)
		->where('US.status','Closed')
		//->whereBetween(DB::raw('Date(US.created_at)'), [$startDate, $endDate])
		->groupBy(DB::raw('Date(US.created_at)'))
        ->orderBy(DB::raw('Date(US.created_at)'),'DESC')
		->get();
		
		
       /*  if(isset($request->start) && isset($request->end)) {
            $startDate = $request->start . ' 00:00:00';
          // print_r ($startdate = $request->start_date);
            $endDate = $request->end . ' 23:59:59'; 
	  
           
            
            // $results = $results->whereBetween(DB::raw('Date(US.created_at)'),[$request->start,$request->end]);
			}*/

		//$results = $results->get();
		/* print_r($startDate);
		print_r($endDate);
		print_r($results);
		die; */

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
     public function shiftdayReports($storeId) {
			/* $startDate = $request->start;
        $endDate = $request->end;
		
		 $type = $_GET['type'];
		
	    if(isset($_GET['search']))
			$search = $_GET['search'];
		else
			$search = ''; */
			$shiftDate = ' ';
			if(!empty($_GET['shiftDate']))
				$shiftDate = $_GET['shiftDate'];
			//$shiftDate = date_create($shiftDate);
	   
			$results = DB::table('usersshift as US')
			//->leftJoin('stores as S','S.id','=','US.storeId')
			->leftJoin('users as U','U.id','US.userId')
			->select ('US.id','US.shiftId','US.storeId','U.firstName','U.lastName',DB::raw('Date(US.created_at) as dateCreated'),'US.shiftEndBalance','US.shiftEndCDBalance','US.shiftInCDBalance', 'US.shiftInBalance','US.userId', DB::raw('(US.shiftEndBalance - US.shiftEndCDBalance) as adjustAmount'))
		 	->where(DB::raw('Date(US.created_at)'),$shiftDate)
			->where('US.storeId',$storeId)
			->where('US.status','Closed');
			
			
		   /*  if(isset($request->start) && isset($request->end)) {
				$startDate = $request->start . ' 00:00:00';
			  // print_r ($startdate = $request->start_date);
				$endDate = $request->end . ' 23:59:59'; 
		  
			   
				
				// $results = $results->whereBetween(DB::raw('Date(US.created_at)'),[$request->start,$request->end]);
				}*/
	
			$results = $results->get();
		
	
			return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	 }

	 public function shiftReportView() {
		/* $startDate = $request->start;
	$endDate = $request->end;
	
	 
	
	if(isset($_GET['search']))
		$search = $_GET['search'];
	else
		$search = ''; */
		$shiftId= '';
		$shiftId = $_GET['shiftId'];

   		 $userId = '';
		$shiftDetails = DB::table('usersshift as US')
		->leftJoin('users as U','U.id','US.userId')
		->leftJoin('mas_reason as M','M.id','US.shiftEndReason')
		->select ('US.shiftId','US.storeId','U.firstName','U.lastName',DB::raw('Date(US.created_at) as dateCreated'), 'US.shiftEndBalance', 'US.shiftInBalance', 'US.shiftInCDBalance', 'US.shiftEndCDBalance', 'US.userId', 'US.shiftInTime', 'US.shiftEndTime', DB::raw('(US.shiftEndBalance - US.shiftEndCDBalance) as adjustAmount'), 'US.shiftEndReason', 'M.name as reason')
		// ->where('US.id',$userId)
		->where('US.id',$shiftId)
		->first();
		/* print_r($shiftDetails);
		die; */

		
		 $userId = $shiftDetails->userId;
		$shiftInTime = $shiftDetails ->shiftInTime;
		$shiftEndTime = $shiftDetails ->shiftEndTime;


		$billCount = DB::table('orders_pos as O')->select('O.id', DB::raw('(COUNT(O.id) ) as billCount'))
		->whereBetween(DB::raw('O.created_at'),[$shiftInTime,$shiftEndTime])
		->where('O.refundTotalAmount', '>', 0)
		->where('O.userId', $userId)->get();

		$cashSales = DB::table('orders_pos as O')
		->select('O.id', DB::raw('(SUM(O.totalAmount)- SUM(O.refundTotalAmount) ) as cash'))
		->whereBetween(DB::raw('O.created_at'),[$shiftInTime,$shiftEndTime])
		->where('O.paymentStatus', 'CASH')
		->where('O.userId', $userId)->get();

		$cardSales = DB::table('orders_pos as O')
		->select('O.id', DB::raw('(SUM(O.totalAmount)- SUM(O.refundTotalAmount) ) as card'))
		->whereBetween(DB::raw('O.created_at'),[$shiftInTime,$shiftEndTime])
		->where('O.paymentStatus', 'CARD')
		->where('O.userId', $userId)->get();

		$refundAmounts = DB::table('orders_pos as O')
		->select('O.id', DB::raw('SUM(O.refundTotalAmount) as refundAmount'))
		->whereBetween(DB::raw('O.created_at'),[$shiftInTime,$shiftEndTime])
		->where('O.refundTotalAmount', '>', 0)
		->where('O.userId', $userId)->get();
 
		
	   /*  if(isset($request->start) && isset($request->end)) {
			$startDate = $request->start . ' 00:00:00';
		  // print_r ($startdate = $request->start_date);
			$endDate = $request->end . ' 23:59:59'; 
	  
		   
			
			// $results = $results->whereBetween(DB::raw('Date(US.created_at)'),[$request->start,$request->end]);
			}*/

		/* $results = $results->get(); */
		$results['shiftDetails'] = $shiftDetails;
		$results['billCount'] = $billCount;
		$results['cashSales'] = $cashSales;
		$results['cardSales'] = $cardSales;
		$results['refundAmounts'] = $refundAmounts;
	

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
 }

	/*Product Inventory Batch Expiry Date Start */
	/* public function getExpiryList($storeId){
		
		$results = DB::Table('cashier as C')->leftJoin('users as U', 'U.id', '=', 'C.userId')
		->select('U.id', DB::raw('upper(CONCAT(U.firstName, " ", U.lastName)) AS name'))
		->where('U.roleId', 13)
		->where('C.storeId',$storeId)
		->orderBy('name', 'ASC')->get();

		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	} */
	
	/*public function expiryList($id){
		$expiryLists = DB::Table('vendorInvoice as I')->select('I.id', 'I.orderDetail')
		->where('I.storeId', $id)->get();
		

		$expiryList = json_decode($expiryLists, true);
		$results = str_replace("\'", "'", $expiryList);

		 foreach ($expiryList as $results)
		{
			$results; // this is your area from json response
		} 
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");

	}*/
	/*Product Inventory Batch Expiry Date Etart */	
		
	public function productbatch($productid){
		$getBatchProduct = DB::Table('productInventoryBatch AS PIB')
                ->leftJoin('products','products.id','=','PIB.productId')
                ->select('PIB.expiryDate','PIB.productID','PIB.inventory','PIB.id','PIB.deleteStatus')
                ->where('PIB.productId',$productid)
               ->where('PIB.deleteStatus','!=','Yes')
                ->orderBy('PIB.expiryDate', 'ASC')
                ->get();

		if(count($getBatchProduct) > 0) {
		
			$loopset = true;
			$deleteStatus = false;
			$batchCount=0;
			$productAmount = 30;
			$reduceBatch = 0;
		   
			while ($loopset == true) {
				$batchInventoryUpdate = ProductInventoryBatch::find($getBatchProduct[$batchCount]->id);
				
				$inventory = $getBatchProduct[$batchCount]->inventory; // 20

				//echo '<br>Product Amount:: ' . $productAmount; // 10
				//echo '<br>Inventory in table:: ' . $inventory; // 20
			   
				if($inventory >= $productAmount)
				{
					$batchInventoryUpdate->inventory = $inventory - $productAmount;
					
					// Only set deleteStatus to true as the exact inventory is reduced. Else inventory will be left so no change in deleteStatus
					if($inventory == $productAmount) 
						$batchInventoryUpdate->deleteStatus = true;
					
					$batchInventoryUpdate->save();
					break;
				}
				else {
					$batchInventoryUpdate->inventory = 0;
					$batchInventoryUpdate->deleteStatus = true;
					
					$batchInventoryUpdate->save();
					
					$productAmount = $productAmount - $inventory;
					
				}
				$batchCount++;
			}
		}				
	}
	
	
	
	public function getStrTime(){
		//echo $now = date('Y-m-d');
		$results =  strtotime("now");
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
			
	public function updateReport(){
		$results =  DB::Table('orders_pos')
		//->leftJoin('orders_pos','orders_pos.orderId','=','R.orderNumber')
		->select('orderId','created_at')
		//->where('R.created_at','!=','orders_pos.created_at')
		//->where('PIB.deleteStatus','!=','Yes')
		->where('orderId','TJ8842302032207')
		//->orderBy('PIB.expiryDate', 'ASC')
		->get();


		foreach($results as $result) {
			//print_r($result);
			//echo $result->orderId;
			//$reportUpdate = $reportUpdate::query()->where('orderNumber', $prod_id)->get();
			$reportUpdate = Reports::where('orderNumber', $result->orderId)->get(); 
			foreach($reportUpdate as $report) {
				print_r($report);
				$report->created_at =  $report->created_at; 
				//$score->jan_hm = $row['jan_hm']; 
				$reportUpdate->save(); 
			}
			//$results['cashAmount'] = $results['cashAmount'] + $result->totalAmount;
			//$result['created_at'] = $result['CR'];
		}

		// $updateReport = DB::Table('reports AS R')
		// ->leftJoin('orders_pos','orders_pos.orderId','=','R.orderNumber')
		// ->select('R.orderNumber','R.created_at','orders_pos.created_at')
		// ->where('R.created_at','!=','orders_pos.created_at')
		// ->update(['R.created_at' => 'orders_pos.created_at']);

		//print_r($updateReport);
		//die;
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
}




