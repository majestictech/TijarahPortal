<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\AppHelper as Helper;
use DB;
use App\UserRole;
use App\User;
use App\SubscriptionPlan;
use Illuminate\Support\Facades\Hash;

class SubscriptionController extends Controller
{
    public function index()
    {		
		//$Gender = config('app.Gender');
		
		$subscriptiondata = DB::Table('subscriptionplan')->get();
		
		$subscriptioncount=count($subscriptiondata);
		
		//echo $subscriptiondata ;
		//echo $subscriptioncount ;
		//die;


		return view('admin.subscription.index',compact('subscriptiondata','subscriptioncount'));
    }
	
	public function create()
    {      
		//$Gender = config('app.Gender');
		
		return view('admin.subscription.create');
    }
	public function store(Request $request)
    {    
        $subscriptiondata = new SubscriptionPlan;
		
        
		$subscriptiondata->plan = $request->plan;
		$subscriptiondata->price = $request->price;
        $subscriptiondata->save(); 

		//echo $subscriptiondata;
		//die;
		//Helper::addToLog('subadminAdd',$request->firstName);
        return redirect('admin/subscription');             
    }
	
	public function edit($id)
    {
		

		$subscriptiondata = DB::Table('subscriptionplan as S')->where('S.id', $id)->get();
		
		$subscriptiondata = $subscriptiondata[0];

		return view('admin.subscription.edit',compact('subscriptiondata'));
    }
	
	public function update(Request $request)
    {
		//$user = new User;
        
		
		$subscriptiondata = SubscriptionPlan::find($request->input('id'));
		
		
		$subscriptiondata->plan = $request->plan;
		$subscriptiondata->price = $request->price;
        $subscriptiondata->save(); 
        return redirect('admin/subscription');  
    }

	public function destroy($id)
    {
		
        $subscriptiondata = SubscriptionPlan::find($id);
		
		
        $subscriptiondata->delete();
		
		//Helper::addToLog('subadmindelete',$id);
		return redirect('admin/subscription');  
		
    }	

	public function view($id)
    {      
	
		
    }
}
