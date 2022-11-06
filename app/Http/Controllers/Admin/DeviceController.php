<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\UserRole;
use App\User;
use App\StoreDevice;
use App\Store;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\AppHelper as Helper;

class DeviceController extends Controller
{
    public function index()
    {		

		$device = new StoreDevice;
		
        $device = StoreDevice::orderBy('created_at', 'DESC')->get();
		$devicecount=count($device);
		return view('admin.device.index',compact('device','devicecount'));
    }
    
    public function storeindex($storeId)
    {		
		//$Gender = config('app.Gender');
		$id = "";
		// $storeId = helper::getStoreId();
        $device = StoreDevice::
		where('storeId',$storeId)
		->orderBy('created_at', 'DESC')->get();
		
		
		$devicecount=count($device);
		return view('admin.device.index',compact('device','storeId','devicecount'));
    }
    

	
/*	public function destroy($id)
    {
		
		
		$cashierData = Cashier::find($id);
		$userId = $cashierData->userId;
		
        $cashierData->delete();
		
		$userData = User::find($userId);
		//$roleData = UserRole::select('id')->where('userId',$userId);
        $userData->delete();
        //$roleData->delete();
        return redirect()->back();
        

		
    }	*/
	


	
}
