<?php 

namespace App\Services;

########
# Simple Wrapper Class
##
class HttpRequest
{
	private $curl;
	
	const POST  = CURLOPT_POST;
	const GET   = null;

	public function __construct($url, array $args = null, $method = HttpRequest::GET)
	{

		$this->curl = curl_init();

		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);

		if( ! is_null($method) )
		{
			curl_setopt($this->curl, $method, true);

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

	public function success()
	{
		return curl_errno($this->curl) == 0;
	}

	public function getResponse()
	{
		return curl_exec($this->curl);
	}

	public function __destruct()
	{
		curl_close($this->curl);
	}
}