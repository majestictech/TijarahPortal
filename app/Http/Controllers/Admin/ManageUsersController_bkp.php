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
use App\StoreType;
use App\Shift;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\AppHelper as Helper;

class ManageUsersController extends Controller
{
    public function index()
    {		
		//$Gender = config('app.Gender');
		$UserRole = UserRole::orderBy('id', 'DESC')->get();
		$masRoles = DB::Table('mas_role as MS')->select('MS.id', 'MS.name')->whereIn('MS.id',[7, 9, 10])->get();
		
		//$userFilter = $request->userFilter;
		$manageusersdata = DB::Table('users as U')
		->leftjoin('usersrole', 'usersrole.id','=','U.roleId')
		->select('U.firstName','U.lastName','U.contactNumber','U.email','U.status','U.roleId','usersrole.id')
		->whereIn('U.roleId',[7, 9, 10])
		->orderBy('U.id', 'DESC')->get();
		//print_r($manageusersdata);
		//die;
		$manageuserscount=count($manageusersdata);
		//print_r($manageusersdata);
		//die;
		return view('admin.manageusers.index',compact('manageusersdata','manageuserscount', 'UserRole','masRoles'));
    }
    
	public function create()
    {      
		//$Gender = config('app.Gender');
		$store = Store::orderBy('id', 'DESC')->get();

		$shift = Shift::orderBy('id', 'DESC')->get();

		$masRoles = DB::Table('mas_role as MS')
		->select('MS.id', 'MS.name')
		->whereIn('MS.id', [7, 9, 10])
		->get();

		$search=

		return view('admin.manageusers.create', compact('store','shift', 'masRoles'));
    }
	
	public function store(Request $request)
    {
		
        $user = new User;
		 $this->validate($request, [
			'password' => 'min:6|required_with:passwordConfirmation|same:passwordConfirmation',
			'passwordConfirmation' => 'min:6',
			 'contactNumber'=>'unique:users,contactNumber',
			 'email'=>'unique:users,email',
			 ]);
        
		$user->firstName = $request->firstName;
		$user->lastName = $request->lastName;
		$user->password = Hash::make($request->password);
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->status = $request->status;
		$user->roleId =  $request->roleId;;
        $user->save(); 
		/*
        $userId = $user->id;
        $cashier->userId = $userId;
		if (Auth::user()->roleId != 4){
            $cashier->storeId = $request->storeId;
        }
        else if (Auth::user()->roleId == 4){
		    $cashier->storeId  = helper::getStoreId();
        }
		
		
		$cashier->save();
		*/
		//Helper::addToLog('cashierAdd',$request->firstName . ' ' . $request->lastName );
		//return redirect('admin/usermanage/' . $request->storeId); 
		
		/*if(Auth::user()->roleId != 4)
            return redirect('admin/cashier'); 
        else if(Auth::user()->roleId == 4)
            $storeId = helper::getStoreId();
            return redirect('admin/cashier/'.$storeId); */
			return redirect('admin/manageusers'); 
    }
	
	public function edit($id)
    {
		$masRole = DB::Table('mas_role as MS')
		->select('MS.id', 'MS.name')
		->whereIn('MS.id', [7, 9, 10])
		->get();

		$manageusersdata = DB::Table('users as U')
		->select('U.id','U.firstName','U.lastName','U.contactNumber','U.email','U.status')
		->where('U.id', $id)->get();
		
		$manageusersdata = $manageusersdata[0];

        /* $userData = DB::Table('cashier as C')
         ->leftJoin('users', 'users.id', '=', 'C.userId')
         ->select('users.firstName','users.lastName','users.email','C.id','users.contactNumber','users.status','C.storeId','C.shiftId')
        ->where('C.id', $id)->get();
		$userData = DB::Table('users as U')->select('U.id','U.firstName','U.lastName','U.email','U.contactNumber')->where('U.id', $id)->get();*/
		$shift = Shift::orderBy('id', 'DESC')->get(); 
		//$userData = $userData[0];

		return view('admin.manageusers.edit',compact('manageusersdata', 'masRole', 'shift'));
    }
	
	public function update(Request $request)
    {
		
		//$user = new User;
        //$user = new User;
       // $cashier = new Cashier;
        //$storeId = helper::getStoreId();
		//$cashier = Cashier::find($request->input('id'));
		
		//$userId = $cashier->userId;
		//$user= User::find($userId);
		
		$user = User::find($request->input('id'));
		$this->validate($request, [
			'password' => 'min:6|required_with:passwordConfirmation|same:passwordConfirmation',
			'passwordConfirmation' => 'min:6',
			 'contactNumber'=>'unique:users,contactNumber',
			 'email'=>'unique:users,email',
			 ]);
		
		
		//$cashier->shiftId = $request->shiftId;
		//$cashier->save(); 
		
		$user->firstName = $request->firstName;
		$user->lastName = $request->lastName;
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->status = $request->status;
		if(!empty($request['password']))
			$user->password = Hash::make($request->password);
		
        $user->save(); 
        
        //Helper::addToLog('cashierEdit',$request->firstName . ' ' . $request->lastName );
       // return redirect('admin/cashier/' . $request->storeId);  
        /*if(Auth::user()->roleId != 4)
            return redirect('admin/cashier'); 
        else if(Auth::user()->roleId == 4)
            $storeId = helper::getStoreId();
            return redirect('admin/cashier/'.$storeId); */
			return view('admin.manageusers.index');  
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

	
}
