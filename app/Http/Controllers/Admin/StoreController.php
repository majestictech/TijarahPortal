<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Store;
use App\ChainStores;
use App\User;
use App\Country;
use Maatwebsite\Excel\Facades\Excel;
use App\StoreType;
use Auth;
use DB;
use App\Product_AR_Global;
use App\ProductGlobal;
use Mail;
use App\StoreDevice;
use Carbon\Carbon;
use App\Shift;
use App\Exports\ProductExport;
use App\Exports\ReportExport;
use App\Exports\ExportTest;

use Illuminate\Support\Str;

// For Log Activity and Other
use App\Helpers\AppHelper as Helper;

use Illuminate\Support\Facades\Storage;


use App\Product;
use App\Product_AR;
use App\Cashier;
use App\Customer;


use App\UserRole;
use Hash;

class StoreController extends Controller
{   
    public function index(Request $request)
    {           
        
        $storetype = StoreType::orderBy('id', 'DESC')->get();
        
        $storeFilter = $request->storeFilter;
        $storeId = $request->storeId;
        $search = $request->search;
      /*   print_r($storeFilter);
		die; */
		
		//dd(Auth::user()->roleId);
		if(Auth::user()->roleId != 11 && Auth::user()->roleId != 12 && Auth::user()->roleId != 14) {		
			$stores=DB::Table('stores as S')->leftJoin('mas_country as C', 'C.id', '=', 'S.countryId')
			->leftJoin('mas_storetype as M', 'M.id', '=', 'S.storeType')->leftJoin('users', 'users.id', '=', 'S.userId')
			->select(DB::raw("CONCAT(users.firstName,' ', users.lastName) AS 'fullName'"), 'S.id', 'M.name AS storeType', 'S.storeName', 'users.contactNumber', 'S.regNo', 'S.city', 'C.nicename', 'S.appVersion', 'S.deviceType', 'S.appType', 'S.shopSize', 'S.vatNumber', 'S.status', 'S.subscriptionExpiry');
		}
		else {
			$parentUserId = 0;
			if(Auth::user()->roleId == 11)
				$parentUserId = auth()->user()->id;
			else {
				$userId = Auth::user()->id ?? '' ;
				$parentUserId = DB::Table('chainstoreusers')
				->where('userId', $userId)->first();
				
				if(!empty($parentUserId))
					$parentUserId = $parentUserId->parentAdminUserId;
			}
			
			$stores=DB::Table('chainstores as CS')->leftJoin('stores as S', 'S.id', '=', 'CS.storeId')
			->leftJoin('mas_country as C', 'C.id', '=', 'S.countryId')->leftJoin('mas_storetype as M', 'M.id', '=', 'S.storeType')->leftJoin('users', 'users.id', '=', 'S.userId')
           ->select(DB::raw("CONCAT(users.firstName,' ', users.lastName) AS 'fullName'"),'S.id','M.name AS storeType', 'S.storeName', 'users.contactNumber', 'S.regNo', 'S.city', 'C.nicename', 'S.appVersion', 'S.deviceType', 'S.appType', 'S.shopSize', 'S.vatNumber', 'S.status', 'S.subscriptionExpiry')->where('CS.parentUserId', $parentUserId);
		   //print_r($stores);
		   
		   //dd($parentUserId);
		}
           

	
        
    	
    	
    	
    	
		//print_r($stores);

		if(!empty($storeId)) {
    	    $stores = $stores->where('S.id', $storeId);
    	}
    	else {
            if(null!=$storeFilter && null==$search)
            {          
                
        	    $stores = $stores->where('S.storeType',$request->storeFilter);
        		//$storeId = helper::getStoreId();
        		
            }
            else if(null!=$storeFilter && null!=$search)
            {          
        	$stores = $stores->where('S.storeType',$request->storeFilter)
               ->where ('S.storeName', 'LIKE', '%' . $search . '%' )
               ->orWhere ('users.contactNumber', 'LIKE', '%' . $search . '%' )
               ->orWhere ('S.appVersion', 'LIKE', '%' . $search . '%' )
               ->orWhere ('S.deviceType', 'LIKE', '%' . $search . '%' )
               ->orWhere ('S.appType', 'LIKE', '%' . $search . '%' );
        		//$storeId = helper::getStoreId();
        		
            }
    		else
    		{
        		
				$stores = $stores->where(function($query) use ($search) {
					$query->orWhere ('users.contactNumber', 'LIKE', '%' . $search . '%' )
					->orWhere ('S.storeName', 'LIKE', '%' . $search . '%' )
					->orWhere ('S.appVersion', 'LIKE', '%' . $search . '%' )
					->orWhere ('S.deviceType', 'LIKE', '%' . $search . '%' )
					->orWhere ('S.appType', 'LIKE', '%' . $search . '%' );
				});
				
				
				
    		    
    		}
    	}

		$stores = $stores->orderBy('S.id', 'DESC')->paginate(10);

		//die;
    	
    	$storecount=count($stores);
		
		
        
        return view('admin.store.index', compact('stores','storecount','storetype','storeFilter','search','storeId'));
        
       
    }
  
  
    
