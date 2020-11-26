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
        ],
        'api'   =>  [
            // Tests
            // 'test.maketest',
            // 'test.maketest.get',
            // 'test.maketest.post',

            // 'auth.login',
            // 'auth.logout',

            [ 'action' => 'users.me',                           'name' => 'View My User Profile', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.profile.view',                 'name' => 'View User Profile', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.administrators.get',           'name' => 'Read Administrator', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.administrators.update',        'name' => 'Update Administrator', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.administrators.delete',        'name' => 'Delete Administrator', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.list',                 'name' => 'List Members', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.get',                  'name' => 'Read Member', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.create',               'name' => 'Create Member', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.update',               'name' => 'Update Member', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.delete',               'name' => 'Delete Member', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.view-all',             'name' => 'List all members and administrators', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'users.members.approval-status.set',  'name' => 'Set Member Approval Status', 'description' => '', 'add_member' => false ],
            
            [ 'action' => 'users.classes.list',                 'name' => 'List Members Classes', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.classes.get',                  'name' => 'Get Members Class', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.classes.create',               'name' => 'Create Members Class', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.classes.update',               'name' => 'Update Members Class', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.classes.delete',               'name' => 'Delete Members Class', 'description' => '', 'add_member' => false ],

            [ 'action' => 'users.address.list',                 'name' => 'List User Addresses', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.address.get',                  'name' => 'Get User Address', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.address.create',               'name' => 'Create User Address', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.address.update',               'name' => 'Update User Address', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.address.delete',               'name' => 'Delete User Addres', 'description' => '', 'add_member' => false ],

            [ 'action' => 'users.address.list.my',              'name' => 'List My Addresses', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.address.get.my',               'name' => 'Get My Address', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.address.create.my',            'name' => 'Create My Address', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.address.update.my',            'name' => 'Update My Address', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.address.delete.my',            'name' => 'Delete My Address', 'description' => '', 'add_member' => true ],

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

            [ 'action' => 'car.models.create',                  'name' => 'Create Car Model', 'description' => '', 'add_member' => false ],
            [ 'action' => 'car.models.update',                  'name' => 'Update Car Model', 'description' => '', 'add_member' => false ],
            [ 'action' => 'car.models.delete',                  'name' => 'Delete Car Model', 'description' => '', 'add_member' => false ],
            [ 'action' => 'car.colors.create',                  'name' => 'Create Car Color', 'description' => '', 'add_member' => false ],
            [ 'action' => 'car.colors.update',                  'name' => 'Update Car Color', 'description' => '', 'add_member' => false ],
            [ 'action' => 'car.colors.delete',                  'name' => 'Delete Car Color', 'description' => '', 'add_member' => false ],

            [ 'action' => 'vehicles.list',                      'name' => 'List Vehicles', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'vehicles.get',                       'name' => 'Get Vehicle', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'vehicles.create',                    'name' => 'Create Vehicle', 'description' => '', 'add_member' => false ],
            [ 'action' => 'vehicles.update',                    'name' => 'Update Vehicle', 'description' => '', 'add_member' => false ],
            [ 'action' => 'vehicles.delete',                    'name' => 'Delete Vehicle', 'description' => '', 'add_member' => false ],
            [ 'action' => 'vehicles.photo.upload',              'name' => 'Upload Photo to Vehicle', 'description' => '', 'add_member' => false ],
            [ 'action' => 'vehicles.photo.delete',              'name' => 'Delete Photo on Vehicle', 'description' => '', 'add_member' => false ],
            [ 'action' => 'vehicles.photo.upload-without-vehicle', 'name' => 'Upload photo without vehicle', 'description' => '', 'add_member' => true ],
            [ 'action' => 'vehicles.my-vehicles.list',          'name' => 'List My Vehicles', 'description' => '', 'add_member' => true ], 
            [ 'action' => 'vehicles.my-vehicles.get',           'name' => 'Get My Vehicle', 'description' => '', 'add_member' => true ], 
            [ 'action' => 'vehicles.my-vehicles.create',        'name' => 'Create My Vehicle', 'description' => '', 'add_member' => true ], 
            [ 'action' => 'vehicles.my-vehicles.update',        'name' => 'Update My Vehicle', 'description' => '', 'add_member' => true ], 
            [ 'action' => 'vehicles.my-vehicles.delete',        'name' => 'Delete My Vehicle', 'description' => '', 'add_member' => true ],
            [ 'action' => 'vehicles.my-vehicles.photo.upload',  'name' => 'Upload Photo to My Vehicle', 'description' => '', 'add_member' => true ],
            [ 'action' => 'vehicles.my-vehicles.photo.delete',  'name' => 'Delete Photo on My Vehicle', 'description' => '', 'add_member' => true ],

            [ 'action' => 'events.list',                        'name' => 'List Events', 'description' => '', 'add_member' => false ],
            [ 'action' => 'events.get',                         'name' => 'Get Event', 'description' => '', 'add_member' => false ],
            [ 'action' => 'events.create',                      'name' => 'Create Event', 'description' => '', 'add_member' => false ],
            [ 'action' => 'events.update',                      'name' => 'Update Event', 'description' => '', 'add_member' => false ],
            [ 'action' => 'events.delete',                      'name' => 'Delete Event', 'description' => '', 'add_member' => false ],

            [ 'action' => 'blacklist.list',                     'name' => 'List Blacklist', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'blacklist.get',                      'name' => 'Get Blacklist', 'description' => '', 'add_member' => false ],    
            [ 'action' => 'blacklist.create',                   'name' => 'Create Blacklist', 'description' => '', 'add_member' => false ],      
            [ 'action' => 'blacklist.update',                   'name' => 'Update Blacklist', 'description' => '', 'add_member' => false ],     

            [ 'action' => 'bankaccount.list',                  'name' => 'List All Bank Accounts', 'description' => '', 'add_member' => false ],
            [ 'action' => 'bankaccount.extract',               'name' => 'Extract of User Bank Account', 'description' => '', 'add_member' => false ],
            [ 'action' => 'bankaccount.my.extract',            'name' => 'Extract of My Bank Account', 'description' => '', 'add_member' => true ],


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
