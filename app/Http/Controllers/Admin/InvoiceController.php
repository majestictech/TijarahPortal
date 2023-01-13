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
use App\ProductInventoryBatch;
use App\Store;
use App\VendorInvoice;
use App\Helpers\AppHelper as Helper;


class InvoiceController extends Controller
{
    public function create($storeId)
    {      
		
		$vendors = StoreVendor::orderBy('id', 'DESC')->where('storeId', $storeId)->get();
		/*$vendors = DB::Table('storeVendors as V')->leftJoin('stores', 'stores.id', '=', 'V.storeId')
		->select('stores.storeName','V.id','V.vendorName','V.contactNumber','V.email')
		->where('V.storeId', $storeId);*/

		//print_r($vendors);
		//die;
		$products =  DB::Table('products as P')->leftJoin('products_ar as PAR','PAR.productId','=','P.id')
		->leftJoin('mas_taxclass as MTC','MTC.id','=','P.taxClassId')
		->select('P.name','PAR.name as name_ar', 'P.id', 'P.costPrice', 'P.sellingPrice', 'P.barCode', 'P.categoryId', 'P.price', 'P.productImage', 'P.productImgBase64', 'P.inventory', 'P.minInventory', 'MTC.value as tax')
		->orderBy('id', 'DESC')->where('storeId', $storeId)
		//->where('P.id', 60)
		->get();
		/* print_r($products);

	    die; */
		$stores = Store::orderBy('id', 'DESC')->get();
		return view('admin.invoice.create',['Products' => $products], compact('vendors','stores','products','storeId'));
    }
	public function fetchProduct(Request $request){
	$search = $request->search;
	//$storeId= $request->storeId;
	
	

      if(!empty($search)) {
          //$employees = Employees::orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->limit(5)->get();
		  $products =  DB::Table('products as P')->leftJoin('products_ar as PAR','PAR.productId','=','P.id')->leftJoin('mas_taxclass as MTC','MTC.id','=','P.taxClassId')->select('P.name','P.id','P.costPrice','P.sellingPrice','P.barCode','MTC.value as tax','PAR.name as name_ar')->orderBy('id', 'DESC')
		  //->where('storeId', $storeId)
		  ->where('P.name', 'like', '%' .$search . '%')->limit(10)->get();
      }

      $response = array();
      foreach($products as $product){
         $response[] = array("value"=>$product->costPrice,"label"=>$product->name,"id"=>$product->id,"name_ar"=>$product->name_ar,"sellingPrice"=>$product->sellingPrice,"barCode"=>$product->barCode,"tax"=>$product->tax);
      }
	  

      return response()->json($response); 
	}

    public function index()
    {      

      
        $invoice = DB:: Table('vendorInvoice as I')->leftJoin('storeVendors as S','S.id','=','I.vendorId')
        ->select('I.id','I.invoiceNumber','I.invoiceDate','I.vendorName')
        ->orderBy('I.id', 'DESC')
        ->paginate(10);
        return view('admin.invoice.index', compact('invoice'));
	}
	
	
	public function storeindex($storeId)
    {     
		$invoice = new VendorInvoice;
		//$storeId = helper::getStoreId();
		
		$invoice = DB::Table('vendorInvoice as I')
		->select('I.id','I.vendorId','I.invoiceNumber','I.invoiceDate')
	    ->where('P.storeId',$storeId)
		->orderBy('P.id', 'DESC')->paginate(10);
		
		die;
		return view('admin.invoice.index', compact('invoice','storeId'));
    }
	

