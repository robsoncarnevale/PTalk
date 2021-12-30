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

	public function tokenization($body)
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

			dd($response);
		}
		catch(RequestException $e)
		{
			if(!$e->getResponse())
				throw new \Exception(__('general.generic.message') . ' (tokenization - 2)');

			if(!$e->getResponse()->getBody())
				throw new \Exception(__('general.generic.message') . ' (tokenization - 3)');

			$response = json_decode($e->getResponse()->getBody());

			if(!$response)
				throw new \Exception(__('general.generic.message') . ' (tokenization - 4)');

			if(isset($response->errors))
			{
				$map = array_map(function($register){

					if(isset($register->description))
						return $register->description;

				}, $response->errors);

				$map = implode(' | ', $map);

				throw new \Exception($map . ' (tokenization - 5)');
			}

			throw new \Exception(__('general.generic.message') . ' (tokenization - 6)');
		}
		catch(ConnectException $e)
		{
			\Log::info($e);

			throw new \Exception(__('general.generic.message') . ' (tokenization - 7)');
		}
	}
}