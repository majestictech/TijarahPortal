<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\UserRole;
use App\User;
use App\Driver;
use App\Delivery;
use App\VehicleType;

class DeliveryController extends Controller
{
    public function index()
    {		
		$deliveryData = new Delivery;
		$deliveryData = Delivery::orderBy('created_at', 'DESC')->get();
		return view('admin.deliveryslot.index',compact('deliveryData'));
    }
	
	public function create()
    {		
		return view('admin.deliveryslot.create');
    }
	
	public function store(Request $request)
    {    
        
        $delivery = new Delivery;
		$delivery->startingTime = $request->startingTime;
		$delivery->endingTime = $request->endingTime;
		$delivery->maxSlot = $request->maxSlot;
		$delivery->slotName = $request->slotName;
        $delivery->save(); 
        return redirect('admin/deliveryslot');             
    }
	
	public function destroy($id)
    {
		
        $DeliveryData = Delivery::find($id);
        $DeliveryData->delete();
		return redirect('admin/deliveryslot'); 
		
    }	
	
	public function edit($id)
    {

		$DeliveryData = Delivery::find($id);
		return view('admin.deliveryslot.edit',compact('id','DeliveryData'));
    }
	
	public function update(Request $request)
    {
		$delivery = Delivery::find($request->input('id'));
		$delivery->startingTime = $request->startingTime;
		$delivery->endingTime = $request->endingTime;
		$delivery->maxSlot = $request->maxSlot;
		$delivery->slotName = $request->slotName;
        $delivery->save(); 
		
        return redirect('admin/deliveryslot');  
    }

}
