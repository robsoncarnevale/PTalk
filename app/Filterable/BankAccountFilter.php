<?php

namespace App\Filterable;

use Illuminate\Database\Eloquent\Builder;

final class BankAccountFilter
{
	protected Builder $builder;

	public function __construct(Builder $builder)
	{
		$this->builder = $builder;
	}

	public function account_number($number)
	{
		return $this->builder->where('account_number', $number);
	}
}