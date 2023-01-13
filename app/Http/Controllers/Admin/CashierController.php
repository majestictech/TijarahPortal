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

class CashierController extends Controller
{
    public function index()
    {		
		

		 
		$storetype = StoreType::orderBy('id', 'DESC')->get();
		//$Gender = config('app.Gender');
		$usersdata = DB::Table('cashier as C')->leftJoin('users as U', 'U.id', '=', 'C.userId')
		->leftJoin('mas_role','mas_role.id','=','U.roleId')
		->select('C.id','U.firstName','U.lastName','U.contactNumber','mas_role.name as role','U.email','U.status')
		->whereIn('U.roleId',[7, 9, 10, 13])
		->orderBy('C.id', 'DESC')->paginate(10);
		$userscount = count($usersdata);
		

		//die;
		return view('admin.cashier.index',compact('usersdata','userscount','storetype'));
    }
    
    public function storeindex($storeId)
    {		
		
		$massRoles = DB::Table('mas_role as MS')->select('MS.id', 'MS.name')->whereIn('MS.id', [7, 9, 10, 13])->get();


		$roleFilter = isset($_GET['roleFilter']) && !empty($_GET['roleFilter']) ? $_GET['roleFilter'] : '7, 9, 10, 13';

		$search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : '';
		$search = trim($search);
		

		$userRoles = explode(",",$roleFilter);
		
		$usersdata = DB::Table('cashier as C')->leftJoin('users as U', 'U.id', '=', 'C.userId')
		->leftJoin('mas_role','mas_role.id','=','U.roleId')
		->select('C.id','U.firstName','U.lastName','U.contactNumber','mas_role.name as role','U.email','U.status')
		->where('C.storeId',$storeId);;
		
		
		if(!empty($roleFilter)) {
			$usersdata = $usersdata->whereIn('U.roleId',$userRoles);
			
		}
		
		if(!empty($search)) {
			$usersdata = $usersdata->where(function($query) use ($search) {
				
				$name = explode(" ", $search);

				$firstName = $name[0];
				$firstName = trim($firstName);

				//$surName = $firstName;
				if(count($name) > 1) {
					$lastName = $name[1];
					$lastName = trim($lastName);

				}
				else {
					$lastName = $firstName;
				}


				$query->orWhere('U.firstName', 'LIKE', $firstName . '%' )
				->orWhere ('U.lastName', 'LIKE', '%' . $lastName . '%' )
				->orWhere ('U.email', 'LIKE', '%' . $search . '%' )
				->orWhere ('U.contactNumber', 'LIKE', '%' . $search . '%' );
			});			
		}


		$usersdata= $usersdata->orderBy('C.id', 'DESC')->paginate(10);
		
		//die;
		//echo $usersdata;
		//die;

		$userscount = count($usersdata);
		return view('admin.cashier.index',compact('usersdata','storeId','userscount','storeId', 'massRoles','roleFilter', 'search'));
    }
    
	
	public function create($id)
    {      
		//$Gender = config('app.Gender');
		$store = Store::orderBy('id', 'DESC')->get();
		//$shift = Shift::orderBy('id', 'DESC')->get();
		$shift = DB::Table('shifts')
		->leftJoin('stores','stores.id','=','shifts.storeId')
		->select('shifts.id','shifts.title')
		->orderBy('shifts.id', 'DESC')
		->get();

		$massRoles = DB::Table('mas_role as MS')->select('MS.id', 'MS.name')->whereIn('MS.id', [7, 9, 10, 13])->get();

		return view('admin.cashier.create', compact('store','shift','id','massRoles'));
    }
	
	public function store(Request $request)
    {    
        $user = new User;
        $cashier = new Cashier;
    	$this->validate($request, [
		'firstName'=> 'required',	
		'roleId'=> 'required',	
		'shiftId'=> 'required',	
		'password' => 'min:6|required_with:passwordConfirmation|same:passwordConfirmation',
		'passwordConfirmation' => 'min:6',
		'contactNumber'=>'unique:users,contactNumber',
		'email'=>'unique:users,email'
     	]);
        
		$user->firstName = $request->firstName;
		$user->lastName = $request->lastName;
		$user->password = Hash::make($request->password);
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->status = $request->status;
		$user->roleId = $request->roleId;
        $user->save(); 
        
        $userId = $user->id;
        $cashier->userId = $userId;
		if (Auth::user()->roleId != 4){
            $cashier->storeId = $request->storeId;
        }
        else if (Auth::user()->roleId == 4){
		    $cashier->storeId  = helper::getStoreId();
        }
		
		$cashier->shiftId = $request->shiftId;
		$cashier->save();
		
		Helper::addToLog('cashierAdd',$request->firstName . ' ' . $request->lastName );
		return redirect('admin/cashier/' . $request->storeId); 
		
		/*if(Auth::user()->roleId != 4)
            return redirect('admin/cashier'); 
        else if(Auth::user()->roleId == 4)
            $storeId = helper::getStoreId();
            return redirect('admin/cashier/'.$storeId); */
    }
	
	public function edit($id)
    {
		

         $userData = DB::Table('cashier as C')
         ->leftJoin('users', 'users.id', '=', 'C.userId')
         ->select('users.firstName','users.lastName','users.email','C.id','users.contactNumber','users.status','C.storeId','C.shiftId', 'users.roleId', 'C.storeId')
        ->where('C.id', $id)
		->get();
		//$userData = DB::Table('users as U')->select('U.id','U.firstName','U.lastName','U.email','U.contactNumber')->where('U.id', $id)->get();
		$shifts = Shift::orderBy('id', 'DESC')->get();
		$massRoles = DB::Table('mas_role as MS')->select('MS.id', 'MS.name')->whereIn('MS.id', [7, 9, 10, 13])->get();

		$userData = $userData[0];

		return view('admin.cashier.edit',compact('userData','shifts','id', 'massRoles'));
    }
	
	public function update(Request $request)
    {
		//$user = new User;
        $user = new User;
		$this->validate($request, [
			'firstName'=> 'required',
			'email'=> 'required',
			'contactNumber'=>'required',	
			'roleId'=> 'required',	
			'shiftId'=> 'required',	
			'password' => 'min:6|required_with:passwordConfirmation|same:passwordConfirmation',
			'passwordConfirmation' => 'min:6'
		]);
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
		
		
		$cashier->shiftId = $request->shiftId;
		$cashier->save(); 
		
		
		$user->firstName = $request->firstName;
		$user->lastName = $request->lastName;
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->roleId = $request->roleId;
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
