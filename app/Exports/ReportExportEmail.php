<?php

namespace App\Exports;

use DB;
use App\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class ReportExportEmail implements FromView
{
    public function view(): View
    {

        $storeId = $_GET['storeId'];
        $type = $_GET['type'];
        $customStartDate = $_GET['start'];
        $customEndDate = $_GET['end'];
        $totalVatAmount = 0;
        $totalVat = 0;
        $refundQty = 0;
        $customerName = '';
        $productName = '';
        $price = 0;
        $sellingPrice = 0;
        $qty = 0;
        $vat = 0;
        $name = 0;
        $vatPer = 0;
        $costPrice = 0;
        $totalMargin = 0;
        $margin = 0;
        $totalSumAmount = 0;
        $startDate = 0;
        $totalAmount = 0;
        $endDate = 0;
        $fromDate = 0;
        $toDate = 0;
        $orderNumber = 0;
        $created_at = 0;
        $search = $_GET['search'] ?? '';  
        /* Type are define as Report-wise Start */
        /*
        for Vat Report The Type are {
          (type == 'vattoday' || type == 'vatyesterday' || type == 'vatthismonth')
        }


        */
        /* Type are define as Report-wise End */



        if($type == 'mediatoday')
        {


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

            $results['cashCount'] = $resultsCash->cashCount;
            $results['cardCount'] = $resultsCard->cardCount;
            $results['otherCount'] = $resultsOther->otherCount;
            $results['refundCount'] = $refundedAmount->refundCount;
            $results['salesCount'] = $salesCount->salesCount;
            $results['fromDate'] = $customStartDate;
            $results['toDate'] = $customEndDate;


        
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
                return view('admin.csvreports.mediareportexports', [
                    'mediaresult' => $results
                ]);
        } 

        if($type == 'refundtoday' || $type == 'refundyesterday' || $type == 'refundcustom'){
            $checkDate = Carbon::now()->toDateString();
            $search=trim($search);
            
          
            $queryData = DB::table('reports')
                ->leftJoin('orders_pos', 'orders_pos.orderId', '=', 'reports.orderNumber')
                ->leftJoin('customers', 'customers.id', '=', 'orders_pos.customerId')
                ->select('reports.created_at', 'reports.orderNumber', DB::raw('SUM(reports.quantity) as qty'), DB::raw('SUM(reports.refundQuantity) as refundQty'), DB::raw('SUM(reports.refundTotal) as totalAmount'), 'customers.customerName')
                ->where('reports.refundQuantity', '>', 0)
                ->groupBy('reports.orderNumber')
                ->where('reports.storeId', $storeId);

            if($type == 'refundtoday') {
                $queryData = $queryData->where(DB::raw('Date(reports.updated_at)'), '=', $checkDate);
            }
            else if ($type == 'refundyesterday') {
                /* $queryData = $queryData->groupBy('reports.orderId')
                    ->orderBy('reports.updated_at', 'DESC'); */
                $checkDate = Carbon::now()->subDays(1)->toDateString();
    	        $queryData = $queryData->where(DB::raw('Date(reports.updated_at)'),'=',$checkDate);

                $fromDate = $checkDate;
                $toDate = $checkDate;
            }
            else if($type == 'refundcustom') {
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

            if(!empty($search)) {
                
    		 $queryData = $queryData->where(function($query) use ($search) {
                $query->orwhere('reports.orderNumber', 'LIKE', $search.'%')
                    ->orwhere('customers.customerName', 'LIKE', $search.'%');
                    
            });
            }

            $completeData = $queryData->get();
        
            foreach($completeData as $data) {
                $orderNumber = $data->orderNumber;
                $created_at = $data->created_at;
                $customerName = $data->customerName;
                $refundQty = $data->refundQty;
                $qty = $data->qty;
                $totalAmount = $data->totalAmount;
            }
    
            $queryData = $queryData->get();
            
            $results['refunddata'] = $queryData;
            $results['orderNumber'] = $orderNumber;
            $results['created_at'] = $created_at;
            $results['customerName'] = $customerName;
            $results['refundQty'] = $refundQty;
            $results['qty'] = $qty;
            $results['totalAmount'] = $totalAmount;
            $results['fromDate'] = $fromDate;
            $results['toDate'] = $toDate;
            
            return view('admin.csvreports.test', [
                'refundresult' => $results
              
            ]);


        }
        else if ($type == 'vattoday' || $type == 'vatyesterday' || $type == 'vatthismonth' || $type == 'vatcustom') {
           
            $queryPurchaseData = DB::table('vendorInvoice')->select(DB::raw('ROUND((SUM(vatAmount)),2) as totalVat'))->where('status', 'Complete')->where('storeId', $storeId);

            $queryData = DB::table('reports')
                ->select(
                    'productName',
                    DB::raw('ROUND((SUM(price) - SUM(refundPrice)),2) as sellingPrice'),
                    DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'),
                    DB::raw('ROUND(SUM(total) - SUM(refundTotal),2) as totalAmount'),
                    DB::raw('ROUND((SUM(vat) - SUM(refundVat)),2) as vat'),
                    DB::raw('ROUND((SUM(costPrice*quantity) - SUM(costPrice*refundQuantity)),2) as totalcostPrice'),
                    DB::raw('ROUND((SUM(vat/quantity)*100)/(SUM(price) - SUM(vat/quantity)),2) as vatPer'),
                    DB::raw('ROUND((SUM(costPrice*quantity) - SUM(costPrice*refundQuantity)),2) as costPrice')
                )
                ->where('storeId', $storeId);
          

            

            if ($type == 'vattoday') {
                $checkDate = Carbon::now()->toDateString();
                $queryData = $queryData->where(DB::raw('Date(created_at)'), '=', $checkDate);
                $queryPurchaseData = $queryPurchaseData->where(DB::raw('Date(created_at)'), '=', $checkDate);

                $fromDate = $checkDate;
                $toDate = $checkDate;
    
            } 
            else if ($type == 'vatyesterday') {
                $checkDate = Carbon::now()->subDays(1)->toDateString();

                $queryData = $queryData->where(DB::raw('Date(created_at)'), '=', $checkDate);

                $queryPurchaseData = $queryPurchaseData->where(DB::raw('Date(created_at)'), '=', $checkDate);

                $fromDate = $checkDate;
                $toDate = $checkDate;
            } 
            else if ($type == 'vatthismonth') {
                $checkDate = Carbon::now()->month;

                $queryData = $queryData->where(DB::raw('Month(created_at)'), '=', $checkDate);

                $fromDate = Carbon::now()->startOfMonth()->toDateString();
                $toDate = Carbon::now()->toDateString();

                $fromDate =  new Carbon('first day of this month');
                $fromDate =  $fromDate->toDateString();
                $toDate =  Carbon::now()->toDateString();
            }
            else if ($type == 'vatcustom') {

                $startDate = isset($_GET['start']) ? $_GET['start'] : '';
                $endDate = isset($_GET['end']) ? $_GET['end'] : '';
                
                //$invoice = $invoice->whereBetween(DB::raw('Date(I.created_at)'),[$startDate,$endDate]);
                $queryData = $queryData->whereBetween(DB::raw('Date(created_at)'), [$startDate,$endDate]);

               /*  $fromDate = Carbon::now()->startOfMonth()->toDateString();
                $toDate = Carbon::now()->toDateString(); */

                $fromDate = $startDate;
                $toDate = $endDate;
            }

            $queryData = $queryData->groupBy('productName', 'price')->orderBy('qty', 'DESC');

            $completeData = $queryData->get();
            $completePurchaseData = $queryPurchaseData->first();

            $totalVatPurchase = $completePurchaseData->totalVat;

           

            foreach ($completeData as $data) {
                $productName = $data->productName;
                $sellingPrice = $data->sellingPrice;
                $costPrice = $data->costPrice;
                $vatPer = $data->vatPer;
                $qty = $data->qty;
                $totalVatPurchase = ($data->vatPer * $data->qty);
            }
            $queryData = $queryData->paginate(10);

            $results['vatdata'] = $queryData;
            $results['productName'] = $productName;
            $results['sellingPrice'] = $sellingPrice;
            $results['costPrice'] = $costPrice;
            $results['vatPer'] = $vatPer;
            $results['vat'] = $vat;
            $results['qty'] = $qty;
            $results['fromDate'] = $fromDate;
            $results['toDate'] = $toDate;
            $results['totalVatPurchase'] = $totalVatPurchase;


            return view('admin.csvreports.vatreportexports', [
                'vatresult' => $results
            ]);
        } 
        else if ($type == 'cashiercustom'){
            $customStartDate = $_GET['start'];
            $customEndDate = $_GET['end'];
            $search=trim($search);
           
            if(empty($customStartDate)) {
               $customStartDate = new Carbon('first day of January 2021');
               $customStartDate =  $customStartDate->toDateString();;
                //$customStartDate = Carbon::now()->toDateString();
            }
                
            if(empty($customEndDate))
                $customEndDate = Carbon::now()->toDateString();
    
            $cashier = DB::table('cashier as C')->select(DB::raw('CONCAT(U.firstName, " ", U.lastName) AS Name'), 'U.id as userId', 'U.contactNumber', 'U.email', DB::raw('Count(O.id) as billCount'), DB::raw('SUM(O.totalAmount - O.refundTotalAmount) as totalSales'))
            ->leftJoin('users as U','U.id','=','C.userId')
            ->leftJoin('orders_pos as O','O.userId','=','C.userId')
            ->whereBetween(DB::raw('Date(O.created_at)'), [$customStartDate, $customEndDate])
            ->where('C.storeId','=',$storeId);
            
            
            if(!empty($search))
                $cashier = $cashier->where('U.firstName', 'LIKE', $search.'%');
            
            $cashier = $cashier->groupBy('O.userId')->get();
    
            $results['results'] = $cashier;
            $results['fromDate'] = $customStartDate;
            $results['toDate'] = $customEndDate;
            
            return view('admin.csvreports.cashierreportexports', [
                'cashiers' => $results
            ]);
        }
        else if ($type == 'profitmargin' || $type == 'profitlosscustom') {
          

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

            $queryData = $queryData->groupBy('productName')->get();


            $results['profits'] = $queryData;
            $results['fromDate'] = $startDate;
            $results['toDate'] = $endDate;



            return view('admin.csvreports.profitlossreportexports', [
                'invoices' => $results
            ]);
        } 
        else if ($type == 'dayend') {
            $checkDate = Carbon::now()->toDateString();

            $queryData = DB::table('orders_pos')
                ->select('orderId', 'vat', 'totalAmount', 'created_at')
                ->where('storeId', $storeId)
                ->where(DB::raw('Date(created_at)'), '=', $checkDate)
                ->orderBy(DB::raw('Date(created_at)'), 'DESC');
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

            // Main Query Starts Here
            if ($type == 'today' || $type == 'yesterday' || $type == 'thismonth' || $type == 'lastsixmonths' || $type == 'custom') {
               
               
                $queryData = DB::table('orders_pos')
                    ->select('orderId', DB::raw('COUNT(id) as totalBill'), DB::raw('ROUND(SUM(vat),2) as vat'), DB::raw('SUM(totalAmount) as totalAmount'), DB::raw('Date(created_at) as created_at'))
                    ->where('storeId', $storeId);

            } else if( $type == 'catwisetoday' || $type == 'catwiseyesterday' || $type == 'catwisecustom'|| $type == 'catwisethismonth'){

                $queryData = DB::table('reports')
                ->select(DB::raw("(CASE WHEN `categoryName` IS NULL THEN 'Custom' ELSE `categoryName` END) as categoryName"), DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'),DB::raw('SUM(vat)/SUM(quantity) as vat'), DB::raw('ROUND((SUM(total) - SUM(refundTotal) - SUM(total*(discPer/100))),2) as totalAmount'))
                ->where('storeId',$storeId);
            
            }
            else if( $type == 'producttoday' || $type == 'productcustom'|| $type == 'productthismonth' ){
                 
                $search=trim($search);
                
            
              
                $queryData = DB::table('reports')
                ->select('productName','price',DB::raw('ROUND((SUM(price)*quantity),2) as totalamount'), DB::raw('ROUND((SUM(quantity) - SUM(refundQuantity)),2) as qty'),DB::raw('ROUND((SUM(total) - SUM(refundTotal)),2) as totalAmount'),DB::raw('SUM(vat)/SUM(quantity) as vat'),DB::raw('ROUND((SUM(price)),2) as totalPrice'))
                ->where('storeId',$storeId);
            }
             else {
                
                $queryData = DB::table('orders_pos')
                ->select('orderId',DB::raw('(vat - refundVat) as vat'), DB::raw('(totalAmount - refundTotalAmount) as totalAmount'),'created_at')
                    ->where('storeId', $storeId);
            }
            // Main Query Ends Here

            // Additional Query Conditions Starts Here
            if ($type == 'today' || $type == 'billwisetoday'|| $type == 'catwisetoday' || $type == 'producttoday' ) {
                $checkDate = Carbon::now()->toDateString();
               
                $customStartDate = $checkDate;
                $customEndDate = $checkDate;
    

                $queryData = $queryData->where(DB::raw('Date(created_at)'), '=', $checkDate);
            } 
            else if($type == 'producttoday'){
                $checkDate = Carbon::now()->toDateString();
                $queryData = $queryData->where(DB::raw('Date(created_at)'),'=',$checkDate);
                    
            }
            
            
            else if ($type == 'yesterday' || $type =='catwiseyesterday') {
                $checkDate = Carbon::now()->subDays(1)->toDateString();

                $queryData = $queryData->where(DB::raw('Date(created_at)'), '=', $checkDate);
                
            } else if ($type == 'thismonth' || $type == 'billwisethismonth'|| $type == 'catwisethismonth' || $type == 'productthismonth' ) {
                $checkDate = Carbon::now()->month;

                $queryData = $queryData->where(DB::raw('Month(created_at)'), '=', $checkDate);
            }
            else if($type == 'productthismonth'){
                $checkDate = Carbon::now()->month;
    	        $queryData = $queryData->where(DB::raw('Month(created_at)'),'=',$checkDate);
                

            }
            
            
            else if ($type == 'quartely') {
                $checkDate = new \Carbon\Carbon('-3 months'); // for the last quarter requirement
                $startDate = $checkDate->startOfQuarter()->toDateString();
                $endDate = $checkDate->endOfQuarter()->toDateString();

                $queryData = $queryData->whereBetween(DB::raw('Date(created_at)'), [$startDate, $endDate]);
            } else if ($type == 'lastsixmonths') {
                $checkDate = Carbon::now()->subMonths(6)->toDateString();

                $queryData = $queryData->where(DB::raw('Date(created_at)'), '>=', $checkDate);
            } else if ($type == 'custom' || $type == 'billwisecustom' || $type == 'catwisecustom' || $type == 'productcustom' ) {
                //$checkDate = Carbon::now()->subMonths(6)->toDateString();

                $queryData = $queryData->whereBetween(DB::raw('Date(created_at)'), [$customStartDate, $customEndDate]);
                //print_r($queryData);
            }


           
            // Addit{ional Query Conditions Ends Here


            // GroupBy and OrderBy Starts Here
            if ($type == 'today' || $type == 'yesterday' || $type == 'thismonth' || $type == 'lastsixmonths' || $type == 'custom') {
                $queryData = $queryData->groupBy(DB::raw('Date(created_at)'))
                    ->orderBy('created_at', 'DESC');
            }
            else if($type == 'catwisetoday' || $type == 'catwiseyesterday' || $type == 'catwisecustom'|| $type == 'catwisethismonth') 
            {
                $queryData = $queryData->groupBy('categoryName')
                        ->orderBy('created_at','DESC');
            }
            else if($type == 'productcustom'|| $type == 'producttoday' || $type == 'productthismonth'){

                $queryData = $queryData->groupBy('productName','price','costPrice')->orderBy('created_at','DESC');
            }
              
         


             else {
                $queryData = $queryData->orderBy('created_at', 'DESC');
            }
            // GroupBy and OrderBy Ends Here


            //$queryData = $queryData->orderBy('created_at','DESC');
        }

        $completeData = $queryData->get();
    
            foreach ($completeData as $data) {
                $totalVat += $data->vat;
                $totalSumAmount += $data->totalAmount;
            }
        

        $queryData = $queryData->get();

        /*
        echo "<br><br>" . $checkDate . " - " . $customStartDate . " - " . $customEndDate;
        echo "<br><br><hr><br>";

        print_r($queryData);
        
        echo "<br><br>";
        */

        $results['bills'] = $queryData;
        $results['totalVat'] = round($totalVat, 2);
        $results['totalSumAmount'] = round($totalSumAmount, 2);
        $results['totalBills'] = count($completeData);
        $results['type'] = $type;
        $results['fromDate'] = $customStartDate ;
        $results['toDate'] = $customEndDate;

        $billsResult = $results;


        if ($type == 'today' || $type == 'yesterday' || $type == 'thismonth' || $type == 'custom' ) {
            return view('admin.csvreports.dailyexports', [
                'invoices' => $results
            ]);
        } else if ($type == 'lastsixmonths') {
            return view('admin.csvreports.monthlyexports', [
                'invoices' => $results
            ]);
        } else if (  $type == 'billwisethismonth' || $type == 'billwisecustom' || $type == 'billwisetoday') {
          
            return view('admin.csvreports.billwiseexports', [
                'bills' => $billsResult
            ]);
           
        }
        else if ( $type == 'catwisetoday' || $type == 'catwiseyesterday' || $type == 'catwisecustom'|| $type == 'catwisethismonth' ) {
           
            
            return view('admin.csvreports.catgeoryreportexports', [
                'catbills'=> $billsResult
                
              

            ]);
           
        }
        else if ( $type == 'producttoday' || $type == 'productcustom'|| $type == 'productthismonth' ) {
           
            
            return view('admin.csvreports.productexports', [
                'prodbills'=> $billsResult
                
              

            ]);
    }
}
}