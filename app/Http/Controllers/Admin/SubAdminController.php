<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\AppHelper as Helper;
use DB;
use App\UserRole;
use App\User;
use Illuminate\Support\Facades\Hash;

class SubAdminController extends Controller
{
    public function index()
    {		
		//$Gender = config('app.Gender');
		
		$subadmindata = DB::Table('users as U')
		->select('U.id','U.firstName','U.lastName','U.email','U.contactNumber')
		->where('U.roleId','=','2')
		
		->orderBy('U.id', 'DESC')->get();
		$subadmincount=count($subadmindata);
		
		return view('admin.subadmin.index',compact('subadmindata','subadmincount'));
    }
	
	public function create()
    {      
		//$Gender = config('app.Gender');
		
		return view('admin.subadmin.create');
    }
	
	public function store(Request $request)
    {    
        $user = new User;
		$this->validate($request, ['password' => 'min:6|required_with:passwordConfirmation|same:passwordConfirmation',
		'passwordConfirmation' => 'min:6'
     	]);
        
		$user->firstName = $request->firstName;
		$user->lastName = $request->lastName;
		$user->password = Hash::make($request->password);
		//$user->password = $request->password;
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->roleId = '2';
		
		//die();
        $user->save(); 

		
		Helper::addToLog('subadminAdd',$request->firstName);
        return redirect('admin/subadmin');             
    }
	
	public function destroy($id)
    {
		
        $userdata = User::find( $id);
		//$user->firstName = $request->firstName;
		
        $userdata->delete();
		//echo  $userdata;
		//die;
		Helper::addToLog('subadminDelete',$userdata->firstName);
		return redirect('admin/subadmin');  
		
    }	
	
	public function edit($id)
    {
		

		$userData = DB::Table('users as U')->select('U.id','U.firstName','U.lastName','U.email','U.contactNumber')->where('U.id', $id)->get();
		
		$userData = $userData[0];

		return view('admin.subadmin.edit',compact('userData'));
    }
	
	public function update(Request $request)
    {
		//$user = new User;
        
		
		$user = User::find($request->input('id'));
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
		
		Helper::addToLog('subadminEdit',$request->firstName);
        return redirect('admin/subadmin');  
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