	public function categories($storeId)
	{
		
		$categoryData = DB::Table('categories as C1')->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C1.id')->leftJoin('categories AS C2', 'C2.id', '=', 'CR.parentCategoryId')
		
		->select('C1.id','C1.catImage','C1.name','C2.name AS ParentName','CR.parentCategoryId')
		->where('C1.storeId', $storeId)->get();
		$categorycount = count($categoryData);
		
		return view('admin.category.index',compact('categoryData','storeId','categorycount'));
	}
	
	public function createcat($id)
    {
		$categoryList = helper::parentCategoriesList("category");
		
		
		return view('admin.category.create',compact('categoryList'));
    }
	
	public function storecat(Request $request,$id)
    {
        $category = new Category;
		$catRelation = new CatRelation;
		$category_ar = new Category_AR;
		$category_ur = new Category_UR;
		$category_ml = new Category_ML;
		$category_bn = new Category_BN;
        /*if ($files = $request->file('catImage')) 
		{
			$catImage = time().'.'.$files->getClientOriginalExtension(); 
			$files->move(public_path('category'), $catImage);
			//print_r($catImage);
			//die;
		}
		*/
		if($request->file())
		{
			$files = $request->file('catImage');
			$destinationPath = public_path().'/category/';		
			$filename = time().'.'.$files->getClientOriginalExtension();
			$thumb_img = Image::make($files->getRealPath())->fit(100, 100);
			$thumb_img->save($destinationPath.'/100x100/'.$filename,100);	
			$files->move($destinationPath, $filename);
			$category->catImage = $filename;			
				
		}
		
		
		$category->name = $request->name;
		$category->description = $request->description;
		$category->metaTitle = $request->metaTitle;
		$category->metaDescription = $request->metaDescription;
		$category->metaKeyword = $request->metaKeyword;
		//$category->catImage = $catImage;
        $category->save();
		
		if(!empty($request->category)) {
			$catRelation->categoryId = $category->id;
			$catRelation->parentCategoryId = $request->category;
			$catRelation->save();
		}
		
		$category_ar->categoryId = $category->id;
		$category_ar->name = $request->name_ar;
		$category_ar->description = $request->description_ar;
		$category_ar->save();
		
		$category_ur->categoryId = $category->id;
		$category_ur->name = $request->name_ur;
		$category_ur->description = $request->description_ur;
		$category_ur->save();
		
		$category_ml->categoryId = $category->id;
		$category_ml->name = $request->name_ml;
		$category_ml->description = $request->description_ml;
		$category_ml->save();
		
		$category_bn->categoryId = $category->id;
		$category_bn->name = $request->name_bn;
		$category_bn->description = $request->description_bn;
		$category_bn->save();
        return redirect('admin/category');             
    }
	
	
	
	
	public function products($storeId)
	{
		
		$search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : '';
		$search = trim($search);
		$inventoryFilter = isset($_GET['inventoryFilter']) && !empty($_GET['inventoryFilter']) ? $_GET['inventoryFilter'] : 'instock';

		
			
		//$search = $request->search;
		//echo $search;
		


		DB::enableQueryLog();


		$product = new Product;
		//$product = Product::orderBy('created_at', 'DESC')->get();
		$product = DB::Table('products as P')->leftJoin('categories', 'categories.id', '=', 'P.categoryId')
		->select('P.id','P.name','P.code','P.price','P.productImage','P.minOrderQty','categories.name AS catName','P.productImgBase64','P.sellingPrice', 'P.status', 'P.inventory', 'P.minInventory', 'P.updated_at')
		->where('P.storeId', $storeId);

		
		if($inventoryFilter == "Available" || $inventoryFilter == "Not Available" ) {
			$product = $product->where('P.status',$inventoryFilter);
		
		}
		else if($inventoryFilter == "lowinventory") {
			
			//$product = $product->where('P.minInventory','>','P.inventory')->where('P.inventory','>', 0);
			$product = $product->whereRaw('P.inventory < P.minInventory')->where('P.inventory','>', 0);
			//$product = $product->where('P.inventory','<', 'P.minInventory');
			//die;
		}
		else if($inventoryFilter == "instock") {
			$product = $product->where('P.inventory','>', 0);
		}
		else if($inventoryFilter == "outofstock") {
			$product = $product->where('P.inventory','<=', 0);
		}
		
		if(!empty($search)) {
			$product = $product->where(function($query) use ($search) {
				
				$query->orWhere('P.name', 'LIKE', '%' . $search . '%' )
				->orWhere ('P.code', 'LIKE', '%' . $search . '%' );
			});
		}
		
		//echo $inventoryFilter;
		//echo count($product);
		//die;
		
		/*
		$query = $product->orderBy('updated_at', 'desc')->toSql();

		$bindings = $product->getBindings();

		$sql = Str::replaceArray('?', $bindings, $query);
		*/
		
		//dd($sql);

		
		

		$product = $product->orderBy('updated_at', 'desc')->paginate(10);

		$productcount = count($product);
		
		
		/*$imageStr = $product['productImgBase64']; // Or wherever you get your string from
        $image = base64_decode(str_replace('data:image/png;base64,', '', $imageStr));
		$base64code = $product->getBase64Image();*/
		
		//dd($sql);

		//print_r($productcount);
		//die();
		return view('admin.product.index', compact('product','storeId','productcount','search', 'inventoryFilter'));
	}
	
	
	public function shifts($storeId)
	{
		$search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : '';

		$shift = DB::Table('shifts as S')
		->select('S.id','S.title','S.hoursOfServiceFrom','S.hoursOfServiceTo')
		->where('S.storeId', $storeId);

		if(!empty($search)) {
			$shift = $shift->where(function($query) use ($search) {
				$query->orWhere('S.title', 'LIKE', '%' . $search . '%' )
				->orWhere ('S.hoursOfServiceFrom', 'LIKE', '%' . $search . '%' )
				->orWhere ('S.hoursOfServiceTo', 'LIKE', '%' . $search . '%' );
			});
		}

		$shift = $shift->paginate(10);
		$shiftcount = count($shift);

		return view('admin.shift.index', compact('shift','storeId','shiftcount','search'));
	}

