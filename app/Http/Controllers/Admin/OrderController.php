<?php

namespace App\Http\Controllers\Admin;
use App\Orders;
use DB;
use Carbon\Carbon;
use App\Helpers\AppHelper as Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;
class OrderController extends Controller
{
    public function create()
    {      
	  return view('admin.order.create');
    }

    public function index(Request $request)
    {      
        $todayDate = Carbon::now()->format('Y-m-d');
        //$data = $request->all();
        $search = $request->search;
        $startdate = $request->start_date;
        $enddate = $request->end_date;
        $export = $request->exportBtn;
        
	    $orders = DB::Table('orders_pos as O')->leftJoin('stores as S','S.id','=','O.storeId')
        ->select('O.id','O.orderId','O.created_at','O.totalAmount','S.storeName as storeName')->orderBy('O.created_at', 'DESC');
        
        if(isset($request->start_date) && isset($request->end_date)) {
            $startdate = $request->start_date . ' 00:00:00';
            $enddate = $request->end_date . ' 23:59:59';
            
            //$orders = $orders->whereBetween('O.created_at',[$request->start_date,$request->end_date]);
            
            //$orders = $orders->where('O.created_at','>=',$request->start_date);
            
            $orders = $orders->whereBetween(DB::raw('Date(O.created_at)'),[$request->start_date,$request->end_date]);
            
            
        }
        
        if(isset($search)) {
            $orders = $orders->where('S.storeName', 'LIKE', '%' . $search . '%' );
            print_r($search);
	        die;
        }
	    if(!empty($request->storeId))
	        $orders = $orders->where('O.storeId',$request->storeId);
	    
	    $orders = $orders->paginate(10);
	    
        $postcount=count($orders);
        
        if(isset($export) && $export == 'yes') {
            $fileName = 'orders.csv';
            return Excel::download(new OrderExport($request->start_date, $request->end_date ), $fileName);
        }
        $startdate = $request->start_date;
        $enddate = $request->end_date;
        return view('admin.order.index', compact('orders','postcount','search','todayDate','startdate','enddate'));
	
	  
	  
	  
	  /*
	  $orders = DB::Table('orders_pos as O')->leftJoin('stores as S','S.id','=','O.storeId')
	  ->select('O.created_at','S.storeName as storeName')->whereBetween('O.created_at',[$request->start_date,$request->end_date])->first();
	 
      
            if ($orders === null) {
                
                if($request->start_date > $todayDate)
                {
                   $orders = DB::Table('orders_pos as O')->leftJoin('stores as S','S.id','=','O.storeId')->select('O.created_at','S.storeName as storeName')->where('O.created_at','=',0)->paginate(10);
                  $postcount= count($orders);	  
            	  
            	  return view('admin.order.index', compact('orders','postcount','data') );
                }
                
                else
                {
                      $orders = DB::Table('orders_pos as O')->leftJoin('stores as S','S.id','=','O.storeId')
                	  ->select('O.id','O.orderId','O.created_at','O.totalAmount','S.storeName as storeName')->orderBy('O.created_at', 'DESC')
                	  ->paginate(10);

                  $postcount= count($orders);	  
            	  
            	  return view('admin.order.index', compact('orders','postcount','data') );
                   
                }
            }
            
            elseif ($orders !== null){
            
            	  $orders = DB::Table('orders_pos as O')->leftJoin('stores as S','S.id','=','O.storeId')->select('O.id','O.orderId','O.created_at','O.totalAmount','S.storeName as storeName')->whereBetween('O.created_at',[$request->start_date,$request->end_date])->orderBy('O.created_at', 'DESC')
                        ->paginate(10);  

                  $postcount= count($orders);	  
            	  
            	  return view('admin.order.index', compact('orders','postcount','data','search') );
                    
            }
        */
  
    // return redirect('admin/order',compact('users'));
	  
	  /*$orders = DB::Table('orders_pos as O')
	  ->select('O.id','O.orderId','O.created_at','O.totalAmount')->orderBy('id', 'DESC')
	 // ->whereBetween('created_at', array($request->from_date, $request->to_date))
	  ->get();*/
	 // $ordercount=count($orders);
	 // print_r($orders);
	  //die;
     
    }
    
    
    public function exportOrder(Request $request) 
    {
        $fileName = 'orders.xlsx';
        return Excel::download(new OrderExport($request->start_date, $request->end_date ), $fileName);
         
    }   
    
    
	public function storeindex($storeId)
    {   
        $id = "";
        $startdate = "";
        $enddate = "";
        $search = "";
        $export = "";
        //$storeId = helper::getStoreId();
        //print_r($storeId);
        //die;
        $orders = DB::Table('orders_pos as O')
        ->select('O.id','O.orderId','O.created_at','O.totalAmount','O.storeId')
        ->where('O.storeId',$id)
        ->orWhere('O.storeId',$storeId)
        ->orderBy('created_at', 'DESC')->paginate(10);
        $postcount=count($orders);
        return view('admin.order.index', compact('orders','postcount','storeId','startdate','enddate','search','export') );
    }
    /*
    public function search(Request $request){
        // Get the search value from the request
        $search = $request->input('search');
    
        // Search in the title and body columns from the posts table
        $orders = Post::query()
            ->where('title', 'LIKE', "%{$search}%")
            ->orWhere('body', 'LIKE', "%{$search}%")
            ->get();
    
        // Return the search view with the resluts compacted
        return view('search', compact('posts'));
    }
    
    */
	
	public function view($id)
    {      

		//$orderdata = Orders::first();
		//$storeId = helper::getStoreId();
		$orderdata = DB::Table('orders_pos as O')
		->leftJoin('stores', 'stores.id', '=', 'O.storeId')
		
		->leftJoin('customers','customers.id','=','O.customerId')
		->leftJoin('storecustomers','storecustomers.customerId','=','O.customerId')
		->select('O.id','O.orderId','stores.storename','stores.address','O.created_at','O.totalAmount','O.paymentStatus','O.orderDetail','storecustomers.loyaltyPoints','customers.customerName','customers.email','customers.contactNumber')
		->where('O.id', $id)->first();
		//$statusType = DB::Table('mas_statustype')->get();
		$drivers = DB::Table('users')->where('users.roleId','=',5)->get();
		//print_r($drivers);
		//die;
		
		$orderDetail = json_decode($orderdata->orderDetail, true);
		$orderDetail = $orderDetail['products'];
		//$deliveryDetail = json_decode($orderdata->deliveryDetails, true);
        //$deliveryDetailAddress = $deliveryDetail['address'];
        //$deliveryDetailSlot = $deliveryDetail['deliverySlot'];
		//print_r($orderDetail);
		//print_r($deliveryDetailSlot);
		//die;
		return view('admin.order.view',compact('orderdata','orderDetail','drivers'));
		
    }
	public function update(Request $request)
	{
		//print_r($request);
		//die;
		
		//echo "Test";
		$type = $request->input('type');
		$order = Orders::find($request->input('id'));
		
		
		
		$order->save();
		
		
		return redirect('admin/order');
	}
	

	
	
       /* 	public function dateRange(Request $request)
        {
            $users = Orders::whereBetween('created_at',[$request->start_date,$request->end_date])
            ->get();
        
            return redirect('admin/order',compact('users'));
        }*/
	
	
	
	
}
