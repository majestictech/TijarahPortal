<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;


class ReportController extends Controller
{
    public function index($type)
    {   
        die;
		$type = ucwords($type);
		$month = array('Jan', 'Feb', 'Mar', 'Apr', 'May');
        $data  = array(1, 2, 3, 4, 5);
		return view('admin.report.index', compact('type','month','data'));
    }
    
    public function index0($type,$id)
    {
       $type = ucwords($type);
       $todayDate = Carbon::today()	;
       $totalAmt = DB::table('orders_pos as O')
                ->select(DB::raw('SUM(totalAmount) as totalAmount'))->whereDate('created_at', Carbon::today())
                ->get();
		
		$totalAmt = $totalAmt[0]->totalAmount;
        
        $tillDate = Carbon::now()->subDays(6)->toDateString();
	    
        $revenueData = DB::table('orders_pos')
                ->select(DB::raw('SUM(totalAmount) as totalAmount'),DB::raw('SUM(totalAmount)/COUNT(totalAmount) as averageAmount'),DB::raw('COUNT(totalAmount) as billCount'),DB::raw('Date(created_at) as date'))
                ->where('storeId','=',$id)
                ->where(DB::raw('Date(created_at)'),'>=',$tillDate)
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
           
            $count++;
        }
        for($i=0; $i<7; $i++) {
             
            $day = Carbon::now()->subDays($i);
            $checkDate = $day->toDateString();
            //$dateDay = $day->format('D');
            $dateDay = $day->format('d/m');
            $position = array_search($checkDate, $graphRevenueSearch);

            $graphdata['revenue']['labels'][] = $dateDay;
            $graphdata['revenue']['data'][] = round($graphRevenue[$position]['totalAmount'],0);
            
            $graphdata['avgBasket']['labels'][] = $dateDay;
            $graphdata['avgBasket']['data'][] = round($graphRevenue[$position]['averageAmount'],0);
            
            $graphdata['bills']['labels'][] = $dateDay;
            $graphdata['bills']['data'][] = round($graphRevenue[$position]['billCount'],0);
           
        }
        echo json_encode($graphdata['revenue']['data']);
        //return view('admin.report.index', compact('type'));
    	return view('admin.report.index', compact('type'))->with('labels',json_encode($graphdata['revenue']['labels'],JSON_NUMERIC_CHECK))
    	->with('data',json_encode($graphdata['revenue']['data'],JSON_NUMERIC_CHECK))
    	->with('avgdata',json_encode($graphdata['avgBasket']['data'],JSON_NUMERIC_CHECK))
    	->with('billdata',json_encode($graphdata['bills']['data'],JSON_NUMERIC_CHECK))
    	->with('dailydatalabel',json_encode($graphdata['revenue']['data'],JSON_NUMERIC_CHECK))
    	->with('billData',json_encode($revenueData,JSON_NUMERIC_CHECK));
    }
    
   
 
		
    
     
    
}
