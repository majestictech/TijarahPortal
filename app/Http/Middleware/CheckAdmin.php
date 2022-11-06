<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Support\Facades\Redirect;
use App\MasRole;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		
       if(!Auth::user()){
			return Redirect::to('/admin/login');
		} 
		
		// Commented as sidebar is loading before this function is called
		/*
		$userRoles = MasRole::select('id','name','userRights')->where ('id', Auth::user()->roleId )->first();
		
		session(['userRights' => $userRoles->userRights]);
		*/
		
		//if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2){
		if(Auth::user()->roleId == 1 || Auth::user()->roleId == 2 || Auth::user()->roleId == 3 || Auth::user()->roleId == 4 || Auth::user()->roleId == 5){
			
			
			return $next($request);
		}	
		
		
		return Redirect::to('/');
		//return redirect()->route('login')->with('error','You have not admin access');
		
    }
	
		
}
