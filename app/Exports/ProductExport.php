<?php

namespace App\Exports;

use App\Product;
use App\Product_AR;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use DB;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class ProductExport extends DefaultValueBinder implements FromCollection, WithHeadings, ShouldAutoSize, WithCustomValueBinder
{
    public function bindValue(Cell $cell, $value)
    {
        if($cell->getColumn() == 'E'){
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_STRING);
    
                return true;
            }
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
    protected $storeId;

    public function collection()
    {

        $storeId = $_REQUEST['storeId'];
        
        //echo "storeId:: " . $storeId;
        //return Product::where('products.storeId','=',$storeId)->get();
        
        
        return DB::Table('products as P')
        ->leftJoin('products_ar as PAR','PAR.productId','=','P.id')
		->leftJoin('brands as B','B.id','=','P.brandId')
		->leftJoin('categories as C','C.id','=','P.categoryId')
		->leftJoin('mas_taxclass as MTC','MTC.id','=','P.taxClassId')
		->leftJoin('mas_weightclass as MWC','MWC.id','=','P.weightClassId')
		->select('P.id as id','P.name as name_en','PAR.name as name_ar', 'B.brandName', 'P.code', 'P.barCode', 'P.sellingPrice', 'P.price', 'P.costPrice', 'C.name', 'P.weight', 'MWC.name as weight_class', 'MTC.value', 'P.status', /* 'P.storeId', */ 'P.splPrice', 'P.splPriceFrom', 'P.splPriceTo', 'P.inventory', /* 'P.inventoryData', */ 'P.minInventory', 'P.minOrderQty', 'P.productImage', 'P.description', 'P.productTags', 'P.metaTitle', 'P.metaDescription', 'P.metaKeyword', 'P.boxBarCode', 'P.piecesPerBox', DB::raw('CONCAT("2099-01-01") as Expiry'), DB::raw('CONCAT("0") as UpdateInvBatch'))
		->where('P.storeId','=',  $storeId)
		->get();
		
       /* $product_ar=Product_AR::all();
       $product=Product::where('products.storeId','=',$storeId)->get();
       
       return[
           '$product_ar',
           '$product'
           ];*/
        //return collect(Product::getAllProduct());
       
       
    }
    
    
    public function headings(): array
    {
        return [
            'Product Id',
            'Product Name - English',
            'Product Name - Arabic',
            'Brand',
          /*   'Product Code', */
            'Code',
            'Barcode',
            'Selling Price',
            'Price',
            'Cost Price',
           /*  'Quantity', */
            'Category',
            'Weight',
            'Weight Class',
            'Tax Class',
            'Status',
           /*  'Store', */
            'Special Price',
            'splPriceFrom',
            'splPriceTo',
            'Inventory',
           /*  'Inventory Data', */
            'Min Inventory',
            'Min Order Qty',
            'Product Image',
            'description',
            'productTags',
            'metaTitle',
            'metaDescription',
            'metaKeyword',
            'box barcode',
            'pieces per box',
            'expiry',
			'Update Inventory Batch'
        ];
    }


    
}
