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
		$this->middleware('guest', ['except' => 'getLogout']);
		$this->middleware('recaptcha');
	}

	public function validator(array $data)
	{
		#########
		# recaptcha is filled by ValidatorReCAPTCHA, then we just check whether is 1 (valid).
		###
		Validator::extend(
			'truable', 
			function($attributes, $value, $parameters)
			{
				return isset($value) and intval($value) == 1;
			}, 
			trans('validation.recaptcha') #message when is not valid.
		);

		#########
		# Add recaptcha value to be validate.
		###
		return Validator::make(
			array_add(
				$data, 'recaptcha', Session::pull('recaptcha', '')
			), 
			[
				'nome' => 'required|max:255',
				'email' => 'required|email|max:255|unique:users',
				'password' => 'required|confirmed|min:6',
				'recaptcha' => 'boolean|truable',
			]
		);
	}

	public function create(array $data)
	{
		$usuario = $this->usrRepo->criar([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
		]);

		return $usuario;
	}
}