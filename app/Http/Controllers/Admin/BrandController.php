<?php

namespace App\Http\Controllers\Admin;
use Validator;
use App\Helpers\AppHelper as Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Http\Controllers\Controller;
use DB;
use App\Brand;
use Illuminate\Support\Facades\Input;
use Auth;
use File;
use Image;

class BrandController extends Controller
{
    public function index()
    {      
		$brand = new Brand;
		$brand = Brand::orderBy('created_at', 'DESC')->paginate(10);
		$brandcount=$brand->count();
        return view('admin.brand.index', compact('brand','brandcount') );
	}
	
	public function create()
    {      
		return view('admin.brand.create');
    }
	
	public function store(Request $request)
    {    
        $brand = new Brand;
        
        $ExitingCount = Brand::where('brandName', $request->brandName)->count();
		if($ExitingCount > 0) {
			return redirect('admin/brand')->with('sub', 'Brand Name Already Exist!');

		}
		else
		{
        
            
    		if($request->file()){
    			$files = $request->file('brandImage');
    				$destinationPath = public_path().'/brand/';		
    						
    				$filename = time().'.'.$files->getClientOriginalExtension();
    				//$filename = str_replace(' ','_',$filename);
    							
    				$thumb_img = Image::make($files->getRealPath())->fit(100, 100);
    				$thumb_img->save($destinationPath.'/100x100/'.$filename,100);	
    				
    				
    				$files->move($destinationPath, $filename);
    				$brand->brandImage = $filename;	
    
    		}
    		
    		$brand->brandName = $request->brandName; 
    		$brand->save();
		}
        
        Helper::addToLog('brandAdd',$request->brandName);
        return redirect('admin/brand'); 
   
	}
	
	public function destroy($id)
    {
        $BrandData = Brand::find($id);
        $BrandData->delete();
		Helper::addToLog('brandDelete',$BrandData->brandName);
		return redirect('admin/brand'); 
    }	
	
	public function edit($id)
    {

		$BrandData = Brand::find($id);
		return view('admin.brand.edit',compact('id','BrandData'));
    }
	
	public function update(Request $request)
    {
        //Retrieve the employee and update
		$brand = Brand::find($request->input('id'));
		$brand->brandName = $request->brandName;
		
    	if($request->file()){
    			$files = $request->file('brandImage');
    				$destinationPath = public_path().'/brand/';		
    				$filename = time().'.'.$files->getClientOriginalExtension();
    				//$filename = str_replace(' ','_',$filename);
    							
    				$thumb_img = Image::make($files->getRealPath())->fit(400, 200);
    				$thumb_img->save($destinationPath.'/400x200/'.$filename,100);	
    				
    				
    				$files->move($destinationPath, $filename);
    				$brand->brandImage = $filename;		
    
    				
    		}

		
		
		
        $brand->save(); 
		
		Helper::addToLog('brandEdit',$request->brandName);
        return redirect()->route('brand.index')->with('info','Brand Updated Successfully');
    }


    
}
