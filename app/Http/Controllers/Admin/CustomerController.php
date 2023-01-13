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
		
		$search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search']: '' ;
		$search = trim($search);

        // $storeId = helper::getStoreId();
        $customer = DB::Table('customers as C')
        ->select('C.email','C.customerName' ,'C.id','C.contactNumber','C.customerVat','C.balanceDue','C.doa','C.dob','C.created_at')
        ->where('C.storeName',$storeId);
        
		if(!empty($search)) {
			$customer =  $customer->where(function($query) use ($search) {
				$query->orWhere('C.customerName', 'LIKE', '%' . $search . '%' )
				->orWhere ('C.email', 'LIKE', '%' . $search . '%' )
				->orWhere ('C.contactNumber', 'LIKE', '%' . $search . '%' )
				->orWhere ('C.customerVat', 'LIKE', '%' . $search . '%' );
			});
		}
		
		$customer = $customer->orderBy('C.id', 'DESC')->paginate(10);
		
		$customercount=count($customer);
		return view('admin.customer.index',compact('customer','storeId','customercount','storeId', 'search'));
    }
    

    public function create($id)
    {   
		
		/*
		$customerStoreId = DB::Table('customers as C')
        ->select('C.id','C.contactNumber','C.customerVat','C.storeName')
        ->where('C.storeName',505)->get();
		
		//$customerStoreId = $customerStoreId->toJson();
		//$customerStoreId = json_decode($customerStoreId);
		//$customerStoreIdCount = count($customerStoreId)-1;

		print_r($customerStoreId[0]);
		die;
		*/

		$store = Store::orderBy('id', 'DESC')->get();
		$customer = DB::Table('customers as C')->leftJoin('stores as S','S.id','=','C.storeName')->select('C.email','C.dob','C.customerName' ,'C.id','C.contactNumber','C.address','C.doa','C.storeName','S.id as sId')
		->where('C.id', $id)->orderBy('id', 'DESC')->paginate(10);
		//print_r($id);
		//die;
		$country = Country::orderBy('id', 'DESC')->get();
       
		return view('admin.customer.create', compact('customer','country','store','id'));
    }

	public function store(Request $request)
    {      	 
        $customer = new Customer;

		/* $this->validate($request, [
			'customerName'=> 'required',
			'email' => [
				'required',
				'unique:users,email',
			 ],
			 'contactNumber'=> 'required',
			 'customerVat'=> 'required',
			 'dob'=> 'required',
			 'address'=> 'required',
		   ]); */
		//$storeId = helper::getStoreId();
		

		/*
		$currentStoreID = $request->storeName;
		$customerStoreId = DB::Table('customers as C')
        ->select('C.id','C.contactNumber','C.customerVat','C.storeName')
        ->where('C.storeName',$currentStoreID)->get();
        
		*/
		/* 
		//Number Unique Start
		$this->validate($request, [
			'contactNumber'=>'unique:customers,contactNumber'
			
			//'contactNumber'=>'unique:customers,contactNumber| unique:customers,storeName',$ignoreStoreID

			/*'contactNumber' => [
				'required',
				Rule::unique('customers')->where(function ($query) use($storeName,$contactNumber) {
					return $query->where('storeName', $storeName)
					->where('hostname', $hostname);
				}),
			],
			*/
			// ]);
		//Number Unique End


		$customer->customerName = $request->customerName;
		$customer->email = $request->email;
		$customer->contactNumber = $request->contactNumber;         	
		        	
		$customer->address = $request->address;
		$customer->dob = $request->dob;
		$customer->doa = $request->doa;
		

		if(!empty($request->customerVat))
			$this->validate($request, [	
			'customerVat' => 'min:15|max:15'
     	]);
		$customer->customerVat = $request->customerVat; 

		//$customer->storeName = $request->storeName;
		if (Auth::user()->roleId != 4){
            $customer->storeName = $request->storeId;
        }
        else if (Auth::user()->roleId == 4){
		    $customer->storeName  = helper::getStoreId();
        }

        $customer->save();   
		Helper::addToLog('customerAdd',$request->customerName);
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
		$customer = DB::Table('customers as C')->leftJoin('stores as S','S.id','=','C.storeName')->select('C.email','C.dob','C.customerName' ,'C.customerVat' ,'C.id','C.contactNumber','C.address','C.doa','C.storeName','S.id as sId')
		->where('C.id', $id)->first();
		//$customer = $customer[0];
		
		return view('admin.customer.edit',compact('customer','country'));
		
    }
	
    public function update(Request $request)
    {
		$customer = new Customer;
		
		$this->validate($request, [
			'customerName'=> 'required',
			'email' => 'required',
			'contactNumber'=> 'required',
			'customerVat'=> 'required',
			 'dob'=> 'required',
			 'address'=> 'required',
		   ]);	
		$customer = Customer::find($request->input('id'));
		
	
        
		$customer->customerName = $request->customerName;
		$customer->email = $request->email;
		$customer->contactNumber = $request->contactNumber;         	
		$customer->address = $request->address;
		
		$customer->dob = $request->dob;
		$customer->doa = $request->doa;

		if(!empty($request->customerVat))
			$this->validate($request, [	
			'customerVat' => 'min:15|max:15'
     	]);
		 $customer->customerVat = $request->customerVat; 
		
        $customer->save();
        Helper::addToLog('customerEdit',$request->customerName);
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
        Helper::addToLog('customerDelete',$customer->customerName);
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
		->join('orders_pos','orders_pos.customerId','=','C.id')
		->leftJoin('storecustomers', 'storecustomers.customerId', '=', 'C.id')
		->leftJoin('stores','stores.Id','=','orders_pos.storeId')
		->select('orders_pos.id as orderid','storecustomers.loyaltyPoints','stores.storeName','orders_pos.orderDetail','orders_pos.orderId','orders_pos.totalAmount')
		->where('C.id', $id)
		->paginate(5, ['*'], 'customerstore');
		
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
		->where('O.customerId', $id)->orderBy('id', 'DESC')->first();
		
		

		$customersCredit = DB::Table('customers_credit')->where('customerId',$id)	
		->orderBy('id', 'DESC')->paginate(2, ['*'], 'customersCredit');

		//print_r($currentOrderId);
		//die;	


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
    	
        return view('admin.customer.view',compact('customer','customerstore','customerloyalty','loyaltytransactions','orderdata','orderDetail', 'customersCredit'));
		
		
	
		
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
