<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Http\Controllers\Controller;
use App\Settings;
use DB;
use App\Faq;
use App\Helpers\AppHelper as Helper;

class FaqController extends Controller
{
    public function create()
    {      
		$titleName = 'Add FAQ';
		return view('admin.faq.create', compact('titleName'));
    }

    public function index()
    {      
		$faq = new Faq;
		$faq = Faq::orderBy('created_at', 'DESC')->get();
		$faqcount=count($faq);
        return view('admin.faq.index', compact('faq','faqcount'));
	}
	
	public function store(Request $request)
    {    
        $faq = new Faq;
		
        $faq->question = $request->question; 	
		$faq->answer = $request->answer;
        $faq->save();    
        
        Helper::addToLog('faqAdd',$request->question);
		return redirect()->route('faq.index')->with('info','FAQ Added Successfully');
    
	}
	public function edit($id)
    {
		
       //get user data
		$FaqData = Faq::find($id);
		$titleName = 'Edit FAQ';
		return view('admin.faq.edit',['id'=>$id,'FaqData'=>$FaqData,'titleName'=>$titleName]);
    }
	public function update(Request $request)
    {
        //Retrieve the employee and update
		$faq = Faq::find($request->input('id'));
        $faq->question = $request->question; 
        $faq->answer = $request->answer; 
		
        Helper::addToLog('faqEdit',$request->question);
        $faq->save(); //persist the data
		
		
        return redirect()->route('faq.index')->with('info','FAQ Updated Successfully');
    }
	
	public function destroy($id)
    {
		
        //destroy user data
        $FaqData = Faq::find($id);
		
		//die();
        if ($FaqData->delete()) {
           
            Helper::addToLog('faqDelete',$FaqData->question);
            return Redirect::to('admin/faq')->withSuccess(['FAQ Deleted Successful.']);
        } else {
            //error msg
            return Redirect::back()->withErrors(['Something went wrong.']);
        }
    }	
}
