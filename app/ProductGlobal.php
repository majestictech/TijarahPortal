<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class ProductGlobal extends Model
{
    protected $table = 'products_global';
	
	protected $fillable = [
        'name', 'code', 'barCode', 'categoryId','brandId', 'status','storeId', 'sellingPrice', 'price', 'costPrice','splPrice', 'splPriceFrom', 'splPriceTo','taxClassId', 'inventory', 'minInventory', 'weight', 'weightClassId', 'minOrderQty', 'productImage', 'productImgBase64', 'description', 'productVariation',        'productTags', 'metaTitle', 'metaDescription', 'metaKeyword'];
    

   /* protected $fillable = [
        'name'
    ];*/
    
    
    public static function getAllProduct()
    {
       $products = DB::Table('products_global as P')
		->select('P.id','P.name','PAR.name as name_ar','P.barCode','P.categoryId','P.price','P.productImage','P.productImgBase64','P.inventory','P.minInventory','MTC.value as tax','P.storeId')
		->leftJoin('products_ar as PAR','PAR.productId','=','P.id')
		->where('P.storeId','=',  '71')
		->orderBy('P.updated_at', 'DESC')->get();
    }
}
