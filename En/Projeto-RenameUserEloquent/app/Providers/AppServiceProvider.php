<?php 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;

class AppServiceProvider extends ServiceProvider {

	public function boot()
	{
	}

	public function register()
	{
		$this->app->bind(
			'App\Eloquents\Usuario',
			function()
			{
				return Auth::user();
			}
		);
	}

}