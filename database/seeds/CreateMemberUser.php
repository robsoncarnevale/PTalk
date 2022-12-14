<?php

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\PrivilegeGroup;

/** 
 * Seeder Create Member User
 *
 * @author Davi Souto
 * @since 15/06/2020
 */
class CreateMemberUser extends Seeder
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
        // if (User::select('id')->where('type', 'member')->count() > 0)
        //     return;

        $this->club_code = DatabaseSeeder::$club_code;

        // member@porschetalk.com
        $this->email = "member@" . strtolower(preg_replace("#[^a-zA-Z0-9]#is", "", $this->club_code)) . ".com";

        $user = new User();

        $user->club_code = $this->club_code;
        $user->name = "Membro";
        $user->email = $this->email;
        $user->password = Hash::make('123456');
        $user->type = 'member';
        $user->approval_status = 'approved';

        $user->privilege_id = PrivilegeGroup::select('id')->where('type', 'member')->first()->id;
        $user->document_cpf = "12345678900";
        $user->cell_phone = "1122223333";

        $user->save();
    }
}

