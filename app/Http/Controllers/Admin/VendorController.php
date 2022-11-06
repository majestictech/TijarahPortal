<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\AppHelper as Helper;
use DB;
use App\User;
use App\Country;
use App\UserRole;
use App\StoreVendor;

class VendorController extends Controller
{
	public function index()
    {  
        $vendors = DB::Table('storeVendors as V')->leftJoin('stores', 'stores.id', '=', 'V.storeId')->select('stores.storeName','V.id','V.vendorName','V.contactNumber','V.email')->orderBy('V.id', 'DESC')->get();
		    
		 $vendorcount=count($vendors);
	  return view('admin.vendor.index',compact('vendors','vendorcount'));
    }
	
    public function create()
    {   
		$country = Country::orderBy('id', 'DESC')->get();
		return view('admin.vendor.create',compact('country'));
    }
    
    	    public function storeindex($storeId)
    {     
		$vendors = new StoreVendor;
		$storeId = helper::getStoreId();
		
		$vendors = DB::Table('storeVendors as S')->leftJoin('stores', 'stores.id', '=', 'V.storeId')
		->select('stores.storeName','V.id','V.vendorName','V.contactNumber','V.email')
	    ->where('S.storeId',$storeId)
		->orderBy('S.id', 'DESC')->get();
		
		return view('admin.vendor.index', compact('vendors','storeId'));
    }
    
    
    
	public function store(Request $request)
    {    
        $user = new User;
        $vendor = new Vendor;
        $userrole = new UserRole;
		
		$name = explode(' ',$request->contactName);
        $user->firstName = $name[0];
		unset($name[0]);
        $user->lastName = implode(' ',$name);
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->save(); 
		$userId = $user->id;
		
		$vendor->userId = $userId;
		$vendor->vendorName = $request->vendorName;
		$vendor->state = $request->state;
		$vendor->city = $request->city;
		$vendor->countryId = $request->country;
		$vendor->description = $request->description;
        $vendor->save(); 

		$userrole->userId = $userId;
		$userrole->roleId = '3';
		
		$userrole->save(); 
		if ($vendor->save()) {
           
            return Redirect::to('admin/vendor')->withSuccess(['vendor Inserted Successful.']);
        } else {
            //error msg
            return Redirect::back()->withErrors(['Something went wrong.']);
        }
        //return redirect('admin/vendor');             
    }
    
	public function destroy($id)
    {
        $vendorData = Vendor::find($id);
		$userId = $vendorData->userId;
		
        $vendorData->delete();
		
		$userData = User::find($userId);
		$roleData = UserRole::select('id')->where('userId',$userId);
        $userData->delete();
        $roleData->delete();
		return redirect('admin/vendor');  
		
    }
	
	public function edit($id)
    {
		$titleName = 'Edit Vendor';
		$country = Country::orderBy('id', 'DESC')->get();
		$vendorData = DB::Table('Vendors as V')->select(DB::raw("CONCAT(users.firstName,' ', users.lastName) AS 'fullName'"),'users.email','V.id','V.vendorName','users.contactNumber','V.countryId','V.state','V.city','V.description')->leftJoin('users', 'users.id', '=', 'V.userId')->leftJoin('mas_country','V.countryId', '=', 'mas_country.id')->where('V.id', $id)->get();
		
		$vendorData = $vendorData[0];

		return view('admin.vendor.edit',compact('titleName','vendorData','country'));
    }
	
	public function update(Request $request)
    {
		$user = new User;
        $vendor = new Vendor;
        $userrole = new UserRole;
		
		$vendor = Vendor::find($request->input('id'));
		
		$userId = $vendor->userId;
		$user= User::find($userId);

		//Update User Details
		$name = explode(' ',$request->contactName);
        $user->firstName = $name[0];
		unset($name[0]);
        $user->lastName = implode(' ',$name);
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->save(); 
		
		$vendor->vendorName = $request->vendorName;
		$vendor->state = $request->state;
		$vendor->city = $request->city;
		$vendor->countryId = $request->country;
		$vendor->description = $request->description;	
        $vendor->save(); 
        
        Helper::addToLog('vendorEdit');
 
        return redirect('admin/vendor');  
    }
	
	
	public function view($id)
    {      
		
		$vendordata = DB::Table('Vendors as V')->leftJoin('mas_country', 'mas_country.id', '=', 'V.countryId')->leftJoin('users', 'users.id', '=', 'V.userId')
		->select('V.id','V.vendorName','users.contactNumber','V.state','V.city','mas_country.nicename','users.firstName','users.lastName','V.description')
		->where('V.id', $id)->first();
		
		return view('admin.vendor.view',compact('vendordata'));
		
    }
}
