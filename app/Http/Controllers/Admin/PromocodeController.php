<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\AppHelper as Helper;
use DB;
use App\Promocode;
use App\Product;
use App\PromoType;

class PromocodeController extends Controller
{
    public function index()
    {		

		$promocodeData = DB::Table('promocodes as P')->leftJoin('mas_promotype', 'mas_promotype.id', '=', 'P.promocodeType')->leftJoin('products', 'products.id', '=', 'P.productsIds')
		->select('P.id','P.promoName','P.offAmount','P.promoFrom','P.promoTo','P.voucherCode','mas_promotype.promoType','products.name' )
		->orderBy('P.id', 'DESC')->get();
		
		return view('admin.promocode.index',compact('promocodeData'));
    }
	
	public function create()
    {
		$categoryList = helper::allCategories("category");
		
		$promotypedata = PromoType::orderBy('id', 'DESC')->get();
		$productdata = Product::orderBy('id', 'DESC')->get();
		
		return view('admin.promocode.create',compact('categoryList', 'promotypedata','productdata'));
		
    }
	
	public function store(Request $request)
    {  
		$promocode = new Promocode;
		
		$promocode->promoName = $request->promoName;
		$promocode->offAmount = $request->offAmount;
		$promocode->voucherCode = $request->voucherCode;
		$promocode->promoTo = $request->promoTo;
		$promocode->promoFrom = $request->promoFrom;
		$promocode->promocodeType = $request->promocodeType;
		
		$products = $request->input('productsIds');
		$products = implode(',', $products);
		
		$promocode->productsIds = $products;
        
		//print_r($products);
		//die;
		
        //Promocode::create($promocode);
        //return redirect()->back();
        $promocode->save(); 
 
		
        return redirect('admin/promocode');             
    }
	
	public function destroy($id)
    {
		
        $promocodeData = Promocode::find($id);
        $promocodeData->delete();
		
		return redirect('admin/promocode');  
		
    }	
	
	public function edit($id)
    {
		$promodata = PromoType::orderBy('id', 'DESC')->get();
		$promocodeData = DB::Table('promocodes as P')
		->select('P.id','P.promoName','P.offAmount','P.promoTo','P.promoFrom','P.voucherCode','P.promocodeType','P.productsIds' )
		->orderBy('P.id', 'DESC')->get();
		
		$promocodeData = $promocodeData[0];
		$productdata = Product::orderBy('id', 'DESC')->get();

		return view('admin.promocode.edit',compact('promocodeData','promodata','productdata'));
    }
	
	public function update(Request $request)
    {
        $promocode = new Promocode;
		$promocode = Promocode::find($request->input('id'));
		
		$promocode->promoName = $request->promoName;
		$promocode->offAmount = $request->offAmount;
		$promocode->promoTo = $request->promoTo;
		$promocode->promoFrom = $request->promoFrom;
		$promocode->voucherCode = $request->voucherCode;
		$promocode->promocodeType = $request->promocodeType;
		$products = $request->input('productsIds');
		$products = implode(',', $products);
		
		$promocode->productsIds = $products;
        
        $promocode->save();
 
        return redirect('admin/promocode');  
    }


}
