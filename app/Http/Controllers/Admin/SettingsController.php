<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\UserRole;
use App\User;
use Illuminate\Support\Facades\Hash;


class SettingsController extends Controller
{
    public function index()
    {		
		
		$user = auth()->user();
		return view('admin.settings.index',compact('user'));
    }
	
	
	public function edit($id)
    {
		

		return view('admin.settings.edit');
    }
	
	public function update(Request $request)
    {
		//$user =auth()->user();
		if(!auth()->user()->password)
		$this->validate($request, [	'password' => 'min:6|:password_confirmation|same:password_confirmation',
		'password_confirmation' => 'min:6'
     	]);
        auth()->user()->firstName = $request['firstName'];
        auth()->user()->lastName = $request['lastName'];
        auth()->user()->email = $request['email'];
        auth()->user()->contactNumber = $request['contactNumber'];
        
		if(!empty($request['password']))
			auth()->user()->password = Hash::make('password');
        auth()->user()->save();
		return redirect('admin'); 
		//return view('admin.settings.index',compact('user'));
    }

	
}
