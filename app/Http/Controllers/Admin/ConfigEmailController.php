<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Http\Controllers\Controller;
use App\Settings;
use DB;
use App\Store;

class ConfigEmailController extends Controller
{
 /*   public function create($id)
    {      
		return view('admin.configemail.create');
    }*/

    /*public function index()
    {      

		$shift = Shift::orderBy('created_at', 'DESC')->get();
        return view('admin.shift.index', compact('shift'));
	}*/
	
	 /*    public function storeindex($storeId)
    {     
		$configemail = new Store;
		$storeId = helper::getStoreId();
		
		$configemail = DB::Table('stores as CE')
		->select('CE.id','CE.lowInventory','CE.dayEndReport','CE.allReport')
	    ->where('CE.storeId',$storeId)
		->orderBy('CE.id', 'DESC')->get();
		
		return view('admin.configemail.create', compact('configemail','storeId'));
    }
	
	public function store(Request $request)
    {    
        $configemail = new Store;
		
		$configemail->lowInventory = $request->lowInventory;
		$configemail->dayEndReport = $request->dayEndReport;
		$configemail->allReport = $request->allReport;
        $configemail->save();     
        return redirect('admin/configemail/create/' . $request->storeId); 
    
	}
	
	
	

    
    /*
	public function update(Request $request)
    {

        $stores = new Store;
        $stores = Store::find($request->input('id'));

		$configemail->lowInventory = $request->lowInventory;
		$configemail->dayEndReport = $request->dayEndReport;
		$configemail->allReport = $request->allReport;
        $configemail->save(); 
		

         return redirect('admin/configemail/create/' . $configemail->storeId);
	}
	
	*/
	
	
	 public function create($id)
    {      
		return view('admin.configemail.create');
    }
	
		public function edit($id)	
    {
		$stores = DB::Table('stores as CE')
		->select('CE.id','CE.lowInventory','CE.dayEndReport','CE.allReport')
		->where('CE.id', $id)->get();
		$stores = $stores[0];

		return view('admin.configemail.create',compact('stores'));
		
    }
    
	
	
	
    public function update(Request $request)
    {
		$stores = new Store;
		$stores = Store::find($request->input('id'));
		
		
		$stores->lowInventory = $request->lowInventory;
		$stores->dayEndReport = $request->dayEndReport;
		$stores->allReport = $request->allReport;
		//print_r($stores->storeName);
		//die();
        $stores->save(); 
		 
        return redirect('admin/store');
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
