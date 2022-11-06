<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;
use App\Helpers\AppHelper as Helper;
use DB;
use App\Category;
use App\StoreType;
use App\CatRelation;
use App\Category_AR;
use App\Category_UR;
use App\Category_ML;
use App\Category_BN;
use Image;

class CategoryController extends Controller
{
	public function index(Request $request)
    {
        $storetype = StoreType::orderBy('id', 'DESC')->get();
        
         $storeFilter = $request->storeFilter;

        
        
        if(null!=$storeFilter)
        {

		 $categoryData = DB::Table('categories as C1')->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C1.id')->leftJoin('categories AS C2', 'C2.id', '=', 'CR.parentCategoryId')->select('C1.id','C1.catImage','C1.catImgBase64','C1.name','C2.name AS ParentName','CR.parentCategoryId')
                    ->where('C1.storeType',$request->storeFilter)
                    ->where('C1.typeadmin','=','pos')
                   ->paginate(10);
		 
		}
		else
		{
		    $categoryData=DB::Table('categories as C1')->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C1.id')->leftJoin('categories AS C2', 'C2.id', '=', 'CR.parentCategoryId')
		    ->select('C1.id','C1.catImage','C1.catImgBase64','C1.name','C2.name AS ParentName','CR.parentCategoryId')
		     ->where('C1.typeadmin','=','pos')
            ->paginate(10);
		}
		
		$categoryDataCount=$categoryData->count();
		
		return view('admin.category.index',compact('categoryData','storetype','storeFilter','categoryDataCount'));
    }
	
	public function create()
    {
		$categoryList = helper::parentCategoriesList("category");
		$storetype = StoreType::orderBy('id', 'DESC')->get();
		
		return view('admin.category.create',compact('categoryList','storetype'));
    }
	
	public function store(Request $request)
    {
        //die;
        
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
		
		/*$ExitingCount = Category::where('name', $request->name)->count();
		if($ExitingCount > 0) {
			return redirect('admin/category')->with('sub', 'Category Already Exist!');

		}
		else
		{*/
		if($request->file())
		{
			$files = $request->file('catImage');
			$destinationPath = public_path().'/category/';		
			$filename = time().'.'.$files->getClientOriginalExtension();
			$thumb_img = Image::make($files->getRealPath())->fit(100, 100);
			
			$thumb_img->save($destinationPath.'/100x100/'.$filename,100);
			$files->move($destinationPath, $filename);
			$category->catImage = $filename;
			
			$data = file_get_contents($destinationPath.'/100x100/'.$filename);
			//$gzdata = gzcompress($data, 9);
			
			$category->catImgBase64 = base64_encode($data);
			
			/*
			
			
			$thumb_img->save($destinationPath.'/100x100/'.$filename,100);	
			$files->move($destinationPath, $filename);
			$category->catImage = $filename;			*/
				
		}
		
		
		
		
		
		$category->name = $request->name;
		$category->description = $request->description;
		$category->metaTitle = $request->metaTitle;
		$category->storeType = $request->storeType;
		$category->metaDescription = $request->metaDescription;
		$category->metaKeyword = $request->metaKeyword;
		$category->typeadmin = $request->typeadmin;
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
		
		
		//AppHelper::addToLog('categoryAdd');
		
        Helper::addToLog('categoryAdd',$request->name);
        return redirect('admin/category');  
		    
		    
		//}
		
		           
    }
	
	public function destroy($id)
    {
        $categoryData = Category::find($id);
		
		$catRelation = CatRelation::select('id')->where('categoryId',$id);
		$category_ar = Category_AR::select('id')->where('categoryId',$id);
		$category_ur = Category_UR::select('id')->where('categoryId',$id);
		$category_ml = Category_ML::select('id')->where('categoryId',$id);
		$category_bn = Category_BN::select('id')->where('categoryId',$id);
		
		$categoryData->delete();
		$catRelation->delete();
		$category_ar->delete();
		$category_ur->delete();
		$category_ml->delete();
		$category_bn->delete();
		
		//AppHelper::addToLog('categoryDelete');
		
		Helper::addToLog('categoryDelete',$categoryData->name);
		return redirect('admin/category');  
    }
	