	public function purchaseorders($storeId)
	{
		$purchaseorder = DB::Table('vendorPurchase as P')->leftJoin('storeVendors as S','S.id','=','P.vendorId')
		->select('P.id','S.vendorName','P.poDate','P.deliveryDate')
		->where('P.storeId', $storeId)->paginate(10);

		return view('admin.purchaseorder.index', compact('purchaseorder','storeId'));
	}
	
	
	
    public function cashiers($storeId)
	{
		$cashier = DB::Table('cashier as C')->leftJoin('users', 'users.id', '=', 'C.userId')
		->select('users.firstName','users.lastName','users.email','C.id','users.contactNumber','users.status')
		->where('C.storeId', $storeId)->get();

		return view('admin.cashier.index', compact('cashier','storeId'));
	}
		
	public function devices($storeId)
	{
        $device = StoreDevice::where('storeId',$storeId)
		->orderBy('created_at', 'DESC')->get();

		return view('admin.device.index', compact('device','storeId'));
	}
	

	public function customers($storeId)
	{
	
		$customer = DB::Table('customers as C')
		->select('C.email','C.customerName' ,'C.id','C.contactNumber','C.address','C.doa','C.dob')
		->where('C.storeName', $storeId)->get();

		return view('admin.customer.index', compact('customer','storeId'));
	}


	
	
	public function invoices($storeId)
	{
		//die;
		$statusFilter = isset($_GET['statusFilter']) && !empty($_GET['statusFilter']) ? $_GET['statusFilter'] : '';

		$startDate = isset($_GET['startDate']) && !empty($_GET['startDate']) ? $_GET['startDate'] : '';
		$endDate = isset($_GET['endDate']) && !empty($_GET['endDate']) ? $_GET['endDate'] : '';

		$search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : '';
		$search = trim($search);

		$invoice = DB::Table('vendorInvoice as I')->leftJoin('storeVendors as S','S.id','=','I.vendorId')
		->select('I.id','S.vendorName','I.invoiceNumber','I.invoiceDate','I.status', 'I.created_at')
		->where('I.storeId', $storeId);

		if(!empty($statusFilter)) {
			$invoice = $invoice->where('I.status', $statusFilter);
		}

		if( !empty($startDate) && !empty($endDate)) {
			$invoice = $invoice->whereBetween(DB::raw('Date(I.created_at)'),[$startDate,$endDate]);
		}
		if(!empty($search)) {
			$invoice = $invoice->where(function($query) use ($search) {
				$query->orWhere('S.vendorName', 'LIKE', '%' . $search . '%' )
				->orWhere ('I.invoiceNumber', 'LIKE', '%' . $search . '%' )
				->orWhere ('I.invoiceDate', 'LIKE', '%' . $search . '%' );
			});
		}

		$invoice = $invoice->orderBy('I.id', 'DESC')->paginate(10);
		
		$invoicecount = count($invoice);
		
		return view('admin.invoice.index', compact('invoice','storeId','search', 'startDate', 'endDate', 'statusFilter'));
	}
	
	public function vendors($storeId)
	{
		$search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : '';	
		$search = trim($search);

		$vendors = DB::Table('storeVendors as V')->leftJoin('stores', 'stores.id', '=', 'V.storeId')
		->select('stores.storeName','V.id','V.vendorName','V.contactNumber','V.email')
		->where('V.storeId', $storeId);

	/* 	$vendors = DB::Table('storeVendors as V')->leftJoin('stores', 'stores.id', '=', 'V.storeId')
		->leftJoin('vendorInvoice as I', 'I.vendorId', '=', 'V.id')
		->select('stores.storeName','V.id','V.vendorName','V.contactNumber','V.email', 'I.vendorId', DB::raw('count(*) as total FROM I WHERE I.vendorId = V.id'))
		>groupBy('I.vendorId')
		->where('V.storeId', $storeId); */

	

		
		

		if(!empty($search)) {
			$vendors = $vendors->where(function($query) use ($search) {
				$query->orWhere('V.vendorName', 'LIKE', '%' . $search . '%' )
				->orWhere ('V.contactNumber', 'LIKE', '%' . $search . '%' )
				->orWhere ('V.email', 'LIKE', '%' . $search . '%' );
			});
		}

		$vendors = $vendors->orderBy('V.id', 'DESC')->paginate(10);
		
		return view('admin.vendor.index', compact('vendors','storeId','search'));

	}
	
	
    public function create()
    {  
		
		$subscriptionPlans = DB::Table('subscriptionplan as SP') 
		->leftJoin('mas_duration', 'mas_duration.id', '=', 'SP.duration_id')
		->select('SP.id','SP.plan', 'mas_duration.duration')->get();
		
		$subscriptionPlanId = 0;
		$subscriptionExpiryDate = '';
		
		if(Auth::user()->roleId != 11 ) {	
			$storetype = StoreType::orderBy('name', 'ASC')->get();
		}
		else {
			// If user is Chain Admin
			
			// Don not show chain store types
			$storetype = StoreType::orderBy('name', 'ASC')->where('type','!=','chain')->get();
			
			// Extract Subscription details from parent
			$parentUserId = auth()->user()->id;
			$parentStore = DB::Table('stores')->select('subscriptionPlanId', 'subscriptionExpiry')->where('userId',$parentUserId)->first();
			
			$subscriptionPlanId = $parentStore->subscriptionPlanId;
			$subscriptionExpiryDate = $parentStore->subscriptionExpiry;
		}
		
		
		$country = Country::orderBy('name', 'ASC')->get();
		$appupdate = DB::Table('app_update')->orderBy('id', 'DESC')->get();
		
		
       
		return view('admin.store.create', compact('storetype','country','appupdate','subscriptionPlans','subscriptionPlanId','subscriptionExpiryDate'));
    }
	
