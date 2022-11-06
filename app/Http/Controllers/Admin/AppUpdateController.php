<?php

namespace App\Http\Controllers\Admin;
use DB;
use App\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\AppHelper as Helper;
use App\AppUpdate;
class AppUpdateController extends Controller
{
    public function create()
    {      
	  $appupdate = DB::Table('app_update')->orderBy('id', 'DESC')->get();
	  return view('admin.appupdate.create',compact('appupdate'));
    }
    
    public function store(Request $request)
    {
      
        /*
        $myfile = fopen("data_mini.json", "w") or die("Unable to open file!");
        //$inputData = $request->only(['app_ver', 'app_code','appfile','apptype']);
        $txt = $request->app_ver;
        $txt += $request->app_code;
        //$txt += $request->appfile;
        //$txt += $request->apptype;
        fwrite($myfile, $txt);
        //$txt = "Jane Doe\n";
        //fwrite($myfile, $txt);
        fclose($myfile);
        */
        //$inputData['appfile'] = $request->appfile->getClientOriginalName();
        $appupdate = new AppUpdate;
        $appupdate->appCode = $request->appCode; 
        $appupdate->appVer = $request->appVer; 
        $appupdate->appType = $request->appType; 
        
        
        if($request->file())
		{
			$files = $request->file('appfile');
			$destinationPath = public_path().'/apk/';		
			$filename = 'tijarah' . '_' .$request->appType. '_' .$request->appVer.'.'.$files->getClientOriginalExtension();
			$files->move($destinationPath, $filename);
			
			$appupdate->appfile = $filename;	
		}
        
        $appupdate->save();    
        
        /*$updatestore = new S
        
        $product_ar->productId = $product->id;
		$product_ar->name = $request->name_ar;
    	//$product_ar->description = $request->desArabic;
		$product_ar->save();
        
        
        */
        
        /*
        
        
        
        $contactInfo = [];
        //$filename = $request->appfile->getClientOriginalName();
        //echo $filename;
        $inputData = $request->only(['app_ver', 'app_code','appfile','apptype']);
        
        $inputData['appfile'] = $request->appfile->getClientOriginalName();
        
        array_push($contactInfo,$inputData);
       
        if($inputData['apptype'] == 'mini')
        {
            Storage::disk('local')->put('data_mini.json', json_encode($contactInfo));
        }
        else
        {
             Storage::disk('local')->put('data_plus.json', json_encode($contactInfo));
        } 
        $destinationPath = public_path();
        if($request->file())
        {
            $files = $request->appfile; // will get all files
            $file_name = 'tijarah' . '_' .$inputData['apptype']. '_' .$inputData['app_ver'].'.'.$files->getClientOriginalExtension();
            $files->move($destinationPath , $file_name); // move files to destination folder
        }
        */
        Helper::addToLog('appEdit',$request->appType);
        return redirect('admin/appupdate/create');
    }

}
