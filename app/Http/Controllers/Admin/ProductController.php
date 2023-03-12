<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Imports\ProductImportGlobal;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\AppHelper as Helper;
use DB;
use PDF;
use App\Product;
use App\ProductInventoryBatch;
use App\InventoryLogs;
use App\Weight;
use App\Brand;
use App\Tax;
use App\Product_AR;
use App\Product_AR_Global;
use App\ProductGlobal;
use App\Product_ML;
use App\Product_UR;
use App\Product_BN;
use App\Exports\ProductExport;
use Image;
use Auth;
use Carbon\Carbon;

class ProductController extends Controller
{
	public function import(Request $request) 
    {
	    //$storeId = helper::getStoreId();
		//echo storage_path('inventory.csv');
		
		//echo "<br><br>";
		
		//echo request()->file('file');
		
		//die;
        //Excel::import(new ProductImport, storage_path('inventory.csv'));
		
        Excel::import(new ProductImport, request()->file('file'));
	
		Helper::addToLog('productImportAdd','file');
        	return redirect()->back();     
    }
    
    
    public function globalimport(Request $request) 
    {
        Excel::import(new ProductImportGlobal, request()->file('file'));
        return redirect()->back();     
    }
    
    
    
	
	
	/*
	public function import() 
    {
        //Excel::import(new ProductImport, request()->file('file'));
        //echo storage_path('products.xlsx');
        /*Excel::import(new ProductImport, storage_path('products.xlsx'));
        //die;
        return redirect()->back();*/
        //Excel::import(new ProductImport, request()->file('file'));
        //Excel::import(new ProductImport, storage_path('products.xlsx'));
        
        
        //return redirect()->back(); 
    //}*/
	
	
    public function index()
    {   
		
		$product = new Product;
		//$product = Product::orderBy('created_at', 'DESC')->get();
		$product = DB::Table('products as P')->leftJoin('categories', 'categories.id', '=', 'P.categoryId')
		->select('P.id', 'P.name', 'P.code', 'P.price', 'P.productImage', 'P.minOrderQty', 'P.inventoryData', 'categories.name AS catName', 'P.productImgBase64')
		->orderBy('P.id', 'DESC')->paginate(10);
    	$productcount= count($product);

		return view('admin.product.index', compact('product','globalproduct','productcount')); 
    }
    
    public function storeindex($storeId)
    {   
		$product = new Product;
		$storeId = helper::getStoreId();
		
		$product = DB::Table('products as P')->leftJoin('categories', 'categories.id', '=', 'P.categoryId')
		->select('P.id','P.name','P.code','P.price','P.status','P.sellingPrice','P.productImage','P.minOrderQty','categories.name AS catName','P.productImgBase64')
	    ->where('P.storeId',$storeId)
		->orderBy('P.id', 'DESC')->get();
        
		
		return view('admin.product.index', compact('product','storeId'));
    }
    
     public function export(Request $request) 
    {
        return Excel::download(new ProductExport($request->storeId), 'products.xls');
    }
    
    
	public function downloadPDF()
	{
		// load view for pdf file
		$pdf = PDF::loadView('admin.product.preview')->setOptions(['defaultFont' => 'sans-serif']);

		return $pdf->download('products.pdf');
	}
	

    public function create($id)
    {
		//$categoryList = helper::allCategoriesCatDisable("category");
		$categoryList = DB::Table('categories as C')
		->leftJoin('stores','stores.storeType','=','C.storeType')
		->select('C.id','C.name','C.storeType')
		->where('stores.id','=',$id)
	    ->where('C.typeadmin','=','pos')
		->orderBy('C.id', 'DESC')->get();
		
		//print_r($categoryList);
		//die;
		$weightdata = Weight::orderBy('id', 'DESC')->get();
		$brands = Brand::orderBy('id', 'DESC')->get();
		$taxdata = Tax::orderBy('id', 'DESC')->get();
		return view('admin.product.create',compact('categoryList','weightdata','taxdata','brands','id'));
    }
	
