<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Helpers\LogActivity as LogActivity;
use Carbon\Carbon;
use App\Orders_pos;
use App\Customer;
use App\Product;
class AdminIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function __construct() {
      $this->middleware('auth');
	}
	
	public function myTestAddToLog()
    {
        LogActivity::addToLog('My Testing Add To Log.');
        dd('log insert successfully.');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function logActivity(Request $request)
    {
        //$logs = LogActivity::logActivityLists();
        
        $countPerPage = 15;
        
        $search = $request->search;
        $logs = DB::Table('user_logs as UL')
        ->leftJoin('users','users.id','=','UL.userId')
        ->select('UL.subject','users.firstName','users.lastName', 'UL.created_at')
        ->where ('users.firstName', 'LIKE', '%' . $search . '%' )
        ->orWhere ('users.lastName', 'LIKE', '%' . $search . '%' )
        ->orWhere ('users.email', '=', $search)
        ->orWhere ('UL.subject', 'LIKE', '%' . $search . '%' )
        ->orderBy('UL.id','DESC')->paginate($countPerPage);
        
        $logcount=count($logs);
        
        if(isset($_REQUEST['page']))
            $page = $_REQUEST['page'];
        else
            $page = 1;
        
        return view('admin.logs.logActivity',compact('logs','search','logcount', 'page', 'countPerPage'));
    }
	
    public function index()
    {
       
        if (Auth::user()->roleId != 4 && Auth::user()->roleId != 11){
			//All Except of Store Owner(4) and Chain Admin(11)

    		$orderplaced=DB::Table('orders_pos as O')
    		->select('O.id','O.created_at')->whereDate('created_at', Carbon::today())->get();
    		$todayorderCount = $orderplaced->count();
    
    		$orderplaced=DB::Table('orders_pos as O')
    		->select('O.id','O.created_at')->get();
    		$allorderCount = $orderplaced->count();
    		
    		
    		$customer=Customer::all();
    		$allcustomer = $customer->count();
    		
    
    		$stores=DB::Table('stores as S')
    		->select('S.id','S.status')->where('S.status','=','Active')->get();
    		$activestores = $stores->count();	
    		
    
        	$revenue = DB::table('orders_pos as O')
                    ->select(DB::raw('SUM(totalAmount) as totalAmount'))->whereDate('created_at', Carbon::today())
                    ->first();
    		
    		//$revenue = $revenue['totalAmount'];

			 //print_r($revenue->totalAmount);
			 //die;
			
			$date = Carbon::now()->subDays(7);
  
			/* $lastSevendaysRevenue = DB::Table('stores as S')->leftJoin('orders_pos as O','O.userId','=','S.userId')
                    ->select(DB::raw('DATE(O.created_at) as date'),DB::raw('SUM(totalAmount) as totalAmount'))
					->where('O.created_at', '>=', $date)
					->groupBy('date')
                    ->get();
			 */
			/*Revenue Start  */
			 $revenueData = DB::table('orders_pos')
                ->select(DB::raw('SUM(totalAmount - refundTotalAmount) as totalAmount'),DB::raw('SUM(totalAmount - refundTotalAmount)/COUNT(totalAmount) as averageAmount'),DB::raw('COUNT(totalAmount) as billCount'),DB::raw('Date(created_at) as date'))
               // ->where('storeId',$storeId)
                ->where(DB::raw('Date(created_at)'),'>=',$date)
                ->groupBy(DB::raw('Date(created_at)'))
                ->orderBy(DB::raw('Date(created_at)'),'DESC')
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


					// echo $graphRevenue[$count]['billCount'];	
					// echo '<br/>';
					// echo $graphRevenue[$count]['date'];	
					// echo '<br/>';
					// echo $graphRevenue[$count]['averageAmount'];	
					// echo '<br/>';
					// echo $graphRevenue[$count]['totalAmount'];	
					// echo '<br/>';
					$count++;
				}
				
				for($i=0; $i<7; $i++) {
					$day = Carbon::now()->subDays($i);
					$checkDate = $day->toDateString();
					//$dateDay = $day->format('D');
					$dateDay = $day->format('d/m');
					
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
			//print_r($revenueData);	
			//print_r($graphRevenue[$count]['billCount']);	
			//print_r($graphRevenue[$count]['averageAmount']);	
			//die;
			/*Revenue End  */

		/* 	print_r($lastSevendaysRevenue);	
			print_r('<br>');	
			print_r($lastSevendaysRevenue[0]->totalAmount);	 */
			/* Find Day Name Using Date*/
			/* $newDate = date('l', strtotime($lastSevendaysRevenue[0]->date));	
			print_r($newDate); */
			//die;
			
			$topSellingData = DB::table('reports')
                ->select('productName', 'productNameAr','price', DB::raw('SUM(quantity) as totalQty'))
            	->where(DB::raw('Date(created_at)'),'>=',$date)
                ->groupBy('productName')
                ->orderBy('totalQty','DESC')
				->limit(5)
                ->get(); 
			
			
			// $topSellingData = DB::table('reports')
            //     ->select('productName', 'productNameAr','price', 'created_at', DB::raw('sum(quantity) as totalQty'))
			// 	->where(DB::raw('Date(created_at)'),'>=',$date)
            //     //->groupBy('productName')
            //     //->groupBy('productNameAr')
            //     ->orderBy('totalQty','DESC')
			// 	//->limit(5)
            //     ->get(); 
			 //print_r($topSellingData);
			//die;  
			
			$lastSevendaysBills = DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
    		->select(DB::raw('DATE(O.created_at) as date'))
    		->where('O.created_at', '>=', $date)
			->groupBy('date')
    		->get();
			$sevenDaysOrderCount = $lastSevendaysBills->count();
			//print_r($lastSevendaysBills);	
			//print_r($sevenDaysOrderCount);	
			/* print_r($lastSevendaysBills[0]);	
			print_r($lastSevendaysBills[1]);	 */
			//die;	


					
			//die;
			// Product Available and Not Available Start
			
			$lowInventory = DB::Table('products as P')->leftJoin('categories', 'categories.id', '=', 'P.categoryId')
			->select('P.id','P.name','P.code','P.price','P.productImage','P.minOrderQty','categories.name AS catName', 'P.productImgBase64', 'P.sellingPrice', 'P.status', 'P.inventory', 'P.minInventory', 'P.updated_at')->whereRaw('P.inventory < P.minInventory')->where('P.inventory','>', 0)
			->count();
			
			
			
			$products = Product::all();
			$allProducts = $products->count();
			$productAvailable = $products->where('status', 'Available')->count();
			
			$productNotAvailable = $products->where('status', 'Not Available')->count();
			$instock = $products->where('inventory','>', 0)->count();
			$outOfStock = $products->where('inventory','<=', 0)->count();
			
			$maxInventory = $instock - $lowInventory;
			
			
			/* print_r($allProducts);
			print_r("productAvailable");
			print_r($productAvailable);
			print_r("productNotAvailable");
			print_r($productNotAvailable);
			die; */
			// Product Available and Not Available End
			/* $allProducts = 0;
			/* $lowInventory = 0;
			$productAvailable = 0;
			$productNotAvailable = 0;
			$instock = 0;
			$outOfStock = 0;
			$maxInventory = 0; */

			//print_r($graphdata['revenue']['data']);
			
			//die;
    
    		return view('admin.dashboard.index', compact('todayorderCount', 'allorderCount', 'allcustomer', 'activestores', 'revenue', 'productAvailable', 'productNotAvailable', 'instock', 'outOfStock', 'lowInventory', 'maxInventory', 'allProducts','graphdata'));
    		
        }		
    	else if(Auth::user()->roleId == 4){	
			//Store Owner = 4
    	    
    	     $storeDetails = DB::table('stores')->where('userId', Auth::id())->select('id','storeName')->get();
    	     //print_r($storeDetails[0]->storeName);
    	     
    	     $storeDetails=$storeDetails[0]->id;
    	     
    		
    		$storedata = DB::Table('stores as S')->leftJoin('mas_country', 'mas_country.id', '=', 'S.countryId')->leftJoin('users', 'users.id', '=', 'S.userId')->leftJoin('mas_storetype','mas_storetype.id','=','S.storeType')

    		->select('S.id', 'S.storeName', 'S.printStoreNameAr', 'users.contactNumber', 'users.email','S.regNo', 'S.state', 'S.city', 'S.appVersion', 'mas_country.nicename', 'users.firstName', 'mas_storetype.name', 'users.lastName', 'S.address', 'S.latitude', 'S.longitude', 'S.deviceType', 'S.appType', 'S.shopSize', 'S.vatNumber', 'S.printStoreNameAr', 'S.printAddAr', 'S.manageInventory', 'S.smsAlert', 'S.printFooterEn', 'S.printFooterAr', 'S.autoGlobalCat', 'S.onlineMarket', 'S.loyaltyOptions', 'S.autoGlobalItems', 'S.chatbot', 'users.id as userId')
    		->where('S.id', $storeDetails)->first();
    		
    		
    		$orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
    		->select('O.id','O.created_at','S.id')
    		->whereDate('O.created_at', Carbon::today())
    		->where('S.id', $storeDetails)
    		->get();
    		
    		$todayorderCount = $orderplaced->count();
    	   
    	    
    		
            $customer = DB::Table('customers as C')->leftJoin('stores as S','S.id' ,'=','C.storeName')
            ->select('C.id')
             ->where('S.id', $storeDetails)->
             get();
    
    		$allcustomer = $customer->count();
    		
    
    
    		$orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
    		->select('O.id','O.created_at','S.id')
    		->where('S.id', $storeDetails)
    		->get();
    
    		$allorderCount = $orderplaced->count();
    		
    
        	$revenue = DB::Table('stores as S')->leftJoin('orders_pos as O','O.userId','=','S.userId')
                    ->select(DB::raw('SUM(O.totalAmount) as totalAmount'))->whereDate('O.created_at', Carbon::today())
                    ->where('S.id', $storeDetails)
                    ->first();
    		
    		//$revenue = $revenue[0]->totalAmount;
			
			/* Dummy Data 0 is replace due to error occur  */
			$lowInventory = 0;
			$productAvailable = 0;
			$productNotAvailable = 0;
			$instock = 0;
			$outOfStock = 0;
			$maxInventory = 0;
			$allProducts = 1;

    		return view('admin.dashboard.index',compact('storedata', 'todayorderCount', 'allorderCount', 'allcustomer', 'revenue', 'storeDetails', 'productAvailable', 'productNotAvailable', 'instock', 'outOfStock', 'lowInventory', 'maxInventory', 'allProducts'));
    		
        }
		else if(Auth::user()->roleId == 11){	
    	    //Chain Admin = 11
    	     //$storeDetails = DB::table('stores')->where('userId', Auth::id())->select('id','storeName')->get();
    	     //print_r($storeDetails[0]->storeName);
    	     
    	     //$storeDetails=$storeDetails[0]->id;

			 
			 $parentUserId = auth()->user()->id;
			 $storeDetails = DB::Table('chainstores as CS')->Join('stores as S','S.id','=','CS.storeId')->where('CS.parentUserId', $parentUserId)->select('S.id')->get();

			 $storeDetails = $storeDetails->implode('id', ',');
			 
			 
			 $storeDetails = explode(',',$storeDetails);
    		
    		$storedata = DB::Table('stores as S')->leftJoin('mas_country', 'mas_country.id', '=', 'S.countryId')->leftJoin('users', 'users.id', '=', 'S.userId')->leftJoin('mas_storetype','mas_storetype.id','=','S.storeType')
    		->select('S.id', 'S.storeName', 'S.printStoreNameAr', 'users.contactNumber', 'users.email', 'S.regNo', 'S.state','S.city', 'S.appVersion', 'mas_country.nicename', 'users.firstName', 'mas_storetype.name', 'users.lastName', 'S.address', 'S.latitude', 'S.longitude', 'S.deviceType', 'S.appType', 'S.shopSize', 'S.vatNumber', 'S.printStoreNameAr', 'S.printAddAr', 'S.manageInventory', 'S.smsAlert', 'S.printFooterEn', 'S.printFooterAr', 'S.autoGlobalCat', 'S.onlineMarket', 'S.loyaltyOptions', 'S.autoGlobalItems', 'S.chatbot', 'users.id as userId')
    		->whereIn('S.id', $storeDetails)->first();
    		//die;
    		
    		$orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
    		->select('O.id','O.created_at','S.id')
    		->whereDate('O.created_at', Carbon::today())
    		->whereIn('S.id', $storeDetails)
    		->get();
			
    		

    		$todayorderCount = $orderplaced->count();
			//print_r($orderplaced);
			//print_r($todayorderCount);
			//die;
    	    
    		
            $customer = DB::Table('customers as C')->leftJoin('stores as S','S.id' ,'=','C.storeName')
            ->select('C.id')
             ->whereIn('S.id',$storeDetails)
			 ->get();
    
    		$allcustomer = $customer->count();
    		
    
			/*// Base Code
    		$orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
    		->select('O.id','O.created_at','S.id')
    		->whereIn('S.id', $storeDetails)
    		->get();
			*/
			//New Code Start
			$orderplaced=  DB::Table('chainstores as CS')->Join('orders_pos as O','CS.storeId','=','O.storeId')
			->Join('stores as S','S.id','=','O.storeId')->select('O.id','O.orderId','O.created_at','O.totalAmount','S.storeName as storeName')
			->where('CS.parentUserId',  $parentUserId);
			//New Code End
			//print_r($orderplaced);

    		$allorderCount = $orderplaced->count();

			//print_r($allorderCount);
			//print_r($storeDetails);
			//die;
    		
    
        	$revenue = DB::Table('stores as S')->leftJoin('orders_pos as O','O.userId','=','S.userId')
                    ->select(DB::raw('SUM(O.totalAmount) as totalAmount'))->whereDate('O.created_at', Carbon::today())
                    ->whereIn('S.id', $storeDetails)
                    ->first();
    		
    		//$revenue = $revenue[0]->totalAmount;
			
			$stores=DB::Table('stores as S')
    		->select('S.id','S.status')->where('S.status','=','Active')->whereIn('S.id', $storeDetails)->get();
    		$activestores = $stores->count();
			
			
			$lastdaysRevenue = '';


			/* Dummy Data 0 is replace due to error occur  */
			$lowInventory = 0;
			$productAvailable = 0;
			$productNotAvailable = 0;
			$instock = 0;
			$outOfStock = 0;
			$maxInventory = 0;
			$allProducts = 1;
			
			
			/* $lowInventory = DB::Table('products as P')->leftJoin('categories', 'categories.id', '=', 'P.categoryId')
			->select('P.id','P.name','P.code','P.price','P.productImage','P.minOrderQty','categories.name AS catName', 'P.productImgBase64', 'P.sellingPrice', 'P.status', 'P.inventory', 'P.minInventory', 'P.updated_at')
			->whereRaw('P.inventory < P.minInventory')->where('P.inventory','>', 0)
			->whereIn('storeId', [$storeDetails])
			->count(); */
			//$storeDetails1 = json_decode($storeDetails);
			/* print_r(gettype($storeDetails));
			die; */
			
			/*  $products = Product::all();
			$allProducts = $products->whereIn('storeId', [$storeDetails])->count();
			 print_r($allProducts);
			die; 
			$productAvailable = $products->where('status', '=', 'Available')->whereIn('storeId', '=', $storeDetails)->count();
			
			$productNotAvailable = $products->where('status', '=', 'Not Available')->whereIn('storeId', '=', $storeDetails)->count();
			$instock = $products->where('inventory','>', 0)->whereIn('storeId', '=', $storeDetails)->count();
			$outOfStock = $products->where('inventory','<=', 0)->whereIn('storeId', '=', $storeDetails)->count();
			
			$maxInventory = $instock - $lowInventory;
  */

			
			

    		return view('admin.dashboard.index',compact('storedata', 'todayorderCount', 'allorderCount', 'allcustomer', 'revenue', 'storeDetails', 'activestores', 'lastdaysRevenue', 'maxInventory', 'outOfStock', 'instock', 'productNotAvailable','productAvailable', 'lowInventory', 'allProducts'));
    		
        }
        
    }

}