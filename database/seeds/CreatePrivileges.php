<?php

use Illuminate\Database\Seeder;

use App\Models\Privilege;

class CreatePrivileges extends Seeder
{
    public static $privileges = [
        'web'   =>  [
            // 'index', 
            // 'auth.logout',
            // 'auth.expired', 
            // 'app.language', 
            // 'auth.register', 
            // 'auth.register.post', 
            // 'auth.login', 
            // 'auth.login.post', 
            // 'dashboard.home', 
            [ 'action' => 'users.administrators.list',          'name' => 'List Administrators', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.administrators.create',        'name' => 'Create Administrator', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.administrators.update',        'name' => 'Update Administrator', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.administrators.delete',        'name' => 'Delete Administrator', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.members.list',                 'name' => 'List Members', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.members.create',               'name' => 'Create Member', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.members.update',               'name' => 'Update Member', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.members.delete',               'name' => 'Delete Member', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.members.view-all',             'name' => 'List all members and administrators', 'description' => '', 'add_member' => true ], 
        ],
        'api'   =>  [
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try
        {
            foreach(self::$privileges as $key_privilege => $privilege)
            {
                foreach($privilege as $add_privilege )
                {
                    $add_privilege['action'] = $key_privilege . "." . $add_privilege['action'];

                    if (! Privilege::select('action')->where('action', $add_privilege['action'])->first())
                    {
                        $privilege = new Privilege();

                        $privilege->action = $add_privilege['action'];
                        $privilege->name = $add_privilege['name'];
                        $privilege->description = $add_privilege['description'];

                        $privilege->save();

                        echo "Create privilege \033[35m" . $privilege->action . "\033[0m " . PHP_EOL;
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
