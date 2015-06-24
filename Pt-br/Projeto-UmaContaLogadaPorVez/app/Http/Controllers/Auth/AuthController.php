<?php 

namespace App\Http\Controllers\Auth;


use Auth;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller 
{

	use AuthenticatesAndRegistersUsers;

	public function __construct()
	{

	}
	
	public function postLogin(Request $request)

		$this->validate($request, [
			'email' => 'required|email', 'password' => 'required',
		]);

		$credentials = $request->only('email', 'password');

		if (Auth::attempt($credentials, $request->has('remember')))
		{
			$usuario = Auth::user();

			if( ! is_null($usuario->sessao) )
			{
				Session::getHandler()->destroy($usuario->sessao);
			}

			$usuario->sessao = Session::getId();

			$usuario->save();
				
			return redirect()->route($this->redirectPath());
		}

		return redirect()
			->route($this->loginPath())
			->withInput($request->only('email', 'remember'))
			->withErrors([
				'email' => $this->getFailedLoginMessage(),
			]);
	}
}