	public function store(Request $request)
    {    
		  	 
        $stores = new Store;

		$this->validate($request, [
			
		   'storeName'=> 'required',
		   'printStoreNameAr'=> 'required',
		   'storeType'=> 'required',
		   'regNo'=> 'required',
		   'address'=> 'required',
		   'printAddAr'=> 'required',
		   'country'=> 'required',
		   'subscriptionPlanId'=> 'required',
		   'subscriptionExpiry'=> 'required',
		   'appVersionUpdate'=> 'required',
		   'contactName'=> 'required',
		   'password' => 'min:6|required_with:passwordConfirmation|same:passwordConfirmation',
			'passwordConfirmation' => 'min:6',
		   'email' => [
			   'required',
			   'unique:users,email',
			],
		   'contactNumber'=> 'unique:users,contactNumber|min:6|max:9|required'
		   ]);
		
		$user = new User;
        $userrole = new UserRole;
        $chainStores = new ChainStores;

		$loggedInUserId = auth()->user()->id;
		$loggedInUserRoleId = auth()->user()->roleId;

		$this->validate($request, [
		'password' => 'min:6|required_with:passwordConfirmation|same:passwordConfirmation',
		'passwordConfirmation' => 'min:6',
		 'contactNumber'=>'unique:users,contactNumber',
		 'email'=>'unique:users,email',
     	]);
		
		$name = explode(' ',$request->contactName);
        $user->firstName = $name[0];
		unset($name[0]);
        $user->lastName = implode(' ',$name);
		$user->email = $request->email;
		//$password = Hash::make('password');
        //$password = Input::get('passwordformfield'); // password is form field
        //$hashed = Hash::make($password);
        
		//$user->password = Hash::make('password');
		$user->password = Hash::make($request->password);
		$user->contactNumber = $request->contactNumber;
		
		$storetype = DB::Table('mas_storetype') 
		->select('type')->where('id', $request->storeType)->first();
		
		// This check is used when admin is creating chain store
		if($storetype->type == 'chain') {
			$user->roleId = '11';
		}
		else {
			$user->roleId = '4';
		}
		
		$user->save(); 
		$userId = $user->id;
		
		$stores->userId = $userId;
		
		$stores->storeName = $request->storeName; 
        $stores->printStoreNameAr = $request->printStoreNameAr;	
		
		
		// This check is used when Chain Admin is setting up new store
		// We will import some of the values from parent store and use them while creating new store
		// Like Store Type will come from parent
		// We can use Grocery Chain store type for sub stores also.
		if(Auth::user()->roleId != 11 ) {	
			//$stores->storeType = $request->storeType;
		}
		else {
			//$stores->storeType = $request->storeType;
		}
        

		
		$stores->storeType = $request->storeType;
		$stores->regNo = $request->regNo;
		$stores->address = $request->address;
		$stores->printAddAr = $request->printAddAr;
		$stores->postalCode = $request->postalCode;
		$stores->city = $request->city;
		$stores->state = $request->state;
		$stores->latitude = $request->latitude;
		$stores->longitude = $request->longitude;
		$stores->countryId = $request->country;
		$stores->appVersion =$request->appVersion;
		$stores->openclosetime =$request->openclosetime;
		$stores->openintime =$request->openintime;
		$stores->status = $request->status;
		$stores->tagLine =$request->tagLine;
		$stores->manageInventory =$request->inventoryLink;
		$stores->smsAlert =$request->smsAlert;
		
		
		$stores->autoGlobalCat =$request->autoGlobalCat;
		
		$stores->onlineMarket =$request->onlineMarket;
		$stores->loyaltyOptions =$request->loyaltyOptions;
		$stores->autoGlobalItems =$request->autoGlobalItems;
		$stores->chatbot =$request->chatbot;
		$stores->deviceType =$request->deviceType;
		$stores->appType =$request->appType;
		$stores->shopSize =$request->shopSize;
		$stores->vatNumber =$request->vatNumber;
		$stores->printFooterEn =$request->printFooterEn;
		$stores->printFooterAr =$request->printFooterAr;
		$stores->subscriptionPlanId =$request->subscriptionPlanId;
		$stores->subscriptionExpiry =$request->subscriptionExpiry;
		$stores->printVat =$request->vatNumber;
		$stores->printPh =$request->contactNumber;
		$stores->appVersionUpdate =$request->appVersionUpdate;
		
        $stores->save();       
		
		
		$storeId = $stores->id;
		
		//print_r($storeId);
		
		
		
		$userrole->userId = $userId;
		$userrole->roleId = '4';
		if($request->autoGlobalItems == 'yes')
	    {
	    $productimport = DB::Table('products_global as GP')->leftJoin('products_ar_global','products_ar_global.productId','=','GP.id')
	    ->select('GP.storeId','GP.id','GP.status','GP.name','GP.price','GP.splPrice','GP.splPriceFrom','GP.splPriceTo','GP.productImgBase64','GP.metaTitle','GP.metaDescription','GP.productVariation','GP.description','GP.minInventory','GP.taxClassId','GP.inventory','GP.metaTitle','GP.metaDescription','GP.metaKeyword','GP.sellingPrice','GP.weight','GP.weightClassId','GP.costPrice','GP.code','GP.barCode','GP.categoryId','GP.productTags','GP.minOrderQty','products_ar_global.name AS name_ar','GP.taxClassId','GP.productImage','GP.brandId')
	    ->where('GP.storeType','=',$request->storeType)
	    ->get();
	    
	    foreach( $productimport as $product)
	    {
	        $newProducts = new Product();
	        
	        $newProducts->name = $product->name;
	   		$newProducts->code = $product->code;
    		$newProducts->barCode = $product->barCode;
    		
    		$newProducts->categoryId = $product->categoryId;
    		$newProducts->brandId = $product->brandId;
    		$newProducts->status= $product->status;
    		$newProducts->storeId = $storeId;
    		$newProducts->price = $product->price;
    		$newProducts->sellingPrice = $product->sellingPrice;
    		$newProducts->costPrice = $product->costPrice;
    		$newProducts->splPrice = $product->splPrice;
    		$newProducts->splPriceFrom = $product->splPriceFrom;
    		$newProducts->splPriceTo = $product->splPriceTo;
    		$newProducts->taxClassId = $product->taxClassId;
    		$newProducts->inventory = $product->inventory;
    		$newProducts->minInventory = $product->minInventory;
    		$newProducts->weight = $product->weight;
    		$newProducts->weightClassId = $product->weightClassId;
    		$newProducts->minOrderQty = $product->minOrderQty;
    		$newProducts->productImage = $product->productImage;
    		$newProducts->productImgBase64 = $product->productImgBase64;
    		$newProducts->productVariation = $product->productVariation;
    		$newProducts->description = $product->description;
    		$newProducts->productTags = $product->productTags;
    		$newProducts->metaTitle = $product->metaTitle;
    		$newProducts->metaDescription = $product->metaDescription;
    		
    		
    		//print_r($product);

	        
            $newProducts->save();
            
            
            $newProductAr = new Product_AR();
            
            $newProductAr->productId =$newProducts->id ;
            $newProductAr->name = $product->name_ar;
            $newProductAr->save();
            
            //echo "AR::" . $product->name_ar;
            
            //die;
	    }
	    
	   /* $productimport::query()
           ->each(function ($oldPost) use ($storeId) {
            $newProducts = $oldPost->replicate();
            $newProducts->setTable('products');

            $newProducts->storeId = $storeId;
            $newProducts->save();
            
            print_r($newProducts);
            
            $newProductAr->productId =$newProducts->id ;
            $newProductAr->name = $newProducts->name_ar;
            $newProductAr->save();
            
            
          });*/
	   
	    }
	   
		$userrole->save(); 

		Helper::addToLog('storeAdd',$request->storeName);
		
		
		if($loggedInUserRoleId == '11') {
			$chainStores->storeId = $storeId;
			$chainStores->parentUserId = $loggedInUserId;
			$chainStores->save();
			
			return redirect('admin/chainstores');
		}
		else
		{
			return redirect('admin/store');
		}
    }

