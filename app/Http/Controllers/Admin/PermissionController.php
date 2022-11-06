<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Country;
use App\Customer;
use Auth;
use App\Permission;
use App\StoreCustomer;
use App\UserRole;
use App\LoyaltyTransaction;
use DB;
use App\Helpers\AppHelper as Helper;

class PermissionController extends Controller
{   
    public function index(Request $request)
    {   
		
	   $permission = Permission::orderBy('id', 'DESC')->get();
       return view('admin.permission.index', compact('permission'));
    }
    

 
}
