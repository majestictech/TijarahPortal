<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\AppHelper as Helper;
use DB;
use App\UserRole;
use App\User;
use App\ChainStoreUsers;
use Illuminate\Support\Facades\Hash;
use Auth;

class UsersManagementController extends Controller
{
    public function index(Request $request)
    {		
		$authUser= Auth::user()->roleId;
		//print_r($authUser);
		//die;
		//$Gender = config('app.Gender');
		$masRoles = DB::Table('mas_role as m')->select('m.id','m.name');

		$search = $request->search;
		$search = trim($search);

		$roleFilter = $request->roleFilter;

	/* 	$usersmanagementdata = DB::Table('users as U')
		->select('U.id','U.firstName','U.lastName','U.email','U.contactNumber', 'U.roleId');
		if($authUser == 1) {
			 $usersmanagementdata = $usersmanagementdata->whereIn('U.roleId', [1, 2, 11, 12]);

			 $masRoles = $masRoles->whereIn('m.id',[1, 2, 11, 12]);
		}
		else if($authUser == 2) {
			$usersmanagementdata = $usersmanagementdata->where('U.roleId', 2, 11, 12);
			$masRoles = $masRoles->whereIn('m.id',[2, 11, 12]);
		}
		else if($authUser == 11) {
			$usersmanagementdata = $usersmanagementdata->whereIn('U.roleId', [11, 12]);
			$masRoles = $masRoles->whereIn('m.id', [11, 12]);
		}
		else if($authUser == 12) {
			$usersmanagementdata = $usersmanagementdata->where('U.roleId', 12);
		} */

		$usersmanagementdata = DB::Table('users as U')->leftJoin('mas_role as m','m.id','U.roleId');
				
		$parentUserId = 0;
		$userId = Auth::user()->id ?? '' ;
		if($authUser == 11) {
			$parentUserId = auth()->user()->id;
		}
		else if($authUser == 12) {
			$parentUserId = DB::Table('chainstoreusers')
			->where('userId', $userId)->first();
			
			$parentUserId = $parentUserId->parentAdminUserId;
		}
		
		
		if($authUser == 11 || $authUser == 12) {
			$usersmanagementdata = $usersmanagementdata->leftJoin('chainstoreusers as CSU','CSU.userId','U.id');
		}
		
		
		$usersmanagementdata = $usersmanagementdata->select('U.id','U.firstName','U.lastName','U.email','U.contactNumber', 'U.roleId');
		
		
		
		if($authUser == 1) {
			 $usersmanagementdata = $usersmanagementdata->whereIn('U.roleId', [1, 2]);

			 $masRoles = $masRoles->whereIn('m.id',[1, 2]);
		}
		else if($authUser == 2) {
			$usersmanagementdata = $usersmanagementdata->where('U.roleId', 2);
			$masRoles = $masRoles->whereIn('m.id',[2]);
		}
		else if($authUser == 11) {
			$usersmanagementdata = $usersmanagementdata->whereIn('U.roleId', [11, 12, 14])->where('CSU.parentAdminUserId',$parentUserId);
			$masRoles = $masRoles->whereIn('m.id', [11, 12, 14]);
		}
		else if($authUser == 12) {
			$usersmanagementdata = $usersmanagementdata->whereIn('U.roleId', [12, 14])->where('CSU.parentAdminUserId',$parentUserId);
		}

		$masRoles = $masRoles->get();
		

		// Role Check Starts
		if(!empty($roleFilter))
			$usersmanagementdata = $usersmanagementdata->where('U.roleId', $roleFilter );
		// Role Check Ends

		// Search Check Starts
		if(!empty($search)) {
			$usersmanagementdata = $usersmanagementdata->where(function($query) use ($search) {
				$name = explode(" ", $search);

				$firstName = $name[0];
				$firstName = trim($firstName);

				if(count($name) > 1) { 
					$lastName = $name[1];
					$lastName = trim($lastName);
				}
				else {
					$lastName = $firstName;
				}

				$query->orWhere('U.firstName', 'LIKE', $firstName . '%' )
				->orWhere ('U.lastName', 'LIKE', $lastName . '%' )
				->orWhere ('U.email', 'LIKE', '%' . $search . '%' )
				->orWhere ('U.contactNumber', 'LIKE', '%' . $search . '%' );
			});
		}
		// Search Check Ends

		/*
		if(null!=$roleFilter && null==$search) {
			$usersmanagementdata = $usersmanagementdata->where('U.roleId', $roleFilter );
		}
		else if(null!=$roleFilter && null!=$search){
			         

				$name = explode(" ", $search);

				$firstName = $name[0];
				if(count($name) > 1) {
					$lastName = $name[1];
				}
				else {
					$lastName = $firstName;
				}

				$usersmanagementdata = $usersmanagementdata->where('U.roleId', $roleFilter )
				->orWhere('U.firstName', 'LIKE', $firstName . '%' )
				->orWhere ('U.lastName', 'LIKE', $lastName . '%' )
				->orWhere ('U.email', 'LIKE', '%' . $search . '%' )
				->orWhere ('U.contactNumber', 'LIKE', '%' . $search . '%' )
				->orWhere ('U.roleId', 'LIKE', '%' . $search . '%' );
			
		}
		else {
			$name = explode(" ", $search);

				$firstName = $name[0];
				if(count($name) > 1) {
					$lastName = $name[1];
				}
				else {
					$lastName = $firstName;
				}

				$usersmanagementdata = $usersmanagementdata->where('U.firstName', 'LIKE', $firstName . '%' )
				->orWhere ('U.lastName', 'LIKE', $lastName . '%' )
				->orWhere ('U.email', 'LIKE', '%' . $search . '%' )
				->orWhere ('U.contactNumber', 'LIKE', '%' . $search . '%' )
				->orWhere ('U.roleId', 'LIKE', '%' . $search . '%' );

		}
		*/

		$usersmanagementdata = $usersmanagementdata->orderBy('U.id', 'DESC')->paginate(10);
		/* print_r($usersmanagementdata);
		die; */
		
		$usersmanagementcount=count($usersmanagementdata);
		//echo $usersmanagementdata;
		//die;
		
		return view('admin.usersmanagement.index',compact('usersmanagementdata','usersmanagementcount', 'search', 'masRoles', 'roleFilter', 'authUser'));
    }
	
