<?php

class UserController extends BaseController {

	public function signup()
	{
        if (Request::isMethod('post'))
        {
            $validation = Validator::make(
                Input::all(),
                array(
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:6|confirmed',
                    'password_confirmation' => 'required|min:6',
                )
            );
            if ($validation->fails())
            {
                return Redirect::route('signup')->withErrors($validation);
            }
            $user = new User();
            $user->email = Input::get('email');
            $user->password = Hash::make(Input::get('password'));
            $user->save();
            return Redirect::route('signin')
                ->with('success', 'Now you can sign in.');
        }
		return View::make('user/signup');
	}

	public function signin()
	{
        if (Request::isMethod('post'))
        {
            $validation = Validator::make(
                Input::all(),
                array(
                    'email' => 'required|email',
                    'password' => 'required',
                )
            );
            if ($validation->fails())
            {
                return Redirect::route('signin')->withErrors($validation);
            }
            $authParams = array(
                'email' => Input::get('email'),
                'password' => Input::get('password')
            );
            if (Auth::attempt($authParams))
            {
                return Redirect::route('all_notes')
                    ->with('success', 'Signed in now!');
            }
        }
		return View::make('user/signin');
	}

    public function forgotPassword()
    {
        // @TODO
    }

    public function settings()
    {
        $user = Auth::user();
        if (Request::isMethod('post'))
        {
            $validation = Validator::make(
                Input::all(),
                array(
                    'old_password' => 'required',
                    'password' => 'required|min:6|confirmed',
                    'password_confirmation' => 'required|min:6',
                )
            );
            if ($validation->fails())
            {
                return Redirect::route('settings')->withErrors($validation);
            }
            $authParams = array(
                'email' => $user->email,
                'password' => Input::get('old_password')
            );
            if (Auth::validate($authParams))
            {
                $user->password = Hash::make(Input::get('password'));
                return Redirect::route('settings')
                    ->with('success', 'Password is successfully changed');
            } else {
                return Redirect::route('settings')
                    ->with('error', 'Current password is incorrect');
            }
        }
		return View::make('user/settings');
    }
}
