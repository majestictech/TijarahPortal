<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\AppHelper as Helper;
use Mail;
use App\Http\Controllers\Controller;
use App\Settings;
use DB;
use App\Tax;

class VatController extends Controller
{
    public function create()
    {      
		return view('admin.vat.create');
    }
    
    function testemail()
    {
            echo "test email::<br>";
                //die;
                $topmsg = "Hello how are You?";
                $this->nameto = "Hemlata";
                $this->emailto = 'abhishek@majestictechnologies.net' ;
                Mail::send('admin.emails.myTestMail',
                   array(
                          'topmsg' => $topmsg
                 ), function($message)
                 {
                          $message->from('info@tijarah.com');
                           $message->to($this->emailto,$this->nameto)->subject('Tijarah ECR');
                  });
                
    }
    
    
    
    public function index()
    {
        
        $vat = Tax::orderBy('created_at', 'DESC')->get();
		$vatcount=count($vat);
        return view('admin.vat.index', compact('vat','vatcount'));
	}
	
	public function store(Request $request)
    {    
        $vat = new Tax;

        $this->validate($request, [
	        'name' => 'required',
		    'value' => 'required'
		   ]);
		
        $vat->name = $request->name; 	
		$vat->value = $request->value;
        $vat->save(); 
        
        Helper::addToLog('vatAdd',$request->name);
		return redirect()->route('vat.index');
    
	}
	public function edit($id)
    {
		
       //get user data
		$vat = Tax::find($id);
		return view('admin.vat.edit',compact('vat'));
    }
	public function update(Request $request)
    {   

        $vat = new Tax;
        
		$this->validate($request, [
			
		   'name' => 'required',
			'value'=> 'required',
		   
		   ]);

        //Retrieve the employee and update
		$vat = Tax::find($request->input('id'));
        $vat->name = $request->name; 	
		$vat->value = $request->value;
        $vat->save();  
		
		Helper::addToLog('vatEdit',$request->name);
        return redirect()->route('vat.index');
    }
	
	public function destroy($id)
    {
		
        //destroy user data
        $vat = Tax::find($id);
		
		//die();
        if ($vat->delete()) {
           
            Helper::addToLog('vatDelete',$vat->name);
            return Redirect::to('admin/vat');
        } else {
            //error msg
            return Redirect::back()->withErrors(['Something went wrong.']);
        }
    }	
}
