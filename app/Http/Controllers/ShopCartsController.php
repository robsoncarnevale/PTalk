<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopCart;
use App\Models\Product;

class ShopCartsController extends Controller
{
    public function getAll() {
        return response()->json([
            'status' => 'success',
            'data' => 1
        ]);
    }

    public function getOpenedCart($id) {
        $cont = ShopCart::where('user_id',$id)->where('state','opened')->get()->count();
        return response()->json([
            'status' => 'success',
            'data' => $cont
        ]);
    }

    public function addToCart($product_id,$user_id,$quantity) {

        //['user_id', 'product_id', 'quantity', 'value', 'state' ];
        $value = Product::where('id',$product_id)->select('value')->get()[0]['value'];

        $shopCart = new ShopCart();
        $shopCart->user_id = $user_id;
        $shopCart->product_id = $product_id;
        $shopCart->quantity = $quantity;
        $shopCart->value = $value;
        $shopCart->state = ShopCart::OPENED;
        $shopCart->save();

        return response()->json([
            'status' => 'success',
            'data' => $shopCart
        ]);
    }
}
