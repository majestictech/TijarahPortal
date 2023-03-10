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
use App\Reports;
use Illuminate\Support\Str;


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
		$fixReportsOrders = isset($_REQUEST['fixReports'])?$_REQUEST['fixReports']:'';
		
		if($fixReportsOrders == 'fixOrders') {
			$orders = DB::Table('orders_pos as O')->select('O.id','O.orderId','O.orderDetail','O.storeId','O.created_at')
			->whereNotIn('O.id', DB::table('reports')->where('storeId',9588)->pluck('orderId'))
			->where('O.storeId',9588)
			->orderBy('O.id','DESC')
			->limit(50)
			->get();
			
			// Entry in Reports Table Starts
			foreach($orders as $order)
			{
				$products = json_decode($order->orderDetail);
				
				
				if(!empty($products->products)) {
				try {
					foreach($products->products as $product) 
					{
						$productFound = false;
						if(substr($product->id,0,1) != 'C') {
							$updateProduct = new Product;
							
							$updateProduct = Product::find($product->id);
							
							if(!empty($updateProduct)) {
								$productFound = true;
								$productid = $updateProduct->id;
								$catId = $updateProduct->categoryId;
							}
						
						
							$updateReport = new Reports;
							
							if($productFound == true) {
								$results = DB::Table('categories')
								->leftJoin('categories_ar','categories_ar.categoryId','=','categories.id')
								->select('categories.id','categories.name','categories_ar.name as nameArCat')
								->where('categories.id','=',$catId)->first();
							}

							$updateReport->storeId = $order->storeId;
							$updateReport->orderId = $order->id;
							$updateReport->orderNumber = $order->orderId;

							if($productFound == true)
								$updateReport->productName = utf8_encode($product->name);

							if(!empty($product->name_ar))
								$updateReport->productNameAr = $product->name_ar;
							if(empty($product->name_ar))
								$updateReport->productNameAr = '';
							
							if($productFound == true) {
								$updateReport->categoryName = $results->name;
								$updateReport->categoryNameAr = $results->nameArCat;
							}
							$updateReport->price = $product->sellingPrice;
							$updateReport->costPrice = $product->costPrice;
							$updateReport->quantity = $product->amount;


							$discountPercentage = 0;
							if($product->discPer == 'NaN')
								$discountPercentage = $product->discPer;
								
							$discountedPrice = $product->sellingPrice  - $product->sellingPrice*$discountPercentage/100;

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
							
							
							$updateReport->storeId = $order->storeId;
							$updateReport->orderId = $order->id;
							$updateReport->orderNumber = $order->orderId;
							
							$updateReport->productName = $product->name;
							
							if(!empty($product->name_ar))
								$updateReport->productNameAr = $product->name_ar;
							if(empty($product->name_ar))
								$updateReport->productNameAr = '';
							
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
				} catch (\Exception $e) {
					continue;
				}
				}
			}
			// Entry in Reports Table End
		}

    	$storeFilter = $request->storeFilter;
		$storeId = $request->storeId;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

		$todayStartDate = Carbon::now()->toDateString();
		$todayEndDate = Carbon::now()->toDateString();

		if(empty($startDate) && empty($endDate)) {
			$startDate = $todayStartDate;
			$endDate = $todayEndDate;
		}

        if (Auth::user()->roleId != 4 && Auth::user()->roleId != 11 && Auth::user()->roleId != 12 && Auth::user()->roleId != 14){
			
			//All Except of Store Owner(4) and Chain Admin(11)

			/* Today Orderplaced Count Start */
    		/* $todayOrderplaced=DB::Table('orders_pos as O')->leftJoin('stores', 'stores.userId','=','O.userId')
    		->select('O.id','O.created_at')->whereDate('O.created_at', Carbon::today());
			
			if(!empty($storeFilter)) {
				$todayOrderplaced = $todayOrderplaced->where('stores.id', $storeFilter);
			}

			$todayOrderplaced = $todayOrderplaced->get();
    		$todayOrderCount = $todayOrderplaced->count(); */
			/* Today Orderplaced Count End */

			 /* Today  Order Count Start*/
				$orderplaced1=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
				->select('O.id','O.created_at','S.id')
				->whereDate('O.created_at', Carbon::today())
				// ->whereIn('S.id', $storeDetails)
				->get();
				$todayOrderCount = $orderplaced1->count();
			//print_r($todayOrderCount);
			// die;
			/* Today  Order Count End*/
    
			/* Orderplaced Count Start */

    		$orderplaced = DB::Table('orders_pos as O')
			->leftJoin('stores', 'stores.userId','=','O.userId')
    		->select('O.id','O.created_at');
			
			//print_r($orderplaced);
			//die;

			//$todayOrderCount = $orderplaced->whereDate('O.created_at', Carbon::today())->count();

			if(!empty($storeFilter) && (!empty($startDate) && !empty($endDate))) {
				$orderplaced = $orderplaced->where('O.storeId', $storeFilter)->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);
			} 
			else if(!empty($storeFilter)) {
				$orderplaced = $orderplaced->where('O.storeId', $storeFilter);
			}
			 else if(!empty($startDate) && !empty($endDate)) {
				$orderplaced = $orderplaced->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);
			}
 
			//$orderplaced = $orderplaced->get();
    		$allorderCount = $orderplaced->count();
			/* if(!empty($dateFilter)) {
				$allorderCount = 0;
			} */
			
    		/* Orderplaced Count End */
			
    		/* Customers Count Start */
			$customer=DB::Table('customers')->leftJoin('stores', 'stores.id', '=', 'customers.storeName')->select('customers.id');

			/* if(!empty($storeFilter)) {
				$customer= $customer->where('customers.storeName', $storeFilter);
			}
 */
			$customer = $customer->get();
    		$allcustomer = $customer->count();
			/* Customers Count End */
    		
    		/*  All Stores and Store Count Start*/
			$allStores=DB::Table('stores as S')
    		->select('S.id', 'S.status', 'S.storeName')->where('S.status','=','Active')
			->orderBy('S.storeName')->get();

    		$stores=DB::Table('stores as S')
    		->select('S.id', 'S.status')->where('S.status','=','Active');

			/* if(!empty($storeFilter)) {
				$stores= $stores->where('S.id', $storeFilter);
			} */

			$stores = $stores->get();
    		$activestores = $stores->count();	
			/*  All Stores and Store Count End*/

    		
			/*  Total Revenues Start*/
        	/* $revenues = DB::table('orders_pos as O')
			->leftJoin('stores', 'stores.userId','=','O.userId')
			->select(DB::raw('SUM(totalAmount) as totalAmount'));

			$todayRevenueCount = $revenues->whereBetween(DB::raw('Date(O.created_at)'), [$todayStartDate, $todayEndDate])->first();

			if(!empty($storeFilter) && (!empty($startDate) && !empty($endDate))) {
				$revenues = $revenues->where('stores.id', $storeFilter)->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);
			}
			else if(!empty($storeFilter)) {
				$revenues = $revenues->where('stores.id', $storeFilter);
			}
			else if(!empty($startDate) && !empty($endDate)) {
				$revenues = $revenues->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);
			}

			$revenues = $revenues->first(); */
			$todayRevenueCount = DB::table('orders_pos as O')
                    ->select(DB::raw('SUM(totalAmount - refundTotalAmount) as totalAmount'))->whereDate('created_at', Carbon::today())
                    ->get();
    		
    		$todayRevenueCount = $todayRevenueCount[0]->totalAmount;
			/* print_r($todayRevenueCount);
			die; */
			$revenues = DB::table('orders_pos as O')
			->select(DB::raw('SUM(totalAmount - refundTotalAmount) as totalAmount'));

			if(!empty($storeFilter) && (!empty($startDate) && !empty($endDate))) {
				$revenues = $revenues->where('O.storeId', $storeFilter)->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);
			}
			else if(!empty($storeFilter)) {
				$revenues = $revenues->where('O.storeId', $storeFilter);
			}
			else if(!empty($startDate) && !empty($endDate)) {
				$revenues = $revenues->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);
			}

			$revenues= $revenues->get();
			$revenues = $revenues[0]->totalAmount;
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
				//->where('storeId',$storeDetails)
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
					//$dateDay = $day->format('jM');
					$dateDay = $day->format('d') ;
					
					//dd($dateDay);
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
			
			//print_r($graphdata['revenue']['labels']);
			
			$revenueLabels = implode(',',$graphdata['revenue']['labels']);
			$revenueData = implode(',',$graphdata['revenue']['data']);
			
			$billLabels = implode(',',$graphdata['bills']['labels']);
			$billData = implode(',',$graphdata['bills']['data']);
			
			$basketLabels = implode(',',$graphdata['avgBasket']['labels']);
			$basketData = implode(',',$graphdata['avgBasket']['data']);

			//print_r($billLabels);
			//print_r($billData);
			//print_r($basketLabels);
			//print_r($basketData);
			
			
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
				->limit(9)
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
			
			
			
			$allProducts = Product::count();
			//$allProducts = $products->count();
			$productAvailable = Product::where('status', 'Available')->count();
			
			$productNotAvailable = Product::where('status', 'Not Available')->count();
			$instock = Product::where('inventory','>', 0)->count();
			$outOfStock = Product::where('inventory','<=', 0)->count();
			
			$maxInventory = $instock - $lowInventory;   
			

			//$graphdata = [];

    		return view('admin.dashboard.index', compact('revenueData', 'topSellingData', 'allStores', 'storeFilter', 'todayOrderCount', 'allorderCount', 'allcustomer', 'activestores', 'todayRevenueCount', 'revenues', 'productAvailable', 'productNotAvailable', 'instock', 'outOfStock', 'lowInventory', 'maxInventory', 'allProducts','graphdata', 'startDate', 'endDate', 'graphDayCount','revenueLabels','revenueData','billLabels','billData','basketLabels','basketData'));
    		
        }		
    	else if(Auth::user()->roleId == 4){	
			//Store Owner = 4

			$revenues = "";
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
    		$todayOrderCount = $orderplaced->count(); */
			/* Today order Count End */

			/* Orderplaced Count Start */
			
			$orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
    		->select('O.id','O.created_at','S.id')
    		->where('S.id', $storeDetails);
    		
			$todayOrderCount = $orderplaced->whereDate('O.created_at', Carbon::today())->count();

			if(!empty($storeFilter) && (!empty($startDate) && !empty($endDate))) {
				$orderplaced = $orderplaced->where('O.storeId', $storeFilter)->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);
			}
			else if(!empty($storeFilter)) {
				$orderplaced = $orderplaced->where('O.storeId', $storeFilter);
			}
			else if(!empty($startDate) && !empty($endDate)) {
				$orderplaced = $orderplaced->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);
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
			  
			/* if(!empty($storeFilter)) {
				$customer= $customer->where('customers.storeName', $storeFilter);
			} */

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
			

			/* if(!empty($storeFilter)) {
				$stores= $stores->where('S.id', $storeFilter);
			} */

			$stores = $stores->get();
    		$activestores = $stores->count();	
			/*  All Stores and Store Count End*/
    		
    		$storedata = DB::Table('stores as S')->leftJoin('mas_country', 'mas_country.id', '=', 'S.countryId')
			->leftJoin('users', 'users.id', '=', 'S.userId')->leftJoin('mas_storetype','mas_storetype.id','=','S.storeType')
    		->select('S.id', 'S.storeName', 'S.printStoreNameAr', 'users.contactNumber', 'users.email', 'S.regNo', 'S.state', 'S.city', 'S.appVersion', 'mas_country.nicename', 'users.firstName', 'mas_storetype.name', 'users.lastName', 'S.address', 'S.latitude', 'S.longitude', 'S.deviceType', 'S.appType', 'S.shopSize', 'S.vatNumber', 'S.printStoreNameAr', 'S.printAddAr', 'S.manageInventory', 'S.smsAlert', 'S.printFooterEn', 'S.printFooterAr', 'S.autoGlobalCat', 'S.onlineMarket', 'S.loyaltyOptions', 'S.autoGlobalItems', 'S.chatbot', 'users.id as userId')
    		->where('S.id', $storeDetails)->first();
    		
    		//print_r($storeDetails);
			//die;
    		
    
        	$revenue = DB::Table('stores as S')->leftJoin('orders_pos as O','O.userId','=','S.userId')
                    ->select(DB::raw('SUM(O.totalAmount) as totalAmount'))->whereDate('O.created_at', Carbon::today())
                    ->where('S.id', $storeDetails)
                    ->first();
					
					
					
			$date = Carbon::now()->subDays(7);
			$revenueData = DB::table('orders_pos')
                ->select(DB::raw('SUM(totalAmount - refundTotalAmount) as totalAmount'),DB::raw('SUM(totalAmount - refundTotalAmount)/COUNT(totalAmount) as averageAmount'),DB::raw('COUNT(totalAmount) as billCount'),DB::raw('Date(created_at) as date'))
               // ->where('storeId',$storeId)
                ->where(DB::raw('Date(created_at)'),'>=',$date)
                ->groupBy(DB::raw('Date(created_at)'))
                ->orderBy(DB::raw('Date(created_at)'),'DESC')
				->where('storeId',$storeDetails)
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
					$dateDay = $day->format('d');
					//$dateDay = $day->format('jS M');
					
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
			
			//print_r($graphdata['revenue']['labels']);
			
			$revenueLabels = implode(',',$graphdata['revenue']['labels']);
			$revenueData = implode(',',$graphdata['revenue']['data']);
			
			$billLabels = implode(',',$graphdata['bills']['labels']);
			$billData = implode(',',$graphdata['bills']['data']);
			
			$basketLabels = implode(',',$graphdata['avgBasket']['labels']);
			$basketData = implode(',',$graphdata['avgBasket']['data']);

			//print_r($billLabels);
			//print_r($billData);
			//print_r($basketLabels);
			//print_r($basketData);
			
			
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
				->limit(9)
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
			
			
			
			$allProducts = Product::count();
			//$allProducts = $products->count();
			$productAvailable = Product::where('status', 'Available')->count();
			
			$productNotAvailable = Product::where('status', 'Not Available')->count();
			$instock = Product::where('inventory','>', 0)->count();
			$outOfStock = Product::where('inventory','<=', 0)->count();
			
			$maxInventory = $instock - $lowInventory;  
    		
    		//$revenue = $revenue[0]->totalAmount;
			
			/* Dummy Data 0 is replace due to error occur  */
			$lowInventory = 0;
			$productAvailable = 0;
			$productNotAvailable = 0;
			$instock = 0;
			$outOfStock = 0;
			$maxInventory = 0;
			$allProducts = 1;

			
			
			
    	 	return view('admin.dashboard.index',compact('todayOrderCount', 'allorderCount', 'allcustomer', 'allStores', 'activestores', 'storedata', 'revenue', 'storeDetails', 'productAvailable', 'productNotAvailable', 'instock', 'outOfStock', 'lowInventory', 'maxInventory', 'allProducts', 'storeFilter', 'startDate', 'endDate','revenueLabels','revenueData','billLabels','billData','basketLabels','basketData','date', 'revenues'));
    		
        }
		else if(Auth::user()->roleId == 11 ||  Auth::user()->roleId == 12 ||  Auth::user()->roleId == 14) {
    	    //Chain Admin = 11

			/* Parent and Child Store Id Start */
			$parentUserId ='';
			$userId = Auth::user()->id ?? ' ' ;
			
			if(Auth::user()->roleId == 11) {
				$parentUserId = auth()->user()->id;
			}
			else {
				$parentUserId =  DB::Table('chainstoreusers')
				->where('userId', $userId)->first();
 
				$parentUserId = $parentUserId->parentAdminUserId;
				//print_r($userId);
				//print_r($parentUserId);
				//die;
			}
			
			
			$storeDetails = DB::Table('chainstores as CS')->Join('stores as S','S.id','=','CS.storeId')
			->where('CS.parentUserId', $parentUserId)->select('S.id')->get();

			$storeDetails = $storeDetails->implode('id', ',');		
			$storeDetails = explode(',',$storeDetails);
			
			//print_r($storeDetails);
			//die;
			/* Parent and Child Store Id End */


			 /* Today  Order Count Start*/
			 /* $orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
    		->select('O.id','O.created_at','S.id')
    		->whereDate('O.created_at', Carbon::today())
    		->whereIn('S.id', $storeDetails)
    		->get();
    		$todayOrderCount = $orderplaced->count();
			*/
			 /* Today  Order Count End*/
			 /* Today Orderplaced Count End */
			 /* Today  Order Count Start*/
			 $orderplaced1=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
			 ->select('O.id','O.created_at','S.id')
			 ->whereDate('O.created_at', Carbon::today())
			 ->whereIn('S.id', $storeDetails)
			 ->get();
			 $todayOrderCount = $orderplaced1->count();
			/* print_r($todayOrderCount);
			die; */
			/* Today  Order Count End*/
			 
			 /* Orderplaced Count Start */
			 $orderplaced=  DB::Table('chainstores as CS')
			 ->Join('orders_pos as O','CS.storeId','=','O.storeId')
			->Join('stores as S','S.id','=','O.storeId')
			//->select('O.id','O.orderId','O.created_at','O.totalAmount')
			->select('O.id','O.orderId','O.created_at','O.totalAmount','S.storeName as storeName')
			->where('CS.parentUserId',  $parentUserId);
			//->get();
			//New Code End
			//print_r($orderplaced);


    		/* $allorderCount = $orderplaced->count();
			print_r($allorderCount);
			die;
 			 */
			 //die;

			 //$todayOrderCount = $orderplaced->whereDate('O.created_at', Carbon::today())->count();
			 if(!empty($storeFilter) && (!empty($startDate) && !empty($endDate))) {
				 $orderplaced = $orderplaced->where('S.id', $storeFilter)->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);
			 } 
			 else if(!empty($storeFilter)) {
				 $orderplaced = $orderplaced->where('S.id', $storeFilter);
			 }
			 else if(!empty($startDate) && !empty($endDate)) {
				 $orderplaced = $orderplaced->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);
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
			
    
			/* if(!empty($storeFilter)) {
				$customer= $customer->where('C.storeName', $storeFilter);
			} */

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
			

    		
			/* if(!empty($storeFilter)) {
				$stores= $stores->where('S.id', $storeFilter);
			}
 			*/
			$stores = $stores->get();
    		$activestores = $stores->count();	
			/*  All Stores and Store Count End*/

			/*  Total Revenues Start*/
			
			/*  Total Revenues Start*/
        	/* $revenues = DB::table('orders_pos as O')
			//->leftJoin('stores', 'stores.userId','=','O.userId')
			->select(DB::raw('SUM(totalAmount - refundTotalAmount) as totalAmount'))
			->whereIn('O.storeId', $storeDetails);
			//->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);
			//$todayRevenueCount = 100;
			//$todayRevenueCount = $revenues->whereBetween(DB::raw('Date(O.created_at)'),[$todayStartDate,$todayEndDate]);
			//$todayRevenueCount = $revenues->whereDate('O.created_at', Carbon::today());

			//$todayRevenueCount = $revenues->whereBetween(DB::raw('O.created_at'), [$todayStartDate, $todayEndDate])->first();

			if(!empty($startDate) && !empty($endDate)) {
				$revenues = $revenues->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);
			}
			if(!empty($storeFilter)) {
				$revenues = $revenues->where('O.storeId', $storeFilter);
			}

			$revenues = $revenues->first(); */
			$todayRevenueCount = DB::table('orders_pos as O')
                    ->select(DB::raw('SUM(totalAmount - refundTotalAmount) as totalAmount'))->whereDate('created_at', Carbon::today())
					->whereIn('O.storeId', $storeDetails)
                    ->get();
    		
			if(!empty($todayRevenueCount))
				$todayRevenueCount = $todayRevenueCount[0]->totalAmount;
			else
				$todayRevenueCount = 0;
			

			$revenues = DB::table('orders_pos as O')
			->select(DB::raw('SUM(totalAmount - refundTotalAmount) as totalAmount'))
			->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);

			if(empty($storeFilter)) {
				$revenues = $revenues->whereIn('O.storeId', $storeDetails);
			}
			else if(!empty($storeFilter)) {
				$revenues = $revenues->where('O.storeId', $storeFilter);
			}

			if(!empty($startDate) && !empty($endDate)) {
				$revenues = $revenues->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate]);
			}

			$revenues= $revenues->get();
			
			if(!empty($revenues))
				$revenues = $revenues[0]->totalAmount;
			else
				$revenues = 0;

			/*  Total Revenue End*/
			
			//dd(\DB::getQueryLog());
			//$bindings = $revenues->getBindings();
			
			//$sql = Str::replaceArray('?', $revenues->getBindings(), $revenues->toSql());

			// print
			//dd($sql);
			//print_r($revenues);
			//die;
        	  
			
			/*  Total Revenue End*/

			$date = Carbon::now()->subDays(7);
			/*Bill Count Start */
				//Write Bill Count Code Here
			/*Bill Count End */

			//dd($revenues);

			/* Top Selling Data Start*/
			$topSellingData = DB::table('reports')
                ->select('productName', 'productNameAr','price', DB::raw('SUM(quantity) as totalQty'))
				->whereIn('storeId', $storeDetails)
            	//->where(DB::raw('Date(created_at)'),'>=',$date)
                ->groupBy('productName')
                ->orderBy('totalQty','DESC')
				->limit(9)
                ->get(); 
			/* Top Selling Data End*/
			
			
			
			
			
			

    		$storedata = DB::Table('stores as S')->leftJoin('mas_country', 'mas_country.id', '=', 'S.countryId')->leftJoin('users', 'users.id', '=', 'S.userId')->leftJoin('mas_storetype','mas_storetype.id','=','S.storeType')
    		->select('S.id', 'S.storeName', 'S.printStoreNameAr', 'users.contactNumber', 'users.email', 'S.regNo', 'S.state','S.city', 'S.appVersion', 'mas_country.nicename', 'users.firstName', 'mas_storetype.name', 'users.lastName', 'S.address', 'S.latitude', 'S.longitude', 'S.deviceType', 'S.appType', 'S.shopSize', 'S.vatNumber', 'S.printStoreNameAr', 'S.printAddAr', 'S.manageInventory', 'S.smsAlert', 'S.printFooterEn', 'S.printFooterAr', 'S.autoGlobalCat', 'S.onlineMarket', 'S.loyaltyOptions', 'S.autoGlobalItems', 'S.chatbot', 'users.id as userId')
    		->whereIn('S.id', $storeDetails)->first();
			/* print_r($storedata);
    		die; */
    		
    		
			//print_r($orderplaced);
			//print_r($todayOrderCount);
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
			
			
			
			//print_r($storeDetails);
			//die;
			$revenueData = DB::table('orders_pos')
                ->select(DB::raw('SUM(totalAmount - refundTotalAmount) as totalAmount'),DB::raw('SUM(totalAmount - refundTotalAmount)/COUNT(totalAmount) as averageAmount'),DB::raw('COUNT(totalAmount) as billCount'),DB::raw('Date(created_at) as date'))
               // ->where('storeId',$storeId)
                ->where(DB::raw('Date(created_at)'),'>=',$date)
                ->groupBy(DB::raw('Date(created_at)'))
                ->orderBy(DB::raw('Date(created_at)'),'DESC')
				->whereIn('storeId', $storeDetails)
				
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
					$dateDay = $day->format('d');
					//$dateDay = $day->format('d/m');
					
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
			
			//print_r($graphdata['revenue']['labels']);
			
			$revenueLabels = implode(',',$graphdata['revenue']['labels']);
			$revenueData = implode(',',$graphdata['revenue']['data']);
			
			$billLabels = implode(',',$graphdata['bills']['labels']);
			$billData = implode(',',$graphdata['bills']['data']);
			
			$basketLabels = implode(',',$graphdata['avgBasket']['labels']);
			$basketData = implode(',',$graphdata['avgBasket']['data']);

			//print_r($billLabels);
			//print_r($billData);
			//print_r($basketLabels);
			//print_r($basketData);
			
			
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
			
			
			
			$allProducts = Product::count();
			//$allProducts = $products->count();
			$productAvailable = Product::where('status', 'Available')->count();
			
			$productNotAvailable = Product::where('status', 'Not Available')->count();
			$instock = Product::where('inventory','>', 0)->count();
			$outOfStock = Product::where('inventory','<=', 0)->count();
			
			$maxInventory = $instock - $lowInventory;  
			/* print_r($startDate);
			print_r($startDate);
			print_r($storeDetails);
			die; */

			/* Total Cash and Card Revenue Start */

			$multipleMode = DB::table('multiplepayment')->select('paymentMode', DB::raw('SUM(amount) as totalAmount'))
			->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate])
			->whereIn('storeId', $storeDetails);

			if(!empty($storeFilter)) {
				$multipleMode = $multipleMode->where('storeId', $storeFilter);
			}
			
			$multipleMode = $multipleMode->groupBy('paymentMode')->get();
			/* print_r($multipleMode);
			die; */
			/* Multiple Refund Start */
			$multipleRefund = DB::table('orders_pos as O')
			->select('O.id', DB::raw('SUM(O.totalAmount) as refund'))
			->where('O.paymentStatus', 'MULTIPLE')
			->where('O.refundTotalAmount', '>',0)
			->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate])
			->whereIn('O.storeId', $storeDetails);
			
			if(!empty($storeFilter)) {
				$multipleRefund = $multipleRefund->where('O.storeId', $storeFilter);
			}
			$multipleRefund = $multipleRefund->get();
			
			if($multipleRefund)
				$multipleRefund = $multipleRefund[0]->refund;
			else
				$multipleRefund = 0;
			/* print_r($multipleRefund);
			die; */

			//$cashSale = $cashSales[0]->cash;
			/* Multiple Refund ENd */

			$cashSales = DB::table('orders_pos as O')
			->select('O.id', DB::raw('(SUM(O.totalAmount)- SUM(O.refundTotalAmount) ) as cash'))
			->where('O.paymentStatus', 'CASH')
			->whereBetween(DB::raw('Date(O.created_at)'), [$startDate, $endDate])
			->whereIn('O.storeId', $storeDetails);
			
			if(!empty($storeFilter)) {
				$cashSales = $cashSales->where('O.storeId', $storeFilter);
			}
			$cashSales = $cashSales->get();
			
			$cashSale = 0;
			if(!empty($cashSales))
				$cashSale = $cashSales[0]->cash;
			
			//if($multipleMode)
			/* print_r($cashSales[0]->cash);
			die; */

			
			$cardSales = DB::table('orders_pos as O')
			->select('O.id', DB::raw('(SUM(O.totalAmount)- SUM(O.refundTotalAmount) ) as card'))
			->whereBetween(DB::raw('Date(O.created_at)'),[$startDate,$endDate])
			->where('O.paymentStatus', 'CARD')
			->whereIn('storeId', $storeDetails);

			if(!empty($storeFilter)) {
				$cardSales = $cardSales->where('O.storeId', $storeFilter);
			}
			$cardSales = $cardSales->get();
			$cardSale = $cardSales[0]->card;
			/* print_r($cardSale);
			print_r($result->totalAmount);
			die; */
			/* print_r($cashSales[0]->cash);
			die; */
			/*  */
			foreach($multipleMode as $result) {
				if($result->paymentMode == 'CASH') {
					$cashSale = $cashSale + $result->totalAmount - $multipleRefund;
				}
				else if($result->paymentMode == 'CARD') {
					$cardSale= $cardSale + $result->totalAmount;
					/* print_r($result->totalAmount);
					die; */
				}
				
				/* print_r($cardSale);
				die; */
				//$results['multipleCount'] = $results['multipleCount'] + $result->multipleCount;
			}

			/*  */

			/* Total Card and Card Revenue End */

			/* Total Profit Percentage Start */
			
			$profitPercentage = DB::table('reports')
			->select('productName','price','costPrice', 'storeId', DB::raw('ROUND((((SUM(price) - SUM(costPrice))/SUM(costPrice)) * 100),2) as percentprofit'), DB::raw('ROUND((((SUM(price) - SUM(costPrice))/SUM(price)) * 100),2) as percentprofitgross'))
			->whereIn('storeId', $storeDetails);

			if(!empty($storeFilter)) {
				$profitPercentage = $profitPercentage->where('storeId', $storeFilter);
			}
			if(!empty($startDate) && !empty($endDate)) {
				$profitPercentage = $profitPercentage->whereBetween(DB::raw('Date(created_at)'),[$startDate,$endDate]);
			}

			$profitPercentage = $profitPercentage->groupBy('productName')->get();

			//dd(count($profitPercentage));
			//die;
			
			
			
			/* print_r($startDate);
			print_r($endDate);
			print_r($storeDetails);
			print_r($profitPercentage);
			die; */
			/* Total Profit Percentage End */

			return view('admin.dashboard.index',compact('orderplaced', 'allcustomer', 'allStores', 'activestores', 'storedata', 'todayOrderCount', 'allorderCount', 'todayRevenueCount', 'revenues', 'storeDetails', 'topSellingData', 'lastdaysRevenue', 'maxInventory', 'outOfStock', 'instock', 'productNotAvailable','productAvailable', 'lowInventory', 'allProducts', 'storeFilter', 'startDate', 'endDate','revenueLabels','revenueData','billLabels','billData','basketLabels','basketData', 'cashSale', 'cardSale', 'profitPercentage')); 
    		
        }
		
    }

}