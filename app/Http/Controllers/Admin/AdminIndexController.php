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
        
        $countPerPage = 10;
        
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
        if (Auth::user()->roleId != 4){
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
                    ->get();
    		
    		$revenue = $revenue[0]->totalAmount;
                
    
    		return view('admin.dashboard.index',compact('todayorderCount','allorderCount','allcustomer','activestores','revenue'));
    		
        }		
    	else if(Auth::user()->roleId == 4){	
    	    
    	     $storeDetails = DB::table('stores')->where('userId', Auth::id())->select('id','storeName')->get();
    	     //print_r($storeDetails[0]->storeName);
    	     
    	     $storeDetails=$storeDetails[0]->id;
    	     
    		
    		$storedata = DB::Table('stores as S')->leftJoin('mas_country', 'mas_country.id', '=', 'S.countryId')->leftJoin('users', 'users.id', '=', 'S.userId')->leftJoin('mas_storetype','mas_storetype.id','=','S.storeType')
    		->select('S.id','S.storeName','S.printStoreNameAr','users.contactNumber','users.email','S.regNo','S.state','S.city','S.appVersion','mas_country.nicename','users.firstName','mas_storetype.name','users.lastName','S.address','S.latitude','S.longitude','S.deviceType','S.appType','S.shopSize','S.vatNumber','S.printStoreNameAr','S.printAddAr','S.manageInventory','S.smsAlert','S.printFooterEn','S.printFooterAr','S.autoGlobalCat','S.onlineMarket','S.loyaltyOptions','S.autoGlobalItems','S.chatbot','users.id as userId')
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
                    ->get();
    		
    		$revenue = $revenue[0]->totalAmount;

    		return view('admin.dashboard.index',compact('storedata','todayorderCount','allorderCount','allcustomer','revenue','storeDetails'));
    		
        }
    }

}