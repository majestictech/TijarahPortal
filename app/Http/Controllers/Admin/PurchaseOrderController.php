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
use App\VendorPurchase;


class PurchaseOrderController extends Controller
{
    public function create()
    {      
		$vendor = StoreVendor::orderBy('id', 'DESC')->get();
		$products = Product::orderBy('id', 'DESC')->get();
		$stores = Store::orderBy('id', 'DESC')->get();
		return view('admin.purchaseorder.create', compact('vendor','products','stores'));
    }

    public function index($id)
    {      

       
        $purchaseorder = DB:: Table('vendorPurchase as P')->leftJoin('storeVendors as S','S.id','=','P.vendorId')
        ->select('P.id','P.poDate','P.deliveryDate','S.vendorName')
        ->orderBy('P.id', 'DESC');
        get();
        //print_r($purchaseorder);
        //die();
        return view('admin.purchaseorder.index', compact('purchaseorder'));
	}
	
	
	public function storeindex($storeId)
    {     
		$purchaseorder = new VendorPurchase;
		$storeId = helper::getStoreId();
		
		$purchaseorder = DB::Table('vendorPurchase as P')
		->select('P.id','P.vendorId','P.poDate','P.deliveryDate')
	    ->where('P.storeId',$storeId)
		->orderBy('P.id', 'DESC')->get();
		
		return view('admin.purchaseorder.index', compact('purchaseorder','storeId'));
    }
	

	public function store(Request $request)
    {    
        $purchaseorder = new VendorPurchase;
        $purchaseorder->storeId = $request->storeId;
        //$data =  $request->json($purchaseorder->productId); //read json in request
        //return response()->json($data);  //send json respond
        //die();

        $purchaseorder->vendorId = $request->vendorId; 	
        $purchaseorder->storeId = $request->storeId;
		$purchaseorder->poDate = $request->poDate;
		$purchaseorder->deliveryDate = $request->deliveryDate;
		
		
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
        $purchaseorder->orderDetail = json_encode($insert_data);
        
        
        
        $purchaseorder->save();             
		return redirect('admin/purchaseorder/' . $request->storeId); 
    
	}
	public function edit($id)
    {
        
        
        $vendor = StoreVendor::orderBy('id', 'DESC')->get();
    	$purchaseorder = DB::Table('vendorPurchase as P')->select('P.storeId','P.id','P.poDate','P.deliveryDate','P.vendorId')
		->where('P.id', $id)->get();
		
		$purchaseorder = $purchaseorder[0];	
		
		return view('admin.purchaseorder.edit',compact('id','purchaseorder','vendor'));
		
    }
    
	public function update(Request $request)
    {
        //Retrieve the employee and update
		$purchaseorder = VendorPurchase::find($request->input('id'));
        $purchaseorder->vendorId = $request->vendorId; 	
		$purchaseorder->poDate = $request->poDate;
		$purchaseorder->deliveryDate = $request->deliveryDate;
		$purchaseorder->storeId = $request->storeId;
        $purchaseorder->save();  
		
		
        return redirect('admin/purchaseorder/' . $purchaseorder->storeId);
    }
	
	
}