	public function store(Request $request)
    {
		$product = new Product;

		

		$product_ar = new Product_AR;
		
					
		/*if ($files = $request->file('productImage')) 
		{
			$productImage = time().'.'.$files->getClientOriginalExtension(); 
			$files->move(public_path('products'), $productImage);
			
		}*/
		if($request->file())
		{
		    //$destinationPath = "/home/majtechnosoft/public_html/posadmin/public/products/";
	        //$imgPre = time();
			//$productImage = $imgPre.basename( $_FILES['file']['name']);
			$files = $request->file('productImage');
			$destinationPath = public_path().'/products/';		
			$filename = $request->input('id') . '_' . time().'.'.$files->getClientOriginalExtension();
			$thumb_img = Image::make($files->getRealPath())->fit(50, 50);
			$thumb_img->save($destinationPath.'/50x50/'.$filename,100);	
			$files->move($destinationPath, $filename);
			
			$product->productImage = $filename;	
			$data = file_get_contents($destinationPath.'50x50/'.$filename);
		    $productImgBase64 = base64_encode($data);
		    $product->productImgBase64 = $productImgBase64;	
		}
		
		
		$product->name = $request->name;
		$product->code = $request->code;
		$product->barCode = $request->barCode;
		$product->brandId = $request->brandId;
		$product->categoryId = $request->categoryId;
		//$product->weight = $request->weight;
		$product->productTags = $request->productTags;
		$product->metaTitle = $request->metaTitle;
		$product->metaDescription = $request->metaDescription;
		$product->metaKeyword = $request->metaKeyword;	
		$product->sellingPrice = $request->sellingPrice;	
		$product->costPrice = $request->costPrice;
		$product->minOrderQty = $request->minOrderQty;	
		//$product->description = $request->description;		
		//$product->productImage = $productImage;
		$product->status = $request->status;
		/*$product->productVariation = $request->productVariation;
		$product->variation = $request->variation;
		$product->variationUnit = $request->variationUnit;
		$product->var_price = $request->var_price;*/
		$product->minInventory = $request->minInventory;
		$product->inventory = $request->inventory;
		//$product->status = $request->status;
		//$product->weightClassId = $request->weightClassId;
		$product->taxClassId = $request->taxClassId;
		
		
		$taxdata = Tax::WHERE('id', $request->taxClassId)->first();
		
		
		$product->looseItem = $request->looseItem;
		
		$product->price = $request->sellingPrice/(1+($taxdata->value/100));
		$product->storeId = $request->storeId;
		//$product->expiryDate =$request->expiryDate;
        $product->save(); 
		
		
		$product_ar->productId = $product->id;
		$product_ar->name = $request->name_ar;
    	//$product_ar->description = $request->desArabic;
		$product_ar->save();
		
		
		Helper::addToLog('productAdd',$request->name);
        return redirect('admin/product/' . $request->storeId);             
    }
	
