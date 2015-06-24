<?php namespace App\Http\Middleware;

use Closure;
use Request;
use Session;
use App\Services\HttpRequest;

class ValidadorReCAPTCHA {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		$httpRequest = new HttpRequest(
			'https://www.google.com/recaptcha/api/siteverify',
			['secret' => env('RECAPTCHA_SECRET'), 
			'response' => $request->input('g-recaptcha-response', '')],
			HttpRequest::POST
			);

		$response = json_decode($httpRequest->getResposta());

		if($httpRequest->sucesso())
			Session::put('recaptcha', $response->success);
		else
			Session::put('recaptcha', false);

		return $next($request);
	}

}