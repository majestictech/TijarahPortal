<?php

namespace App\Http\Controllers\Admin;
use DB;
use App\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\AppUpdate;
use App\MasRole;
use Auth;
class UserRoleController extends Controller
{
    public function index(Request $request)
    {      
	  
        $roleFilter = $request->roleFilter;
	    $userRoles = MasRole::select('id','name','userRights')->orderBy('orderRoles','ASC')->get();
	    
	    //$myString = "9,admin@example.com,8";
        //$myArray = explode(',', $myString);
	   
        //print_r($myArray);
	    $permissionArray = [];
	    $userRolesSingle = '';
	    if(!empty($roleFilter))
	    {
	        $userRolesSingle = MasRole::select('userRights')->where('id',$roleFilter)->first();
	        $permissionArray = explode(',', $userRolesSingle->userRights);
	    }
	    //print_r($permissionArray);
	    //die;
	    return view('admin.userroles.index',compact('userRoles','roleFilter','permissionArray','userRolesSingle'));
    }
    
    public function restricted()
    {
         return view('admin.restricted.index');
    }
    
    
    public function store(Request $request)
    {
        //die;
        //$userRoles = MasRole::select('id','name')->get();
        $userrole = new MasRole;
        $userrole = MasRole::find($request->roleFilterVal);
        //$userrole = MasRole::find('1');
        
        $catPermissions = implode(",",$request->get('catPermissions'));
        //print_r($catPermissions);
        //die();
        
        
        $userrole->userRights = $catPermissions; 	
        $userrole->save();   
        //DB::table('users')->update(['remember_token' => null]);
        //$request->session()->flush();
    
        //Auth::logout();
        return redirect('/');
        //return redirect('admin/userroles'); 
        
		
    }
    

}
