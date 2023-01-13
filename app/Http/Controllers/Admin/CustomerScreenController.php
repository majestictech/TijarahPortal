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
use App\CustomerScreen;
use Image;
use Auth;

class CustomerScreenController extends Controller
{
	
    public function index($storeId)
    {   
     
		$sliderData=DB::Table('storeSlider')
		    ->select('id','image')
		     ->where('storeid','=',$storeId)
            ->paginate(10);
            
        $sliderDataCount=$sliderData->count();
    
		return view('admin.customerscreen.index',compact('sliderData','sliderDataCount','storeId'));
    }
    
    
    public function create($storeId)
    {
		$sliderList = helper::parentCategoriesList("slider");
		
		return view('admin.customerscreen.create',compact('sliderList','storeId'));
    }
    
    public function store(Request $request)
    {
        $customerScreen = new CustomerScreen;
        
		if($request->file())
		{
			$files = $request->file('customerScreenImage');
			$destinationPath = public_path().'/customerscreen/';		
			$filename = time().'.'.$files->getClientOriginalExtension();
			$files->move($destinationPath, $filename);
			
			$data = file_get_contents($destinationPath.$filename);
			
			$customerScreen->storeId = $request->storeId;
			$customerScreen->image = base64_encode($data);
			
			$customerScreen->save();
				
		}
		Helper::addToLog('customerScreenAdd',$request->file('customerScreenImage'));
        return redirect('admin/customerscreen/' . $request->storeId);
    }
   
   public function edit($id)
    {
		$sliderData=DB::Table('storeSlider')
		    ->select('id','storeId','image')
		     ->where('id','=',$id)->first();
		
		$storeId=0;
		if(!empty($sliderData))
		{
		    $storeId = $sliderData->storeId;
		}
		return view('admin.customerscreen.edit',compact('sliderData','id','storeId'));
    }
    
    public function update(Request $request)
    {	
		$customerScreen = CustomerScreen::find($request->input('id'));
		
	    if($request->file())
		{
			$files = $request->file('customerScreenImage');
			$destinationPath = public_path().'/customerscreen/';		
			$filename = time().'.'.$files->getClientOriginalExtension();
			$files->move($destinationPath, $filename);
			
			$data = file_get_contents($destinationPath.$filename);
			
			$customerScreen->image = base64_encode($data);
			
			$customerScreen->save();
				
		}
		Helper::addToLog('customerScreenEdit',$request->file('customerScreenImage'));

        return redirect('admin/customerscreen/' . $request->storeId);  
    }
	
	
}
