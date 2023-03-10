<?php

namespace App\Imports;

use App\Product;
use App\Brand;
use App\Category;
use App\Product_AR;
use App\ProductInventoryBatch;
use DB;
use App\Helpers\AppHelper as Helper;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImport implements ToCollection, WithHeadingRow
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
        //print_r($row);
        //print_r($row['name']);
        
		$storeId = $_REQUEST['storeId'];
		
		//echo $storeId;
		//die;
		foreach ($rows as $row) 
        {
			/*
			$productName = $row['product_name_english'] ?? $row['product_name'] ?? $row['name'] ?? null;
			
			//if(!preg_match('/[^a-z_\-0-9]/i', $productName))
			if(!preg_match('/[^[:alnum:]\-_]/', $productName) ) 
			{
				echo "not valid string:: " .$productName;
				die;
			}
			*/
		
			/* echo $storeId;
		die;
			 */
            
    		//$b64image = '';
    		$categoryId = 0;
    		$taxId = 0;
    		//$brandId = 0;
    		$weightId = 0;
    		
    		
    		/* if(!empty($row['product_image']))
    		    $b64image = base64_encode(file_get_contents($row['product_image']));
    		     */
    		/*print_r($row['product_image']);
    		print_r($b64image);
    		die;*/
    	
    
    		if(!empty($row['category'])) {
				//echo "<br>Cat Is::adasdw ".$row['category']."<br>";
    		    $category = DB::Table('categories as C')->select('C.id','C.name')
    		    ->leftJoin('mas_storetype as MST', 'MST.id', '=', 'C.storeType')
    		    ->leftJoin('stores as S','S.storeType','=','MST.id')
				->where('C.name',$row['category'])
    		    ->where('typeadmin','=','pos')
    		    ->where('S.id','=',$storeId)
    		    ->get();
    			//print_r($category);
    			if(isset($category)){
					//print_r($category);
					//print_r("category output");
					//echo "<br>";
					//die;
					if(!empty($category))
						$categoryId = $category[0]->id;
				}
    		}
			
			
			if(!empty($row['categoryId'])) {
				$categoryId = $row['categoryId'];
			}
			
			
			//print_r($row['category']);
			//print_r($categoryId);
    		//die;
    		if(!empty($row['tax_class'])) {
    		    $tax = DB::Table('mas_taxclass')->select('id')->where('value','=',$row['tax_class'])->get();
        		/*echo "<br>Tax Is:: ".$row['tax_class']."<br>";
        		print_r($tax);*/
        		
        		if(!empty($tax))
        		    $taxId = $tax[0]->id;
    		}
    		
			/*
    		if(!empty($row['brand'])) {
    		    $brand = DB::Table('brands')->select('id')->where('brandName','=',$row['brand'])->get();
        		echo "<br>Brand Is:: ".$row['brand']."<br>";
        		print_r($brand);
        		echo "<br>";
        		if(!empty($brand))
        		    $brandId = $brand[0]->id;
    		}
    		*/

			// Commenting as it is not in use on app right now
			/*
    		if(!empty($row['weight_class'])) {
    		    $weight = DB::Table('mas_weightclass AS MW')->select('MW.id')->where('MW.name','=',$row['weight_class'])->get();
    			if(!empty($weight))
    			    $weightId = $weight[0]->id;
    		}
			*/
    	
    		$price =  $row['selling_price']/(1+ $row['tax_class'] /100);
			

            //$product = new Product;
            //return Product::where('name',$product->name)->get()(['name', 'barCode' ]);
            
            
            $productName = $row['product_name_english'] ?? $row['product_name'] ?? $row['name'] ?? null;
			$productName = trim($productName);
			$productName = str_replace("`","", $productName);
			
			//$productName = substr($productName, 0, 100);
           
			//$productName = utf8_encode($productName);
			//$productName = addslashes($productName); // Error:- Undefined offset: 0
			//$productName = stripslashes($productName);
			//$productName = utf8_decode($productName);
			
            $barCode = str_replace("#","",$row['barcode']);
			$barCode = str_replace("'","", $barCode);

			
            
            $product = Product::select('id')->where('storeId',$storeId)
		    ->where('name',$productName);
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
	        
	        $productInventory = false;
            if(!$product)
            {
				/* print_r("Product not found");
				die; */
                $product = new Product;
                $product_ar = new Product_AR;
				$productInventory =true;
            }
            else
            {
                $product_ar = Product_AR::select('id')->where('productID', $product->id)->first();
            }
            
			
			$updateInventoryBatch = $row['update_inventory_batch'] ?? 0;
			$inventoryStock = $row['inventory'] ?? 0;


            $product->name = $productName;
			 
    		$product->code = $row['product_code'] ?? $row['code'] ?? null;
    		$product->barCode = $barCode;

			/* Box Barcode and Box Pieces Start */
			$product->boxBarCode =  $row['box_barcode'] ?? $row['boxBarcode'] ?? null;
			$product->piecesPerBox =  $row['pieces_per_box'] ?? $row['piecesPerBox'] ?? '0';
			/* Box Barcode and Box Pieces End */
			
    		$product->categoryId = $categoryId;
    		//$product->brandId = $brandId;
			
			
    		$product->status = $row['status'];
    		$product->storeId = $storeId;
    		$product->sellingPrice = $row['selling_price'];
    		$product->price = $price;
    		$product->costPrice = $row['cost_price'];
    		$product->splPrice = $row['special_price'];
    		$product->splPriceFrom = $row['splpricefrom'] ?? $row['spl_price_from'] ?? null;
    	    $product->splPriceTo = $row['splpriceto'] ?? $row['spl_price_to'] ?? null;
    		$product->taxClassId = $taxId;
    		
			if($updateInventoryBatch)
				$product->inventory = $inventoryStock;
			
			
			if(isset($row['inventory_data']))
				$product->inventoryData = $row['inventory_data'];
			else
				$product->inventoryData = 0;
    		$product->minInventory = $row['min_inventory'];
    		$product->weight = $row['weight'];
    		$product->weightClassId = $weightId; 

    		//$product->minOrderQty = $row['min_order_qty'];
    		//$product->productImage = $row['product_image'];
    		//$product->productImgBase64 = $b64image;
    		
    		/*  if(!empty($row['product_image'])) {
    		    $product->productImage = $row['product_image'];
    		    $product->productImgBase64 = $b64image;
    		}
 */
    		
    		$product->description = $row['description'];
    		$product->productTags = $row['producttags'];
    		$product->metaTitle = $row['metatitle'];
    		$product->metaDescription = $row['metadescription']  ?? ' ';
    		$product->metaKeyword = $row['metakeyword'] ?? ' ';

          	$product->save();
            
			try {

				if(!empty($row['product_name_arabic']) || !empty($row['name_arabic'])) {
					$product_ar->productId = $product->id;
					$product_ar->name = $row['product_name_arabic'] ?? $row['name_arabic'] ?? $row['product_arabic'] ?? null;
					$product_ar->save();
				}
			
			} catch (\Exception $e) {
			
				continue;
			}
			
			$inventoryFromExcel = $row['inventory'] ?? '0';
			
			$productInventoryBatchCheck = ProductInventoryBatch::select('id')->where('productId', $product->id)->get();
			
			// Insert Inventory Batches Starts
			if(count($productInventoryBatchCheck) == 0) {
				// This means it's a new product so we need to create a new Inventory batch.				
				$productInventoryBatch = new ProductInventoryBatch;

				$productInventoryBatch->productId = $product->id;
				
				// Updating Inventory value in Products table as this is a new product and will not depend on updateInventoryBatch value
				//$productUpdate = Product::find($product->id);
				$productUpdate = Product::select('id')->where('id', $product->id)->first();
				$productUpdate->inventory = $inventoryStock;
				$productUpdate->save();
			}
			
			//echo count($productInventoryBatchCheck);
			
			//print_r($productInventoryBatchCheck);
			//echo empty($productInventoryBatchCheck);
			
			
			//echo $row['product_id'] . "<br>";
			//echo $updateInventoryBatch;
			//die;
			if(count($productInventoryBatchCheck) == 0 || $updateInventoryBatch) {
				
				if(count($productInventoryBatchCheck) > 0)
					$productInventoryBatch = ProductInventoryBatch::select('id')->where('productId', $product->id)->first();

				$productInventoryBatch->inventory = $inventoryFromExcel;
					
				$productExpiryDate = $row['expiry'] ?? '2099-01-01';
				$productExpiryDate = date_create($productExpiryDate);

				$productInventoryBatch->costPrice = $row['cost_price'] ?? '0.0';
				$productInventoryBatch->expiryDate = $productExpiryDate;

				$productInventoryBatch->save();
			}			
			//die;
        }
        
       
        
        /*
        return new Product_AR([
			'productId' => '10',
			'name' => 'test',
			'description' => 'desc'
        ]);*/
        
    }
}




