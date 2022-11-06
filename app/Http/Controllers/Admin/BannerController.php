<?php

namespace App\Http\Controllers\Admin;
use Validator;
use App\Helpers\AppHelper as Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Http\Controllers\Controller;
use DB;
use App\Banner;


use Illuminate\Support\Facades\Input;
use Auth;
use File;
use Image;



class BannerController extends Controller
{
    public function index()
    {      
		$banner = new Banner;
		$banner = Banner::orderBy('created_at', 'DESC')->get();
        return view('admin.banner.index', compact('banner') );
	}
	
	public function create()
    {      
		$categoryList = helper::parentCategoriesList("category","","","",false);
		return view('admin.banner.create', compact('categoryList'));
    }
	
	public function store(Request $request)
    {    
        $banner = new Banner;
		if($request->file()){
			$files = $request->file('bannerImage');
				$destinationPath = public_path().'/images/';		
						
				$filename = time().'.'.$files->getClientOriginalExtension();
				//$filename = str_replace(' ','_',$filename);
							
				$thumb_img = Image::make($files->getRealPath())->fit(400, 200);
				$thumb_img->save($destinationPath.'/400x200/'.$filename,100);	
				
				
				$files->move($destinationPath, $filename);
				$banner->bannerImage = $filename;			
				
		}
		
		
		
		
		
		/*if ($files = $request->file('bannerImage')) 
		{
			$bannerImage = time().'.'.$files->getClientOriginalExtension(); 
			$files->move(public_path('images'), $bannerImage);
			//$bannerImage = $bannerImage;
		}
			*/	
		$banner->categoryId = $request->category; 
		$banner->bannerTitle = $request->bannerTitle; 	
		$banner->bannerFrom = $request->bannerFrom; 	
		$banner->bannerTo = $request->bannerTo; 
		//$banner->bannerImage = $bannerImage; 
		$banner->description = $request->description;
		
		
        $banner->save();

        return redirect('admin/banner'); 
   
	}
	
	public function destroy($id)
    {
        $BannerData = Banner::find($id);
        $BannerData->delete();
		 return redirect('admin/banner'); 
    }	
	
	public function edit($id)
    {

		$BannerData = Banner::find($id);
		$categoryList = helper::parentCategoriesList("category",$BannerData->categoryId,"","",false);
		return view('admin.banner.edit',compact('id','BannerData','categoryList'));
    }
	
	public function update(Request $request)
    {
        //Retrieve the employee and update
		$banner = Banner::find($request->input('id'));
        $banner->bannerTitle = $request->bannerTitle; 
		$banner->bannerFrom = $request->bannerFrom;
		$banner->bannerTo = $request->bannerTo;
		$banner->description = $request->description;
		$banner->categoryId = $request->category;
		
	if($request->file()){
			$files = $request->file('bannerImage');
				$destinationPath = public_path().'/images/';		
						
				$filename = time().'.'.$files->getClientOriginalExtension();
				//$filename = str_replace(' ','_',$filename);
							
				$thumb_img = Image::make($files->getRealPath())->fit(400, 200);
				$thumb_img->save($destinationPath.'/400x200/'.$filename,100);	
				
				
				$files->move($destinationPath, $filename);
				$banner->bannerImage = $filename;			
				
		}
		
		
		
        $banner->save(); 
		
		
        return redirect()->route('banner.index')->with('info','Banner Updated Successfully');
    }


    
}
