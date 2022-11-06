<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\AppHelper as Helper;
use Mail;
use App\Http\Controllers\Controller;
use App\Settings;
use DB;
use App\StoreType;

class StoreTypeController extends Controller
{
    public function create()
    {      
		return view('admin.storetype.create');
    }

    public function index()
    {      

		$storetype = StoreType::orderBy('created_at', 'DESC')->get();
        return view('admin.storetype.index', compact('storetype'));
	}
	
	public function store(Request $request)
    {    
        $storetype = new StoreType;
		
        $storetype->name = $request->name; 	
        $storetype->save();
        
        Helper::addToLog('storetypeAdd',$request->name);
		return redirect()->route('storetype.index');
    
	}
	public function edit($id)
    {
		
       //get user data
		$storetype = StoreType::find($id);
		return view('admin.storetype.edit',compact('storetype'));
    }
	public function update(Request $request)
    {
        //Retrieve the employee and update
		$storetype = StoreType::find($request->input('id'));
        $storetype->name = $request->name; 
        $storetype->save();  
		
		Helper::addToLog('storetypeEdit',$request->name);
        return redirect()->route('storetype.index');
    }
	
	public function destroy($id)
    {
		
        //destroy user data
        $storetype = StoreType::find($id);
		
		//die();
        if ($storetype->delete()) {
            
            Helper::addToLog('storetypeDelete',$storetype->name);
            return Redirect::to('admin/storetype');
        } else {
            //error msg
            return Redirect::back()->withErrors(['Something went wrong.']);
        }
    }	
}
