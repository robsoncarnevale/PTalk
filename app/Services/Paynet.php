<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

class Paynet
{
	private Client $client;

	private $host = 'https://infraecommerce.paynet.net.br:8080';
	private $email = 'daniel@paynet.net.br';
	private $password = '1234';
	private $headers;

	public function __construct()
	{
		$this->client = new Client([
			'base_uri' => $this->host,
			'timeout' => 60
		]);

		$this->headers['route'] = '110';
		$this->headers['version'] = 'v2';
	}

	public function login() : void
	{
		try
		{
			$response = $this->client->POST('/login', [
				'json' => [
					'email' => $this->email,
					'password' => $this->password
				],
				'headers' => $this->headers
			]);

			if(!$response->getBody())
				throw new \Exception(__('general.generic.message') . ' (login - 1)');

			$response = json_decode($response->getBody());

			if(!isset($response->api_key))
				throw new \Exception(__('general.generic.message') . ' (login - 2)');

			$this->headers['authorization'] = $response->api_key;
		}
		catch(RequestException $e)
		{
			if(!$e->getResponse())
				throw new \Exception(__('general.generic.message') . ' (login - 3)');

			if(!$e->getResponse()->getBody())
				throw new \Exception(__('general.generic.message') . ' (login - 4)');

			$response = json_decode($e->getResponse()->getBody());

			if(isset($response->status) && $response->status == 'fail')
				throw new \Exception(__('paynet_api.fail'));

			throw new \Exception(__('general.generic.message') . ' (login - 5)');
		}
		catch(ConnectException $e)
		{
			throw new \Exception(__('general.generic.message') . ' (login - 6)');
		}
	}

	public function tokenization($body) : string
	{
		try
		{
			$response = $this->client->POST('/card', [
				'json' => $body,
				'headers' => $this->headers
			]);

			if(!$response->getBody())
				throw new \Exception(__('general.generic.message') . ' (tokenization - 1)');

			$response = json_decode($response->getBody());

			if(!$response)
				throw new \Exception(__('general.generic.message') . ' (tokenization - 2)');

			if(!isset($response->numberToken))
				throw new \Exception(__('general.generic.message') . ' (tokenization - 3)');

			return $response->numberToken;
		}
		catch(RequestException $e)
		{
			if(!$e->getResponse())
				throw new \Exception(__('general.generic.message') . ' (tokenization - 4)');

			if(!$e->getResponse()->getBody())
				throw new \Exception(__('general.generic.message') . ' (tokenization - 5)');

			$response = json_decode($e->getResponse()->getBody());

			if(!$response)
				throw new \Exception(__('general.generic.message') . ' (tokenization - 6)');

			if(isset($response->errors))
			{
				$map = array_map(function($register){

					if(isset($register->description))
						return $register->description;

				}, $response->errors);

				$map = implode(' | ', $map);

				throw new \Exception($map . ' (tokenization - 7)');
			}

			throw new \Exception(__('general.generic.message') . ' (tokenization - 8)');
		}
		catch(ConnectException $e)
		{
			throw new \Exception(__('general.generic.message') . ' (tokenization - 9)');
		}
	}

	public function brand($number)
	{
		try
		{
			$number = substr($number, 0, 6);

			$response = $this->client->GET('/bin/' . $number, [
				'headers' => $this->headers
			]);

			if(!$response->getBody())
				throw new \Exception(__('general.generic.message') . ' (brand - 1)');

			$response = json_decode($response->getBody());

			if(!$response)
				throw new \Exception(__('general.generic.message') . ' (brand - 2)');

			if(is_array($response) && count($response) > 1)
				$response = $response[0];

			if(!isset($response->code))
				throw new \Exception(__('general.generic.message') . ' (brand - 3)');

			return $response->code;
		}
		catch(RequestException $e)
		{
			if(!$e->getResponse())
				throw new \Exception(__('general.generic.message') . ' (brand - 4)');

			if(!$e->getResponse()->getBody())
				throw new \Exception(__('general.generic.message') . ' (brand - 5)');

			$response = json_decode($e->getResponse()->getBody());

			if(!$response)
				throw new \Exception(__('general.generic.message') . ' (brand - 6)');

			if(isset($response->errors))
			{
				$map = array_map(function($register){

					if(isset($register->description))
						return $register->description;

				}, $response->errors);

				$map = implode(' | ', $map);

				throw new \Exception($map . ' (brand - 7)');
			}

			throw new \Exception(__('general.generic.message') . ' (brand - 8)');
		}
		catch(ConnectException $e)
		{
			throw new \Exception(__('general.generic.message') . ' (brand - 9)');
		}
	}

	public function payment()
	{
		try
		{
			$response = $this->client->GET('/brands', [
				'headers' => $this->headers
			]);

			$response = json_decode($response->getBody());

			dd($response);
		}
		catch(RequestException $e)
		{
			if(!$e->getResponse())
				throw new \Exception(__('general.generic.message') . ' (payment - 4)');

			if(!$e->getResponse()->getBody())
				throw new \Exception(__('general.generic.message') . ' (payment - 5)');

			$response = json_decode($e->getResponse()->getBody());

			if(!$response)
				throw new \Exception(__('general.generic.message') . ' (payment - 6)');

			if(isset($response->errors))
			{
				$map = array_map(function($register){

					if(isset($register->description))
						return $register->description;

				}, $response->errors);

				$map = implode(' | ', $map);

				throw new \Exception($map . ' (payment - 7)');
			}

			throw new \Exception(__('general.generic.message') . ' (payment - 8)');
		}
		catch(ConnectException $e)
		{
			throw new \Exception(__('general.generic.message') . ' (payment - 9)');
		}
	}
}