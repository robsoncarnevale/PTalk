<?php

use Illuminate\Database\Seeder;

use App\Models\HasPrivilege;
use App\Models\PrivilegeGroup;

class AddPrivilegesAdmin extends Seeder
{
    /**
     * @var string
     */
    private $club_code;

    /**
     * @var collection
     */
    private $privilege_group;

    /**
     * @var array
     */
    private $privileges = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->club_code = DatabaseSeeder::$club_code;
        $this->privileges = CreatePrivileges::$privileges;
        $this->privilege_group = PrivilegeGroup::select('id', 'name', 'type')
            ->where('club_code', $this->club_code)
            ->where('name', 'Administrador')
            ->where('type', 'admin')
            ->first();

        DB::beginTransaction();

        try
        {
            foreach($this->privileges as $key_privilege => $privilege)
            {
                foreach($privilege as $add_privilege )
                {
                    $add_privilege['action'] = $key_privilege . "." . $add_privilege['action'];

                    if (! HasPrivilege::select('privilege_action')->where('privilege_action', $add_privilege['action'])->where('privilege_group_id', $this->privilege_group->id)->first())
                    {
                        $has_privilege = new HasPrivilege();

                        $has_privilege->privilege_action = $add_privilege['action'];
                        $has_privilege->privilege_group_id = $this->privilege_group->id;

                        $has_privilege->save();

                        echo "Add admin privilege \033[35m" . $has_privilege->privilege_action . "\033[0m " . PHP_EOL;
                    }
                    
                }
            }

            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }
}
