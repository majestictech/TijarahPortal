<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Country;
use App\Customer;
use Auth;
use App\Store;
use App\StoreCustomer;
use App\UserRole;
use App\LoyaltyTransaction;
use DB;
use App\Helpers\AppHelper as Helper;

class CustomerController extends Controller
{   
    public function index(Request $request)
    {   
		
	   $customer = Customer::orderBy('id', 'DESC')->get();
	   $customercount=count($customer);
       return view('admin.customer.index', compact('customer','customercount'));
    }
    
   /* public function storeindex($storeId)
    {   
	   if(Auth::user()->roleId == 4)
            $storeId = helper::getStoreId();
	   
	   $customer = DB::Table('customers as C')
	   ->where('C.storeName',$storeId)
	   ->orderBy('id', 'DESC')
	   ->get();
	   $customercount=count($customer);
       return view('admin.customer.index', compact('customer','storeId','customercount'));
    }*/
    
    
        public function storeindex($storeId)
    {		
        //$Gender = config('app.Gender');
        $id = "";
        // $storeId = helper::getStoreId();
        $customer = DB::Table('customers as C')
        ->select('C.email','C.customerName' ,'C.id','C.contactNumber','C.address','C.doa','C.dob','C.created_at')
        ->where('C.storeName',$storeId)
        ->orderBy('C.id', 'DESC')
        ->get();
		
		$customercount=count($customer);
		return view('admin.customer.index',compact('customer','storeId','customercount','storeId'));
    }
    

    public function create($id)
    {    
		$store = Store::orderBy('id', 'DESC')->get();
		$customer = DB::Table('customers as C')->leftJoin('stores as S','S.id','=','C.storeName')->select('C.email','C.dob','C.customerName' ,'C.id','C.contactNumber','C.address','C.doa','C.storeName','S.id as sId')
		->where('C.id', $id)->get();
		$country = Country::orderBy('id', 'DESC')->get();
       
		return view('admin.customer.create', compact('customer','country','store','id'));
    }

	public function store(Request $request)
    {      	 
        $customer = new Customer;
		////$storeId = helper::getStoreId();
		$customer->customerName = $request->customerName;
		$customer->email = $request->email;
		$customer->contactNumber = $request->contactNumber;         	
		$customer->address = $request->address;
		$customer->dob = $request->dob;
		$customer->doa = $request->doa;
		//$customer->storeName = $request->storeName;
		if (Auth::user()->roleId != 4){
            $customer->storeName = $request->storeId;
        }
        else if (Auth::user()->roleId == 4){
		    $customer->storeName  = helper::getStoreId();
        }

        $customer->save();   
        
        return redirect('admin/customer/' . $request->storeId); 
		
        /*if(Auth::user()->roleId != 4)
                return redirect('admin/customer'); 
        else if(Auth::user()->roleId == 4)
            $storeId = helper::getStoreId(); 
            return redirect('admin/customer/'.$storeId);  */          
    }
	
    public function edit($id)	
    {
		
        $country = Country::orderBy('id', 'DESC')->get();
		$customer = DB::Table('customers as C')->leftJoin('stores as S','S.id','=','C.storeName')->select('C.email','C.dob','C.customerName' ,'C.id','C.contactNumber','C.address','C.doa','C.storeName','S.id as sId')
		->where('C.id', $id)->get();
		$customer = $customer[0];
		
		return view('admin.customer.edit',compact('customer','country'));
		
    }
	
