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
		$this->middleware('recaptcha');
	}

	public function validator(array $data)
	{
		#########
		# recaptcha é preenchido pelo middleware ValidadorReCAPTCHA e checamos se é 1 (válido).
		###
		Validator::extend(
			'truable', 
			function($attributes, $value, $parameters)
			{
				return isset($value) and intval($value) == 1;
			}, 
			trans('validation.recaptcha') #mensagem para quando recaptcha não for 1.
		);

		#########
		# adiciono recaptcha para ser validado.
		###
		return Validator::make(
			array_add($data, 'recaptcha', Session::pull('recaptcha', '')), 
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