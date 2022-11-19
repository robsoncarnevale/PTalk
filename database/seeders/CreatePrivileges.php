<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
            [ 'action' => 'users.me.update',                    'name' => 'Update My User Profile', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.me.address',                   'name' => 'List My Addresses', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.me.address.create',            'name' => 'Create My Address', 'description' => '', 'add_member' => true ],
            [ 'action' => 'users.me.address.update',            'name' => 'Update My Address', 'description' => '', 'add_member' => true ],

            [ 'action' => 'users.administrators.list',          'name' => 'List administrators', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.administrators.update',        'name' => 'Read Administrator', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.administrators.save',          'name' => 'Update Administrator', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.administrators.create',        'name' => 'Create Administrator', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.administrators.store',         'name' => 'Salvar Administrator', 'description' => '', 'add_member' => false ],

            [ 'action' => 'users.profile.view',                 'name' => 'View User Profile', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.history',                      'name' => 'View User History', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.history-approval',     'name' => 'User History Approval', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.list',                 'name' => 'List Members', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.get',                  'name' => 'Read Member', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.create',               'name' => 'Create Member', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.update',               'name' => 'Update Member', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.delete',               'name' => 'Delete Member', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.approval-status.set',  'name' => 'Set Member Approval Status', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.change-type',                  'name' => 'Change User Type', 'description' => '', 'add_member' => false ],
            [ 'action' => 'users.members.waiting-approval',     'name' => 'List members awaiting approval', 'description' => '', 'add_member' => false ],

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
            [ 'action' => 'users.findbyemail',                  'name' => 'Fid By Email', 'description' => '', 'add_member' => false ],

            [ 'action' => 'privileges.list', 'name' => 'List Permissions', 'description' => '', 'add_member' => false ],
            
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
            [ 'action' => 'events.start',                       'name' => 'Start Event', 'description' => '', 'add_member' => false ],
            [ 'action' => 'events.cancel',                      'name' => 'Cancel Event', 'description' => '', 'add_member' => false ],
            [ 'action' => 'events.subscribe',                   'name' => 'Subscribe in Event', 'description' => '', 'add_member' => true ],
            [ 'action' => 'events.unsubscribe',                 'name' => 'Unsubscribe on Event', 'description' => '', 'add_member' => true ],
            [ 'action' => 'events.members',                     'name' => 'List Members of Event', 'description' => '', 'add_member' => false ],
            [ 'action' => 'events.print',                       'name' => 'Print Event', 'description' => '', 'add_member' => false ],

            [ 'action' => 'events.address.create',              'name' => 'Create Event Address', 'description' => '', 'add_member' => false ],
            [ 'action' => 'events.address.update',              'name' => 'Update Event Address', 'description' => '', 'add_member' => false ],
            [ 'action' => 'events.address.delete',              'name' => 'Delete Event Address', 'description' => '', 'add_member' => false ],

            [ 'action' => 'blacklist.list',                     'name' => 'List Blacklist', 'description' => '', 'add_member' => false ], 
            [ 'action' => 'blacklist.get',                      'name' => 'Get Blacklist', 'description' => '', 'add_member' => false ],    
            [ 'action' => 'blacklist.create',                   'name' => 'Create Blacklist', 'description' => '', 'add_member' => false ],      
            [ 'action' => 'blacklist.update',                   'name' => 'Update Blacklist', 'description' => '', 'add_member' => false ],     

            [ 'action' => 'config.data',                        'name' => 'Config Get Data', 'description' => '', 'add_member' => false ],
            [ 'action' => 'config.save',                        'name' => 'Save Config Data', 'description' => '', 'add_member' => false ],

            [ 'action' => 'bank-accounts.index',                'name' => 'List All Bank Accounts', 'description' => '', 'add_member' => false ],
            [ 'action' => 'bank-accounts.my',                   'name' => 'My Extract Bank Account', 'description' => '', 'add_member' => false ],
            [ 'action' => 'bank-accounts.extract',              'name' => 'Member Extract Bank Account', 'description' => '', 'add_member' => false ],
            [ 'action' => 'bank-accounts.club.extract',         'name' => 'Club Extract Bank Account', 'description' => '', 'add_member' => false ],
            [ 'action' => 'bank-accounts.transfers',            'name' => 'Transfer Bank Account', 'description' => '', 'add_member' => false ],
            [ 'action' => 'bank-accounts.transfers.store',      'name' => 'Effective Transfer Bank Account', 'description' => '', 'add_member' => false ],
            [ 'action' => 'bank-accounts.detail',               'name' => 'Detail Bank Account', 'description' => '', 'add_member' => false ],
            [ 'action' => 'bank-accounts.load',                 'name' => 'Load Bank Account', 'description' => '', 'add_member' => false ],
            [ 'action' => 'bank-accounts.load.store',           'name' => 'Load Bank Account (Store)', 'description' => '', 'add_member' => false ],

            // [ 'action' => 'bankaccount.list',                  'name' => 'List All Bank Accounts', 'description' => '', 'add_member' => false ],
            // [ 'action' => 'bankaccount.extract',               'name' => 'Extract of User Bank Account', 'description' => '', 'add_member' => false ],
            // [ 'action' => 'bankaccount.my.extract',            'name' => 'Extract of My Bank Account', 'description' => '', 'add_member' => true ],
            // [ 'action' => 'bankaccount.find',                  'name' => 'Find a Bank Account', 'description' => '', 'add_member' => true ],
            // [ 'action' => 'bankaccount.launch.debit',          'name' => 'Launch Debit on Bank Account', 'description' => '', 'add_member' => false ],
            // [ 'action' => 'bankaccount.launch.credit',         'name' => 'Launch Credit on Bank Account', 'description' => '', 'add_member' => false ],

            // [ 'action' => 'club.bankaccount.extract',           'name' => 'Extract of Club Bank Account', 'description' => '', 'add_member' => false ],      
            // [ 'action' => 'club.bankaccount.launch.debit',      'name' => 'Launch Debit on Club Bank Account', 'description' => '', 'add_member' => false ],        
            // [ 'action' => 'club.bankaccount.launch.credit',     'name' => 'Launch Credit on Club Bank Account', 'description' => '', 'add_member' => false ],         
            // [ 'action' => 'club.bankaccount.data',              'name' => 'Get Club Bank Account Data', 'description' => '', 'add_member' => false ],

            [ 'action' => 'products.index',                   'name' => 'List Club Store', 'description' => '', 'add_member' => false ],
            [ 'action' => 'products.ad_registration',         'name' => 'Product Registration', 'description' => '', 'add_member' => false ],
            [ 'action' => 'products.instactive_ads',          'name' => 'Inactive Products', 'description' => '', 'add_member' => false ],
            [ 'action' => 'products.sales_history',           'name' => 'Sales History', 'description' => '', 'add_member' => false ],
            [ 'action' => 'products.list',                    'name' => 'Products List', 'description' => '', 'add_member' => false ],
            [ 'action' => 'products.discount_coupon',         'name' => 'Discount Coupon', 'description' => '', 'add_member' => false ],

            [ 'action' => 'club.status',                        'name' => 'Read club status', 'description' => '', 'add_member' => true ],
            [ 'action' => 'club.data',                          'name' => 'Club Details', 'description' => '', 'add_member' => true ],
            [ 'action' => 'club.data.store',                    'name' => 'Save Club Details', 'description' => '', 'add_member' => true ],

            [ 'action' => 'product.index', 'name' => 'List Club Store', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.ad_registration', 'name' => 'Product Registration', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.inactive_ads', 'name' => 'Inactive Ads', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.sales_history', 'name' => 'Sales History', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.list', 'name' => 'List', 'description' => '', 'add_member' => true ],
            [ 'action' => 'product.discount_coupon', 'name' => 'Discount Coupon', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.store', 'name' => 'Save Product', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.edit', 'name' => 'Edit Product', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.get_product', 'name' => 'Get Product', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.listall', 'name' => 'List All', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.delete', 'name' => 'Product Delete', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.deactivate', 'name' => 'Product Deactivate', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.activate', 'name' => 'Product Activate', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.logs', 'name' => 'Get logs all products', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.create_log', 'name' => 'Get logs all products', 'description' => '', 'add_member' => false ],
            [ 'action' => 'product.addtocart', 'name' => 'Add to cart', 'description' => '', 'add_member' => true ],

            [ 'action' => 'monthlypayment.index', 'name' => 'Monthlypayment index', 'description' => '', 'add_member' => false ],
            [ 'action' => 'monthlypayment.registration', 'name' => 'Monthlypayment Registration', 'description' => '', 'add_member' => false ],
            [ 'action' => 'monthlypayment.store', 'name' => 'Monthlypayment Store', 'description' => '', 'add_member' => false ],
            [ 'action' => 'monthlypayment.fare', 'name' => 'Monthlypayment Fare', 'description' => '', 'add_member' => false ],
            [ 'action' => 'monthlypayment.edit', 'name' => 'Monthlypayment Edit', 'description' => '', 'add_member' => false ],
            [ 'action' => 'monthlypayment.delete', 'name' => 'Monthlypayment Delete', 'description' => '', 'add_member' => false ],
            [ 'action' => 'monthlypayment.parameters', 'name' => 'Monthlypayment parameters', 'description' => '', 'add_member' => false ],
            [ 'action' => 'monthlypayment.pendencies', 'name' => 'Monthlypayment pendencies', 'description' => '', 'add_member' => false ],

            [ 'action' => 'invoice.index', 'name' => 'Invoice index', 'description' => '', 'add_member' => false ],
            [ 'action' => 'invoice.participation', 'name' => 'Invoice participation', 'description' => '', 'add_member' => false ],
            [ 'action' => 'invoice.extract', 'name' => 'Invoice extract', 'description' => '', 'add_member' => false ],

            [ 'action' => 'philanthropy.index', 'name' => 'philanthropy index', 'description' => '', 'add_member' => false ],
            [ 'action' => 'philanthropy.charity', 'name' => 'philanthropy charity', 'description' => '', 'add_member' => false ],

            [ 'action' => 'sponsorship.index', 'name' => 'Sponsorship index', 'description' => '', 'add_member' => false ],
            [ 'action' => 'sponsorship.sponsors', 'name' => 'Sponsorship sponsors', 'description' => '', 'add_member' => false ],
            [ 'action' => 'sponsorship.partner', 'name' => 'Sponsorship partner', 'description' => '', 'add_member' => false ],

            [ 'action' => 'charge.getall', 'name' => 'Get all charges', 'description' => '', 'add_member' => true ],

            [ 'action' => 'shopcart.getall', 'name' => 'Get all products on cart', 'description' => '', 'add_member' => true ],
            [ 'action' => 'shopcart.getopenedcart', 'name' => 'Get opened cart by user id', 'description' => '', 'add_member' => true ],
            
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
