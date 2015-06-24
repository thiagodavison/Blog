<?php 

namespace App\Services;

########
# Simples Classe Wrapper para Curl
##
class HttpRequest
{
	private $curl;
	
	const POST  = CURLOPT_POST;
	const GET   = null;

	public function __construct($url, array $args = null, $metodo = HttpRequest::GET)
	{

		$this->curl = curl_init();

		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);

		if( ! is_null($metodo) )
		{
			curl_setopt($this->curl, $metodo, true);

			if( ! is_null($args) )
			{
				curl_setopt($this->curl, CURLOPT_POSTFIELDS, $args);
			}
		}
		else
		{
			$url = rtrim($url, '/');

			if($args != null)
			{
				$url .= '?';

				foreach ($args as $key => $value) 
				{
					$url .= $key . '=' . $value . '&';
				}
				
				$url = rtrim($url, '&');
			}
		}

		curl_setopt($this->curl, CURLOPT_URL, $url);
	}

	public function sucesso()
	{
		return curl_errno($this->curl) == 0;
	}

	public function getResposta()
	{
		return curl_exec($this->curl);
	}

	public function __destruct()
	{
		curl_close($this->curl);
	}
}