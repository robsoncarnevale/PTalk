<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Privilege;

class ProductPrivilegesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $privilege = new Privilege();
        $privilege->action = 'product.index';
        $privilege->name = 'List Club Store';
        $privilege->created_at = date('Y-m-d');
        $privilege->updated_at = date('Y-m-d');
        $privilege->save();

        $privilege = new Privilege();
        $privilege->action = 'product.ad_registration';
        $privilege->name = 'Product Registration';
        $privilege->created_at = date('Y-m-d');
        $privilege->updated_at = date('Y-m-d');
        $privilege->save();

        $privilege = new Privilege();
        $privilege->action = 'product.inactive_ads';
        $privilege->name = 'Inactive Ads';
        $privilege->created_at = date('Y-m-d');
        $privilege->updated_at = date('Y-m-d');
        $privilege->save();

        $privilege = new Privilege();
        $privilege->action = 'product.sales_history';
        $privilege->name = 'Sales History';
        $privilege->created_at = date('Y-m-d');
        $privilege->updated_at = date('Y-m-d');
        $privilege->save();

        $privilege = new Privilege();
        $privilege->action = 'product.list';
        $privilege->name = 'List';
        $privilege->created_at = date('Y-m-d');
        $privilege->updated_at = date('Y-m-d');
        $privilege->save();

        $privilege = new Privilege();
        $privilege->action = 'product.discount_coupon';
        $privilege->name = 'Discount Coupon';
        $privilege->created_at = date('Y-m-d');
        $privilege->updated_at = date('Y-m-d');
        $privilege->save();

        $privilege = new Privilege();
        $privilege->action = 'product.store';
        $privilege->name = 'Save Product';
        $privilege->created_at = date('Y-m-d');
        $privilege->updated_at = date('Y-m-d');
        $privilege->save();

        $privilege = new Privilege();
        $privilege->action = 'users.findbyemail';
        $privilege->name = 'Find By Email';
        $privilege->created_at = date('Y-m-d');
        $privilege->updated_at = date('Y-m-d');
        $privilege->save();
    }
}
