<?php

class ApiAuthController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return View::make('login');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postLogin()
	{
		$data = array(
        'email'    => Input::get('email'),
        'password' => Input::get('password'),
        'confirmed' => 1
      );

    $rules = array('email' => 'required|exists:users,email',
                    'password' => 'required|min:6');

    $validator = Validator::make($data, $rules);

    if ($validator->passes()) {
      $user = User::where('email', $data['email'])->get();
      if ($user = Auth::loginUsingId($user[0]->id)) {
        return Response::json(array('user' => Auth::user(),
                                    'messages' => 'Ingreso correctamente',
                                    'status' => 1));
      }else{
        Input::flash();
        return Response::json(array('messages' => 'Su correo o contraseña son incorrectos',
                                    'status' => 0));
      }
    }else{
      Input::flash();
      return Response::json(array('messages' => $validator->messages(),
                                  'status' => 0));
    }
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getLogout()
	{
		if (Auth::check()){
      Auth::logout();
      return Response::json(array('messages' => 'Ha cerrado sesión',
                                  'status' => 1));
    }

    return Response::json(array('messages' => 'No se ha podido cerrar la sesión',
                                'status' => 0));
	}


}
