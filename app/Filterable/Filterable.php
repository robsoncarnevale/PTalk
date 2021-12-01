<?php

namespace App\Filterable;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
	private Builder $builder;

	public function scopeFilter($query, Request $request)
	{
		$this->builder = $query;

		$classFilter = explode('\\', $this::class);
		$classFilter = '\\App\\Filterable\\' . end($classFilter) . 'Filter';

		if(!class_exists($classFilter))
			return $this->builder;

		$classFilter = new $classFilter($this->builder);

		foreach($request->all() as $method => $value)
		{
			if(!method_exists($classFilter, $method))
				continue;

			$classFilter->$method($value);
		}

		return $this->builder;
	}
}