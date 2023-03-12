<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class AuthenticateController extends Controller
{
    
    /*public function login(Request $req)
    {
             $credentials = [
              'email'     => Request::input('email'),
              'password'  => Request::input('password')    
            ];
            //$attempt = Auth::attempt( array('email' => $input['email'], 'password' => $input['password']) );
            if(Auth::attempt($credentials)) {           
           return view('admin.authenticate.login');        
        
            } 
            else {            
                return Redirect::back()->withErrors(['msg', 'The Message']);;     
            }
                   
        
        
    }*/
    public function login(Request $req)
    {
        return view('admin.authenticate.login');        
    }
    
    public function userlogin() 
    {
        $input = request()->all();
        $attempt = Auth::attempt( array('email' => $input['email'], 'password' => $input['password']) );
        
        if($attempt) {           
            return Redirect::to('/admin');        
        
        } else {            
            return Redirect::to('/admin/login');     
        }
    } 
    
    
    

	public function register()
    {      
	  return view('admin.authenticate.register');
    }

	public function recoverPassword()
    {      
	  //return view('admin.authenticate.passwords.reset');
	  return view('admin.authenticate.recoverPassword');
    }

    public function confirmMail()
    {      
      return view('admin.authenticate.passwords.email');
    }
    
	public function logout() { 
		
       Auth::logout();
       return Redirect::to('admin/login');
    } 
	
	public function resetpass() {
       return view('admin.authenticate.passwords.email');
    } 
   
	
} 
