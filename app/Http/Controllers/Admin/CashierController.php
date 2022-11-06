<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\UserRole;
use App\User;
use App\Cashier;
use App\Store;
use App\Shift;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\AppHelper as Helper;

class CashierController extends Controller
{
    public function index()
    {		
		//$Gender = config('app.Gender');
		
		$cashierdata = DB::Table('cashier as C')->leftJoin('users', 'users.id', '=', 'C.userId')
		->leftJoin('stores','stores.id','=','C.storeId')
		->select('C.id','users.firstName','users.lastName','users.contactNumber','stores.storeName','users.email','users.status')
		->where('users.roleId','=','7')
		->orderBy('C.id', 'DESC')->get();
		$cashiercount=count($cashierdata);
		return view('admin.cashier.index',compact('cashierdata','cashiercount'));
    }
    
    public function storeindex($storeId)
    {		
		//$Gender = config('app.Gender');
		$id = "";
		// $storeId = helper::getStoreId();
		$cashierdata = DB::Table('cashier as C')->leftJoin('users', 'users.id', '=', 'C.userId')
		->leftJoin('stores','stores.id','=','C.storeId')
		->select('C.id','users.firstName','users.lastName','users.contactNumber','stores.storeName','users.email','users.status')
		->where('users.roleId','=','7')
		->where('C.storeId',$storeId)
		->orderBy('C.id', 'DESC')->get();
		
		

		$cashiercount=count($cashierdata);
		return view('admin.cashier.index',compact('cashierdata','storeId','cashiercount','storeId'));
    }
    
	
	public function create($id)
    {      
		//$Gender = config('app.Gender');
		$store = Store::orderBy('id', 'DESC')->get();
		$shift = Shift::orderBy('id', 'DESC')->get();
		return view('admin.cashier.create', compact('store','shift','id'));
    }
	
	public function store(Request $request)
    {    
        $user = new User;
        $cashier = new Cashier;
    	$this->validate($request, [
		'password' => 'min:6|required_with:passwordConfirmation|same:passwordConfirmation',
		'passwordConfirmation' => 'min:6'
     	]);
        
		$user->firstName = $request->firstName;
		$user->lastName = $request->lastName;
		$user->password = Hash::make($request->password);
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->status = $request->status;
		$user->roleId = '7';
        $user->save(); 
        
        $userId = $user->id;
        $cashier->userId = $userId;
		if (Auth::user()->roleId != 4){
            $cashier->storeId = $request->storeId;
        }
        else if (Auth::user()->roleId == 4){
		    $cashier->storeId  = helper::getStoreId();
        }
		
		
		$cashier->save();
		
		Helper::addToLog('cashierAdd',$request->firstName . ' ' . $request->lastName );
		return redirect('admin/cashier/' . $request->storeId); 
		
		/*if(Auth::user()->roleId != 4)
            return redirect('admin/cashier'); 
        else if(Auth::user()->roleId == 4)
            $storeId = helper::getStoreId();
            return redirect('admin/cashier/'.$storeId); */
    }
	
	public function destroy($id)
    {
		
		
		$cashierData = Cashier::find($id);
		$userId = $cashierData->userId;
		
        $cashierData->delete();
		
		$userData = User::find($userId);
		//$roleData = UserRole::select('id')->where('userId',$userId);
        $userData->delete();
        //$roleData->delete();


		Helper::addToLog('cashierDelete',$userData->firstName . ' ' . $userData->lastName );
        return redirect()->back();
        
        
        
        /*
		
		if(Auth::user()->roleId != 4)
            return redirect('admin/cashier'); 
        if(Auth::user()->roleId == 4)
            $storeId = helper::getStoreId();
            return redirect('admin/cashier/'.$storeId);*/
		
    }	
	
	public function edit($id)
    {
		

         $userData = DB::Table('cashier as C')
         ->leftJoin('users', 'users.id', '=', 'C.userId')
         ->select('users.firstName','users.lastName','users.email','C.id','users.contactNumber','users.status','C.storeId')
        ->where('C.id', $id)->get();
		//$userData = DB::Table('users as U')->select('U.id','U.firstName','U.lastName','U.email','U.contactNumber')->where('U.id', $id)->get();
		
		$userData = $userData[0];

		return view('admin.cashier.edit',compact('userData'));
    }
	
	public function update(Request $request)
    {
		//$user = new User;
        $user = new User;
        $cashier = new Cashier;
        //$storeId = helper::getStoreId();
		$cashier = Cashier::find($request->input('id'));
		
		$userId = $cashier->userId;
		$user= User::find($userId);
		
		//$user = User::find($request->input('id'));
		if(!$user->password)
		$this->validate($request, [	'password' => 'min:6|:password_confirmation|same:password_confirmation',
		'password_confirmation' => 'min:6'
     	]);
		
		$user->firstName = $request->firstName;
		$user->lastName = $request->lastName;
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->status = $request->status;
		if(!empty($request['password']))
			$user->password = Hash::make($request->password);
		
        $user->save(); 
        
        Helper::addToLog('cashierEdit',$request->firstName . ' ' . $request->lastName );
        return redirect('admin/cashier/' . $request->storeId);  
        /*if(Auth::user()->roleId != 4)
            return redirect('admin/cashier'); 
        else if(Auth::user()->roleId == 4)
            $storeId = helper::getStoreId();
            return redirect('admin/cashier/'.$storeId); */
    }

	
}
