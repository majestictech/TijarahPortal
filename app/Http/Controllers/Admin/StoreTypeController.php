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
use App\StoreTypeCategory;

class StoreTypeController extends Controller
{
    public function create()
    {		
		return view('admin.storetype.create');
    }

    public function index(Request $request)
    {     

        $search = $request->search;
		$search = trim($search);

		$storetype = DB::Table('mas_storetype')
         ->select('id','name','type')
		->orderBy('created_at', 'DESC');
        
        if(!empty($search)) {
            $storetype = $storetype->where('name','LIKE', '%' . $search . '%');
        }

        $storetype = $storetype ->get();
        return view('admin.storetype.index', compact('storetype', 'search'));
	}
	
	public function store(Request $request)
    {    
        $storetype = new StoreType;

        $this->validate($request, [
			
            'name' => 'required',
            'type'=> 'required'
            
        ]);
		
        $storetype->name = $request->name; 	
        $storetype->type = $request->type; 	
        $storetype->save();
        
        Helper::addToLog('storetypeAdd',$request->name);
		return redirect()->route('storetype.index');
    
	}
	public function edit($id)
    {
		
       //get user data
		$storetype = StoreType::find($id);
		
		//$storetype = DB::Table('storetype as ST')
         //->leftJoin('storetypecat as STC', 'STC.id', '=', 'ST.storeTypeCat')
         //->select('STC.firstName','STC.lastName','STC.email','ST.id','users.contactNumber','users.status','C.storeId','C.shiftId', 'users.roleId', 'C.storeId')
        //->where('C.id', $id)
		//->get();
		
		
		return view('admin.storetype.edit',compact('storetype'));
    }
	public function update(Request $request)
    {

        $storetype = new StoreType;
       


        $this->validate($request, [
			
            'name' => 'required',
            'type' => 'required'
            
            ]);        

        //Retrieve the employee and update
        $storetype = StoreType::find($request->input('id'));
        $storetype->name = $request->name; 
		$storetype->type = $request->type; 	
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