	public function edit($id)
    {
		$categoryData = DB::Table('categories AS C')->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C.id')->leftJoin('categories_ar AS C_AR', 'C_AR.categoryId', '=', 'C.id')->leftJoin('categories_ur AS C_UR', 'C_UR.categoryId', '=', 'C.id')->leftJoin('categories_ml AS C_ML', 'C_ML.categoryId', '=', 'C.id')->leftJoin('categories_bn AS C_BN', 'C_BN.categoryId', '=', 'C.id')->select('C.catImage','C.id','C.storeType','C.name','C.description','C.metaTitle','C.metaDescription','C.metaKeyword','CR.parentCategoryId','C_AR.name AS name_ar','C_AR.description AS description_ar','C_UR.name AS name_ur','C_UR.description AS description_ur','C_ML.name AS name_ml','C_ML.description AS description_ml','C_BN.name AS name_bn','C_BN.description AS description_bn')->where('C.id', $id)->get();
		
		$categoryData = $categoryData[0];
		$parentId = $categoryData->parentCategoryId;		
		$storetype = StoreType::orderBy('id', 'DESC')->get();
		$categoryList = helper::parentCategoriesList("category",$parentId,$id);
		return view('admin.category.edit',compact('categoryData','categoryList','storetype'));
    }
	
	public function update(Request $request)
    {	
		$category = Category::find($request->input('id'));
		
		$category->name = $request->name;
		$category->description = $request->description;
		$category->storeType = $request->storeType;
		$category->metaTitle = $request->metaTitle;
		$category->metaDescription = $request->metaDescription;
		$category->metaKeyword = $request->metaTitle;
		
		if($request->file())
		{
			$files = $request->file('catImage');
			
			/*echo "Details:<br>";
		    echo public_path().'/category/';
		    echo "<br>";
		    echo $files->getClientOriginalExtension();
		    echo "<br>";
		    echo $files->getRealPath();*/
		    
		    //die;
			
			$destinationPath = public_path().'/category/';		
			$filename = time().'.'.$files->getClientOriginalExtension();
			$thumb_img = Image::make($files->getRealPath())->fit(100, 100);
			$thumb_img->save($destinationPath.'/100x100/'.$filename,100);
			$files->move($destinationPath, $filename);
			$category->catImage = $filename;
			
			$data = file_get_contents($destinationPath.'/100x100/'.$filename);
			//$gzdata = gzcompress($data, 9);
			
			$category->catImgBase64 = base64_encode($data);
			
		}
		
        $category->save();
		
		$catRelation = CatRelation::select('id')->where('categoryId', $request->input('id'))->first();
		$category_ar = Category_AR::select('id')->where('categoryId', $request->input('id'))->first();
		$category_ur = Category_UR::select('id')->where('categoryId', $request->input('id'))->first();
		$category_ml = Category_ML::select('id')->where('categoryId', $request->input('id'))->first();
		$category_bn = Category_BN::select('id')->where('categoryId', $request->input('id'))->first();
		
		//print_r($request->category);
		//die;
				
		if(!empty($request->category)) {
			if(empty($catRelation))
				$catRelation = new CatRelation;

			$catRelation->categoryId = $request->input('id');
			$catRelation->parentCategoryId = $request->category;
			$catRelation->save();
		}
		else {
			// If request is empty and previously cat relation has entry remove that entry
			if(!empty($catRelation)) {
				$catRelation->delete();
			}
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
		
		//AppHelper::addToLog('categoryEdit');
		
		Helper::addToLog('categoryEdit',$request->name);
        return redirect('admin/category');  
    }
}