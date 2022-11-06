<?php

namespace App\Exports;

use App\Reports;
use App\Store;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport extends DefaultValueBinder implements FromCollection, WithHeadings, ShouldAutoSize, WithCustomValueBinder, WithStyles
{
    
    public function bindValue(Cell $cell, $value)
    {
        if($cell->getColumn() == 'E'){

            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_STRING);
    
                return true;
            }
            
        }
        
        
        return parent::bindValue($cell, $value);
    }
    
        public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],

        ];
    }
    

    
    
    public function collection()
    {
        $storeId = $_REQUEST['storeId'];
        $startDate = $_REQUEST['start_date'];
        $endDate = $_REQUEST['end_date'];
  
        
         return DB::Table('reports as R')
         ->leftJoin('stores as S','S.id','R.storeId')
         ->leftJoin('orders_pos as O','O.orderId','R.orderNumber')
         ->select(DB::raw("CONCAT('TIJ',R.storeId) as storeId") ,'S.storeName',DB::raw('DATE_FORMAT(R.created_at, "%d-%m-%Y %h:%i %p")'),'R.orderNumber',DB::raw("'' as hsncode"),'R.productName','R.barCode',DB::raw("'' as brand"),'R.quantity','R.price','R.discPer','R.total',DB::raw("'' as adddis"),'O.totalAmount',DB::raw("'Miscellaneous' as categoryName"),DB::raw(" '0' as stock"),DB::raw("'PCS' as unitWeight") )->where('R.storeId',$storeId)->whereBetween('R.created_at',[$startDate,$endDate])->orderBy('R.created_at', 'ASC')->get();
    }
    
    public function headings(): array
    {
        return [
            'SHOP NO',
            'SHOP NAME',
            'DATE',
            'BILL NO',
            'HSN CODE',
            'PRODUCT NAME',
            'BARCODE',
            'BRAND',
            'QTY',
            'RATE',
            'DISCOUNT%',
            'TOTAL VALUE',
            'ADDITIONAL DISCOUNT',
            'TRANSACTION TOTAL SALES',
            'ITEM CATEGORY',
            'STOCK',
            'UNIT WEIGHT',
        ];
    }

    
}