    public function update(Request $request)
    {
		$customer = new Customer;
		
			
		$customer = Customer::find($request->input('id'));
		
	
        
		$customer->customerName = $request->customerName;
		$customer->email = $request->email;
		$customer->contactNumber = $request->contactNumber;         	
		$customer->address = $request->address;
		
		$customer->dob = $request->dob;
		
		
        $customer->save();
        
         return redirect('admin/customer/' . $request->storeId); 
		 
        /*if(Auth::user()->roleId != 4)
            return redirect('admin/customer'); 
        if(Auth::user()->roleId == 4)
            $storeId = helper::getStoreId();
            return redirect('admin/customer/'.$storeId);*/
    }
	
	
    public function destroy($id)
    {
        $customer = Customer::find($id);
       
		
        $customer->delete();
        
        return redirect()->back();
		
		/*if(Auth::user()->roleId != 4)
            return redirect('admin/customer'); 
        if(Auth::user()->roleId == 4)
            $storeId = helper::getStoreId();
            return redirect('admin/customer/'.$storeId);  */
		
		
		
    }
	public function view($id)
    {      
		//$storeId = helper::getStoreId();
		$customer = DB::Table('customers as C')
		->select('C.email','C.customerName' ,'C.id','C.contactNumber','C.address','C.doa','C.dob','C.storeName')
		//->leftJoin('mas_country','mas_country.id', '=', 'C.countryId')
		->where('C.id', $id)->first();
		
		$customerstore = DB::Table('customers as C')
		->leftJoin('orders_pos','orders_pos.customerId','=','C.id')
		->leftJoin('storecustomers', 'storecustomers.customerId', '=', 'C.id')
		
		->leftJoin('stores','stores.Id','=','orders_pos.storeId')
		->select('orders_pos.id as orderid','storecustomers.loyaltyPoints','stores.storeName','orders_pos.orderDetail','orders_pos.orderId','orders_pos.totalAmount')
		->where('C.id', $id)
	
		->get();
		
		$customerloyalty = DB::Table('customers as C')
		->leftJoin('storecustomers', 'storecustomers.customerId', '=', 'C.id')
		->leftJoin('stores','stores.Id','=','storecustomers.storeId')
		->select('storecustomers.loyaltyPoints','stores.storeName','stores.Id as storeid')
		->where('C.id', $id)
		->get();
		
		$loyaltytransactions = DB::Table('loyaltytransactions as L')
		->leftJoin('stores','stores.id','=','L.storeId')
		->select('L.orderId','L.id','L.points','stores.storeName','L.type','L.created_at')
		->where('L.customerId',$id)
		
		

		->get();
		
		
		
		$orderdata = DB::Table('orders_pos as O')
		->leftJoin('stores', 'stores.id', '=', 'O.storeId')
		->leftJoin('customers','customers.id','=','O.customerId')
		->leftJoin('storecustomers','storecustomers.customerId','=','O.customerId')
		->select('O.id','O.orderId','stores.storename','stores.address','O.created_at','O.totalAmount','O.paymentStatus','O.orderDetail','storecustomers.loyaltyPoints','customers.customerName','customers.email','customers.contactNumber')
		->where('O.customerId', $id)->first();
		
		/*
		if (empty($orderdata))
		    $orderDetail = '';
		if (empty($orderDetail))
		    $orderDetail['products'] = '';
		*/
		
		$orderDetail = "";
		
    	if(!empty($orderdata)) {
            if(!empty($orderdata->orderDetail)) {
    		    $orderDetail = json_decode($orderdata->orderDetail, true);
        		$orderDetail = $orderDetail['products'];
            }
    	}
    	
        return view('admin.customer.view',compact('customer','customerstore','customerloyalty','loyaltytransactions','orderdata','orderDetail'));
		
		
	
		
    }
	
	public function loyalty($id)
	{
		$loyaltytransactions = DB::Table('loyaltytransactions as L')
		->leftJoin('storecustomers', 'storecustomers.customerId', '=', 'L.id')
		->leftJoin('stores','stores.id','=','storecustomers.storeId')
		->select('L.id','L.points','stores.storeName','L.type','L.created_at')
		->where('L.storeId', $id)

		->first();
		//print_r($loyaltytransactions);
		//die;
		return view('admin.customer.loyaltyview', compact('loyaltytransactions'));
	
	}
}