	public function create()
    {    
		$authUser= Auth::user()->roleId;
		/* if($authUser == 11) {
			$usersmanagementdata = $usersmanagementdata->whereIn('U.roleId', [11, 12]);
	   } */
		  
		//$Gender = config('app.Gender');
		$masRoles = DB::Table('mas_role as m');
		
		if($authUser == 1) {
			$masRoles = $masRoles->whereIn('m.id',[1, 2])->get();
		}
		else if($authUser == 2) {
			$masRoles = $masRoles->where('m.id',2)->get();
		}
		else if($authUser == 11) {
			$masRoles = $masRoles->whereIn('m.id', [12,14])->get();
		}

		
		return view('admin.usersmanagement.create', compact('masRoles'));
    }
	
	public function store(Request $request)
    {    
        $user = new User;
		/*
		$this->validate($request, ['password' => 'min:6|required_with:passwordConfirmation|same:passwordConfirmation',
		'passwordConfirmation' => 'min:6'
     	]);
		*/
		
		$authUser= Auth::user()->roleId;
		$userId = Auth::user()->id ?? '' ;
		if($authUser == 11) {
			$parentUserId = auth()->user()->id;
		}
		else if($authUser == 12) {
			$parentUserId = DB::Table('chainstoreusers')
			->where('userId', $userId)->first();
			
			$parentUserId = $parentUserId->parentUserId;
		}
		
		
		$email = $request->email;
		
		$this->validate($request, [
			'password' => 'min:6|required_with:passwordConfirmation|same:passwordConfirmation',
			'passwordConfirmation' => 'min:6',
		   'email' => [
			   'required',
			   'unique:users,email',
			],
		   'contactNumber'=> 'unique:users,contactNumber|min:6|max:9|required',
		   'firstName'=> 'required',
		   'roleId'=> 'required'
		   ]);
		
		// User::where('contact',$contact)
		
        
		$user->firstName = $request->firstName;
		$user->lastName = $request->lastName;
		$user->password = Hash::make($request->password);
		//$user->password = $request->password;
		$user->email = $request->email;
		$user->roleId = $request->roleId;
		$user->contactNumber = $request->contactNumber;
		//$user->roleId = '1';
		$user->save(); 

		
		$userId = $user->id;
		
		$chainStoreUsers = new ChainStoreUsers;
		$chainStoreUsers->userId = $userId;
		$chainStoreUsers->parentAdminUserId = $parentUserId;
		$chainStoreUsers->save();
		
		Helper::addToLog('adminmanagementAdd',$request->firstName);
        return redirect('admin/usersmanagement');             
    }
	