	public function store(Request $request)
    {    
		/* print_r($request->expiryDate);
		die; */

		/* $name_ar = $request->productNameAr;
		print_r($name_ar);
		die; */
		$response = [
	        'expiryDate'=>$request->expiryDate,
	        'vendorId'=>$request->vendorId,
	        'invoiceNumber'=>$request->invoiceNumber,
	        'invoiceDate'=>$request->invoiceDate,
	        'status'=>$request->status,
	        'storeId'=>$request->storeId,
	        'totalAmount'=>$request->totalAmount,
	        'vatAmount'=>$request->vatAmount,
	        'refundDetail'=>json_encode($request->refundDetail),
	        'orderDetail'=>json_encode($request->orderDetail)
	        ];

		$this->validate($request, [
			'vendorId'=> 'required',
		   	'invoiceNumber'=> 'required',
		   	'invoiceDate'=> 'required',
		   	'productId'=> 'required',
		   	'quantity'=> 'required'
		   ]);
		$vendorData = DB::Table('storeVendors')->select('vendorName','contactNumber','VatNumber')->where('id',$request->vendorId)->first();

		$invoice = new VendorInvoice;
		$invoice->vendorId = $request->vendorId;
		$invoice->vendorDetail = json_encode($vendorData);

		$invoice->invoiceNumber = $request->invoiceNumber;
		$invoice->invoiceDate = $request->invoiceDate;

		$invoice->totalAmount = $request->totalAmount;
		$invoice->vatAmount = $request->vatAmount;
		/* print_r($request->totalAmount);
		die; */


		if( $request->status== 'Complete'){
			$invoice->paymentMode = $request->paymentMode;
			$this->validate($request, [
				'paymentMode'=> 'required'
			], [
				'paymentMode.required' => 'The payment mode field is required. When create the invoice.'
				
			]);
		}

		//$invoice->status = $request->status;
		$invoice->storeId = $request->storeId;

		$productIds = $request->productId;
		$quantity = $request->quantity;
		/* cp is equal to  costPrice*/
		$costPrice = $request->cp;
		$sellingPrice = $request->sellingPrice;
		$barCode = $request->barCode;
		
		$tax = $request->tax;

		$expiryDate = $request->expiryDate;

		/* if(!empty($request->expiryDate)){
		   //$expiryDate = '2099-12-01';
		   print_r($expiryDate);
		   print_r(123);
		   echo "123";
		}

		if(empty($request->expiryDate)){
		   //$expiryDate = '2099-12-01';
		  // print_r($expiryDate);
		   print_r(456);
		   echo "456";
		}
		die; */


		/* print_r($expiryDate);
		print_r(gettype($expiryDate));
		if(count($expiryDate) == 0)
		   echo "Hello";
		else
		   echo "test";
		die; */

		
		/* if( count($request->expiryDate) > 0) {
			
			print_r($request->invoiceDate );
			print_r("Hello" );
			print_r($request->expiryDate );
			print_r("Hello1" );
			print_r($request->expiryDate[0] );
			print_r("Hello2" );

			print_r(count($request->expiryDate) );
			
			die;

		}
		else {
			$expiryDate = '2099-12-01';
			print_r($expiryDate );
			die;

		} */
		

		$productName = $request->productName;
		$name_ar = $request->productNameAr;


		/*
        $invoice = new VendorInvoice;
		
        $invoice->vendorId = $request->vendorId; 	
		$invoice->invoiceNumber = $request->invoiceNumber;
		$invoice->invoiceDate = $request->invoiceDate;
		$invoice->storeId = $request->storeId;
		$productIds = $request->productId;
		$quantity = $request->quantity;
		$cp = $request->cp;
		*/
		//$rowIdx = count($productIds);
		
		//echo count($productIds);
		//print_r($productIds);
		//print_r($invoice);
		//print_r($productName);
		//die;
		//const $totalAmount= '';
		for($count = 0; $count < count($productIds); $count++)
        {
			
            $insert = array(                        
                'id' => (int)($productIds[$count]),
				'name' => $productName[$count],
				'name_ar' => $name_ar[$count],
				'costPrice'     =>  (float)($costPrice[$count]),
				'barCode' => $barCode[$count],
				'sellingPrice' =>  (float)($sellingPrice[$count]),
				'tax' =>  (float)($tax[$count]),
                'quantity'  => (float)($quantity[$count]),
				'expiryDate' => $expiryDate[$count]
            );
            $insert_data[] = $insert; 
           // print_r($insert_data);
            
        }
        $invoice->orderDetail = json_encode($insert_data);
	
		//$totalAmount = ($insert['quantity'] * $insert['costPrice']);
		//$invoice->totalAmount = $totalAmount;

		//$status = 'Complete';
		$invoice->status =   $request->status;
		$invoice->save(); 
		$vendorInvoiceId = $invoice->id;


		/* Update Stock Code Starts */
    	 $products = $insert_data;
		//print_r($products);
		//die;
		
		if($request->status == 'Complete') {
			foreach($products as $product) 
			{
			
				//$updateProduct = new Product;
				$updateProductInventoryBatch = new ProductInventoryBatch;
				
				$updateProduct = Product::find($product['id']);

				$updateProduct->inventory = $updateProduct->inventory + $product['quantity'];
				$updateProduct->save();
				
				// Save Vendor Invocie Id
				$updateProductInventoryBatch->vendorInvoiceId = $vendorInvoiceId;
				$updateProductInventoryBatch->productId = $product['id'];
				$updateProductInventoryBatch->inventory = $product['quantity'];
				$updateProductInventoryBatch->expiryDate = $product['expiryDate'];
				
				$updateProductInventoryBatch->save();
			
			}
		}
        /* Update Stock Code Ends */


		
		
              
		
		Helper::addToLog('invoiceAdd',$request->invoiceNumber);
		return redirect('admin/invoice/' . $request->storeId);
		
    
	}
	public function edit($id)
    {
		$invoice = DB::Table('vendorInvoice as I')
		->select('I.storeId','I.id','I.invoiceNumber','I.invoiceDate','I.vendorId', 'I.orderDetail', 'I.paymentMode',  'I.totalAmount', 'I.vatAmount' )
		->where('I.id', $id)->first();
		
		$storeId = $invoice->storeId;
		
		// Dropdown Data Starts
        $vendor = StoreVendor::where('storeId',$storeId)->orderBy('id', 'DESC')->get();
		$products = DB::Table('products as P')->select('P.id','P.name')->orderBy('id', 'DESC')->get();
		// Dropdown Data Ends
		//print_r($invoice->orderDetail);
		$orderProducts = json_decode($invoice->orderDetail, true);
		$orderProductsCount = count($orderProducts);
		/* print_r($orderProducts);
		print_r($orderProductsCount);
		die; */
		//print_r($area[0]['id']);
		/*
		foreach($orderProducts as $key=>$orderProduct)
		{
		print_r($orderProduct['id']); // this is your area from json response
		print_r($key); // this is your area from json response
		}
		die;*/
		// json_decode orderDetails to fetch products array and set as variable orderProducts
		// pass orderProducts in compact
		// Apply loop of orderProducts in UI
		
		
		return view('admin.invoice.edit',compact('id','invoice','vendor', 'products','orderProducts', 'orderProductsCount'));
		
    }
    
