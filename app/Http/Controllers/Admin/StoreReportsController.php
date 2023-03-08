<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Reports;
use App\Helpers\AppHelper as Helper;
use App\Exports\ProfitLossReportExport;
use Maatwebsite\Excel\Facades\Excel;

class StoreReportsController extends Controller
{
    public function index($storeId)
    {      

        $storedata = DB::Table('stores as S')->leftJoin('mas_country', 'mas_country.id', '=', 'S.countryId')->leftJoin('users', 'users.id', '=', 'S.userId')->leftJoin('mas_storetype','mas_storetype.id','=','S.storeType')
		->select('S.id','S.storeName','users.contactNumber','users.email','S.regNo','S.state','S.city','S.appVersion','mas_country.nicename','users.firstName','mas_storetype.name','users.lastName','S.address','S.latitude','S.longitude','S.deviceType','S.appType','S.shopSize','S.vatNumber','S.printStoreNameAr','S.printAddAr','S.manageInventory','S.smsAlert','S.printFooterEn','S.printFooterAr','S.autoGlobalCat','S.onlineMarket','S.loyaltyOptions','S.autoGlobalItems','S.chatbot', DB::raw("DATE_FORMAT(S.subscriptionExpiry, '%d-%b-%Y') as subscriptionExpiryDate") ,DB::raw("DATE_FORMAT(S.created_at, '%d-%b-%Y') as storeCreatedOn"),'users.id as userId')
		->where('S.id', $storeId)->first();
		
		
		$orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
		->select('O.id','O.created_at','S.id')
		->whereDate('O.created_at', Carbon::today())
		->where('S.id', $storeId)
		->get();
		
		$todayorderCount = $orderplaced->count();
	    
	    
		
        $customer = DB::Table('customers as C')->leftJoin('stores as S','S.id' ,'=','C.storeName')
        ->select('C.id')
         ->where('S.id', $storeId)->
         get();

		$allcustomer = $customer->count();
		


		$orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
		->select('O.id','O.created_at','S.id')
		->where('S.id', $storeId)
		->get();

		$allorderCount = $orderplaced->count();
		

    	$revenue = DB::Table('stores as S')->leftJoin('orders_pos as O','O.userId','=','S.userId')
                ->select(DB::raw('SUM(O.totalAmount) as totalAmount'))->whereDate('O.created_at', Carbon::today())
                ->where('S.id', $storeId)
                ->get();
		
		$revenue = $revenue[0]->totalAmount;

		return view('admin.storereports.index',compact('storeId', 'orderplaced','storedata','todayorderCount','allorderCount','allcustomer','revenue'));
    }
	
	public function salesreports($storeId)
    {
		
		$type = "today";
		$reportType = "daily";
		if(isset($_GET['startDate']) && !empty($_GET['startDate'])) {
		    $startDate = $_GET['startDate'];
			$customStartDate = $_GET['startDate'] . ' 00:00:00';
		}
		else{
			$startDate = '';
		}
		if(isset($_GET['endDate']) && !empty($_GET['startDate'])) {
		    $endDate = $_GET['endDate'];
			$customEndDate = $_GET['endDate'] . ' 23:59:59';
		}
		else{
			$endDate ='';
		}
	
		if(isset($_GET['type']))
			$type = $_GET['type'];
		
		if(isset($_GET['search']) && !empty($_GET['search'])) {
			$search = $_GET['search'];
		}
		
		
	   
	    $customStartDate = $startDate . ' 00:00:00';
		$customEndDate = $endDate . ' 23:59:59';
		$fromDate = 0;
        $toDate = 0;
		
	    if(empty($startDate)) {
	        $customStartDate = new Carbon('first day of January 2021');
	    }
	        
	    if(empty($endDate))
	        $customEndDate = Carbon::now()->toDateString() . ' 23:59:59';
		
		
		
		
		
		
		if(isset($_GET['reportType']))
			$reportType = $_GET['reportType'];
		
		if($reportType == 'billwise' && !isset($_GET['type'])) 
		    $type = "billwisetoday";
		
		if(isset($_GET['type']))
			$type = $_GET['type'];
		
		
		
		
	    $totalVat = 0;
	    $totalSumAmount = 0;
	    
	    $fromDate = 0;
        $toDate = 0;
        
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
			/* print_r($queryData);
			die; */
	    }
	    else {
	    
    	    // Reference
    	    
            
            if($type == 'today' || $type == 'yesterday' || $type == 'thismonth' || $type == 'billwisethismonth' || $type == 'billwisetoday'|| $type == 'lastsixmonths' || $type == 'custom') {
                $queryData = DB::table('orders_pos')
                    ->select(DB::raw('COUNT(id) as totalBill'), DB::raw('ROUND((SUM(vat) - SUM(refundVat)),2) as vat'), DB::raw('(SUM(totalAmount) - SUM(refundTotalAmount)) as totalAmount'), DB::raw('Date(created_at) as created_at'))
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
    	        
    	        $startDate = $checkDate;
                $endDate = $checkDate;
    	    }
    	    else if($type == 'yesterday') {
    	        $checkDate = Carbon::now()->subDays(1)->toDateString();
    	        
    	        $queryData = $queryData->where(DB::raw('Date(created_at)'),'=',$checkDate);
    	        
    	        $startDate = $checkDate;
                $endDate = $checkDate;
    	    }
    	    else if($type == 'thismonth' || $type == 'billwisethismonth') {
    	        $checkDate = Carbon::now()->month;
    	        
    	        $queryData = $queryData->where(DB::raw('Month(created_at)'),'=',$checkDate);
    	        
    	        // This needs to fixed as start date will be first date of the month
    	        //$fromDate = $checkDate;
    	        $startDate = Carbon::now()->startOfMonth()->toDateString();
                $endDate = Carbon::now()->toDateString();
    	    }
    	    /*else if($type == 'quartely') {
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
    	    }*/
    	    else if($type == 'custom' || $type == 'billwisecustom') {
    	        $checkDate = Carbon::now()->subMonths(6)->toDateString();
    	        
    	        $queryData = $queryData->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate]);
    	        //print_r($queryData);
    	        
