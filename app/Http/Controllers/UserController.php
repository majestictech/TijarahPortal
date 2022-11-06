<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\User;
use Auth;
use DB;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function cors(){
// Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
}
    
    public function customerdisplay(Request $request)
    {
        $this->cors();
        $storeId = $request->storeId;
        $results = DB::Table('storeSlider')
		->select('storeSlider.id','storeSlider.image','storeSlider.storeId')
		//->where('storeId',$storeId)
		->orderBy('storeSlider.id', 'DESC')->get();
		//Session::flush();
		return view('customerdisplay',compact('results','storeId'));
    }
    
    public function sliderimages(Request $request)
    {
        $this->cors();
        $storeId = $request->storeId;
        $results = DB::Table('storeSlider')
		->select('storeSlider.id','storeSlider.image','storeSlider.storeId')
		->where('storeId',$storeId)
		->orderBy('storeSlider.id', 'DESC')->get();
		//Session::flush();
		
		return view('slider',compact('results','storeId'));
    }
    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }    
	
     public function login()
    {
         //return view('admin.user.signIn');
		 return view('admin.authenticate.login');
    }
    	
    public function userlogin() {
        $input = request()->all();
        $attempt = Auth::attempt( array('email' => $input['email'], 'password' => $input['password']) );
        
        if($attempt) {           
            return Redirect::to('admin');        
        
        } else {            
            return Redirect::to('/admin/login');     
        }
    } 
    
    public function logout() {            
       Auth::logout();
       return Redirect::to('/admin/login');
    }  
   

}
