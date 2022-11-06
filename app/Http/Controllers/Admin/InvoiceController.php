<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Http\Controllers\Controller;
use App\Settings;
use DB;
use App\StoreVendor;
use App\Product;
use App\Store;
use App\VendorInvoice;
use App\Helpers\AppHelper as Helper;


class InvoiceController extends Controller
{
    public function create()
    {      
		$vendor = StoreVendor::orderBy('id', 'DESC')->get();
		$products = Product::orderBy('id', 'DESC')->get();
		$stores = Store::orderBy('id', 'DESC')->get();
		return view('admin.invoice.create', compact('vendor','stores','products'));
    }

    public function index($id)
    {      

       
        $invoice = DB:: Table('vendorInvoice as I')->leftJoin('storeVendors as S','S.id','=','I.vendorId')
        ->select('I.id','I.invoiceNumber','I.invoiceDate','I.vendorName')
        ->orderBy('I.id', 'DESC');
        get();
        return view('admin.invoice.index', compact('invoice'));
	}
	
	
	public function storeindex($storeId)
    {     
		$invoice = new VendorInvoice;
		$storeId = helper::getStoreId();
		
		$invoice = DB::Table('vendorInvoice as I')
		->select('I.id','I.vendorId','I.invoiceNumber','I.invoiceDate')
	    ->where('P.storeId',$storeId)
		->orderBy('P.id', 'DESC')->get();
		
		return view('admin.invoice.index', compact('invoice','storeId'));
    }
	

	public function store(Request $request)
    {    
        $invoice = new VendorInvoice;
		
        $invoice->vendorId = $request->vendorId; 	
		$invoice->invoiceNumber = $request->invoiceNumber;
		$invoice->invoiceDate = $request->invoiceDate;
		$invoice->storeId = $request->storeId;
		$productIds = $request->productId;
		$quantity = $request->quantity;
		$cp = $request->cp;
		
		$rowIdx = count($productIds);
		
		//echo count($productIds);
		//print_r($productIds);
		//die;
		
		for($count = 0; $count < count($productIds); $count++)
        {
            $insert = array(                        
                'id' => $productIds[$count],
                'quantity'  => $quantity[$count],
                'cp'     => $cp[$count],
            );
            $insert_data[] = $insert; 
            print_r($insert_data);
        }
        $invoice->orderDetail = json_encode($insert_data);
		
		
        $invoice->save();       
		
		Helper::addToLog('invoiceAdd',$request->vendorId);
		return redirect('admin/invoice/' . $request->storeId); 
    
	}
	public function edit($id)
    {
        
        
        $vendor = StoreVendor::orderBy('id', 'DESC')->get();
    	$invoice = DB::Table('vendorInvoice as I')->select('I.storeId','I.id','I.invoiceNumber','I.invoiceDate','I.vendorId')
		->where('I.id', $id)->get();
		
		$invoice = $invoice[0];	
		
		return view('admin.invoice.edit',compact('id','invoice','vendor'));
		
    }
    
	public function update(Request $request)
    {
		$vendor = StoreVendor::orderBy('id', 'DESC')->get();
        //Retrieve the employee and update
		$invoice = VendorInvoice::find($request->input('id'));
        $invoice->vendorId = $request->vendorId; 	
		$invoice->invoiceNumber = $request->invoiceNumber;
		$invoice->invoiceDate = $request->invoiceDate;
		$invoice->storeId = $request->storeId;
        $invoice->save();  
		
		
        return redirect('admin/invoice/' . $invoice->storeId);
    }
	
	
}
