<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\UserRole;
use App\User;
use App\Salesman;
use App\VehicleType;


class SalesmanController extends Controller
{
    public function index()
    {		
		$Gender = config('app.Gender');
		
		$salesmandata = DB::Table('Salesmen as S')->leftJoin('mas_vehicletype', 'mas_vehicletype.id', '=', 'S.vehicleType')->leftJoin('users', 'users.id', '=', 'S.userId')
		->select('S.id','S.gender','S.vehicleNumber','S.uniqueId','S.hoursOfServiceFrom','S.hoursOfServiceTo','mas_vehicletype.typeofvehicle','users.firstName','users.lastName','users.contactNumber')
		->orderBy('S.id', 'DESC')->get();
		
		return view('admin.salesman.index',compact('Gender', 'salesmandata'));
    }
	
	public function create()
    {      
		$Gender = config('app.Gender');
		
		$vehicledata = VehicleType::orderBy('id', 'DESC')->get();
		
		return view('admin.salesman.create',compact('Gender','vehicledata'));
    }
	
	public function store(Request $request)
    {    
        $user = new User;
        $salesman = new Salesman;
        $userrole = new UserRole;
		
		$name = explode(' ',$request->fullName);
        $user->firstName = $name[0];
		unset($name[0]);
        $user->lastName = implode(' ',$name);
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->save(); 
		$userId = $user->id;
		
		$salesman->userId = $userId;
		
		//$salesman->uniqueId = $request->uniqueId;
		$salesman->uniqueId = '#'.str_pad($user->id + 1, 8, "0", STR_PAD_LEFT);
		$salesman->gender = $request->gender;
		$salesman->vehicleType = $request->vehicleType;
		$salesman->vehicleNumber = $request->vehicleNumber;
		$salesman->address = $request->address;
		$salesman->hoursOfServiceFrom = $request->hoursOfServiceFrom;
		$salesman->hoursOfServiceTo = $request->hoursOfServiceTo;	
        $salesman->save(); 

		$userrole->userId = $userId;
		$userrole->roleId = '5';
		
		$userrole->save(); 
		
        return redirect('admin/salesman');             
    }
	
	public function destroy($id)
    {
        $salesmanData = Salesman::find($id);
		$userId = $salesmanData->userId;
		
        $salesmanData->delete();
		
		$userData = User::find($userId);
		$roleData = UserRole::select('id')->where('userId',$userId);
        $userData->delete();
        $roleData->delete();
		return redirect('admin/salesman');  
		
    }	
	
	public function edit($id)
    {
		$titleName = 'Edit Salesman';
		$Gender = config('app.Gender');
		$vehicledata = VehicleType::orderBy('id', 'DESC')->get();

		$salesmanData = DB::Table('Salesmen as S')->select(DB::raw("CONCAT(users.firstName,' ', users.lastName) AS 'fullName'"),'users.email','S.id','S.gender','users.contactNumber','S.uniqueId','S.vehicleType','S.vehicleNumber','S.address','S.hoursOfServiceFrom','S.hoursOfServiceTo')->leftJoin('users', 'users.id', '=', 'S.userId')->where('S.id', $id)->get();
		
		$salesmanData = $salesmanData[0];

		return view('admin.salesman.edit',compact('titleName','Gender','vehicledata','salesmanData'));
    }
	
	public function update(Request $request)
    {
		$user = new User;
        $salesman = new Salesman;
        $userrole = new UserRole;
		
		$salesman = Salesman::find($request->input('id'));
		
		$userId = $salesman->userId;
		$user= User::find($userId);

		//Update User Details
		$name = explode(' ',$request->fullName);
        $user->firstName = $name[0];
		unset($name[0]);
        $user->lastName = implode(' ',$name);
		$user->email = $request->email;
		$user->contactNumber = $request->contactNumber;
		$user->save(); 
		
		$salesman->gender = $request->gender;
		//$salesman->uniqueId = $request->uniqueId;
		$salesman->vehicleType = $request->vehicleType;
		$salesman->vehicleNumber = $request->vehicleNumber;
		$salesman->address = $request->address;
		$salesman->hoursOfServiceFrom = $request->hoursOfServiceFrom;
		$salesman->hoursOfServiceTo = $request->hoursOfServiceTo;	
        $salesman->save(); 
 
        return redirect('admin/salesman');  
    }

	public function view($id)

	 {      
		$Gender = config('app.Gender');
		
		$salesmandata = DB::Table('Salesmen as S')->leftJoin('mas_vehicletype', 'mas_vehicletype.id', '=', 'S.vehicleType')->leftJoin('users', 'users.id', '=', 'S.userId')
		->select('S.id','S.gender','users.contactNumber','S.vehicleNumber','S.hoursOfServiceFrom','S.hoursOfServiceTo','mas_vehicletype.typeofvehicle','users.firstName','users.lastName')
		->where('S.id', $id)->first();
		
		return view('admin.salesman.view',compact('Gender', 'salesmandata'));
		
    }
	
}
