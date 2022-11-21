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
        $this->cratePrivilege('product.logs','Get logs all products');
        $this->cratePrivilege('product.create_log','Create log products');
        $this->cratePrivilege('monthlypayment.index','Monthlypayment index');
        $this->cratePrivilege('monthlypayment.registration','Monthlypayment Registration');
        $this->cratePrivilege('monthlypayment.store','Monthlypayment Store');
        $this->cratePrivilege('monthlypayment.fare','Monthlypayment Fare');
        $this->cratePrivilege('monthlypayment.edit','Monthlypayment Edit');
        $this->cratePrivilege('monthlypayment.delete','Monthlypayment Delete');
        $this->cratePrivilege('monthlypayment.parameters','Monthlypayment parameters');
        $this->cratePrivilege('monthlypayment.pendencies','Monthlypayment pendencies');
        $this->cratePrivilege('invoice.index','Invoice index');
        $this->cratePrivilege('invoice.participation','Invoice participation');
        $this->cratePrivilege('invoice.extract','Invoice extract');
        $this->cratePrivilege('philanthropy.index','Philanthropy index');
        $this->cratePrivilege('philanthropy.charity','philanthropy charity');
        $this->cratePrivilege('sponsorship.index','Sponsorship index');
        $this->cratePrivilege('sponsorship.sponsors','Sponsorship sponsors');
        $this->cratePrivilege('sponsorship.partner','Sponsorship partner');
        $this->cratePrivilege('charge.getall','Get all Charges');
        $this->cratePrivilege('shopcart.getall','Get all products on cart');
        $this->cratePrivilege('shopcart.getopenedcart','Get opened cart by user id');
        $this->cratePrivilege('product.addtocart','Put product on cart by user id');
        $this->cratePrivilege('product.removetocart','Remove product on cart by user id');
        $this->cratePrivilege('product.gotocart','Get products on cart');
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
