<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\UserRole;
use App\User;
use Illuminate\Support\Facades\Hash;

class AssociateController extends Controller
{
    public function index()
    {		
		//$Gender = config('app.Gender');
		
		$associatedata = DB::Table('users as U')
		->select('U.id','U.firstName','U.lastName','U.email','U.contactNumber')
		->where('U.roleId','=','8')
		
		->orderBy('U.id', 'DESC')->get();
		$associatecount=count($associatedata);
		
		return view('admin.associate.index',compact('associatedata','associatecount'));
    }
	
	public function create()
    {      
		//$Gender = config('app.Gender');
		
		return view('admin.associate.create');
    }
	
	public function store(Request $request)
    {    
        $user = new User;
		$this->validate($request, [
		'password' => 'min:6|required_with:passwordConfirmation|same:passwordConfirmation',
		'passwordConfirmation' => 'min:6'
     	]);
        
		$user->firstName = $request->firstName;
		$user->lastName = $request->lastName;
		$user->password = Hash::make($request->password);
		//$user->password = $request->password;
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->roleId = '8';
		
		//die();
        $user->save(); 

		
		
        return redirect('admin/associate');             
    }
	
	public function destroy($id)
    {
		
        $userdata = User::find($id);
		
		
        $userdata->delete();
		
		
		return redirect('admin/associate');  
		
    }	
	
	public function edit($id)
    {
		

		$userData = DB::Table('users as U')->select('U.id','U.firstName','U.lastName','U.email','U.contactNumber')->where('U.id', $id)->get();
		
		$userData = $userData[0];

		return view('admin.associate.edit',compact('userData'));
    }
	
	public function update(Request $request)
    {
		//$user = new User;
        
		
		$user = User::find($request->input('id'));
		if(!$user->password)
		$this->validate($request, [	'password' => 'min:6|:passwordConfirmation|same:passwordConfirmation',
		'passwordConfirmation' => 'min:6'
     	]);
		
		$user->firstName = $request->firstName;
		$user->lastName = $request->lastName;
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		if(!empty($request['password']))
			$user->password = Hash::make($request->password);
			
		
        $user->save(); 
 
        return redirect('admin/associate');  
    }

/*	public function view($id)
    {      
		$Gender = config('app.Gender');
		
		$driverdata = DB::Table('Drivers as D')->leftJoin('mas_vehicletype', 'mas_vehicletype.id', '=', 'D.vehicleType')->leftJoin('users', 'users.id', '=', 'D.userId')
		->select('D.id','D.gender','users.contactNumber','D.vehicleNumber','D.hoursOfServiceFrom','D.hoursOfServiceTo','mas_vehicletype.typeofvehicle','users.firstName','users.lastName')
		->where('D.id', $id)->first();
		
		return view('admin.driver.view',compact('Gender', 'driverdata'));
		
    }*/
}
