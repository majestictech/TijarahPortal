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

class ProfitLossReportExport extends DefaultValueBinder implements FromCollection, WithHeadings, ShouldAutoSize, WithCustomValueBinder, WithStyles
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
    function __construct($storeId) {
        $this->id = $storeId;
    }
    

    
    
    public function collection()
    {
        /* $startDate = $_REQUEST['start_date'] ?? '';
        $endDate = $_REQUEST['end_date'] ?? '';
        $search = $_REQUEST['search'] ?? ''; */
        
        
        // return $startDate;
         /*
         return DB::Table('orders_pos as O')
         ->leftJoin('stores as S','S.id','O.storeId')
         //->leftJoin('orders_pos as O','O.orderId','R.orderNumber')
         ->select('O.orderId','O.created_at','O.totalAmount','S.storeName')
         ->whereBetween('O.created_at',[$startDate,$endDate])->orderBy('O.created_at', 'ASC')->get();*/
         
        
       /*  $orders = DB::Table('orders_pos as O')->leftJoin('stores as S','S.id','=','O.storeId')
        ->select('O.id','O.orderId','O.created_at','O.totalAmount','S.storeName as storeName');
        
        
        if(!empty($startDate) && !empty($endDate)) {
            $startDate = $startDate . ' 00:00:00';
            $endDate = $endDate . ' 23:59:59';
            
            $orders = $orders->whereBetween('O.created_at',[$startDate,$endDate]);
            
            
        }
        
        if(isset($search)) {
            $orders = $orders->where ('S.storeName', 'LIKE', '%' . $search . '%' );
        }
        
        $orders = $orders->orderBy('O.created_at', 'DESC')->get();
         */
       
        $storeId = $this->id;
       
         if(isset($_GET['search']))
			$search = $_GET['search'];
		else
			$search = '';

		if(isset($_GET['start']))
			$startDate = $_GET['start'];
		else
			$startDate = Carbon::now()->toDateString();
		
		if(isset($_GET['end']))
			$endDate = $_GET['end'];
		else
			$endDate = Carbon::now()->toDateString();


		$results = DB::table('reports')
		->select('productName','costPrice', 'price', DB::raw('SUM(quantity) as qty'), DB::raw('(price - costPrice) as margin'),DB::raw('ROUND((((price - costPrice)/costPrice) * 100),2) as percentprofit'), DB::raw('((price - costPrice) * (SUM(quantity))) as totalMargin'))
		->where('storeId', $storeId);

		if(!empty($search)) {
			$results = $results->where('productName','LIKE', '%' . $search . '%');
		}
		if(!empty($startDate) && !empty($endDate)) {
			$results = $results->whereBetween(DB::raw('Date(created_at)'),[$startDate,$endDate]);
		}

		$results = $results->groupBy('productName')->orderBy('qty','DESC')->paginate(10);


        return $results;
    }
    
    public function headings(): array
    {
        return [
            'Name',
            'C.P (SAR)',
            'S.P (SAR)',
            'Qty.',
            'MARGIN (SAR)',
            'Percentage',
            'TOTAL MARGIN (SAR)'
          /*   'Order No',
            'Placed On',
            'Payment',
            'Store Name', */
        ];
    }

    
}
