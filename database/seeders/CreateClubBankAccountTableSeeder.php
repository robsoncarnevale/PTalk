<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankAccount;
use App\Models\BankAccountType;
use App\Models\Status;

class CreateClubBankAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account = BankAccount::where('bank_account_type_id', BankAccountType::CLUB)->first();

        if($account)
            return;

        $sequential = env('CLUB_ACCOUNT_SEQUENTIAL');

        if(!$sequential)
            throw new \Exception('Sequencial não está configurado!');

        $account = BankAccount::create([
            'account_number' => str_pad($sequential, 11, 0, STR_PAD_LEFT),
            'bank_account_type_id' => BankAccountType::CLUB,
            'status_id' => Status::ACTIVE
        ]);

        if(!$account)
            throw new \Exception('Falha ao criar conta bancária do clube!');
    }
}
