<?php

namespace App\Imports;

use App\ProductGlobal;
use App\Brand;
use App\Category;
use App\Product_AR_Global;
use DB;
use App\Helpers\AppHelper as Helper;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImportGlobal implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    /*public function map($row): array
    {
        return array_map('trim', $row['barcode']);
    }*/
    
    
    public function collection(Collection $rows)
    {
        //echo "sdfsff";
        //echo "<br><br>";
        //print_r($rows);
        //print_r($rows['loose_item']);
        
		//echo $storeId;
		//die;
		foreach ($rows as $row) 
        {
            print_r($row);
    		//die;
    		$b64image = '';
    		$categoryId = 0;
    		$taxId = 0;
    		$brandId = 0;
    		$weightId = 0;
    		
    		
    		if(!empty($row['product_image']))
    		    $b64image = base64_encode(file_get_contents($row['product_image']));
    		    
    		/*print_r($row['product_image']);
    		print_r($b64image);
    		die;*/
    
    		if(!empty($row['category'])) {
    		    $category = DB::Table('categories as C')->select('C.id')->where('C.name','=',$row['category'])
    		    ->leftJoin('mas_storetype as MST', 'MST.id', '=', 'C.storeType')
    		    ->leftJoin('stores as S','S.storeType','=','MST.id')
    		    ->where('typeadmin','=','pos')
    		    ->get();
    			echo "<br>Cat Is:: ".$row['category']."<br>";
        		print_r($category);
        		echo "<br>";
    			//die;
    			if(!empty($category))
    			    $categoryId = $category[0]->id;
    		}
    		
    		if(!empty($row['tax_class'])) {
    		    $tax = DB::Table('mas_taxclass')->select('id')->where('value','=',$row['tax_class'])->get();
        		echo "<br>Tax Is:: ".$row['tax_class']."<br>";
        		print_r($tax);
        		
        		if(!empty($tax))
        		    $taxId = $tax[0]->id;
    		}
    		
    		if(!empty($row['brand'])) {
    		    $brand = DB::Table('brands')->select('id')->where('brandName','=',$row['brand'])->get();
        		echo "<br>Brand Is:: ".$row['brand']."<br>";
        		print_r($brand);
        		echo "<br>";
        		if(!empty($brand))
        		    $brandId = $brand[0]->id;
    		}
    		
    		if(!empty($row['weight_class'])) {
    		    $weight = DB::Table('mas_weightclass AS MW')->select('MW.id')->where('MW.name','=',$row['weight_class'])->get();
    			if(!empty($weight))
    			    $weightId = $weight[0]->id;
    		}
    		
    		 if(!empty($row['store_type'])) {
    		    $storetypename = DB::Table('mas_storetype')->select('id')->where('name','=',$row['store_type'])->get();
        		echo "<br>Store Is:: ".$row['store_type']."<br>";
        		print_r($storetypename);
        		echo "<br>";
        		if(!empty($storetypename))
        		    $storeType = $storetypename[0]->id;
    		}
    		
    		
    		
    		$price =  $row['selling_price']/(1+ $row['tax_class'] /100);
            //$product = new Product;
            //return Product::where('name',$product->name)->get()(['name', 'barCode' ]);
            
            
            $productName = $row['product_name_english'] ?? $row['product_name'] ?? $row['name'] ?? null;
            $barCode = str_replace("#","",$row['barcode']);
            
            $product = ProductGlobal::where( 'name',$productName);
		    //print_r($product);
		    
		    if(!empty($barCode))
	            $product = $product->where( 'barCode',$barCode);
	        
	        
	        $product = $product->first();
	        
	        /*
	        if($product) {
    	        echo "Product Found<br>";
    	        
    	        print_r($product);
    	        
    	        echo "<br><br>ID:: " .$product->id;
    	        
    	        //$product = Product::find($product->id);
    	        
    	        //echo "<br><br>Product Found<br>";
    	        
    	        //print_r($product);
    	        
    	        $product->sellingPrice = '50';
    	        
    	        $product->save();
	        }
	        else {
	            
	            echo "Not Found. New Product";
	        }
	        die;
	        */
	        
	       
	        
	        
            if(!$product)
            {
                $product = new ProductGlobal;
                $product_ar = new Product_AR_Global;
            }
            else
            {
                $product_ar = Product_AR_Global::select('id')->where('productID', $product->id)->first();
            }
            
            $product->name = $productName;
    		$product->code = $row['product_code'] ?? $row['code'] ?? null;
    		$product->barCode = $barCode;
    		$product->categoryId = $categoryId;
    		$product->brandId = $brandId;
    		$product->status = $row['status'];
    		$product->sellingPrice = $row['selling_price'];
    		$product->price = $price;
    		$product->costPrice = $row['cost_price'];
    		$product->splPrice = $row['spl_price'];
    		$product->splPriceFrom = $row['splpricefrom'] ?? $row['spl_price_from'] ?? null;
    	    $product->splPriceTo = $row['splpriceto'] ?? $row['spl_price_to'] ?? null;
    		$product->taxClassId = $taxId;
    		$product->inventory = $row['inventory'];
    		//$product->minInventory = $row['min_inventory'];
    		$product->weight = $row['weight'];
    		$product->weightClassId = $weightId;
    		//$product->minOrderQty = $row['min_order_qty'];
    		$product->productImage = $row['product_image'];
    		$product->productImgBase64 = $b64image;
    		
    		/*if(!empty($row['product_image'])) 
    		    $product->productImage = $row['product_image'];
    		if(!empty($b64image)) 
    		    $product->productImgBase64 = $b64image;*/
    		$product->description = $row['description'];
    		$product->productTags = $row['producttags'];
    		$product->metaTitle = $row['metatitle'];
    		$product->metaDescription = $row['metadescription'];
    		$product->metaKeyword = $row['metakeyword'];
    		$product->storeType = $storeType;
            $product->looseItem = $row['loose_item'];
            $product->save();
                
            
            $product_ar->productId = $product->id;
        	$product_ar->name = $row['product_name_arabic'] ?? $row['name_arabic'] ?? $row['product_arabic'] ?? null;
        	$product_ar->save();
        }
        
        
        
        /*
        return new Product_AR([
			'productId' => '10',
			'name' => 'test',
			'description' => 'desc'
        ]);*/
        
    }
}

