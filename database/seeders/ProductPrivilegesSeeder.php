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
        //Se precisar criar um registro novo, basta adiciona-lo e executar o seed
        //php artisan db:seed --class=ProductPrivilegesSeeder
        $this->cratePrivilege('product.index','List Club Store');
        $this->cratePrivilege('product.ad_registration','Product Registration');
        $this->cratePrivilege('product.inactive_ads','Inactive Ads');
        $this->cratePrivilege('product.sales_history','Sales History');
        $this->cratePrivilege('product.list','List');
        $this->cratePrivilege('product.discount_coupon','Discount Coupon');
        $this->cratePrivilege('product.store','Save Product');
        $this->cratePrivilege('users.findbyemail','Find By Email');
        $this->cratePrivilege('product.edit','Edit Product');
        $this->cratePrivilege('product.get_product','Get Product');
        $this->cratePrivilege('product.listall','List All');
        $this->cratePrivilege('product.delete','Product Delete');
        $this->cratePrivilege('product.deactivate','Product Deactivate');
        $this->cratePrivilege('product.activate','Product Activate');
        $this->cratePrivilege('monthlypayment.index','Monthlypayment index');
        $this->cratePrivilege('monthlypayment.parameters','Monthlypayment parameters');
        $this->cratePrivilege('monthlypayment.pendencies','Monthlypayment pendencies');
    }

    private function cratePrivilege($action,$name) {
        $privilege = new Privilege();
        if ($privilege->getByAction($action)->count() < 1) {
            $privilege->action = $action;
            $privilege->name = $name;
            $privilege->created_at = date('Y-m-d');
            $privilege->updated_at = date('Y-m-d');
            $privilege->save();
        }
    }
}