	public function edit($id)
    {
        /*
        $ProductData = DB::Table('productswithimages')->where('storeId', 94)->get();
        
        foreach($ProductData as $productUpdate) {
            $productGlobal = ProductGlobal::where('name', $productUpdate->name)->first();
            
            if(!empty($productGlobal)) {
                $productImage = $productUpdate->productImgBase64;
                $productGlobal->productImgBase64 = $productImage;
                
                //$productGlobal->productImgBase64 = 'NULL';
                
                //echo "ID:: " . $productUpdate->id . " - ";
                //echo "ID:: " . $productUpdate->name . "<br>";
                
                //echo "Image:: " . empty($productUpdate->productImgBase64);
                
                $productGlobal->save();
            }
		    //die;
        }
       */
       
        /*
        $productGlobal = ProductGlobal::get();
        
        foreach($productGlobal as $productUpdate) {
            $ProductData = DB::Table('productswithimages')->where('storeId', 94)->where('name', $productUpdate->name)->get();
            
            $productImage = $productUpdate->productImgBase64;
            
            $productGlobalUpdate = ProductGlobal::where('id', $productUpdate->id)->first();
            
            $productGlobal->productImgBase64 = $productImage;
            
            
            byte[] image = cursor.getBlob(cursor.getColumnIndex("image"));
            if(image.length == 1) {
               //column data is empty
            } else{
               //column has data
            }
            
            $productGlobal->save();
        }
        */
       // die;
        
        
        
        /*$ProductData = DB::Table('products AS P')->leftJoin('products_ar AS P_AR', 'P_AR.productId', '=', 'P.id')
		->leftJoin('products_ur AS P_UR', 'P_UR.productId', '=', 'P.id')->leftJoin('products_ml AS P_ML', 'P_ML.productId', '=', 'P.id')
		->leftJoin('products_bn AS P_BN', 'P_BN.productId', '=', 'P.id')
		->select('P.storeId','P.id','P.status','P.name','P.description','P.minInventory','P.taxClassId','P.inventory','P.metaTitle','P.metaDescription','P.metaKeyword','P.sellingPrice','P.weight','P.weightClassId','P.costPrice','P.code','P.barCode','P.categoryId','P.productTags','P.minOrderQty','P_AR.name AS name_ar','P_AR.description AS desArabic','P_UR.name AS name_ur','P_UR.description AS desUrdu','P.taxClassId','P.productImage','P_ML.name AS name_ml','P_ML.description AS desMalay','P_BN.name AS name_bn','P_BN.description AS desBengali','P.brandId')
		->where('P.storeId', 91)->get();
		//->where('P.id',79927)
		
		foreach($ProductData as $productUpdate) {
		    echo "ID:: " . $productUpdate->id . "<br>";
		    echo "Name:: " . $productUpdate->name . "<br><br>";
		    $name = $productUpdate->name ;
		    $filename = $name . '.jpeg';
		    //$filename = $name . '.jpg';
		    //$filename = $name . '.png';

		    
		    $destinationPath = public_path().'/products/chickenstore/';
		    $destinationFile = $destinationPath . $filename;
		    
		    echo $destinationPath. "<br><br>";
		    echo $destinationFile. "<br><br>";
		    
		    if(file_exists($destinationFile))
		    {
		        $thumb_img = Image::make($destinationFile)->fit(100, 100);
		        $thumb_img->save($destinationPath.'/50x50/'.$filename,100);	
		        //echo $thumb_img;
		        
		        $data = file_get_contents($destinationPath.'50x50/'.$filename);
		   
		        echo filesize($destinationPath.'50x50/'.$filename) . "<br><br>";
		        
		        //if(filesize($destinationPath) < 48000) {
        		    $productImgBase64 = base64_encode($data);
        		    
        		    $product = Product::find($productUpdate->id);
        		    $product->productImgBase64 = $productImgBase64;
        		    
        		    $product->save();
		        //}
		    }
		}
		
		die;*/
		
		
		
		$ProductData = DB::Table('products AS P')->leftJoin('products_ar AS P_AR', 'P_AR.productId', '=', 'P.id')
		->leftJoin('products_ur AS P_UR', 'P_UR.productId', '=', 'P.id')->leftJoin('products_ml AS P_ML', 'P_ML.productId', '=', 'P.id')
		->leftJoin('products_bn AS P_BN', 'P_BN.productId', '=', 'P.id')
		->select('P.looseItem','P.storeId','P.id','P.status','P.name','P.expiryDate','P.description','P.minInventory','P.taxClassId','P.inventory','P.metaTitle','P.metaDescription','P.metaKeyword','P.productImgBase64','P.sellingPrice','P.weight','P.weightClassId','P.costPrice','P.code','P.barCode', 'P.boxBarCode', 'P.categoryId', 'P.productTags', 'P.minOrderQty', 'P_AR.name AS name_ar','P_AR.description AS desArabic','P_UR.name AS name_ur','P_UR.description AS desUrdu','P.taxClassId','P.productImage','P_ML.name AS name_ml','P_ML.description AS desMalay','P_BN.name AS name_bn','P_BN.description AS desBengali','P.brandId')
		->where('P.id', $id)->get();
		$weightdata = Weight::orderBy('id', 'DESC')->get();
		$taxdata = Tax::orderBy('id', 'DESC')->get();
		$brands = Brand::orderBy('id', 'DESC')->get();
		
		$ProductData = $ProductData[0];	
		
		/*
		//$categoryList = helper::allCategoriesCatDisable("category",$ProductData->categoryId);
		$categoryList = DB::Table('categories as C')->select('C.id','C.name','C.storeType')
		//->where('C.storeType','=','')
	    ->where('C.typeadmin','=','pos')
		->orderBy('C.id', 'DESC')->get();
		*/
		
		
		$categoryList = DB::Table('categories as C')
		->leftJoin('stores','stores.storeType','=','C.storeType')
		->select('C.id','C.name','C.storeType')
		->where('stores.id','=',$ProductData->storeId)
	    ->where('C.typeadmin','=','pos')
		->orderBy('C.id', 'DESC')->get();
		
		
		return view('admin.product.edit',compact('categoryList','id','ProductData','weightdata','taxdata','brands'));
		
		
				
    }
	public function update(Request $request)
    {

		$product = new Product;

		$this->validate($request, [
			'name'=> 'required',
			'name_ar'=> 'required',
			'sellingPrice'=> 'required',
			'taxClassId'=> 'required'
		   ]);

		$product = Product::find($request->input('id'));
        $product->name = $request->name;
		$product->code = $request->code;
		$product->barCode = $request->barCode;
		$product->boxBarCode = $request->boxBarCode;
		$product->storeId = $request->storeId;
		$product->categoryId = $request->categoryId;
		$product->brandId = $request->brandId;
		$product->productTags = $request->productTags;
		$product->metaTitle = $request->metaTitle;
		$product->metaDescription = $request->metaDescription;
		$product->metaKeyword = $request->metaKeyword;	
		$product->sellingPrice = $request->sellingPrice;	
		$product->costPrice = $request->costPrice;
		$product->minOrderQty = $request->minOrderQty;	
		//$product->description = $request->description;
		$product->status = $request->status;	
		/*$product->productVariation = $request->productVariation;
		$product->variation = $request->variation;
		$product->variationUnit = $request->variationUnit;
		$product->var_price = $request->var_price;*/
		$product->minInventory = $request->minInventory;
		$product->inventory = $request->inventory;
		$product->looseItem = $request->looseItem;
		//$product->weightClassId = $request->weightClassId;
		$product->taxClassId = $request->taxClassId;
		//$product->expiryDate =$request->expiryDate;
		
		$taxdata = Tax::WHERE('id', $request->taxClassId)->first();
		
		$product->price = $request->sellingPrice/(1+($taxdata->value/100));
		
		if($request->file())
		{
			$files = $request->file('productImage');
			$destinationPath = public_path().'/products/';		
			$filename = time().'.'.$files->getClientOriginalExtension();
			$thumb_img = Image::make($files->getRealPath())->fit(100, 100);
			$thumb_img->save($destinationPath.'/50x50/'.$filename,100);	
			$files->move($destinationPath, $filename);
			$product->productImage = $filename;	
			$data = file_get_contents($destinationPath.'50x50/'.$filename);
		    $productImgBase64 = base64_encode($data);
		    $product->productImgBase64 = $productImgBase64;
		}
		
        $product->save();
		
		$product_ar = Product_AR::select('id')->where('productID', $request->input('id'))->first();
		if(empty($product_ar)) {
			$product_ar = new Product_AR;
			/* print_r(123);
			print_r($request->input('id'));
			print_r($product->id);
			die; */
		}
		
		
		// $product_ar->productId = $product->id;
		$product_ar->productId = $request->input('id');
		$product_ar->name = $request->name_ar;
	//	$product_ar->description = $request->desArabic;
		$product_ar->save();
		
         Helper::addToLog('productEdit',$request->name);
         return redirect('admin/product/' . $product->storeId);
	}
	
	
	public function destroy($id)
    {
        //destroy user data
        $ProductData = Product::find($id);
		
		$product_ar = Product_AR::select('id')->where('productID',$id);
		$product_ur = Product_UR::select('id')->where('productID',$id);
		$product_ml = Product_ML::select('id')->where('productID',$id);
		$product_bn = Product_BN::select('id')->where('productID',$id);
		
        $ProductData->delete();
		$product_ar->delete();
		$product_ur->delete();
		$product_ml->delete();
		$product_bn->delete();
		

		Helper::addToLog('productDelete','file');
		return redirect()->back();
		
    }
	
