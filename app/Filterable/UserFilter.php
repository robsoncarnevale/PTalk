<?php

namespace App\Filterable;

use Illuminate\Database\Eloquent\Builder;

final class UserFilter
{
	protected Builder $builder;

	public function __construct(Builder $builder)
	{
		$this->builder = $builder;
	}

	public function id($id)
	{
		return $this->builder->where('id', $id);
	}

	public function name($name)
	{
		return $this->builder->where('name', 'like', '%' . $name . '%');
	}

	public function email($email)
	{
		return $this->builder->where('email', $email);
	}

	public function account_number($number)
	{
		return $this->builder->whereHas('bank', function($bank) use ($number){

			$bank->whereHas('account', function($account) use ($number){

				$account->where('account_number', $number);

			});

		});
	}

	public function status($status)
	{
		return $this->builder->where('status', $status);
	}

	public function state($state)
	{
		return $this->builder->whereHas('addresses', function($addresses) use ($state){

			$addresses->where('state', $state);

		});
	}

	public function city($city)
	{
		return $this->builder->whereHas('addresses', function($addresses) use ($city){

			$addresses->where('city', $city);

		});
	}

	public function car_model_id($id)
	{
		return $this->builder->whereHas('vehicles', function($vehicles) use ($id){

			$vehicles->where('car_model_id', $id);

		});
	}

	public function car_color_id($id)
	{
		return $this->builder->whereHas('vehicles', function($vehicles) use ($id){

			$vehicles->where('car_color_id', $id);

		});
	}
}