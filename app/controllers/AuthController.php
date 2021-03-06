<?php

class AuthController extends BaseController {


	public function getLogin()
	{
		return View::make('admin.login', array('showLogin'=>true));
	}

	public function postLogin()
	{

		$input = Input::all();

		$rules = array('username' => 'required', 'password' => 'required');

		$v = Validator::make($input, $rules);

		if($v->passes())
		{
			$credentials = array('username' => Input::get('username'), 'password' => Input::get('password'));

			if(Auth::attempt($credentials)){	
				Session::put('debugbar', true);	
				return Redirect::intended('/admin');
				// return Redirect::back();
			} else {
				return Redirect::back()->withInput()->withErrors($v);
			}
		}
		
		return Redirect::to('login')->withErrors($v);
	}

	public function logout()
	{
		Session::put('debugbar', false);			
		Auth::logout();
		return Redirect::to('/');
	}

}