    public function edit($id)	
    {
		
		$subscriptionPlans = DB::Table('subscriptionplan as SP') 
		->leftJoin('mas_duration', 'mas_duration.id', '=', 'SP.duration_id')
		->select('SP.id','SP.plan', 'mas_duration.duration')->get();
		
		
		
        //$stores = Store::find($id);
        $country = Country::orderBy('name', 'ASC')->get();
		
		$subscriptionPlanId = 0;
		$subscriptionExpiryDate = '';
		
        if(Auth::user()->roleId != 11 ) {	
			$storetype = StoreType::orderBy('name', 'ASC')->get();
		}
		else{
			// If user is Chain Admin
			
			// Don not show chain store types
			$storetype = StoreType::orderBy('name', 'ASC')->where('type','!=','chain')->get();
			
			// Extract Subscription details from parent
			$parentUserId = auth()->user()->id;
			$parentStore = DB::Table('stores')->select('subscriptionPlanId', 'subscriptionExpiry')->where('userId',$parentUserId)->first();
			
			$subscriptionPlanId = $parentStore->subscriptionPlanId;
			$subscriptionExpiryDate = $parentStore->subscriptionExpiry;
		}
		
		
		
		//$storestype = StoreType::orderBy('id', 'DESC')->get();
		$appupdate = DB::Table('app_update')->orderBy('id', 'DESC')->get();
		$stores = DB::Table('stores as S')->select(DB::raw("CONCAT(users.firstName,' ', users.lastName) AS 'contactName'"),'S.appVersionUpdate','users.email','S.storeName' ,'S.id','users.contactNumber','S.countryId','S.address','S.storeType','S.printFooterEn','S.printFooterAr','S.postalCode','S.city','S.latitude','S.longitude','S.regNo','S.state','S.appVersion','S.openclosetime','S.openintime','S.tagLine','S.deviceType','S.appType','S.shopSize','S.vatNumber','S.manageInventory','S.smsAlert','S.autoGlobalCat','S.onlineMarket','S.printStoreNameAr','S.printAddAr','S.loyaltyOptions','S.autoGlobalItems','S.chatbot','S.status','S.subscriptionPlanId','S.subscriptionExpiry')
		->leftJoin('users', 'users.id', '=', 'S.userId')->leftJoin('mas_country','S.countryId', '=', 'mas_country.id')
		->where('S.id', $id)->get();
	
		

		
		$stores = $stores[0];
		
		//$subscriptionData = DB::Table('subscriptionPlan as S')->where('S.id', $id)->get();
		
		
		return view('admin.store.edit',compact('stores','country','storetype','appupdate','subscriptionPlans','subscriptionPlanId','subscriptionExpiryDate'));
		
    }
	