	public function view($id)
    {      
		
		$ProductData = DB::Table('products AS P')->leftJoin('products_ar AS P_AR', 'P_AR.productId', '=', 'P.id')->leftJoin('products_ur AS P_UR', 'P_UR.productId', '=', 'P.id')->leftJoin('products_ml AS P_ML', 'P_ML.productId', '=', 'P.id')->leftJoin('products_bn AS P_BN', 'P_BN.productId', '=', 'P.id')->leftJoin('categories', 'categories.id', '=', 'P.categoryId')
		->select('P.id','P.name','P.description','P.metaTitle','P.metaDescription','P.metaKeyword','P.price','P.code','P.barCode','P.productTags','P.minOrderQty','P_AR.name AS name_ar','P_AR.description AS desArabic','P_UR.name AS name_ur','P_UR.description AS desUrdu','P.productImage','P_ML.name AS name_ml','P_ML.description AS desMalay','P_BN.name AS name_bn','P_BN.description AS desBengali','categories.name AS catName')
		->where('P.id', $id)->first();
		
		return view('admin.product.view',compact('ProductData'));
		
    }


	 public function expirydate($productId)
    {
		//die;
		$expiryDate = DB::Table('productInventoryBatch AS PI')
		->select('PI.id','PI.inventory', 'PI.expiryDate')->where('PI.productId', $productId)->orderBy('id','DESC')->get();
		
		$ProductData = DB::Table('products')->select('storeId')->where('id', $productId)->first();
		
		$storeId = $ProductData->storeId;		
		
		return view('admin.product.expirydate',compact('expiryDate','storeId'));
    }

