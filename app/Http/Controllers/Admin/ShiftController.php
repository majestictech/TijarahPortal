<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Http\Controllers\Controller;
use App\Settings;
use DB;
use App\Helpers\AppHelper as helper;
use App\Shift;
use Illuminate\Support\Facades\Hash;


class ShiftController extends Controller
{
    public function create($id)
    {      
		return view('admin.shift.create');
    }

    public function index()
    {      

		$shift = Shift::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.shift.index', compact('shift'));
	}
	
	    public function storeindex($storeId)
    {     
		$shift = new Shift;
		$storeId = helper::getStoreId();
		
		$shift = DB::Table('shifts as S')
		->select('S.id','S.title','S.hoursOfServiceFrom','S.hoursOfServiceTo')
	    ->where('S.storeId',$storeId)
		->orderBy('S.id', 'DESC')->paginate(10);
		
		return view('admin.shift.index', compact('shift','storeId'));
    }
	
	public function store(Request $request)
    {    
        $shift = new Shift;

		$this->validate($request, [
			'title'=> 'required',
			'hoursOfServiceFrom'=> 'required',
			'hoursOfServiceTo'=> 'required'
		]);

        $shift->title = $request->title; 	
		$shift->hoursOfServiceFrom = $request->hoursOfServiceFrom;
		$shift->hoursOfServiceTo = $request->hoursOfServiceTo;
		$shift->storeId = $request->storeId;
        $shift->save();
        
        Helper::addToLog('shiftAdd',$request->title);
        return redirect('admin/shift/' . $request->storeId); 
		//return redirect()->route('shift.index');
    
	}
	
	public function edit($id)
    {
		$shift = DB::Table('shifts as S')->select('S.storeId','S.id','S.title','S.hoursOfServiceFrom','S.hoursOfServiceTo')
		->where('S.id', $id)->get();
		
		$shift = $shift[0];	
		
		return view('admin.shift.edit',compact('id','shift'));
    }
    

	public function update(Request $request)
    {
		$this->validate($request, [
			'title'=> 'required',
			'hoursOfServiceFrom'=> 'required',
			'hoursOfServiceTo'=> 'required'
		]);
		$shift = Shift::find($request->input('id'));
		$shift->title = $request->title; 
		$shift->hoursOfServiceFrom = $request->hoursOfServiceFrom;
		$shift->hoursOfServiceTo = $request->hoursOfServiceTo;
		$shift->storeId = $request->storeId;
        $shift->save(); 

		
         Helper::addToLog('shiftEdit',$request->title);
         return redirect('admin/shift/' . $shift->storeId);
	}
	
}
