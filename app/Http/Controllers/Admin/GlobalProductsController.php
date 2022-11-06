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
use App\Weight;
use App\Brand;
use App\Tax;
use App\Product_AR_Global;
use App\ProductGlobal;
use App\Product_UR;
use App\Product_BN;
use App\Exports\ProductExport;
use Image;
use Auth;
use App\StoreType;

class GlobalProductsController extends Controller
{

    
    public function globalimport(Request $request) 
    {
        Excel::import(new ProductImportGlobal, request()->file('file'));
        return redirect()->back();     
    }
    

	
    public function index(Request $request)
    {     
		$product = new ProductGlobal;
		
    	 $storetype = StoreType::orderBy('id', 'DESC')->get();
        
         $storeFilter = $request->storeFilter;
         $search = $request->search;
         
         
         if(null!=$storeFilter)
        {

		    $product = DB::Table('products_global as P')->leftJoin('categories', 'categories.id', '=', 'P.categoryId')
	    	->select('P.id','P.name','P.code','P.price','P.productImage','P.sellingPrice','P.minOrderQty','categories.name AS catName','P.productImgBase64')
	    	->where('P.storeType',$request->storeFilter)
	    	->where ('P.name', 'LIKE', '%' . $search . '%' )
	    	->orderBy('P.id', 'DESC')->paginate(20);
		   
		}
		
		else
		{
		    $product = DB::Table('products_global as P')->leftJoin('categories', 'categories.id', '=', 'P.categoryId')
		    ->select('P.id','P.name','P.code','P.price','P.productImage','P.sellingPrice','P.minOrderQty','categories.name AS catName','P.productImgBase64')
		    ->where ('P.name', 'LIKE', '%' . $search . '%' )
		    ->orderBy('P.id', 'DESC')->paginate(20);
		    
		}
         
         
		$productcount= count($product);
		return view('admin.globalproducts.index', compact('product','productcount','storetype','storeFilter','search'));
    }

    

  /*  public function create($id)
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
			
		}
		if($request->file())
		{
		    //$destinationPath = "/home/majtechnosoft/public_html/posadmin/public/products/";
	        //$imgPre = time();
			//$productImage = $imgPre.basename( $_FILES['file']['name']);
			$files = $request->file('productImage');
			$destinationPath = public_path().'/products/';		
			$filename = time().'.'.$files->getClientOriginalExtension();
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
		$product->var_price = $request->var_price;
		$product->minInventory = $request->minInventory;
		$product->inventory = $request->inventory;
		//$product->weightClassId = $request->weightClassId;
		$product->taxClassId = $request->taxClassId;
		
		
		$taxdata = Tax::WHERE('id', $request->taxClassId)->first();
		
		$product->price = $request->sellingPrice/(1+($taxdata->value/100));
		$product->storeId = $request->storeId;
        $product->save(); 
		
		
		$product_ar->productId = $product->id;
		$product_ar->name = $request->name_ar;
    	//$product_ar->description = $request->desArabic;
		$product_ar->save();
		
		
		
        return redirect('admin/product/' . $request->storeId);             
    }*/
	
	public function update(Request $request)
    {
		$product = ProductGlobal::find($request->input('id'));
        $product->name = $request->name;
		$product->code = $request->code;
		$product->barCode = $request->barCode;
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
		//$product->weightClassId = $request->weightClassId;
		$product->taxClassId = $request->taxClassId;
		$product->looseItem = $request->looseItem;
		
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
		
		$product_ar = Product_AR_Global::select('id')->where('productID', $request->input('id'))->first();
		
		
		
		$product_ar->productId = $product->id;
		$product_ar->name = $request->name_ar;
	//	$product_ar->description = $request->desArabic;
		$product_ar->save();
		
         Helper::addToLog('globalproductsEdit',$request->name);
         return redirect('admin/globalproducts/');
	}

	
	public function edit($id)
    {
		$ProductData = DB::Table('products_global AS P')->leftJoin('products_ar_global AS P_AR', 'P_AR.productId', '=', 'P.id')
		->leftJoin('products_ur AS P_UR', 'P_UR.productId', '=', 'P.id')->leftJoin('products_ml AS P_ML', 'P_ML.productId', '=', 'P.id')
		->leftJoin('products_bn AS P_BN', 'P_BN.productId', '=', 'P.id')
		->select('P.looseItem','P.storeId','P.id','P.status','P.name','P.description','P.minInventory','P.taxClassId','P.inventory','P.metaTitle','P.metaDescription','P.productImgBase64','P.metaKeyword','P.sellingPrice','P.weight','P.weightClassId','P.costPrice','P.code','P.barCode','P.categoryId','P.productTags','P.minOrderQty','P_AR.name AS name_ar','P_AR.description AS desArabic','P_UR.name AS name_ur','P_UR.description AS desUrdu','P.taxClassId','P.productImage','P_ML.name AS name_ml','P_ML.description AS desMalay','P_BN.name AS name_bn','P_BN.description AS desBengali','P.brandId')
		->where('P.id', $id)->get();
		$weightdata = Weight::orderBy('id', 'DESC')->get();
		$taxdata = Tax::orderBy('id', 'DESC')->get();
		$brands = Brand::orderBy('id', 'DESC')->get();
		
		$ProductData = $ProductData[0];	
		
		//$categoryList = helper::allCategoriesCatDisable("category",$ProductData->categoryId);
		$categoryList = DB::Table('categories as C')->select('C.id','C.name','C.storeType')
		//->where('C.storeType','=','')
	    ->where('C.typeadmin','=','pos')
		->orderBy('C.id', 'DESC')->get();
		
		return view('admin.globalproducts.edit',compact('categoryList','id','ProductData','weightdata','taxdata','brands'));
    }
	
	
	public function destroy($id)
    {
        //destroy user data
        $ProductData = ProductGlobal::find($id);
		
		$product_ar = Product_AR_Global::select('id')->where('productID',$id);
	//	$product_ur = Product_UR::select('id')->where('productID',$id);
	//	$product_ml = Product_ML::select('id')->where('productID',$id);
	//	$product_bn = Product_BN::select('id')->where('productID',$id);
		
        $ProductData->delete();
		$product_ar->delete();
	//	$product_ur->delete();
	//	$product_ml->delete();
	//	$product_bn->delete();
		
		return redirect()->back();
		
    }
	
	
}
