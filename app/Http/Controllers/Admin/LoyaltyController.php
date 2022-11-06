<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LoyaltyHistory;
use Auth;
use App\LoyaltyTransaction;
use DB;

class LoyaltyController extends Controller
{   
    public function index(Request $request)
    {
	   //$loyaltyHistory = LoyaltyHistory::orderBy('id', 'DESC')->get();
	   $loyaltyHistory = DB::Table('loyaltytransactions as LT')->leftJoin('customers as C', 'C.id', '=', 'LT.customerId')->leftJoin('stores as S', 'S.id', '=', 'LT.storeId')
       ->select('LT.points','S.storeName','LT.orderId','C.customerName','LT.type','LT.created_at AS date')->orderBy('LT.id', 'DESC')->limit(1)->get();
       $loyaltycount=count($loyaltyHistory);
	   
       return view('admin.loyaltyhistory.index', compact('loyaltyHistory','loyaltycount'));
    }
}
