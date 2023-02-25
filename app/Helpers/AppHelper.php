<?php
namespace App\Helpers;

use App\Driver;
use config\app;
use App\UserActivityLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use DB;
use App\UserActivityLog as LogActivity;
use App\Category;
/**
 * AppHelper class
 *
 **/

class AppHelper
{
    public static function imageBase64Encode($image)
    {
        return $image;
    }
    
    public static function parentCategoriesList($name="category",$selectedId = '',$skipCategory = '', $class='',$required=false)
    {
		if(empty($skipCategory))
			$categoryData = DB::Table('categories as C')->select('C.id','C.name')
		->whereNotIn('id',function($query) {
			$query->select('categoryId')->from('catrelation');
		})->orderBy('C.id', 'DESC')->get();
		else
			$categoryData = DB::Table('categories as C')->select('C.id','C.name')->where('id', '!=', $skipCategory)->whereNotIn('id',function($query) {
			$query->select('categoryId')->from('catrelation');
		})->orderBy('C.id', 'DESC')->get();
			
		$requiredCheck = "";
		$selected = "";
		
		// Required check
		if($required)
			$requiredCheck = 'data-error="' . __('lang.req_category') . '" required';
		
		$listCategories = '<select name="' . $name . '" class="selectpicker form-control ' . $class . '" data-style="py-0" ' . $requiredCheck . '><option value="">' . __('lang.selectcategory') . '</option>';
		foreach($categoryData as $category) {
			if($category->id == $selectedId)
				$selected = "selected";
			else
				$selected = "";
			$listCategories .= '<option value="' . $category->id . '" ' . $selected . '>' . $category->name . '</option>';
		}
		
		$listCategories .= '</select>';
		
		// Error Message in case of Required
		if($required)
			$listCategories .= '<div class="help-block with-errors"></div>';
		
		return $listCategories;
    }
	
	public static function allCategoriesCatDisable($name="category",$selectedId = '',$class='',$required=false)
    {
		$categoryData = DB::Table('catrelation as CR')
		->leftJoin('categories AS C1', 'C1.id', '=', 'CR.parentCategoryId')
		->leftJoin('categories AS C2', 'C2.id', '=', 'CR.categoryId')
		->select('C1.id AS ParentId', 'C2.id AS CategoryId','C1.name AS ParentName','C2.name AS SubCatName')
		->orderBy('ParentName', 'ASC')
		->orderBy('SubCatName', 'ASC')->get();
		
		$requiredCheck = "required";
		$selected = "";
		
		// Required check
		if($required)
			$requiredCheck = 'data-error="' . __('lang.req_category') . '" required';
		
		$listCategories = '<select name="' . $name . '" class="selectpicker form-control ' . $class . '" data-style="py-0" ' . $requiredCheck . '><option value="">' . __('lang.selectcategory') . '</option>';
		
		$mainCategory = "";
		foreach($categoryData as $category) {
			if($mainCategory != $category->ParentName) {
				// if optgroup started previously
				if(!empty($mainCategory))
					$listCategories .= '</optgroup>';

				$listCategories .= '<optgroup label="' . $category->ParentName . '">';
				
				$mainCategory = $category->ParentName;
			}		
			
			if($category->CategoryId == $selectedId)
				$selected = "selected";
			else
				$selected = "";
			$listCategories .= '<option value="' . $category->CategoryId . '" ' . $selected . ' .$requiredCheck.>' . $category->SubCatName . '</option>';
		}
		
		$listCategories .= '</optgroup></select>';
		
		// Error Message in case of Required
		if($required)
			$listCategories .= '<div class="help-block with-errors"></div>';
		
		return $listCategories;
    }
	
