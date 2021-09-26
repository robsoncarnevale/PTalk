<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\PrivilegeGroup;

/** 
 * Seeder Create Member User Waiting Approval
 *
 * @author Davi Souto
 * @since 18/06/2020
 */
class CreateMemberUserWaitingApproval extends Seeder
{
    /**
     * @var string
     */
    private $club_code;

    /**
     * @var string
     */
    private $email;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->club_code = DatabaseSeeder::$club_code;

        // member_new@porschetalk.com
        $this->email = "member_new@" . strtolower(preg_replace("#[^a-zA-Z0-9]#is", "", $this->club_code)) . ".com";

        $user = new User();

        $user->club_code = $this->club_code;
        $user->name = "Membro Novo";
        $user->email = $this->email;
        $user->password = \Hash::make('123456');
        $user->type = 'member';
        $user->approval_status = 'waiting';

        $user->privilege_id = PrivilegeGroup::select('id')->where('type', 'member')->first()->id;
        $user->document_cpf = "12345678999";
        // $user->cell_phone = "1122224444";

        $user->save();
    }
}