    	        $fromDate = $customStartDate;
                $toDate = $customEndDate;
				
				$startDate = $_GET['startDate'];
				$endDate = $_GET['endDate'];
    	    }
    	    

            if($type == 'today' || $type == 'yesterday' || $type == 'thismonth' || $type == 'custom' || $type == 'billwisetoday' || $type == 'billwisethismonth') {
                $queryData = $queryData->groupBy(DB::raw('Date(created_at)'))
                    ->orderBy('created_at','DESC');
				
            }
            /*else if($type == 'lastsixmonths') {
            //else if($type == 'lastsixmonths' || $type == 'custom') {
                $queryData = $queryData->groupBy(DB::raw('Month(created_at)'))
                    ->orderBy('created_at','DESC');
            }*/
            else {
                $queryData = $queryData->orderBy('created_at','DESC');
            }
			
			
			//print_r($fromDate);
			//print_r($toDate);
			//die;
			
			
			
            
            
	    }
		
		if($reportType == 'category'){
			$queryData = DB::table('reports')
            ->select(DB::raw("(CASE WHEN `categoryName` IS NULL THEN 'Custom' ELSE `categoryName` END) as categoryName"), DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'), DB::raw('ROUND((SUM(total) - SUM(refundTotal) - SUM(total*(discPer/100))),2) as totalAmount'))
			->groupBy('categoryName')
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
				$toDate = Carbon::now()->toDateString();
				
				$startDate = Carbon::now()->startOfMonth()->toDateString();
                $endDate = Carbon::now()->toDateString();
				
			}
			if($type == 'custom') {
				//print_r($customStartDate);
				//print_r($customEndDate);
				$queryData = $queryData->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate]);
				$fromDate = $customStartDate;
                $toDate = $customEndDate;
				$startDate = $_GET['startDate'];
				$endDate = $_GET['endDate'];
			}
		  
			if($type == 'catwisetoday' || $type == 'catwiseyesterday') 
			{
				$queryData = $queryData->orderBy('created_at','DESC');
				$fromDate = $checkDate;
				$toDate = $checkDate;
				$startDate = $checkDate;
                $endDate = $checkDate;
			}
			
			
		}
		if($reportType == 'productwise'){
			$queryData = DB::table('reports')
            ->select('productName' , DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'), DB::raw('ROUND((SUM(total) - SUM(refundTotal) - SUM(total*(discPer/100))),2) as totalAmount'),'price')
			->groupBy('productName')
			->orderBy('id', 'DESC')
            ->where('storeId',$storeId);
			
			if($type == 'producttoday') {
			$checkDate = Carbon::now()->toDateString();
			$queryData = $queryData->where(DB::raw('Date(created_at)'),'=',$checkDate);
			$startDate = $checkDate;
            $endDate = $checkDate;
			}
			
			
			
			if($type == 'productthismonth') 
			{
				$checkDate = Carbon::now()->month;
				$queryData = $queryData->where(DB::raw('Month(created_at)'),'=',$checkDate);
				$fromDate = Carbon::now()->startOfMonth()->toDateString();
				$toDate = Carbon::now()->toDateString();
				$startDate = Carbon::now()->startOfMonth()->toDateString();
                $endDate = Carbon::now()->toDateString();
				
				//print_r($startDate);
				//die;
				
			}
			if($type == 'custom') {
				print_r($customStartDate);
				print_r($customEndDate);
				$queryData = $queryData->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate]);
				$fromDate = $customStartDate;
                $toDate = $customEndDate;
				$startDate = $_GET['startDate'];
				$endDate = $_GET['endDate'];
				//print_r($endDate);
				//die;
			}
		  
			if($type == 'producttoday') 
			{
				$queryData = $queryData->orderBy('created_at','DESC');
				$fromDate = $checkDate;
				$toDate = $checkDate;
			}
			
			
		}
	    
		
		
		
		
        $completeData = $queryData->get();
        
        foreach($completeData as $data) {
			if($reportType != 'category' && $reportType != 'productwise'){
           		 $totalVat += $data->vat;
			}
            $totalSumAmount += $data->totalAmount;
        }
       

        $queryData = $queryData->paginate(10);

        
        
        $results['bills'] = $queryData;
        $results['totalVat'] = round($totalVat,2);
        $results['totalSumAmount'] = round($totalSumAmount,2);
        $results['totalBills'] = count($completeData);

        $results['fromDate'] = $fromDate;
        $results['toDate'] = $toDate;
		
		//print_r($results['bills']);
		//die;
		
		
		
		return view('admin.storereports.salesreports', compact('storeId', 'results', 'reportType', 'type', 'startDate', 'endDate'));
    }
	
	public function vatreports($storeId)
    {   
		$type = "vattoday";
		$search ='';
		
		if(isset($_GET['startDate']) && !empty($_GET['startDate'])) {
		    $startDate = $_GET['startDate'];
			$customStartDate = $_GET['startDate'] . ' 00:00:00';
		}
		else{
			$startDate = '';
		}
		if(isset($_GET['endDate']) && !empty($_GET['startDate'])) {
		    $endDate = $_GET['endDate'];
			$customEndDate = $_GET['endDate'] . ' 23:59:59';
		}
		else{
			$endDate ='';
		}
	
		if(isset($_GET['type']))
			$type = $_GET['type'];
		
		if(isset($_GET['search']) && !empty($_GET['search'])) {
			$search = $_GET['search'];
		}
		
		
	    /*if(isset($_GET['start']))
			$startDate = $_GET['start'];
		else
			$startDate = '';
		
		if(isset($_GET['end']))
			$endDate = $_GET['end'];
		else
			$endDate = '';
	    */
	    $customStartDate = $startDate . ' 00:00:00';
		$customEndDate = $endDate . ' 23:59:59';
		$fromDate = 0;
        $toDate = 0;
		
	    if(empty($startDate)) {
	        $customStartDate = new Carbon('first day of January 2021');
	    }
	        
	    if(empty($endDate))
	        $customEndDate = Carbon::now()->toDateString() . ' 23:59:59';
	        
        $queryPurchaseData = DB::table('vendorInvoice')->select(DB::raw('ROUND((SUM(vatAmount)),2) as totalVat'))->where('status','Complete')->where('storeId',$storeId);
        
        
        
        
        $queryData = DB::table('reports')
                ->select('productName',DB::raw('ROUND((SUM(price) - SUM(refundPrice)),2) as sellingPrice'),DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'),DB::raw('ROUND(SUM(total) - SUM(refundTotal),2) as totalAmount'),
                DB::raw('ROUND((SUM(vat) - SUM(refundVat)),2) as vat'),DB::raw('ROUND((SUM(costPrice*quantity) - SUM(costPrice*refundQuantity)),2) as totalcostPrice'), DB::raw('ROUND((SUM(vat/quantity)*100)/(SUM(price) - SUM(vat/quantity)),2) as vatPer'))
                //->select('productName',DB::raw('ROUND((SUM(price) - SUM(refundPrice)),2) as sellingPrice'),DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'),DB::raw('ROUND(SUM(total) - SUM(refundTotal),2) as totalAmount'),DB::raw('ROUND((SUM(vat) - SUM(refundVat)),2) as vat'),DB::raw('ROUND((SUM(costPrice) - SUM(refundCostPrice) ),2) as totalcostPrice'), DB::raw('ROUND((SUM(vat/quantity)*100)/(SUM(price) - SUM(vat/quantity)),2) as vatPer'))
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
			$startDate = $checkDate;
            $endDate = $checkDate;
        }
        
        if($type == 'vatyesterday') {
        
           $checkDate = Carbon::now()->subDays(1)->toDateString();
    	   $queryData = $queryData->where(DB::raw('Date(created_at)'),'=',$checkDate);
    	   
    	   $queryPurchaseData = $queryPurchaseData->where(DB::raw('Date(created_at)'),'=',$checkDate);
		   $startDate = $checkDate;
           $endDate = $checkDate;
    	   
        }
        
        if($type == 'vatthismonth') 
        {
            $checkDate = Carbon::now()->month;
    	    $queryData = $queryData->where(DB::raw('Month(created_at)'),'=',$checkDate);
    	   
    	    $fromDate = Carbon::now()->startOfMonth()->toDateString();
    	    
    	    //echo $fromDate;
    	    //die;
    	    
            $toDate = Carbon::now()->toDateString();
			
			$startDate = $fromDate;
            $endDate = $toDate;
        }
        
        
        
        
        
        /*if($type == 'vatlastsixmonths') {

            $checkDate = Carbon::now()->subMonths(6)->toDateString();
    	    $queryData = $queryData->where(DB::raw('Date(created_at)'),'>=',$checkDate);
    	    
    	    $queryPurchaseData = $queryPurchaseData->where(DB::raw('Date(created_at)'),'>=',$checkDate);
                
        }
        */
        if($type == 'vatcustom') {
           $queryData = $queryData->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate]);
           $queryPurchaseData = $queryPurchaseData->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate]);
           $fromDate = $customStartDate;
           $toDate = $customEndDate;
		   $startDate = $_GET['startDate'];
		   $endDate = $_GET['endDate'];
        }
        
        
        if($type == 'vattoday' || $type == 'vatyesterday') 
        {
            $fromDate = $checkDate;
            $toDate = $checkDate;
        }
        
        $queryData = $queryData->groupBy('productName','price')->orderBy('id','DESC');
		
		if(!empty($search))
		    $queryData = $queryData->where('productName', 'LIKE', $search.'%');
        
        $completeData = $queryData->get();
        $completePurchaseData = $queryPurchaseData->first();

        $totalVatPurchase = $completePurchaseData->totalVat;
        
        
       
        $totalSumAmount = 0;
        $totalVatAmount = 0;
        
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
		
		$rows = $queryData;
		
		
		
		return view('admin.storereports.vatreports',compact('storeId','results','startDate','endDate', 'search', 'type'));
    }
	
	public function refundreports($storeId)
    {   
		$type = "refundtoday";
		if(isset($_GET['startDate']) && !empty($_GET['startDate'])) {
		    $startDate = $_GET['startDate'];
			$customStartDate = $_GET['startDate'] . ' 00:00:00';
		}
		else{
			$startDate = '';
		}
		
		if(isset($_GET['endDate']) && !empty($_GET['startDate'])) {
		    $endDate = $_GET['endDate'];
			$customEndDate = $_GET['endDate'] . ' 23:59:59';
		}
		else{
			$endDate = '';
		}
	
		if(isset($_GET['type']))
			$type = $_GET['type'];
		
		if(isset($_GET['search']) && !empty($_GET['search'])) {
			$search = $_GET['search'];
		}
	    
	    $customStartDate = $startDate . ' 00:00:00';
		$customEndDate = $endDate . ' 23:59:59';
		
	    if(empty($startDate)) {
	        $customStartDate = new Carbon('first day of January 2021');
	    }
	        
	    if(empty($endDate))
	        $customEndDate = Carbon::now()->toDateString() . ' 23:59:59';
		
	    $totalSumAmount = 0;
        
        $fromDate = 0;
        $toDate = 0;
        
        $queryData = DB::table('reports')
                ->leftJoin('orders_pos','orders_pos.orderId','=','reports.orderNumber')
                ->leftJoin('customers','customers.id','=','orders_pos.customerId')
                ->select('reports.created_at','reports.orderNumber',DB::raw('SUM(reports.quantity) as qty'),DB::raw('SUM(reports.refundQuantity) as refundQty'),DB::raw('SUM(reports.refundTotal) as totalAmount'),'customers.customerName')
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
			$startDate = $_GET['startDate'];
			$endDate = $_GET['endDate'];
        }
        
		
        if($type == 'refundtoday' || $type == 'refundyesterday') 
        {
            $queryData = $queryData->orderBy('reports.updated_at','DESC');
            
            $fromDate = $checkDate;
            $toDate = $checkDate;
			 $startDate = $checkDate;
           $endDate = $checkDate;
        }
        
        /*if($type == 'refundcustom' ) 
        {
            $queryData = $queryData->orderBy('reports.updated_at','DESC');
                    
            $fromDate = $customStartDate;
            $toDate = $customEndDate;
        }*/
        
        $completeData = $queryData->get();
        
        foreach($completeData as $data) {
            $totalSumAmount += $data->totalAmount;
        }

        $queryData = $queryData->paginate(10);
        
        $datas['refunddata'] = $queryData;
        $datas['totalSumAmount'] = round($totalSumAmount,2);
        $datas['fromDate'] = $fromDate;
        $datas['toDate'] = $toDate;
		
		
		
		
		return view('admin.storereports.refundreports',compact('storeId','datas','startDate','endDate','type'));
    }
	
	public function purchasereports($storeId)
    {
		if(isset($_GET['start']))
			$startDate = $_GET['start'];
		else
			$startDate = '';
		
		if(isset($_GET['end']))
			$endDate = $_GET['end'];
		else
			$endDate = '';
	    
	    $customStartDate = $startDate . ' 00:00:00';
		$customEndDate = $endDate . ' 23:59:59';
		
	    if(empty($startDate)) {
	        $customStartDate = new Carbon('first day of January 2021');
	    }
	        
	    if(empty($endDate))
	        $customEndDate = Carbon::now()->toDateString() . ' 23:59:59';
		
	    $totalSumAmount = 0;

		/*
        $queryData = DB::table('reports')
                //->select('productName','price',DB::raw('ROUND((SUM(price)*quantity),2) as totalamount'),DB::raw('ROUND((SUM(price)),2) as totalPrice'), DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'),DB::raw('ROUND((SUM(total) - SUM(refundTotal)),2) as totalAmount'))
                ->select('productName','price')
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
            $queryData = $queryData->groupBy('productName','price');
            $fromDate = $checkDate;
            $toDate = $checkDate;
        }
        
        if($type == 'productthismonth')
        {
            $queryData = $queryData->groupBy('productName','price');
            $fromDate = Carbon::now()->startOfMonth()->toDateString();
            $toDate = Carbon::now()->toDateString();
        }
        
        if($type == 'productcustom') 
        {
            $queryData = $queryData->groupBy('productName','price');
            $fromDate = $customStartDate;
            $toDate = $customEndDate;
        }
		*/
		
		$fromDate = $customStartDate;
        $toDate = $customEndDate;
			
		$queryData = DB::table('vendorinvoice')
                //->select('productName','price',DB::raw('ROUND((SUM(price)*quantity),2) as totalamount'),DB::raw('ROUND((SUM(price)),2) as totalPrice'), DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'),DB::raw('ROUND((SUM(total) - SUM(refundTotal)),2) as totalAmount'))
                ->select('vendorDetail','vatAmount','totalAmount','invoiceDate','invoiceNumber')
                ->where('storeId',$storeId);
        
        $completeData = $queryData->get();

		//print_r($completeData[0]->vendorDetail);
		//$completeData = json_decode($completeData->vendorDetail, true);
		//$completeData = count($completeData);
		//print_r($completeData);

		//die;
		//print_r($completeData);
		//die;
        
       
			//print_r($vendorDetailData);
			//print_r('<br>');
			//die;
		//print_r($vendorDetailData);
		/*
		foreach($vendorDetailData as $key=>$xyz) 
		{
			print_r($xyz);

			//$totalSumAmount += $data->totalAmount;
		}
		
		die;*/

        $queryData = $queryData->paginate(10);
		
        $results['productdata'] = $queryData;
        $results['fromDate'] = $fromDate;
        $results['toDate'] = $toDate;
        $results['totalSumAmount'] = round($totalSumAmount,2);

		
		
		return view('admin.storereports.purchasereports',compact('storeId','results','completeData'));
    }
	
	public function inventoryreports($storeId)
    {      
		$type = "outofstock";
		
		if(isset($_GET['type']))
			$type = $_GET['type'];
	    
        $queryData = DB::table('reports')
                ->select(DB::raw('SUM(quantity)-SUM(refundQuantity) as qty'),'productName as name','productNameAr as name_ar')
                ->where('storeId',$storeId)->groupBy('productName','productNameAr');
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
		
		return view('admin.storereports.inventoryreports',compact('storeId','results'));
    }
	
	public function mediareports($storeId)
    {
		/* if(isset($_GET['start']))
			$startDate = $_GET['start'];
		else
			$startDate = '';
		
		if(isset($_GET['end']))
			$endDate = $_GET['end'];
		else
			$endDate = '';
	    
	    $customStartDate = $startDate . ' 00:00:00';
		$customEndDate = $endDate . ' 23:59:59';
		
	    if(empty($startDate)) {
	        $customStartDate = new Carbon('first day of January 2021');
	    }
	        
	    if(empty($endDate))
	        $customEndDate = Carbon::now()->toDateString() . ' 23:59:59';
	    
	    $resultsCash = DB::table('orders_pos')->select(DB::raw('Count(id) as cashCount'), DB::raw('SUM(totalAmount - refundTotalAmount) as cashAmount'))
	    ->where('paymentStatus', '=', 'Cash')
	    ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
	    ->where('storeId','=',$storeId)->first();
	    
	    $resultsCard = DB::table('orders_pos')->select(DB::raw('Count(id) as cardCount'), DB::raw('SUM(totalAmount - refundTotalAmount) as cardAmount'))
	    ->where('paymentStatus', '=', 'Card')
	    ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
	    ->where('storeId','=',$storeId)->first();
	    
	    $resultsOther = DB::table('orders_pos')->select(DB::raw('Count(id) as otherCount'), DB::raw('SUM(totalAmount - refundTotalAmount) as otherAmount'))
	    ->where('paymentStatus', '=', 'CREDIT')
	    ->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate])
	    ->where('storeId','=',$storeId)->first();

	    $results['cashCount'] = $resultsCash->cashCount;
	    $results['cardCount'] = $resultsCard->cardCount;
	    $results['otherCount'] = $resultsOther->otherCount;
	    
	    if($results['cashCount'] == 0)
	        $results['cashAmount'] = 0;
	    else
	        $results['cashAmount'] = $resultsCash->cashAmount;
	        
	    if($results['cardCount'] == 0)
	        $results['cardAmount'] = 0;
	    else
	        $results['cardAmount'] = $resultsCard->cardAmount;
	        
	    if($results['otherCount'] == 0)
	        $results['otherAmount'] = 0;
	    else
	        $results['otherAmount'] = $resultsOther->otherAmount; */

			/* $startDate = $_GET['start'];
			$endDate = $_GET['end']; */
			if(isset($_GET['start']))
			$startDate = $_GET['start'];
			else
				$startDate = '';
			
			if(isset($_GET['end']))
				$endDate = $_GET['end'];
			else
			$endDate = '';
			
			if(empty($startDate))
				$startDate = new Carbon('first day of January 2021');
				
			if(empty($endDate))
				$endDate = Carbon::now()->toDateString();
			
			$resultsCash = DB::table('orders_pos')->select(DB::raw('Count(id) as cashCount'), DB::raw('SUM(totalAmount - refundTotalAmount) as cashAmount'), DB::raw('SUM(refundTotalAmount) as refundTotalAmount'))
			->where('paymentStatus', '=', 'Cash')
			->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate])
			->where('storeId','=',$storeId)->first();
			
			$resultsCashRefund = DB::table('orders_pos')->select(DB::raw('Count(id) as cashRefundCount'))
			->where('refundTotalAmount','>','0')
			->where(function($query) {
				$query->orwhere('paymentStatus', '=', 'Cash')
					->orwhere('paymentStatus', '=', 'Multiple');
			})
			->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate])
			->where('storeId','=',$storeId)->first();
			
			$resultsCard = DB::table('orders_pos')->select(DB::raw('Count(id) as cardCount'), DB::raw('SUM(totalAmount - refundTotalAmount) as cardAmount'), DB::raw('SUM(refundTotalAmount) as refundTotalAmount'))
			->where('paymentStatus', '=', 'Card')
			->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate])
			->where('storeId','=',$storeId)->first();
			
			$resultsCardRefund = DB::table('orders_pos')->select(DB::raw('Count(id) as cardRefundCount'))
			->where('refundTotalAmount','>','0')
			->where('paymentStatus', '=', 'Card')
			->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate])
			->where('storeId','=',$storeId)->first();
			
			$resultsOther = DB::table('orders_pos')->select(DB::raw('Count(id) as otherCount'), DB::raw('SUM(totalAmount - refundTotalAmount) as otherAmount'))
			->where('paymentStatus', '=', 'Credit')
			->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate])
			->where('storeId','=',$storeId)->first();
			
			$resultsCreditRefund = DB::table('orders_pos')->select(DB::raw('Count(id) as creditRefundCount'))
			->where('refundTotalAmount','>','0')
			->where('paymentStatus', '=', 'Credit')
			->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate])
			->where('storeId','=',$storeId)->first();
			
			// Calculate Sum of refund in case of multiple. This will be subtracted from Cash amount
			$resultsMultipleRefund = DB::table('orders_pos')->select(DB::raw('SUM(refundTotalAmount) as multipleRefundAmount'))
			->where('paymentStatus', '=', 'Multiple')
			->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate])
			->where('storeId','=',$storeId)->first();
			
			$resultsMultipleCash = DB::table('multiplepayment')->select('paymentMode', DB::raw('Count(id) as multipleCount'), DB::raw('SUM(amount) as totalAmount'))
			->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate])
			//->where(TJ8082301031981)
			->where('storeId','=',$storeId)->groupBy('paymentMode')->get();
			
			$refundedAmount = DB::table('orders_pos')->select(DB::raw('SUM(refundTotalAmount) as RefundAmount'),DB::raw('Count(id) as refundCount'))
			 ->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate])
			->where('storeId','=',$storeId)
			->where('refundTotalAmount','>','0')
			->first();
			
			$salesCount =  DB::table('orders_pos')->select(DB::raw('Count(id) as salesCount'))
			 ->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate])
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
			
			//print_r($results);
		
		return view('admin.storereports.mediareports',compact('storeId','results','startDate','endDate'));
    }
	
	public function cashierreports($storeId)
    {
		
		if(isset($_GET['start']))
			$startDate = $_GET['start'];
		else
			$startDate = '';
		
		if(isset($_GET['end']))
			$endDate = $_GET['end'];
		else
			$endDate = '';
		
	    if(isset($_GET['search']))
			$search = $_GET['search'];
		else
			$search = '';
	    
	    $customStartDate = $startDate . ' 00:00:00';
		$customEndDate = $endDate . ' 23:59:59';
		
	    if(empty($startDate)) {
	        $customStartDate = new Carbon('first day of January 2021');
	    }
	        
	    if(empty($endDate))
	        $customEndDate = Carbon::now()->toDateString() . ' 23:59:59';
		
	    $results = DB::table('cashier as C')
		->leftJoin('users as U','U.id','=','C.userId')
	    ->leftJoin('orders_pos as O','O.userId','=','C.userId')
		->select(DB::raw('CONCAT(U.firstName, " ", U.lastName) AS Name'), 'U.id as userId', 'U.contactNumber', 'U.email', DB::raw('Count(O.id) as billCount'), DB::raw('SUM(O.totalAmount - O.refundTotalAmount) as totalSales'))
	    ->whereBetween(DB::raw('Date(O.created_at)'), [$customStartDate, $customEndDate])
	    ->where('C.storeId','=',$storeId);
	    
	    if(!empty($search))
		    $results = $results->where('U.firstName', 'LIKE', $search.'%');
		    
	    $results = $results->groupBy('userId', 'U.contactNumber', 'U.email')->get();
		
		return view('admin.storereports.cashierreports',compact('storeId','results','startDate','endDate','search'));
    }

	public function profitlossreports($storeId)
    {
		$storeId = $storeId;

		if(isset($_GET['export']))
			$export = $_GET['export'];
		else
			$export = '';

		if(isset($_GET['search']))
			$search = $_GET['search'];
		else
			$search = '';

		if(isset($_GET['start']))
			$startDate = $_GET['start'];
		else
			$startDate = Carbon::now()->toDateString();
		
		if(isset($_GET['end']))
			$endDate = $_GET['end'];
		else
			$endDate = Carbon::now()->toDateString();


		$results = DB::table('reports')
		->select('productName','price','costPrice', 'storeId', DB::raw('SUM(quantity) as qty'), DB::raw('(price - costPrice) as margin'),DB::raw('ROUND((((price - costPrice)/costPrice) * 100),2) as percentprofit'), DB::raw('((price - costPrice) * (SUM(quantity))) as totalMargin'))
		->where('storeId', $storeId);

		if(!empty($search)) {
			$results = $results->where('productName','LIKE', '%' . $search . '%');
		}
		if(!empty($startDate) && !empty($endDate)) {
			$results = $results->whereBetween(DB::raw('Date(created_at)'),[$startDate,$endDate]);
		}

		$results = $results->groupBy('productName')->orderBy('qty','DESC')->paginate(10);
		/* print_r($results);
		die;
 		*/
		 if(isset($export) && $export == 'yes') {
			
			$startExport = explode('-',$startDate);
			$startExportDate = $startExport[1].''.$startExport[2];

			$endExport = explode('-',$endDate);
			$endExportDate = $endExport[1].''.$endExport[2];

			
			/* print_r(gettype ($startExport));
			print_r($startDate);
			print_r($startExport);
			print_r($startExportDate);
			die; */
            $fileName = 'p&l_'.$startExportDate.'_'.$endExportDate.'.csv';
            return Excel::download(new ProfitLossReportExport($storeId), $fileName  );
        }

		return view('admin.storereports.profitlossreports',compact('storeId','results','search','startDate','endDate'));
    }
	public function profitlossreportsexport(Request $request)
    {
		print_r(123);
		die;
		/* print_r($results);
		die;
 */
		return view('admin.storereports.profitlossreports',compact('storeId','results','search','startDate','endDate'));
    }

	public function shiftreports($storeId,Request $request)
    {
		
		$startDate = $request->start;
        $endDate = $request->end;
		
		
		
	    if(isset($_GET['search']))
			$search = $_GET['search'];
		else
			$search = '';
	   
		
	    $results = DB::table('usersshift as US')
		->leftJoin('stores as S','S.id','=','US.storeId')
		->select ( DB::raw('SUM(US.shiftEndCDBalance) as shiftEndCDBalance'), DB::raw('SUM(US.shiftEndBalance) as shiftEndBalance'), DB::raw('ROUND((SUM(US.shiftEndBalance) - SUM(US.shiftEndCDBalance)),2) as adjustAmount'), DB::raw('COUNT(US.id) as totalShifts'), DB::raw('Date(US.created_at) as dateCreated'), DB::raw('ROUND(SUM(US.shiftEndBalance),2) as totalAmount'),'US.storeId')
		->where('US.storeId',$storeId)
		->where('US.status','Closed');
		

		
		
		
		
        if(isset($request->start) && isset($request->end)) {
            $startDate = $request->start . ' 00:00:00';
          // print_r ($startdate = $request->start_date);
            $endDate = $request->end . ' 23:59:59';
           
            
            $results = $results->whereBetween(DB::raw('Date(US.created_at)'),[$request->start,$request->end]);
            
            $startDate = $request->start;
		$endDate = $request->end;
        }
		
		
		$results = $results->groupBy(DB::raw('Date(US.created_at)'))
        ->orderBy(DB::raw('Date(US.created_at)'),'DESC')->paginate(10);
		

		
		return view('admin.storereports.shiftreports',compact('storeId','results','startDate','endDate','search'));
    }

	public function shiftdayreport($storeId, $shiftDate, Request $request)
    {
		$storeId =$storeId;
		$shiftDate = $shiftDate;
		$startDate = $request->start;
        $endDate = $request->end;
		
		
		
	    if(isset($_GET['search']))
			$search = $_GET['search'];
		else
			$search = '';
	    
	     $results = DB::table('usersshift as US')
		//->leftJoin('stores as S','S.id','=','US.storeId')
		->leftJoin('users as U','U.id','US.userId')
		->select ('US.id','US.shiftId','US.storeId','U.firstName','U.lastName',DB::raw('Date(US.created_at) as dateCreated'),'US.shiftEndBalance','US.shiftEndCDBalance','US.shiftInCDBalance', 'US.shiftInBalance','US.userId', DB::raw('(US.shiftEndBalance - US.shiftEndCDBalance) as adjustAmount'))
		->where(DB::raw('Date(US.created_at)'),$shiftDate)
		->where('US.storeId',$storeId)
		->where('US.status','Closed');
		//->groupBy(DB::raw('Date(US.created_at)'))
        //->orderBy(DB::raw('Date(US.created_at)'),'DESC');
		
		if(isset($request->start) && isset($request->end)) {
            $startDate = $request->start . ' 00:00:00';
            $endDate = $request->end . ' 23:59:59';
            $results = $results->whereBetween(DB::raw('Date(US.created_at)'),[$request->start,$request->end]);
            
        }
		
		
		$results = $results->paginate(10);
		
		/* print_r($results);
		die; */
		
		
	    
		
		return view('admin.storereports.shiftdayreport', compact('results', 'startDate', 'endDate', 'search', 'storeId', 'shiftDate'));
    }
	public function shiftreport($id)
    {
	
		$results = DB::table('usersshift as US')
		->leftJoin('users as U','U.id','US.userId')
		->leftJoin('mas_reason as M','M.id','US.shiftEndReason')
		->select ('US.shiftId','US.storeId','U.firstName','U.lastName',DB::raw('Date(US.created_at) as dateCreated'), 'US.shiftEndBalance', 'US.shiftInBalance', 'US.shiftInCDBalance', 'US.shiftEndCDBalance', 'US.userId', 'US.shiftInTime', 'US.shiftEndTime', DB::raw('(US.shiftEndBalance - US.shiftEndCDBalance) as adjustAmount'), 'US.shiftEndReason', 'M.name as reason')
		->where('US.id',$id)
		->first();

		$userId = $results->userId;
		$shiftInTime = $results ->shiftInTime;
		$shiftEndTime = $results ->shiftEndTime;

		/* $cashSale = DB::table('orders_pos as O')
		->select('O.id', DB::raw('COUNT(O.id) as totalBills'),'orderId', 'O.created_at')
		->whereBetween(DB::raw('O.created_at'),[$shiftInTime,$shiftEndTime])
		->where('O.userId', 884)
		->groupBy('id')
		->orderBy('id', 'DESC')
		->get(); */
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


		
		/* print_r($shiftInTime);
		print_r($shiftEndTime); */
		/* print_r($refundAmounts);
		die; */
		
		return view('admin.storereports.shiftreport',compact('results','billCount', 'cashSales', 'cardSales', 'refundAmounts'));
    }
	
	
}