	public static function allCategories($name="category",$selectedId = '',$class='',$required=false,$multiple=false)
    {
		$categoryData = DB::Table('catrelation as CR')
		->leftJoin('categories AS C1', 'C1.id', '=', 'CR.parentCategoryId')
		->leftJoin('categories AS C2', 'C2.id', '=', 'CR.categoryId')
		->select('C1.id AS ParentId', 'C2.id AS CategoryId','C1.name AS ParentName','C2.name AS SubCatName')
		->orderBy('ParentName', 'ASC')
		->orderBy('SubCatName', 'ASC')->get();
		
		$requiredCheck = "";
		$selected = "";
		$multipleCheck = "";
		
		// Required check
		if($required)
			$requiredCheck = 'data-error="' . __('lang.req_category') . '" required';
		
		if($multiple)
			$multipleCheck = 'multiple';
		
		$listCategories = '<select name="' . $name . '" class="selectpicker form-control ' . $class . '" data-style="py-0" ' . $requiredCheck . ' ' . $multipleCheck . '><option value="">' . __('lang.selectcategory') . '</option>';
		
		$mainCategory = "";
		foreach($categoryData as $category) {
			if($mainCategory != $category->ParentName) {
				if($category->ParentId == $selectedId)
					$selected = "selected";
				else
					$selected = "";
			
				$listCategories .= '<option value="' . $category->ParentId . '" ' . $selected . '>' . $category->ParentName . '</option>';
				
				$mainCategory = $category->ParentName;
			}		
			
			if($category->CategoryId == $selectedId)
				$selected = "selected";
			else
				$selected = "";
			
			$listCategories .= '<option value="' . $category->CategoryId . '" ' . $selected . '>&nbsp;--' . $category->SubCatName . '</option>';
		}
		
		$listCategories .= '</select>';
		
		// Error Message in case of Required
		if($required)
			$listCategories .= '<div class="help-block with-errors"></div>';
		
		return $listCategories;
    }
	
	
	public static function getStoreId()
    {
        $userId = auth()->user()->id;
        $storeId = DB::Table('stores')->where('stores.userId',$userId)->select('stores.id')->first();
        $storeId = json_encode($storeId->id); //array to json string conversion
        $storeId = json_decode($storeId); 
        //print_r($storeId);
        return $storeId;
    }
    
    public static function checkStoreId($storeId)
    {
        $userId = auth()->user()->id;
        
        $roleId = auth()->user()->roleId;
        
        // Cashier Check
        if($roleId != 4) {
            return '';
        }
        
        $newStoreIdArray = DB::Table('stores')->select('id')->where('stores.userId',$userId)->select('stores.id')->first();
        
        $newStoreId = $newStoreIdArray->id;
        
        //echo $newStoreId . ' :: ' . $storeId;
        //die;
        
        if($newStoreId != $storeId) {
            
            echo '<div class="card">
    			<div class="card-body">
    				You are not an authorised user.
    			</div>
    		</div>';
    		die;
        }
        
        return '';
    }
    
    public static function totalStoreOrders($storeId)
    {
        
        $totalOrders=DB::Table('orders_pos')
    		    ->where('storeId',$storeId)
                ->count();
    
    
        return $totalOrders;
     
    } 
	
    public static function todayStoreOrders($storeId)
    {
        
        $todayOrders=DB::Table('orders_pos')
                ->whereDate('created_at', Carbon::today())
    		    ->where('storeId',$storeId)
                ->count();
    
    
        return $todayOrders;
     
    } 
    
    
    public static function lastStoreBilled($storeId)
    {
        
        $lastbilled=DB::Table('orders_pos')
                ->select('created_at')
    		    ->where('storeId',$storeId)
    		    ->orderBy('created_at','DESC')
                ->first();
                
        if(!empty($lastbilled))
            return $lastbilled->created_at;
    } 
    
    
    public static function storeTypeCategory($id)
    {
        
        $storeTypes=DB::Table('categories as C1')->leftJoin('catrelation AS CR', 'CR.categoryId', '=', 'C1.id')->leftJoin('categories AS C2', 'C2.id', '=', 'CR.parentCategoryId')->select('C1.id','C1.catImage','C1.catImgBase64','C1.name','C2.name AS ParentName','CR.parentCategoryId')
    		    ->where('C1.storeType',$id)
                ->count();
    
    
        return $storeTypes;
     
    } 
 