	public function edit($id)
    {
		$userData = DB::Table('users as U')->leftJoin('mas_role as m','m.id','U.roleId')
		->select('U.id','U.firstName','U.lastName','U.email','U.contactNumber', 'U.roleId','m.name')->where('U.id', $id)->get();
		
		$userData = $userData[0];

		return view('admin.usersmanagement.edit',compact('userData'));
    }
	
	public function update(Request $request)
    {
		$user = new User;
        
		$this->validate($request, [
			
		   'email' => 'required',
			'firstName'=> 'required',
		   
		   ]);
		
		
		//$user = new User;
		$user = User::find($request->input('id'));
		/*
		$this->validate($request, [
			
		  /* 'email' => [
			   'required',
			   'unique:users,email',
			],
			*/

		  /* 'email'=> 'required|unique:users,email'.$request->input('id'),
		   'contactNumber'=> 'unique:users,contactNumber|min:6|max:9'.$request->input('id'),*/
		   //'email'=> 'required|unique:users,email,', 
		   //'contactNumber'=> 'unique:users,contactNumber|min:6|max:9'
		  // ]);
		//*/
		//if(!$user->password)
		/*$this->validate($request, [	'password' => 'min:6|:passwordConfirmation|same:passwordConfirmation',
		'passwordConfirmation' => 'min:6'
     	]);*/
		
		if(!empty($request->password)) {
			$this->validate($request, [
				'password' => 'min:6',
				'passwordConfirmation' => 'min:6|required_with:password|same:password'
			]);
		}
		
		$user->firstName = $request->firstName;
		$user->lastName = $request->lastName;
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		if(!empty($request->password))
			$user->password = Hash::make($request->password);
			
		
        $user->save(); 
		
		Helper::addToLog('adminmanagementEdit', $request->firstName);
        return redirect('admin/usersmanagement');  
    }
	public function destroy($id)
    {
		
        $userdata = User::find($id);
		
		
        $userdata->delete();
		
		Helper::addToLog('adminmanagementAdd',$request->firstName);
		return redirect('admin/adminmanagementAdd');  
		
    }	

	public function view($id)
    {      
		$Gender = config('app.Gender');
		
		$driverdata = DB::Table('Drivers as D')->leftJoin('mas_vehicletype', 'mas_vehicletype.id', '=', 'D.vehicleType')->leftJoin('users', 'users.id', '=', 'D.userId')
		->select('D.id','D.gender','users.contactNumber','D.vehicleNumber','D.hoursOfServiceFrom','D.hoursOfServiceTo','mas_vehicletype.typeofvehicle','users.firstName','users.lastName')
		->where('D.id', $id)->first();
		
		return view('admin.driver.view',compact('Gender', 'driverdata'));
		
    }
}
