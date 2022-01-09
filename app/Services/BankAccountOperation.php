<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\User;
use App\Models\BankAccountHistory;
use App\Models\BankAccountType;
use App\Models\Config;

trait BankAccountOperation
{
	private BankAccount $origin;
	private BankAccount $destiny;
	private float $amount;
	private $description;
	private $json;

	private function setUser() : void
	{
		$user = User::find(User::getAuthenticatedUserId());

		$this->json['user'] = [
			'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'document_cpf' => $user->document_cpf,
            'document_rg' => $user->document_rg,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
		];
	}

	public function getAccountUserLogged() : BankAccount
	{
		$user = User::find(User::getAuthenticatedUserId());

		if(!$user)
            throw new \Exception(__('users.not-found'));

        if($user->type == User::TYPE_ADMIN)
            $account = BankAccount::where('bank_account_type_id', BankAccount::CLUB)->first();

        if($user->type == User::TYPE_MEMBER)
            $account = $user->bank?->account;

        if(!isset($account) || !$account)
            throw new \Exception(__('bank_account.not-found'));

        return $account;
	}

	public function getAccountClub() : BankAccount
	{
		$account = BankAccount::where('bank_account_type_id', BankAccount::CLUB)->first();

		if(!$account)
			throw new \Exception(__('bank_account.not-found'));

		return $account;
	}

	public function setOrigin($origin) : void
	{
		if(!$origin)
			throw new \Exception(__('bank_account.errors.bank-account-not-found-origin'));

		$this->json['origin'] = [
			'id' => $origin->id,
            'account_number' => $origin->account_number,
            'account_holder' => $origin->through?->user?->name,
            'type' => [
                'id' => $origin->bank_account_type_id,
                'description' => $origin->type->description
            ],
            'status' => [
                'id' => $origin->status_id,
                'description' => $origin->status->description
            ]
		];

		$this->origin = $origin;
	}

	public function setDestiny($destiny) : void
	{
		if(!$destiny)
			throw new \Exception(__('bank_account.errors.bank-account-not-found'));

		$this->json['destiny'] = [
			'id' => $destiny->id,
            'account_number' => $destiny->account_number,
            'account_holder' => $destiny->through?->user?->name,
            'type' => [
                'id' => $destiny->bank_account_type_id,
                'description' => $destiny->type->description
            ],
            'status' => [
                'id' => $destiny->status_id,
                'description' => $destiny->status->description
            ]
		];

		$this->destiny = $destiny;
	}

	public function setAmount(float $amount) : void
	{
		if($amount <= 0)
			throw new \Exception(__('bank_account.errors.min-transfer'));

		$this->json['amount'] = $amount;

		$this->amount = $amount;
	}

	public function setDescription($description) : void
	{
		$this->json['description'] = $description;

		$this->description = $description;
	}

	public function transfer() : void
	{
		$this->setUser();

		$this->json['operation'] = 'transfer';

		$required = [
			'amount',
			'user',
			'destiny',
			'origin',
			'description',
			'operation'
		];

		foreach($required as $field)
		{
			if(!in_array($field, array_keys($this->json)))
				throw new \Exception(__('bank_account.error-transfer'));
		}

		if($this->origin->id == $this->destiny->id)
            throw new \Exception(__('bank_account.errors.transfer-my'));

        if($this->amount > (float) $this->origin->balance)
        {
        	if($this->origin->bank_account_type_id == BankAccountType::CLUB)
        	{
        		if(!Config::Get()->allow_negative_balance)
					throw new \Exception(__('bank_account.errors.insufficient-fund'));
        	}
        	else
        	{
        		throw new \Exception(__('bank_account.errors.insufficient-fund'));
        	}
        }

        $this->origin->balance -= $this->amount;
        $this->origin->save();

        $this->json['operation_type'] = 'debit';

        $historyOrigin = BankAccountHistory::create([
            'bank_account_id' => $this->origin->id,
            'data' => json_encode($this->json)
        ]);

        if(!$historyOrigin)
            throw new \Exception(__('bank_account.error-transfer'));

        $this->destiny->balance += $this->amount;
        $this->destiny->save();

        $this->json['operation_type'] = 'credit';

        $historyDestiny = BankAccountHistory::create([
            'bank_account_id' => $this->destiny->id,
            'data' => json_encode($this->json)
        ]);

        if(!$historyDestiny)
            throw new \Exception(__('bank_account.error-transfer'));
	}
}