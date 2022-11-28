<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use App\Models\User;
use App\Models\TransactionStatus;
use App\Jobs\JobReversalTransaction;

class Paynet
{
	private Client $client;

	private $host = 'https://infraecommerce.paynet.net.br:8080';
	private $email = 'ti@4clubes.com.br';
	private $password = '6vJ^LCu3#Pd$QP';
	private $headers;

	public function __construct()
	{
		$this->client = new Client([
			'base_uri' => $this->host,
			'timeout' => 60
		]);

		$this->headers['route'] = '196';
		$this->headers['version'] = 'v1';
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

	public function payment($token, $brand, $transaction)
	{
		try
		{
			$user = User::find(User::getAuthenticatedUserId());

			if(!$user)
				throw new \Exception(__('users.not-found'));

			$amount = (integer) str_replace('.', '', number_format($transaction->amount, 2));
			$date = \Carbon\Carbon::now()->format('dmYHis');
			$name = explode(' ', $user->name);

			$response = $this->client->POST('/financial', [
				'headers' => $this->headers,
				'json' => [
					'payment' => [
						'documentNumber' => '37833643000126',
						'transactionType' => 1,
						'amount' => $amount,
						'currencyCode' => 'brl',
						'productType' => 1,
						'installments' => $transaction->installments,
						'captureType' => 1,
						'recurrent' => false
					],
					'cardInfo' => [
						'numberToken' => $token,
						'brand' => $brand
					],
					'sellerInfo' => [
						'orderNumber' => $transaction->order_number,
						'softDescriptor' => $date . rand(000, 999)
					],
					'customer' => [
						'documentType' => 2,
						'documentNumberCDH' => $user->document_cpf,
						'firstName' => $name[0],
						'lastName' => end($name),
						'country' => 'BRA'
					],
					'transactionSimple' => 0
				]
			]);

			if(!$response->getBody())
				throw new \Exception(__('general.generic.message') . ' (payment - 1)');

			$response = json_decode($response->getBody());

			if(!$response)
				throw new \Exception(__('general.generic.message') . ' (payment - 2)');

			$transaction->order_number = isset($response->orderNumber) ? $response->orderNumber : null ;
			$transaction->authorization = isset($response->authorizationCode) ? $response->authorizationCode : null ;
			$transaction->nsu = isset($response->nsu) ? $response->nsu : null ;
			$transaction->payment_token = isset($response->paymentId) ? $response->paymentId : null ;
			$transaction->response_code = isset($response->returnCode) ? $response->returnCode : null ;

			if(!isset($response->paymentId))
				throw new \Exception(__('general.generic.message') . ' (payment - 3)');

			if(isset($response->returnCode))
			{
				if($response->returnCode == '00')
					$transaction->transaction_status_id = TransactionStatus::APPROVED;

				if($response->returnCode != '00')
					$transaction->transaction_status_id = TransactionStatus::DENIED;
			}

			$transaction->save();
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

				$transaction->transaction_status_id = TransactionStatus::DENIED;
				$transaction->save();

				throw new \Exception($map . ' (payment - 7)');
			}

			throw new \Exception(__('general.generic.message') . ' (payment - 8)');
		}
		catch(ConnectException $e)
		{
			throw new \Exception(__('general.generic.message') . ' (payment - 9)');
		}
	}

	public function cancel($cancel, $token, $amount)
	{
		try
		{
			$amount = (integer) str_replace('.', '', number_format($amount, 2));

			$response = $this->client->POST('/cancel', [
				'headers' => $this->headers,
				'json' => [
					'documentNumber' => '37833643000126',
					'paymentId' => $token,
					'amount' => $amount
				]
			]);

			if(!$response->getBody())
				throw new \Exception(__('general.generic.message') . ' (cancel - 1)');

			$response = json_decode($response->getBody());

			if(!$response)
				throw new \Exception(__('general.generic.message') . ' (cancel - 2)');

			$cancel->authorization = isset($response->authorizationCode) ? $response->authorizationCode : null ;
			$cancel->payment_token = isset($response->paymentId) ? $response->paymentId : null ;
			$cancel->response_code = isset($response->returnCode) ? $response->returnCode : null ;

			if(!isset($response->paymentId))
				throw new \Exception(__('general.generic.message') . ' (cancel - 3)');

			if(isset($response->returnCode))
			{
				if($response->returnCode == '00')
					$cancel->transaction_status_id = TransactionStatus::APPROVED;

				if($response->returnCode != '00')
					$cancel->transaction_status_id = TransactionStatus::DENIED;
			}

			$cancel->save();
		}
		catch(RequestException $e)
		{
			if(!$e->getResponse())
				throw new \Exception(__('general.generic.message') . ' (cancel - 4)');

			if(!$e->getResponse()->getBody())
				throw new \Exception(__('general.generic.message') . ' (cancel - 5)');

			$response = json_decode($e->getResponse()->getBody());

			if(!$response)
				throw new \Exception(__('general.generic.message') . ' (cancel - 6)');

			if(isset($response->errors))
			{
				$map = array_map(function($register){

					if(isset($register->description))
						return $register->description;

				}, $response->errors);

				$map = implode(' | ', $map);

				$transaction->transaction_status_id = TransactionStatus::DENIED;
				$transaction->save();

				throw new \Exception($map . ' (cancel - 7)');
			}

			throw new \Exception(__('general.generic.message') . ' (cancel - 8)');
		}
		catch(ConnectException $e)
		{
			throw new \Exception(__('general.generic.message') . ' (cancel - 9)');
		}
	}
}