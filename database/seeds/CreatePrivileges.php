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
            [ 'action' => 'users.profile.view',                 'name' => 'View My User Profile', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.administrators.list',          'name' => 'List Administrators', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.administrators.create',        'name' => 'Create Administrator', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.administrators.update',        'name' => 'Update Administrator', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.administrators.delete',        'name' => 'Delete Administrator', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.members.list',                 'name' => 'List Members', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.members.create',               'name' => 'Create Member', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.members.update',               'name' => 'Update Member', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.members.delete',               'name' => 'Delete Member', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.members.view-all',             'name' => 'List all members and administrators', 'description' => '', 'add_member' => true ], 
            [ 'action' => 'users.members.waiting-approval',     'name' => 'List Members Waiting Approval', 'description' => '', 'add_member' => false ],
            
            [ 'action' => 'vehicles.list',                      'name' => 'List Vehicles', 'description' => '', 'add_member' => true ], 
            [ 'action' => 'vehicles.get',                       'name' => 'Get Vehicle', 'description' => '', 'add_member' => true ], 
        ],
        'api'   =>  [
            // Tests
            // 'test.maketest',
            // 'test.maketest.get',
            // 'test.maketest.post',

            // 'auth.login',
            // 'auth.logout',

            [ 'action' => 'users.me',                           'name' => 'View My User Profile', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.profile.view',                 'name' => 'View User Profile', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.administrators.list',          'name' => 'List Administrators', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.administrators.get',           'name' => 'Read Administrator', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.administrators.create',        'name' => 'Create Administrator', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.administrators.update',        'name' => 'Update Administrator', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.administrators.delete',        'name' => 'Delete Administrator', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.list',                 'name' => 'List Members', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.get',                  'name' => 'Read Member', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.members.create',               'name' => 'Create Member', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.update',               'name' => 'Update Member', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.delete',               'name' => 'Delete Member', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.view-all',             'name' => 'List all members and administrators', 'description' => '', 'add_member' => true ], 
            [ 'action' => 'users.members.waiting-approval',     'name' => 'List Members Waiting Approval', 'description' => '', 'add_member' => false ],

            [ 'action' => 'privileges.groups.list',             'name' => 'List Privileges', 'description' => '', 'add_member' => false ],
            [ 'action' => 'privileges.groups.get',              'name' => 'Read Privilege', 'description' => '', 'add_member' => false ],
            [ 'action' => 'privileges.groups.admins.list',      'name' => 'List Admins Privileges', 'description' => '', 'add_member' => false ],
            [ 'action' => 'privileges.groups.members.list',     'name' => 'List Members Privileges', 'description' => '', 'add_member' => false ],
            
            // [ 'action' => 'car.brands.list',                    'name' => 'List car brands', 'description' => '', 'add_member' => false ],
            // [ 'action' => 'car.brands.get',                     'name' => 'Read car brands', 'description' => '', 'add_member' => false ],
            // [ 'action' => 'car.models.list',                   'name' => 'List car models', 'description' => '', 'add_member' => false ],
            // [ 'action' => 'car.models.get',                     'name' => 'Read car models', 'description' => '', 'add_member' => false ],
            // [ 'action' => 'car.models.all',                     'name' => 'Read car models with car brands', 'description' => '', 'add_member' => false ],
            // [ 'action' => 'car.colors.list',                    'name' => 'List car colors', 'description' => '', 'add_member' => false ],
            // [ 'action' => 'car.colors.get',                     'name' => 'Read car colors', 'description' => '', 'add_member' => false ],

            [ 'action' => 'vehicles.list',                      'name' => 'List Vehicles', 'description' => '', 'add_member' => true ], 
            [ 'action' => 'vehicles.get',                       'name' => 'Get Vehicle', 'description' => '', 'add_member' => true ], 
            [ 'action' => 'vehicles.my-vehicles.list',          'name' => 'List My Vehicles', 'description' => '', 'add_member' => true ], 
            [ 'action' => 'vehicles.my-vehicles.get',           'name' => 'Get My Vehicle', 'description' => '', 'add_member' => true ], 
            [ 'action' => 'vehicles.my-vehicles.create',        'name' => 'Create My Vehicle', 'description' => '', 'add_member' => true ], 
            [ 'action' => 'vehicles.my-vehicles.update',        'name' => 'Update My Vehicle', 'description' => '', 'add_member' => true ], 



            [ 'action' => 'club.status',                        'name' => 'Read club status', 'description' => '', 'add_member' => true ],
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
                    // $add_privilege['action'] = $key_privilege . "." . $add_privilege['action'];

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