    public function update(Request $request)
    {

		$stores = new Store;

		$this->validate($request, [
			
		   'storeName'=> 'required',
		   'printStoreNameAr'=> 'required',
		   'storeType'=> 'required',
		   'regNo'=> 'required',
		   'address'=> 'required',
		   'printAddAr'=> 'required',
		   'country'=> 'required',
		  // 'subscriptionPlanId'=> 'required',
		   'appVersionUpdate'=> 'required',
		  // 'contactName'=> 'required',
		   'email' => 'required',
		  // 'contactNumber'=> 'min:6|max:9|required'
		   ]);

		//$subscriptiondata = SubscriptionPlan::find($request->input('id'));
		//$durations = DB :: Table('mas_duration')->select('id','duration')->get(); 
		//$subscriptiondata->plan = $request->plan;
		//$stores->duration_id = $request->duration;
		
		//$subscriptiondata->duration = $durations;
        //$subscriptiondata->save(); 


		$stores = new Store;
		$user = new User;
		$userrole = new UserRole;
			
		$stores = Store::find($request->input('id'));
		
		$userId = $stores->userId;
		$user= User::find($userId);
		
		//if(!empty($user->password))
		    //$this->validate($request, [	'password' => 'min:6|:passwordConfirmation|same:passwordConfirmation', 'passwordConfirmation' => 'min:6']);
     	
		//Update User Details	
		$name = explode(' ',$request->contactName);
        $user->firstName = $name[0];
		unset($name[0]);
        $user->lastName = implode(' ',$name);
		$user->email = $request->email;
		//$user->contactNumber = $request->contactNumber;
		
		if(!empty($request['password']))
			$user->password = Hash::make($request->password);
			
		//print_r($user);
		//die;
		$user->save();
        
        $stores->storeName = $request->storeName; 
        $stores->printStoreNameAr = $request->printStoreNameAr; 
		$stores->storeType = $request->storeType;
		$stores->regNo = $request->regNo;
		$stores->address = $request->address;
		$stores->printAddAr = $request->printAddAr;
		$stores->state = $request->state;
		$stores->latitude = $request->latitude;
		$stores->longitude = $request->longitude;
		$stores->postalCode = $request->postalCode;
		$stores->countryId = $request->country;
		$stores->city = $request->city;
		$stores->status = $request->status;
		$stores->appVersion =$request->appVersion;	
		$stores->openclosetime =$request->openclosetime;
		$stores->openintime =$request->openintime;
		$stores->tagLine =$request->tagLine;
		$stores->manageInventory =$request->inventoryLink;
		$stores->smsAlert =$request->smsAlert;
		$stores->autoGlobalCat =$request->autoGlobalCat;
		$stores->onlineMarket =$request->onlineMarket;
		$stores->loyaltyOptions =$request->loyaltyOptions;
		$stores->autoGlobalItems =$request->autoGlobalItems;
		$stores->chatbot =$request->chatbot;
		$stores->deviceType =$request->deviceType;
		$stores->appType =$request->appType;
		$stores->shopSize =$request->shopSize;
		$stores->vatNumber =$request->vatNumber;
		$stores->printFooterEn =$request->printFooterEn;
		$stores->printFooterAr =$request->printFooterAr;
		if(!empty($request->subscriptionPlanId))
			$stores->subscriptionPlanId =$request->subscriptionPlanId;
		$stores->subscriptionExpiry =$request->subscriptionExpiry;
		$stores->appVersionUpdate =$request->appVersionUpdate;
		//print_r($stores->storeName);
		
		//print_r($stores->autoGlobalItems);
		//print_r($request->autoGlobalItems);
	    //die();
		if($request->autoGlobalItems == 'NULL' || $request->autoGlobalItems == '')
	    {
	           $stores->autoGlobalItems = 'Yes';
	    }
        $stores->save();
        
        $storeId = $stores->id;
        
        
        
        

		if($request->autoGlobalItems == 'yes')
	    {
    	    $productimport = DB::Table('products_global as GP')->leftJoin('products_ar_global','products_ar_global.productId','=','GP.id')
    	    ->select('GP.storeId','GP.id','GP.status','GP.name','GP.price','GP.splPrice','GP.splPriceFrom','GP.splPriceTo','GP.productImgBase64','GP.metaTitle','GP.metaDescription','GP.productVariation','GP.description','GP.minInventory','GP.taxClassId','GP.inventory','GP.metaTitle','GP.metaDescription','GP.metaKeyword','GP.sellingPrice','GP.weight','GP.weightClassId','GP.costPrice','GP.code','GP.barCode','GP.categoryId','GP.productTags','GP.minOrderQty','products_ar_global.name AS name_ar','GP.taxClassId','GP.productImage','GP.brandId')
    	    ->where('GP.storeType','=',$request->storeType)
    	    ->get();
    	    
    	    foreach( $productimport as $product)
    	    {
    	        $newProducts = new Product();
    	        
    	        $newProducts->name = $product->name;
    	   		$newProducts->code = $product->code;
        		$newProducts->barCode = $product->barCode;
        		
        		$newProducts->categoryId = $product->categoryId;
        		$newProducts->brandId = $product->brandId;
        		$newProducts->status= $product->status;
        		$newProducts->storeId = $storeId;
        		$newProducts->price = $product->price;
        		$newProducts->sellingPrice = $product->sellingPrice;
        		$newProducts->costPrice = $product->costPrice;
        		$newProducts->splPrice = $product->splPrice;
        		$newProducts->splPriceFrom = $product->splPriceFrom;
        		$newProducts->splPriceTo = $product->splPriceTo;
        		$newProducts->taxClassId = $product->taxClassId;
        		$newProducts->inventory = $product->inventory;
        		$newProducts->minInventory = $product->minInventory;
        		$newProducts->weight = $product->weight;
        		$newProducts->weightClassId = $product->weightClassId;
        		$newProducts->minOrderQty = $product->minOrderQty;
        		$newProducts->productImage = $product->productImage;
        		$newProducts->productImgBase64 = $product->productImgBase64;
        		$newProducts->productVariation = $product->productVariation;
        		$newProducts->description = $product->description;
        		$newProducts->productTags = $product->productTags;
        		$newProducts->metaTitle = $product->metaTitle;
        		$newProducts->metaDescription = $product->metaDescription;
        		
        		
        		//print_r($product);
    
    	        
                $newProducts->save();
                
                
                $newProductAr = new Product_AR();
                
                $newProductAr->productId =$newProducts->id ;
                $newProductAr->name = $product->name_ar;
                $newProductAr->save(); 
                
    	    }
	    
	    }
        
        // Log Activity Code Starts Here
        
        $activityMessage = $request->storeName . ' store is updated by {{user}}';
        
        // Helper::addToLog('storeEdit',$request->storeName,$activityMessage);
        Helper::addToLog('storeEdit',$request->storeName);
        
        // Log Activity Code Ends Here

        return redirect('admin/store');
    }
	
	
	