	 public function productlog($productId)
    {
		//die;
		$productlogs = DB::Table('product_log as PL')
		->leftJoin('users as U','U.id', 'PL.userId')
		->leftJoin('stores as S','S.userId', 'PL.userId')
		->select('PL.id', 'PL.userId', 'PL.productId', 'PL.previousStock', 'PL.newStock', 'S.id as storeId',DB::raw('CONCAT(U.firstName, " ", U.lastName) AS userName'), 'PL.created_at' )
		->where('PL.productId', $productId )->orderBy('PL.created_at', 'DESC')->paginate(10);
		
		$productStore = DB::Table('products')->select('storeId')->where('id', $productId)->first();
		
		$storeId = $productStore->storeId;		
		
		return view('admin.product.productlog',compact('productlogs','storeId'));
    }



	public function editInventory($id)
	{
		$stockReasons = DB::Table('mas_reason')->where('type','stock')->get();
		// print_r($stockReason);
		//die; 

		$inventoryData = DB::Table('productInventoryBatch AS PI')
		->select('PI.id','PI.inventory', 'PI.expiryDate')->where('PI.id',$id)->get();

		//print_r($inventoryData);
		//die;

		$inventoryData = $inventoryData[0];
		
		return view('admin.product.stockedit',compact('inventoryData', 'stockReasons'));
	}


	public function updateInventory(Request $request)
	{
		
		$inventoryLogs = new InventoryLogs;
		$inventoryUpdate = ProductInventoryBatch::find($request->input('id'));
		
		$id =  $inventoryUpdate->productId;
		//echo $request->inventory;
		//die;
		$this->validate($request, [
			'reduceQuantity'=> 'required',
			//'reasonId'=> 'required',
			
		   ]);
		/*Inventory Logs Start  */
		$inventoryLogs->productId = $inventoryUpdate->productId;
		$inventoryLogs->expiryId = $inventoryUpdate->id;
		$inventoryLogs->reduceQuantity = $request->reduceQuantity;
		$inventoryLogs->reasonId = $request->reasonId;
	/* 	$inventoryLogs->productId = 43;
		$inventoryLogs->expiryId =2;
		$inventoryLogs->reduceQuantity = 2;
		$inventoryLogs->reasonId =2; */
		/*Inventory Logs End  */

		$inventoryUpdate->inventory = $inventoryUpdate->inventory - $request->reduceQuantity;
		//$inventoryUpdate->reasonId = $request->reasonId;
		$inventoryUpdate->id = $request->id;
		//die;
		$inventoryUpdate->save();
		$inventoryLogs->save();
		
		Helper::addToLog('inventoryEdit',$request->inventory);
		return redirect('admin/product/expirydate/'.$id); 
	}
	
	public function test()
	{
		/*  today */
		//$checkDate = Carbon::now()->toDateString();
		 
		/* yesterday  */
		$checkDate = Carbon::now()->subDays(365)->toDateString();


		$queryData = DB::table('orders_pos')
		->select('id','orderId', 'orderDetail', 'totalAmount','storeId','created_at')
		->where('totalAmount','>',0)
		->where(DB::raw('Date(created_at)'),'>=',$checkDate)
		->orderBy('id','DESC')
		->get();

		$errorOrders = [];
		
		foreach($queryData as $OD) {
			$orderDetails = $OD->orderDetail;
			//print_r($orderDetails);
			$orderDetails = json_decode($orderDetails);
			
			//print_r($orderDetails);
			
			//echo "<br><br>";
			
			
			$productTotal = 0;
			foreach($orderDetails->products as $product) {
				//print_r($product);
				//echo "<br><br>";
				if($product->discPer != 'NaN')
					$productTotal = $productTotal + (($product->sellingPrice) - ($product->sellingPrice*$product->discPer/100))* $product->amount;
				else
					$productTotal = $productTotal + ($product->sellingPrice * $product->amount);
			}
			
			//echo "<br><br>";
			//echo $productTotal;
			//echo "<br><br>";
			//echo $OD->totalAmount;
			
			
			//if(round(floatval($OD->totalAmount),2) != round($productTotal,2)) {
			if(($OD->totalAmount - $productTotal) > 1 || ($productTotal - $OD->totalAmount) > 1) {
				$OD->errorTotalCheck = round($productTotal,2);
				$errorOrders[] = $OD;
			}
			
		}	

		//print_r($errorOrders);
		
		/*
		foreach($errorOrders as $order) {
			print_r($order);
			echo "<br><br>";
		}
		die;
		*/
		return view('admin.product.test',compact('errorOrders'));
	}
	
}