	public function update(Request $request)
    {
		$this->validate($request, [
			'vendorId'=> 'required',
		   	'invoiceNumber'=> 'required',
		   	'invoiceDate'=> 'required',
		   	'productId'=> 'required',
		   	'quantity'=> 'required',
		   	'expiryDate'=> 'required'
		   ]);
		$vendorData = DB::Table('storeVendors')->select('vendorName','contactNumber','VatNumber')->where('id',$request->vendorId)->first();
		

		$invoice =  VendorInvoice::find($request->id);

		$invoice->vendorId = $request->vendorId;
		$invoice->vendorDetail = json_encode($vendorData);

		$invoice->invoiceNumber = $request->invoiceNumber;
		$invoice->invoiceDate = $request->invoiceDate;

		$invoice->totalAmount = $request->totalAmount;
		$invoice->vatAmount = $request->vatAmount;
		/* print_r($request->totalAmount);
		die; */
		
		if( $request->status== 'Complete'){
			$invoice->paymentMode = $request->paymentMode;
			$this->validate($request, [
				'paymentMode'=> 'required'
			], [
				'paymentMode.required' => 'The payment mode field is required. When create the invoice.'
				
			]);
		}


		//$invoice->status = $request->status;
		$invoice->storeId = $request->storeId;

		$productIds = $request->productId;
		$quantity = $request->quantity;

		/* cp is equal to  costPrice*/
		$costPrice = $request->cp;
		$sellingPrice = $request->sellingPrice;
		$barCode = $request->barCode;
		$tax = $request->tax;

		$expiryDate = $request->expiryDate;
		$productName = $request->productName;

		
		/*
        $invoice = new VendorInvoice;
		
        $invoice->vendorId = $request->vendorId; 	
		$invoice->invoiceNumber = $request->invoiceNumber;
		$invoice->invoiceDate = $request->invoiceDate;
		$invoice->storeId = $request->storeId;
		$productIds = $request->productId;
		$quantity = $request->quantity;
		$cp = $request->cp;
		*/
		$rowIdx = count($productIds);
		
		//echo count($productIds);
		//print_r($productIds);
		//die;
		//const $totalAmount= '';
		for($count = 0; $count < count($productIds); $count++)
        {
            $insert = array(                        
                'id' => $productIds[$count],
                'quantity'  => $quantity[$count],
                'costPrice'     => $costPrice[$count],
                'sellingPrice'     => $sellingPrice[$count],
                'barCode'     => $barCode[$count],
                'tax'     => $tax[$count],
				'expiryDate' => $expiryDate[$count],
				'name' => $productName[$count]
            );
            $insert_data[] = $insert; 
          //  print_r($insert_data);
            
        }
        $invoice->orderDetail = json_encode($insert_data);
	
		//$totalAmount = ($insert['quantity'] * $insert['costPrice']);
		//$invoice->totalAmount = $totalAmount;

		//$status = 'Complete';
		$invoice->status =   $request->status;
		$invoice->save(); 
		$vendorInvoiceId = $invoice->id;


		/* Update Stock Code Starts */
    	 $products = $insert_data;
		//print_r($products);
		//die;
		
		if($request->status == 'Complete') {
			foreach($products as $product) 
			{
			
				//$updateProduct = new Product;
				$updateProductInventoryBatch = new ProductInventoryBatch;
				
				$updateProduct = Product::find($product['id']);

				$updateProduct->inventory = $updateProduct->inventory + $product['quantity'];
				$updateProduct->save();
				
				// Save Vendor Invocie Id
				$updateProductInventoryBatch->vendorInvoiceId = $vendorInvoiceId;
				$updateProductInventoryBatch->productId = $product['id'];
				$updateProductInventoryBatch->inventory = $product['quantity'];
				$updateProductInventoryBatch->expiryDate = $product['expiryDate'];
				
				$updateProductInventoryBatch->save();
			
			}
		}
		/*
		$vendor = StoreVendor::orderBy('id', 'DESC')->get();
        //Retrieve the employee and update
		$invoice = VendorInvoice::find($request->input('id'));
        $invoice->vendorId = $request->vendorId; 	
		$invoice->invoiceNumber = $request->invoiceNumber;
		$invoice->invoiceDate = $request->invoiceDate;
		$invoice->storeId = $request->storeId;
        $invoice->save();  
		*/
		
		Helper::addToLog('invoiceEdit',$request->invoiceNumber);
        return redirect('admin/invoice/' . $invoice->storeId);
    }
	
	
}