    public function destroy($id)
    {
        //echo 'deleting' . $id;
        
        //die;
        
        /*
        $stores = Store::find($id);
		$userId = $stores->userId;
		
		$storeId = $stores->id;
		
		//$userData = User::find($userId);
		
		
		$userData = User::select('id')->where('id',$userId)->first();
		
		$roleData = UserRole::select('id')->where('userId',$userId)->first();
	
		
		$productData = Product::select('id')->where('storeId',$storeId)->get();
		
	    foreach ( $productData as $productDataId )
		{
    		$productId=	$productDataId->id;
    
    		$productDataAr = Product_AR::select('id')->where('productId',$productId);
    		
    		$productDataAr->delete();
		}
		
        $customerData = Customer::select('id')->where('storeName',$storeId);
        
        $cashierData = Cashier::select('id','userId')->where('storeId',$storeId)->get();
        
        foreach ( $cashierData as $cashierDataUserId )
        {
            $cashierUserId = $cashierDataUserId->userId;
            
            $userCashierData = User::select('id')->where('id',$cashierUserId);
            
            $userCashierData->delete();
            
        }
		
		$stores->delete();
		$userData->delete();
        $roleData->delete();
        $productData->each->delete();
        	
        $customerData->delete();
        $cashierData->each->delete();
        */
		return redirect('admin/store');  

    }
	public function view($id)
    {      
		
		$storedata = DB::Table('stores as S')->leftJoin('mas_country', 'mas_country.id', '=', 'S.countryId')->leftJoin('users', 'users.id', '=', 'S.userId')->leftJoin('mas_storetype','mas_storetype.id','=','S.storeType')
		->select('S.id','S.storeName','users.contactNumber','users.email','S.regNo','S.state','S.city','S.appVersion','mas_country.nicename','users.firstName','mas_storetype.name','users.lastName','S.address','S.latitude','S.longitude','S.deviceType','S.appType','S.shopSize','S.vatNumber','S.printStoreNameAr','S.printAddAr','S.manageInventory','S.smsAlert','S.printFooterEn','S.printFooterAr','S.autoGlobalCat','S.onlineMarket','S.loyaltyOptions','S.autoGlobalItems','S.chatbot', DB::raw("DATE_FORMAT(S.subscriptionExpiry, '%d-%b-%Y') as subscriptionExpiryDate") ,DB::raw("DATE_FORMAT(S.created_at, '%d-%b-%Y') as storeCreatedOn"),'users.id as userId')
		->where('S.id', $id)->first();
		
		
		$orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
		->select('O.id','O.created_at','S.id')
		->whereDate('O.created_at', Carbon::today())
		->where('S.id', $id)
		->get();
		
		$todayorderCount = $orderplaced->count();
	    
	    
		
        $customer = DB::Table('customers as C')->leftJoin('stores as S','S.id' ,'=','C.storeName')
        ->select('C.id')
         ->where('S.id', $id)->
         get();

		$allcustomer = $customer->count();
		


		$orderplaced=DB::Table('orders_pos as O')->leftJoin('stores as S','S.userId','=','O.userId')
		->select('O.id','O.created_at','S.id')
		->where('S.id', $id)
		->get();

		$allorderCount = $orderplaced->count();
		

    	$revenue = DB::Table('stores as S')->leftJoin('orders_pos as O','O.userId','=','S.userId')
                ->select(DB::raw('SUM(O.totalAmount) as totalAmount'))->whereDate('O.created_at', Carbon::today())
                ->where('S.id', $id)
                ->get();
		
		$revenue = $revenue[0]->totalAmount;
		
		return view('admin.store.view',compact('orderplaced','storedata','todayorderCount','allorderCount','allcustomer','revenue'));
  
    }
    

    
    
