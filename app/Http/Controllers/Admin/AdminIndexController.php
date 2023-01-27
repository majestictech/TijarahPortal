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
	
    public function index(Request $request)
    {
		if(!isset($_REQUEST['test']) || empty($_REQUEST['test']))
			return redirect('admin/inventory'); 

    	$storeFilter = $request->storeFilter;
		$storeId = $request->storeId;
        $starDate = $request->starDate;
        $endDate = $request->endDate;
		/* print_r($storeFilter);
		print_r($starDate);
		print_r($endDate); */
		/* if(!empty($starDate))
			//die; */

        if (Auth::user()->roleId != 4 && Auth::user()->roleId != 11){
			//All Except of Store Owner(4) and Chain Admin(11)

			/* Today Orderplaced Count Start */
    		/* $todayOrderplaced=DB::Table('orders_pos as O')->leftJoin('stores', 'stores.userId','=','O.userId')
    		->select('O.id','O.created_at')->whereDate('O.created_at', Carbon::today());
			
			if(!empty($storeFilter)) {
				$todayOrderplaced = $todayOrderplaced->where('stores.id', $storeFilter);
			}

			$todayOrderplaced = $todayOrderplaced->get();
    		$todayorderCount = $todayOrderplaced->count(); */
			/* Today Orderplaced Count End */
    
			/* Orderplaced Count Start */
    		$orderplaced=DB::Table('orders_pos as O')
			->Join('stores', 'stores.userId','=','O.userId')
    		->select('O.id','O.created_at');

			if(!empty($storeFilter) && (!empty($starDate) && !empty($endDate))) {
				$orderplaced = $orderplaced->where('stores.id', $storeFilter)->whereBetween(DB::raw('Date(O.created_at)'), [$starDate, $endDate]);
			} 
			else if(!empty($storeFilter)) {
				$orderplaced = $orderplaced->where('stores.id', $storeFilter);
			}
			 else if(!empty($starDate) && !empty($endDate)) {
				$orderplaced = $orderplaced->whereBetween(DB::raw('Date(O.created_at)'), [$starDate, $endDate]);
			}
 
			//$orderplaced = $orderplaced->get();
    		$allorderCount = $orderplaced->count();
			/* if(!empty($dateFilter)) {
				$allorderCount = 0;
			} */
			
    		/* Orderplaced Count End */
			
    		/* Customers Count Start */
			$customer=DB::Table('customers')->leftJoin('stores', 'stores.id', '=', 'customers.storeName')->select('customers.id');

			if(!empty($storeFilter)) {
				$customer= $customer->where('customers.storeName', $storeFilter);
			}

			$customer = $customer->get();
    		$allcustomer = $customer->count();
			/* Customers Count End */
    		
    		/*  All Stores and Store Count Start*/
			$allStores=DB::Table('stores as S')
    		->select('S.id', 'S.status', 'S.storeName')->where('S.status','=','Active')
			->orderBy('S.storeName')->get();

    		$stores=DB::Table('stores as S')
    		->select('S.id', 'S.status')->where('S.status','=','Active');

			if(!empty($storeFilter)) {
				$stores= $stores->where('S.id', $storeFilter);
			}

			$stores = $stores->get();
    		$activestores = $stores->count();	
			/*  All Stores and Store Count End*/

    		
			/*  Total Revenues Start*/
        	$revenues = DB::table('orders_pos as O')
			->leftJoin('stores', 'stores.userId','=','O.userId')
			->select(DB::raw('SUM(totalAmount) as totalAmount'));

			if(!empty($storeFilter) && (!empty($starDate) && !empty($endDate))) {
				$revenues = $revenues->where('stores.id', $storeFilter)->whereBetween(DB::raw('Date(O.created_at)'), [$starDate, $endDate]);
			}
			else if(!empty($storeFilter)) {
				$revenues = $revenues->where('stores.id', $storeFilter);
			}
			else if(!empty($starDate) && !empty($endDate)) {
				$revenues = $revenues->whereBetween(DB::raw('Date(O.created_at)'), [$starDate, $endDate]);
			}

			$revenues = $revenues->first();
			/*  Total Revenue End*/

			$date = Carbon::now()->subDays(7);
  
			/* $lastSevendaysRevenue = DB::Table('stores as S')->leftJoin('orders_pos as O','O.userId','=','S.userId')
                    ->select(DB::raw('DATE(O.created_at) as date'),DB::raw('SUM(totalAmount) as totalAmount'))
					->where('O.created_at', '>=', $date)
					->groupBy('date')
                    ->get();
			 */
			/*Bill Count Start  */
			 $revenueData = DB::table('orders_pos')
                ->select(DB::raw('SUM(totalAmount - refundTotalAmount) as totalAmount'),DB::raw('SUM(totalAmount - refundTotalAmount)/COUNT(totalAmount) as averageAmount'),DB::raw('COUNT(totalAmount) as billCount'),DB::raw('Date(created_at) as date'))
               // ->where('storeId',$storeId)
                ->where(DB::raw('Date(created_at)'),'>=',$date)
                ->groupBy(DB::raw('Date(created_at)'))
                ->orderBy(DB::raw('Date(created_at)'),'DESC')
                ->get();

/* print_r(123);
print_r($revenueData);
print_r(456);
die; */



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
				$graphDayCount = count($graphdata['revenue']['labels']);
				

			//print_r($graphDayCount);

			//print_r($revenueData[0]->date);
			//print_r($graphdata[0]->date);
			
			//print_r($revenueData);
			//$revenueData = json_encode($revenueData);	
			//print_r($revenueData);
			//die;
		
			//print_r($graphRevenue[$count]['billCount']);	
			//print_r($graphRevenue[$count]['averageAmount']);	
			//die;
			/*Bill Count End */

		/* 	print_r($lastSevendaysRevenue);	
			print_r('<br>');	
			print_r($lastSevendaysRevenue[0]->totalAmount);	 */
			/* Find Day Name Using Date*/
			/* $newDate = date('l', strtotime($lastSevendaysRevenue[0]->date));	
			print_r($newDate); */
			//die;
			
			/* Top Selling Data Start*/
			$topSellingData = DB::table('reports')
                ->select('productName', 'productNameAr','price', DB::raw('SUM(quantity) as totalQty'))
            	->where(DB::raw('Date(created_at)'),'>=',$date)
                ->groupBy('productName')
                ->orderBy('totalQty','DESC')
				->limit(5)
                ->get(); 
			
			/* Top Selling Data End*/
			
			$lastSevendaysBills = DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
    		->select(DB::raw('DATE(O.created_at) as date'))
    		->where('O.created_at', '>=', $date)
			->groupBy('date')
    		->get();
			$sevenDaysOrderCount = $lastSevendaysBills->count();
			
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
    
			
    		 return view('admin.dashboard.index', compact('revenueData', 'topSellingData', 'allStores', 'storeFilter', 'allorderCount', 'allcustomer', 'activestores', 'revenues', 'productAvailable', 'productNotAvailable', 'instock', 'outOfStock', 'lowInventory', 'maxInventory', 'allProducts','graphdata', 'starDate', 'endDate', 'graphDayCount'));
    		
        }		
    	else if(Auth::user()->roleId == 4){	
			//Store Owner = 4
    	    /* Login Store Details Start */
    	     $storeDetails = DB::table('stores')->where('userId', Auth::id())->select('id','storeName')->get();
    	     //print_r($storeDetails[0]->storeName);
    	     $storeDetails=$storeDetails[0]->id;
    	    /* Login Store Details End */

			/* Today order Count Start */
			/* $orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
    		->select('O.id','O.created_at','S.id')
    		->whereDate('O.created_at', Carbon::today())
    		->where('S.id', $storeDetails)
    		->get();
    		$todayorderCount = $orderplaced->count(); */
			/* Today order Count End */

			/* Orderplaced Count Start */
			$orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
    		->select('O.id','O.created_at','S.id')
    		->where('S.id', $storeDetails);
    		
			if(!empty($storeFilter) && (!empty($starDate) && !empty($endDate))) {
				$orderplaced = $orderplaced->where('stores.id', $storeFilter)->whereBetween(DB::raw('Date(O.created_at)'), [$starDate, $endDate]);
			}
			else if(!empty($storeFilter)) {
				$orderplaced = $orderplaced->where('stores.id', $storeFilter);
			}
			else if(!empty($starDate) && !empty($endDate)) {
				$orderplaced = $orderplaced->whereBetween(DB::raw('Date(O.created_at)'), [$starDate, $endDate]);
			}
			
			$orderplaced = $orderplaced->get();
    		$allorderCount = $orderplaced->count();
			if(!empty($dateFilter)) {
				$allorderCount = 0;
			}
			
    		/* Orderplaced Count End */

			/* Customers Count Start */
			/* $customer = DB::Table('customers as C')->leftJoin('stores as S','S.id' ,'=','C.storeName')
            ->select('C.id')
             ->whereIn('S.id',$storeDetails); */
			 $customer = DB::Table('customers as C')->leftJoin('stores as S','S.id' ,'=','C.storeName')
			 ->select('C.id')
			  ->where('S.id', $storeDetails);
			  
			if(!empty($storeFilter)) {
				$customer= $customer->where('customers.storeName', $storeFilter);
			}

			$customer = $customer->get();
    		$allcustomer = $customer->count();
			/* Customers Count End */

			/*  All Stores and Store Count Start*/
			$allStores=DB::Table('stores as S')
    		->select('S.id', 'S.status', 'S.storeName')->where('S.status','=','Active')
			->where('S.id', $storeDetails)
			->get();
			
    		
			$stores=DB::Table('stores as S')
    		->select('S.id','S.status')->where('S.status','=','Active')
			->where('S.id', $storeDetails);
			

			if(!empty($storeFilter)) {
				$stores= $stores->where('S.id', $storeFilter);
			}

			$stores = $stores->get();
    		$activestores = $stores->count();	
			/*  All Stores and Store Count End*/
    		
    		$storedata = DB::Table('stores as S')->leftJoin('mas_country', 'mas_country.id', '=', 'S.countryId')
			->leftJoin('users', 'users.id', '=', 'S.userId')->leftJoin('mas_storetype','mas_storetype.id','=','S.storeType')
    		->select('S.id', 'S.storeName', 'S.printStoreNameAr', 'users.contactNumber', 'users.email', 'S.regNo', 'S.state', 'S.city', 'S.appVersion', 'mas_country.nicename', 'users.firstName', 'mas_storetype.name', 'users.lastName', 'S.address', 'S.latitude', 'S.longitude', 'S.deviceType', 'S.appType', 'S.shopSize', 'S.vatNumber', 'S.printStoreNameAr', 'S.printAddAr', 'S.manageInventory', 'S.smsAlert', 'S.printFooterEn', 'S.printFooterAr', 'S.autoGlobalCat', 'S.onlineMarket', 'S.loyaltyOptions', 'S.autoGlobalItems', 'S.chatbot', 'users.id as userId')
    		->where('S.id', $storeDetails)->first();
    		
    		
    		
    
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
			
			
    	 	return view('admin.dashboard.index',compact('allorderCount', 'allcustomer', 'allStores', 'activestores', 'storedata', 'revenue', 'storeDetails', 'productAvailable', 'productNotAvailable', 'instock', 'outOfStock', 'lowInventory', 'maxInventory', 'allProducts', 'storeFilter', 'starDate', 'endDate'));
    		
        }
		else if(Auth::user()->roleId == 11){	
    	    //Chain Admin = 11
			/* Parent and Child Store Id Start */
			$parentUserId = auth()->user()->id;
			$storeDetails = DB::Table('chainstores as CS')->Join('stores as S','S.id','=','CS.storeId')
			->where('CS.parentUserId', $parentUserId)->select('S.id')->get();

			$storeDetails = $storeDetails->implode('id', ',');
			
			$storeDetails = explode(',',$storeDetails);
			
			/* Parent and Child Store Id End */

    	     //$storeDetails = DB::table('stores')->where('userId', Auth::id())->select('id','storeName')->get();
    	     //print_r($storeDetails[0]->storeName);
    	     
    	     //$storeDetails=$storeDetails[0]->id;

			 /* Today  Order Count Start*/
			 /* $orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
    		->select('O.id','O.created_at','S.id')
    		->whereDate('O.created_at', Carbon::today())
    		->whereIn('S.id', $storeDetails)
    		->get();
    		$todayorderCount = $orderplaced->count();
			*/
			 /* Today  Order Count End*/
			 
			 /* Orderplaced Count Start */
			 $orderplaced=  DB::Table('chainstores as CS')
			 ->Join('orders_pos as O','CS.storeId','=','O.storeId')
			->Join('stores as S','S.id','=','O.storeId')
			->select('O.id','O.orderId','O.created_at','O.totalAmount','S.storeName as storeName')
			->where('CS.parentUserId',  $parentUserId);
			//New Code End
			//print_r($orderplaced);


    		/* $allorderCount = $orderplaced->count();
			print_r($allorderCount);
			die;
 			 */
			 
			 if(!empty($storeFilter) && (!empty($starDate) && !empty($endDate))) {
				 $orderplaced = $orderplaced->where('S.id', $storeFilter)->whereBetween(DB::raw('Date(O.created_at)'), [$starDate, $endDate]);
			 } 
			 else if(!empty($storeFilter)) {
				 $orderplaced = $orderplaced->where('S.id', $storeFilter);
			 }
			 else if(!empty($starDate) && !empty($endDate)) {
				 $orderplaced = $orderplaced->whereBetween(DB::raw('Date(O.created_at)'), [$starDate, $endDate]);
			 } 
 
			// $orderplaced = $orderplaced->get();
			 $allorderCount = $orderplaced->count();
			  /* if(!empty($dateFilter)) {
				 $allorderCount = 0;
			 	}  */
			/*  print_r($allorderCount);
			 die; */
			
			/* Orderplaced Count End */

			/* Customers Count Start */
			$customer = DB::Table('customers as C')->leftJoin('stores as S','S.id' ,'=','C.storeName')
            ->select('C.id', 'C.storeName')
             ->whereIn('S.id',$storeDetails);
			
    
			if(!empty($storeFilter)) {
				$customer= $customer->where('C.storeName', $storeFilter);
			}

			$customer = $customer->get();
    		$allcustomer = $customer->count();
			/* Customers Count End */

			/*  All Stores and Store Count Start*/
			 $allStores=DB::Table('stores as S')
    		->select('S.id', 'S.status', 'S.storeName')->where('S.status','=','Active')
			->whereIn('S.id', $storeDetails)
			->orderBy('S.storeName')
			->get();
			
    		
			$stores=DB::Table('stores as S')
    		->select('S.id','S.status')->where('S.status','=','Active')
			->whereIn('S.id', $storeDetails);
			

    		
			if(!empty($storeFilter)) {
				$stores= $stores->where('S.id', $storeFilter);
			}

			$stores = $stores->get();
    		$activestores = $stores->count();	
			/*  All Stores and Store Count End*/

			/*  Total Revenues Start*/
        	/* $revenues = DB::table('orders_pos as O')
			->leftJoin('stores', 'stores.userId','=','O.userId')
			->select(DB::raw('SUM(totalAmount) as totalAmount')); */
			$revenue = DB::Table('stores as S')->leftJoin('orders_pos as O','O.userId','=','S.userId')
                    ->select(DB::raw('SUM(O.totalAmount) as totalAmount'))
                    ->whereIn('S.id', $storeDetails);
                   

			if(!empty($storeFilter) && (!empty($starDate) && !empty($endDate))) {
				$revenue = $revenue->where('S.id', $storeFilter)->whereBetween(DB::raw('Date(O.created_at)'), [$starDate, $endDate]);
			}
			else if(!empty($storeFilter)) {
				$revenue = $revenue->where('S.id', $storeFilter);
			}
			else if(!empty($starDate) && !empty($endDate)) {
				$revenue = $revenue->whereBetween(DB::raw('Date(O.created_at)'), [$starDate, $endDate]);
			}

			$revenues = $revenue->first();
			/*  Total Revenue End*/

			$date = Carbon::now()->subDays(7);
			/*Bill Count Start */
				//Write Bill Count Code Here
			/*Bill Count End */

			/* Top Selling Data Start*/
			$topSellingData = DB::table('reports')
                ->select('productName', 'productNameAr','price', DB::raw('SUM(quantity) as totalQty'))
				->whereIn('storeId', $storeDetails)
            	->where(DB::raw('Date(created_at)'),'>=',$date)
                ->groupBy('productName')
                ->orderBy('totalQty','DESC')
				->limit(5)
                ->get(); 
			/* Top Selling Data End*/

    		$storedata = DB::Table('stores as S')->leftJoin('mas_country', 'mas_country.id', '=', 'S.countryId')->leftJoin('users', 'users.id', '=', 'S.userId')->leftJoin('mas_storetype','mas_storetype.id','=','S.storeType')
    		->select('S.id', 'S.storeName', 'S.printStoreNameAr', 'users.contactNumber', 'users.email', 'S.regNo', 'S.state','S.city', 'S.appVersion', 'mas_country.nicename', 'users.firstName', 'mas_storetype.name', 'users.lastName', 'S.address', 'S.latitude', 'S.longitude', 'S.deviceType', 'S.appType', 'S.shopSize', 'S.vatNumber', 'S.printStoreNameAr', 'S.printAddAr', 'S.manageInventory', 'S.smsAlert', 'S.printFooterEn', 'S.printFooterAr', 'S.autoGlobalCat', 'S.onlineMarket', 'S.loyaltyOptions', 'S.autoGlobalItems', 'S.chatbot', 'users.id as userId')
    		->whereIn('S.id', $storeDetails)->first();
			/* print_r($storedata);
    		die; */
    		
    		
			//print_r($orderplaced);
			//print_r($todayorderCount);
			//die;
    	    
    		
           
    
			/*// Base Code
    		$orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
    		->select('O.id','O.created_at','S.id')
    		->whereIn('S.id', $storeDetails)
    		->get();
			*/
			//New Code Start
			

			//print_r($allorderCount);
			//print_r($storeDetails);
			//die;
    		
    
        	
    		
    		//$revenue = $revenue[0]->totalAmount;
			
			
			
			
			$lastdaysRevenue = '';


			/* Dummy Data 0 is replace due to error occur  */
			/*  $lowInventory = DB::Table('products as P')->leftJoin('categories', 'categories.id', '=', 'P.categoryId')
			->select('P.id','P.name','P.code','P.price','P.productImage','P.minOrderQty','categories.name AS catName', 'P.productImgBase64', 'P.sellingPrice', 'P.status', 'P.inventory', 'P.minInventory', 'P.updated_at')
			->whereRaw('P.inventory < P.minInventory')->where('P.inventory','>', 0)
			//->whereIn('P.storeId', [502, 503, 504, 505, 506])
			->whereIn('P.storeId', [$storeDetails])
			->count();  */
			/*  $lowInventory = DB::Table('products as P')
			->select('P.id','P.name', 'P.status', 'P.inventory', 'P.minInventory', 'P.updated_at')
			->whereIn('P.storeId', [$storeDetails])
			->whereRaw('P.inventory < P.minInventory')->where('P.inventory','>', 0)
			->count(); */ 

			//$storeDetails1 = json_decode($storeDetails);
			 /*  print_r($storeDetails);
			 print_r($lowInventory);
			die;  
			 */
			/* $products = Product::all();
			$allProducts = $products->whereIn('storeId', [$storeDetails])->count();
			print_r($allProducts);
			die; 
			$productAvailable = $products->where('status', '=', 'Available')
			->whereIn('storeId', '=', $storeDetails)->count();
			$outOfStock = $products->where('inventory','<=', 0)
			->whereIn('storeId', '=', $storeDetails)->count();
			print_r($allProducts);
			print_r($productAvailable);
			print_r($outOfStock);
		   */
			/* 
			$productNotAvailable = $products->where('status', '=', 'Not Available')->whereIn('storeId', '=', $storeDetails)
			->count();
			$instock = $products->where('inventory','>', 0)->whereIn('storeId', '=', $storeDetails)->count();
			
			
			$maxInventory = $instock - $lowInventory;
  			*/
			$lowInventory = 0;
			$productAvailable = 0;
			$productNotAvailable = 0;
			$instock = 0;
			$outOfStock = 0;
			$maxInventory = 0;
			$allProducts = 1;

    	 	return view('admin.dashboard.index',compact('orderplaced', 'allcustomer', 'allStores', 'activestores', 'storedata', 'allorderCount',  'revenues', 'storeDetails', 'topSellingData', 'lastdaysRevenue', 'maxInventory', 'outOfStock', 'instock', 'productNotAvailable','productAvailable', 'lowInventory', 'allProducts', 'storeFilter', 'starDate', 'endDate')); 
    		
        }
        
    }

}