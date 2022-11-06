<?php

namespace App\Http\Controllers\Admin;
use App\Returns;
use App\Orders;
use App\ReturnStatus;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {      
		$returns=DB::Table('returns as R')->leftJoin('mas_return','mas_return.id','=','R.returnStatus')->leftJoin('orders','orders.id','=','R.orderId')
		->select('R.id','mas_return.name','R.dateOrder','R.referenceNo','orders.orderId')
		->orderBy('R.id','DESC')->get();
		return view('admin.return.index', compact('returns') );
    }
	
	public function create()
    {      
	  $returnstatus = ReturnStatus::orderBy('created_at', 'DESC')->get();
	  return view('admin.return.create',compact('returnstatus'));
    }
	public function store(Request $request)
    {    
        $returns = new Returns;
		/*if ($files = $request->file('attachDoc')) 
		{
			$attachDoc = time().'.'.$files->getClientOriginalExtension(); 
			$files->move(public_path('returns'), $attachDoc);
			
		}
		*/
		$returns->referenceNo = $request->referenceNo;
		//$returns->dateOrder = $request->dateOrder;
		$returns->returnNote = $request->returnNote;
		//$returns->attachDoc = $attachDoc;
		
		
        $returns->save(); 

		return redirect('admin/return');             
    }
	
	public function edit($id)
    {
		$returnData = Returns::find($id);
		$returnstatus = ReturnStatus::orderBy('created_at', 'DESC')->get();
		$orderdata = Orders::orderBy('created_at', 'DESC')->get();
		return view('admin.return.edit',compact('id','returnData','returnstatus','orderdata'));
    }
	public function update(Request $request)
    {
		$returnData = Returns::find($request->input('id'));
        //$returnData->referenceNo = $request->referenceNo; 
        //$returnData->dateOrder = $request->dateOrder; 
        $returnData->returnNote = $request->returnNote; 
        $returnData->orderId = $request->orderId; 
        $returnData->returnStatus = $request->returnStatus; 

		/*if ($files = $request->file('attachDoc')) {
			$destinationPath = 'public/returns/'; 
			$attachDoc = date('YmdHis') . "." . $files->getClientOriginalExtension();
			$files->move($destinationPath, $attachDoc);
			$returnData->attachDoc = "$attachDoc";
		}		
		*/
        $returnData->save(); //persist the data
		
		
        return redirect()->route('return.index')->with('info','Returns Updated Successfully');
    }
	
	
	public function destroy($id)
    {
        $returnData = Returns::find($id);
        $returnData->delete();
		 return redirect('admin/return'); 
    }	
	

    
}