    public function disableStore(Request $request,$id)
    {
        
		$storedisable = Store::find($id);

        $storedata = DB::table('stores')
        ->WHERE('stores.id','=', $id )
        ->update(['stores.status'=> 'Suspended']);

		//echo $storedata1;
		//die;
		Helper::addToLog('storeDisable',$storedisable->storeName);

        return redirect('admin/store');
        
    }
   
       public function enableStore(Request $request,$id)
    {
		$storeenable = Store::find($id);

        $storedata = DB::table('stores')
        ->WHERE('stores.id','=', $id )
        ->update(['stores.status'=> 'Active']);
		
		//echo $storedata;
		//die;
		Helper::addToLog('storeEnable',$storeenable->storeName);


        return redirect('admin/store');
        
    }
   
    
    public function zeroInventory(Request $request,$id)
    {
        $storeenable = Store::find($id);

        $productUpdate = DB::table('products')
        ->where("products.storeId", '=',  $id)
        ->update(['products.inventory'=> '0']);

		Helper::addToLog('zeroInventory',$storeenable->storeName);
		 
        return redirect('admin/store');
        
    }
    public function emptyInventory(Request $request,$id)
    {
		$storeenable = Store::find($id);

        $productUpdate = DB::table('products')
        ->where("products.storeId", '=',  $id)
        ->delete();
		 
		Helper::addToLog('emptyInventory',$storeenable->storeName);
        return redirect('admin/store');
    }
    
    
    public function export(Request $request) 
    {
        return Excel::download(new ProductExport($request->storeId), 'products.xlsx');


    }
    
    
 
     public function exportReport(Request $request) 
    {
        $fileName = 'TIJ'.$request->storeId.'.xlsx';
        return Excel::download(new ReportExport($request->storeId, $request->start_date, $request->end_date ), $fileName);
         
    }   
    
    public function lowinventoryemail()
	{
	    $storeId = $_REQUEST['storeId'];
	    
	    $test = $_REQUEST['test'];
	    
	     $storedata = DB::table('stores as S')
	     ->select('S.lowInventory')
	     ->where('S.id','=',$storeId)
	     ->get();
	     
	     $storedata = $storedata[0]->lowInventory;

        $fileName = 'lowinventory_' . $storeId . '.csv';
	     //echo $storedata;
	     //die;
	    
	    // Delete old file if any. No need to check if exist as it is not giving any error.
	    Storage::delete($fileName);
	    
	    if($test == "true") {
	        $inventoryFile = Excel::store(new ExportTest(), $fileName);
	    }
	    else {
	        $inventoryFile = Excel::store(new ProductExport(), $fileName);
	    }
	    
	    //echo "inventoryFile:: " . storage_path('app/products123.xlsx');
	    //echo "<br>inventoryFile:: " . storage_path('products123.xlsx');
	    //die;
	    
        $data["email"] = "$storedata";
        //$data["email"] = "hemlata@majestictechnologies.net";
        $data["title"] = "Tijarah ECR Low Inventory Report";
        $data["body"] = "Hello Store Owner<br>This is a low inventory email for your store.<br><br>Regards<br><br>Team<br>Tijarah ECR";
 
        /*
        $files = [
            public_path('files/160031367318.pdf'),
            public_path('files/1599882252.png'),
        ];
        */
        
        //echo '';
        
        //die;
        $files = [
            storage_path('app/' . $fileName)
        ];
  
        Mail::send('admin.emails.myTestMail', array('data' => $data), function($message)use($data, $files) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"]);
 
            foreach ($files as $file){
                $message->attach($file);
            }
        });
        
        die;
        return redirect('admin/store');
	}
}