    public static function storeTypeStores($id)
    {
        
        $storeTypes=DB::Table('stores as S')
    		    ->where('S.storeType',$id)
                ->count();
    
    
        return $storeTypes;
     
    } 
    
    
    
    
    public static function addToLog($type,$value='',$extra='')
    {
        $log = [];
        
        // We can use below keywords for message
        // {{user}}         For Firstname and Lastname
        // {{username}}     Email
        
        if(empty($extra)) {
            switch ($type) {
              case "categoryAdd":
                $message = "<b>{{user}}</b> ({{username}}) added a new category of <b>" . $value . "</b>";
                break;
              case "categoryEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated the category details of <b>" . $value . "</b>";
                break;
              case "categoryDelete":
                $message = "<b>{{user}}</b> ({{username}}) delete the category details of <b>" . $value . "</b>";
                break;
                
              case "storeAdd":
                $message = "<b>{{user}}</b> ({{username}}) added the store of <b>" . $value . "</b>";
                break;
              case "storeEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated the store of <b>" . $value . "</b>";
                break;
              case "cashierAdd":
                $message = "<b>{{user}}</b> ({{username}}) added the cashier details of <b>" . $value . "</b>";
                break;
              case "cashierEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated the cashier details of <b>" . $value . "</b>";
                break;  
              case "cashierDelete":
                $message = "<b>{{user}}</b> ({{username}}) delete the cashier details of   <b>" . $value . "</b>";
                break;  
              case "shiftAdd":
                $message = "<b>{{user}}</b> ({{username}}) added a new shift of <b>" . $value . "</b>";
                break;
              case "shiftEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated the existing shift of <b>" . $value . "</b>";
                break; 
              case "productAdd":
                $message = "<b>{{user}}</b> ({{username}}) added a new product of <b>" . $value . "</b>";
                break;
              case "productImportAdd":
                $message = "<b>{{user}}</b> ({{username}}) impoted a new product of <b>" . $value . "</b>";
                break;
              case "productEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated the new product of <b>" . $value . "</b>";
                break;
              case "productDelete":
                $message = "<b>{{user}}</b> ({{username}}) deleted the new product of <b>" . $value . "</b>";
                break;
                  
              case "globalproductsEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated the global product of <b>" . $value . "</b>";
                break;
                
              case "brandAdd":
                $message = "<b>{{user}}</b> ({{username}}) added a new brand of <b>" . $value . "</b>";
                break;
              case "brandEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated the brand name of <b>" . $value . "</b>";
                break;
              case "brandDelete":
                $message = "<b>{{user}}</b> ({{username}}) deleted the brand name of <b>" . $value . "</b>";
                break;
                
              case "storetypeAdd":
                $message = "<b>{{user}}</b> ({{username}}) added a new store type of <b>" . $value . "</b>";
                break;
              case "storetypeEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated the store type of <b>" . $value . "</b>";
                break;
              case "storetypeDelete":
                $message = "<b>{{user}}</b> ({{username}}) delete the store type of <b>" . $value . "</b>";
                break;
                
              case "vatAdd":
                $message = "<b>{{user}}</b> ({{username}}) added the VAT of <b>" . $value . "</b>";
                break;
              case "vatEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated the VAT of <b>" . $value . "</b>";
                break;
              case "vatDelete":
                $message = "<b>{{user}}</b> ({{username}}) delete the VAT of <b>" . $value . "</b>";
                break;
              case "adminmanagementAdd":
                $message = "<b>{{user}}</b> ({{username}}) added new admin user <b>" . $value . "</b>";
                break;
              case "adminmanagementEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated details for admin user <b>" . $value . "</b>";
                break;
              case "subadminAdd":
                $message = "<b>{{user}}</b> ({{username}}) added new subadmin user <b>" . $value . "</b>";
                break;
              case "subadminEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated details for admin user <b>" . $value . "</b>";
                break;
              case "subadminDelete":
                $message = "<b>{{user}}</b> ({{username}}) deleted the subadmin <b>" . $value . "</b>";
                break;
              case "appEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated the App of <b>" . $value . "</b>";
                break;
              case "faqAdd":
                $message = "<b>{{user}}</b> ({{username}}) added the FAQ of <b>" . $value . "</b>";
                break;
              case "faqEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated details for FAQ of <b>" . $value . "</b>";
                break;
              case "faqDelete":
                $message = "<b>{{user}}</b> ({{username}}) deleted the FAQ of <b>" . $value . "</b>";
                break;
              case "storeDisable":
                $message = "<b>{{user}}</b> ({{username}}) disable the STORE of <b>" . $value . "</b>";
                break;
              case "storeEnable":
                $message = "<b>{{user}}</b> ({{username}}) enable the STORE of <b>" . $value . "</b>";
                break;
              case "emptyInventory":
                $message = "<b>{{user}}</b> ({{username}}) empty inventory the STORE of <b>" . $value . "</b>";
                break;
              case "zeroInventory":
                $message = "<b>{{user}}</b> ({{username}}) zero inventory the STORE of <b>" . $value . "</b>";
                break;
              case "invoiceAdd":
                $message = "<b>{{user}}</b> ({{username}}) added the invoice of <b>" . $value . "</b>";
                break;
              case "invoiceEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated details for invoice of <b>" . $value . "</b>";
                break;  
              case "subscriptionAdd":
                $message = "<b>{{user}}</b> ({{username}}) added the subscription of <b>" . $value . "</b>";
                break;
              case "subscriptionEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated details for subscription of <b>" . $value . "</b>";
                break;
              case "subscriptionDelete":
                $message = "<b>{{user}}</b> ({{username}}) deleted the subscription of <b>" . $value . "</b>";
                break;
              case "vendorAdd":
                $message = "<b>{{user}}</b> ({{username}}) added new vendor of <b>" . $value . "</b>";
                break;
              case "vendorEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated details for vendor of <b>" . $value . "</b>";
                break; 
              case "vendorDelete":
                $message = "<b>{{user}}</b> ({{username}}) deleted the vendor of <b>" . $value . "</b>";
                break;
              case "associateAdd":
                $message = "<b>{{user}}</b> ({{username}}) added the associate of <b>" . $value . "</b>";
                break;
              case "associateEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated details for associate of <b>" . $value . "</b>";
                break;
              case "associateDelete":
                $message = "<b>{{user}}</b> ({{username}}) deleted the associate of <b>" . $value . "</b>";
                break;
              case "bannerAdd":
                $message = "<b>{{user}}</b> ({{username}}) added the banner of <b>" . $value . "</b>";
                break;
              case "bannerEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated details for banner of <b>" . $value . "</b>";
                break;
              case "bannerDelete":
                $message = "<b>{{user}}</b> ({{username}}) deleted the banner of <b>" . $value . "</b>";
                break;
              case "customerAdd":
                $message = "<b>{{user}}</b> ({{username}}) added the customer of <b>" . $value . "</b>";
                break;
              case "customerEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated datails for customer of <b>" . $value . "</b>";
                break;
              case "customerDelete":
                $message = "<b>{{user}}</b> ({{username}}) deleted the customer of <b>" . $value . "</b>";
                break;  
              case "customerScreenAdd":
                $message = "<b>{{user}}</b> ({{username}}) added the customerScreen of <b>" . $value . "</b>";
                break; 
              case "customerScreenEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated details for customerScreen of <b>" . $value . "</b>";
                break; 
              case "customerScreenDelete":
                $message = "<b>{{user}}</b> ({{username}}) deleted the customerScreen of <b>" . $value . "</b>";
                break; 
              case "driverAdd":
                $message = "<b>{{user}}</b> ({{username}}) added the driver of <b>" . $value . "</b>";
                break; 
              case "driverEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated deails for driver of <b>" . $value . "</b>";
                break; 
              case "driverDelete":
                $message = "<b>{{user}}</b> ({{username}}) deleted the driver of <b>" . $value . "</b>";
                break; 
              case "inventoryEdit":
                $message = "<b>{{user}}</b> ({{username}}) updated details for Stock of Inventory";
                break;
              
              
                
              default:
                $message = "Activity happened in portal";
            }
        }
    	else {
    	    $message = $extra;
    	}
    	
    	$user = Auth()->user()->firstName . ' ' . Auth()->user()->lastName;
    	$username = Auth()->user()->email;
    	
    	$message = str_replace("{{user}}",$user,$message);
    	$message = str_replace("{{username}}",$username,$message);
    	
    	$log['subject'] = $message;
    	$log['userId'] = Auth()->user()->id;
    	
    	LogActivity::create($log);
    }
 
    public static function checkUserRights($section,$sectionActivity='')
    {
        $userRights = session('userRights');
        $userRights = explode(',', $userRights);
        
        if(in_array($section, $userRights)) {
                
                if(empty($sectionActivity)) {
                    return true;
                }
                
                if(in_array($sectionActivity, $userRights)) {
                    return true;
                }
        }
        
        return false;
    }
    
    public static function checkUserURLAccess($section,$sectionActivity='')
    {
		// Temp Disable
		return true;
		
        // Role Id:: Auth()->user()->roleId
        
        // Check if the user has access to the store 
        
        $userRights = session('userRights');
        $userRights = explode(',', $userRights);
        
        if(in_array($section, $userRights)) {
            if(empty($sectionActivity)) {
                return true;
            }
            
            if(in_array($sectionActivity, $userRights)) {
                return true;
            }
        }
        
        echo '<div class="card">
			<div class="card-body">
				You are not an authorised user.
			</div>
		</div>';
		die;
    }
	
}