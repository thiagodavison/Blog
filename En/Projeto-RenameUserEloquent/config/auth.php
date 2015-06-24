<?php

return [

	'driver' => 'eloquent',

	'model' => 'App\Eloquents\Usuario',

	'table' => 'usuarios',

	'password' => [
		'email' => 'emails.password',
		'table' => 'password_resets',
		'expire' => 60,
	],

];