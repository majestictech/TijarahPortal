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
		$subscriptionData = DB::Table ('subscriptionplan')->orderBy('id','DESC')->get();
		//$subscriptionData = DB::Table ('subscriptionplan')->get();
		

		return view('admin.subscription.index',compact('subscriptionData'));
    }
	
	public function create()
    {      
		//$Gender = config('app.Gender');
		//$features = DB::Table('massfeature')->select('id', 'name', 'value')->get(); 

		$durations = DB :: Table('mas_duration')->select('id','duration')->get();


		return view('admin.subscription.create', compact('durations'));
    }
	public function store(Request $request)
    {     
        $subscriptionData = new SubscriptionPlan;

		//$features = DB::Table('massfeature')->select('id', 'name', 'value')->get(); 
		//$durations = DB :: Table('mas_duration')->select('id','duration')->get();

		//$durations = implode(',', $request->duration);
        //$subscriptiondata->duration_id = $durations;
		//$subscriptiondata->duration = $durations;

		$subscriptionData->plan = $request->plan;
		$subscriptionData->price = $request->price;
		$subscriptionData->duration_id = $request->duration;

        $subscriptionData->save(); 

		//echo $subscriptiondata;
		//die;
		Helper::addToLog('subscriptionAdd',$request->plan);
        return redirect('admin/subscription');             
    }
	
	public function edit($id)
    {
		

		$subscriptionData = DB::Table('subscriptionPlan as S')->where('S.id', $id)->get();
		//echo $subscriptiondata;
		$durations = DB :: Table('mas_duration')->select('id','duration')->get();

		
		//die;
		
	   
		$subscriptionData = $subscriptionData[0];



		return view('admin.subscription.edit',compact('subscriptionData','durations'));
    }
	
	public function update(Request $request)
    {
		//$user = new User;
        
		
		$subscriptiondata = SubscriptionPlan::find($request->input('id'));
		//$durations = mas_duration::find($request->input('id'));

	
		$subscriptiondata->plan = $request->plan;
		$subscriptiondata->price = $request->price;
		//$subscriptiondata->duration = $durations;
        $subscriptiondata->save(); 

		Helper::addToLog('subscriptionEdit',$request->plan);
        return redirect('admin/subscription');  
    }

	public function destroy($id)
    {
		
        $subscriptiondata = SubscriptionPlan::find($id);
		
		
        $subscriptiondata->delete();
		
		Helper::addToLog('subscriptionDelete',$subscriptiondata->plan);
		return redirect('admin/subscription');  
		
    }	

	public function view($id)
    {      
	
		
    }
}
