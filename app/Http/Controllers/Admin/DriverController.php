<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\UserRole;
use App\User;
use App\Driver;
use App\VehicleType;
use App\Helpers\AppHelper as Helper;

class DriverController extends Controller
{
    public function index()
    {		
		$Gender = config('app.Gender');
		
		$driverData = DB::Table('drivers as D')->leftJoin('mas_vehicletype', 'mas_vehicletype.id', '=', 'D.vehicleType')->leftJoin('users', 'users.id', '=', 'D.userId')
		->select('D.id','D.gender','D.vehicleNumber','D.uniqueId','D.hoursOfServiceFrom','D.hoursOfServiceTo','mas_vehicletype.typeofvehicle','users.firstName','users.lastName','users.contactNumber')
		->orderBy('D.id', 'DESC')->get();
		
		return view('admin.driver.index',compact('Gender', 'driverData'));
    }
	
	public function create()
    {      
		$Gender = config('app.Gender');
		
		$vehicledata = VehicleType::orderBy('id', 'DESC')->get();
		
		return view('admin.driver.create',compact('Gender','vehicledata'));
    }
	
	public function store(Request $request)
    {    
        $user = new User;
        $driver = new Driver;
        $userrole = new UserRole;
		
		$name = explode(' ',$request->fullName);
        $user->firstName = $name[0];
		unset($name[0]);
        $user->lastName = implode(' ',$name);
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->save(); 
		$userId = $user->id;
		
		$driver->userId = $userId;
		
		//$driver->uniqueId = $request->uniqueId;
		$driver->uniqueId = '#'.str_pad($user->id + 1, 8, "0", STR_PAD_LEFT);
		$driver->gender = $request->gender;
		$driver->vehicleType = $request->vehicleType;
		$driver->vehicleNumber = $request->vehicleNumber;
		$driver->address = $request->address;
		$driver->hoursOfServiceFrom = $request->hoursOfServiceFrom;
		$driver->hoursOfServiceTo = $request->hoursOfServiceTo;	
        $driver->save(); 

		$userrole->userId = $userId;
		$userrole->roleId = '5';
		
		$userrole->save(); 
		Helper::addToLog('driverAdd',$request->fullName);
        return redirect('admin/driver');             
    }
	
	public function destroy($id)
    {
		
        $driverData = Driver::find($id);
		$userId = $driverData->userId;
		
        $driverData->delete();
		
		$userData = User::find($userId);
		$roleData = UserRole::select('id')->where('userId',$userId);
        $userData->delete();
        $roleData->delete();
		Helper::addToLog('driverDelete',$driverData->fullName);
		return redirect('admin/driver');  
		
    }	
	
	public function edit($id)
    {
		$titleName = 'Edit Driver';
		$Gender = config('app.Gender');
		$vehicledata = VehicleType::orderBy('id', 'DESC')->get();

		$driverData = DB::Table('drivers as D')->select(DB::raw("CONCAT(users.firstName,' ', users.lastName) AS 'fullName'"),'users.email','D.id','D.gender','users.contactNumber','D.uniqueId','D.vehicleType','D.vehicleNumber','D.address','D.hoursOfServiceFrom','D.hoursOfServiceTo')->leftJoin('users', 'users.id', '=', 'D.userId')->where('D.id', $id)->get();
		
		$driverData = $driverData[0];

		return view('admin.driver.edit',compact('titleName','Gender','vehicledata','driverData'));
    }
	
	public function update(Request $request)
    {
		$user = new User;
        $driver = new Driver;
        $userrole = new UserRole;
		
		$driver = Driver::find($request->input('id'));
		
		$userId = $driver->userId;
		$user= User::find($userId);

		//Update User Details
		$name = explode(' ',$request->fullName);
        $user->firstName = $name[0];
		unset($name[0]);
        $user->lastName = implode(' ',$name);
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->save(); 
		
		$driver->gender = $request->gender;
		//$driver->uniqueId = $request->uniqueId;
		$driver->vehicleType = $request->vehicleType;
		$driver->vehicleNumber = $request->vehicleNumber;
		$driver->address = $request->address;
		$driver->hoursOfServiceFrom = $request->hoursOfServiceFrom;
		$driver->hoursOfServiceTo = $request->hoursOfServiceTo;	
        $driver->save(); 
		Helper::addToLog('driverEdit',$request->fullName);
        return redirect('admin/driver');  
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
