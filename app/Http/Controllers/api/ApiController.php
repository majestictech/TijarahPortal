<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Driver;
use App\Banner;
use App\Orders;
use App\Brand;
use App\OrdersData;

use App\Http\Controllers\Controller;
use DB;
use Hash;
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
	
	public function orders()
	{
		$results = DB::Table('orders as O')->leftJoin('users','users.id','=','O.userId')
		->leftJoin('mas_statustype AS MS','MS.id','=','O.orderStatus')
		->select(DB::raw('DATE_FORMAT(O.created_at, "%d %M, %Y %h:%i %p") as placed'),'O.id','O.orderId','O.orderDetail','O.totalAmount','O.paymentStatus','O.deliveryDetails','O.deliveredOn','users.firstName','users.lastName','MS.statusName')
		->orderBy('O.id', 'DESC')->get();
		//echo $results;
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function getOrder($id)
	{
		
		$results =DB::Table('orders as O')->leftJoin('users','users.id','=','O.userId')
		->leftJoin('mas_statustype AS MS','MS.id','=','O.orderStatus')
		->select(DB::raw('DATE_FORMAT(O.created_at, "%d %M, %Y %h:%i %p") as placed'),'O.id','O.orderId','O.orderDetail','O.orderStatus','O.totalAmount','O.paymentStatus','O.deliveryDetails','O.deliveredOn','users.firstName','users.lastName','MS.statusName')
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
	public function vendorSelect()
	{
		$results = DB::Table('vendors as V')
		->select('V.id','V.vendorName','V.state')
		->orderBy('V.id', 'DESC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
	public function deliverySlot()
	{
		$results = DB::Table('deliveryslot as D')
		->select(DB::raw('DATE_FORMAT(D.startingTime, "%h:%i %p") as startingTime'),DB::raw('DATE_FORMAT(D.endingTime, "%h:%i %p") as endingTime'), 'D.id','D.slotName')
		->orderBy('D.startingTime', 'ASC')->get();
		
		return response()->json(compact('results'))->header("Access-Control-Allow-Origin",  "*");
	}
	
}








	