<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopCart;
use App\Models\Product;
use App\Models\BankAccount;

class ShopCartsController extends Controller
{
    public function getAll($user_id) {
        $products = ShopCart::
                    join('products as p','p.id','shop_carts.product_id')
                    ->select('p.id','shop_carts.created_at as data','p.name','p.description','shop_carts.quantity', 'shop_carts.value', 'p.img_url')
                    ->where('shop_carts.state','opened')
                    ->where('shop_carts.user_id',$user_id)
                    ->get()->toArray();
        return response()->json([
            'status' => 'success',
            'data' => $products
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

        $shop_cart = ShopCart::where('user_id',$user_id)
                            ->where('product_id',$product_id)
                            ->where('state','opened')
                            ->get();
        if ($shop_cart->count() > 0) {
            $shop_cart_return = ShopCart::select()
                                ->where( 'id', $shop_cart[0]['id'])
                                ->first();
            $shop_cart_return->quantity += $quantity;
            $shop_cart_return->value += $quantity*$value;
            $shop_cart_return->update();
        } else {
            $shop_cart_return = new ShopCart();
            $shop_cart_return->user_id = $user_id;
            $shop_cart_return->product_id = $product_id;
            $shop_cart_return->quantity = $quantity;
            $shop_cart_return->value = $value*$quantity;
            $shop_cart_return->state = ShopCart::OPENED;
            $shop_cart_return->save();
        }

        return response()->json([
            'status' => 'success',
            'data' => $shop_cart_return
        ]);
    }

    public function removeToCart($product_id,$user_id,$quantity) {

        //['user_id', 'product_id', 'quantity', 'value', 'state' ];
        $value = Product::where('id',$product_id)->select('value')->get()[0]['value'];
        $shop_cart_get = ShopCart::where('user_id',$user_id)
                            ->where('product_id',$product_id)
                            ->where('state','opened')
                            ->first();
        if ($shop_cart_get->quantity <= $quantity) {
            $shop_cart_get->delete();
        } else {
            $shop_cart_get->quantity -= $quantity;
            $shop_cart_get->value -= $quantity*$value;
            $shop_cart_get->update();
        }

        return response()->json([
            'status' => 'success',
            'data' => $shop_cart_get
        ]);
    }

    public function getProductsOnCartOpened() {
        return response()->json([
            'status' => 'success',
            'data' => []
        ]);
    }

    public function getWallet($user_id) {

        try {
            
            $wallet = BankAccount::getBankAccountUser($user_id);
            
            if (count($wallet) < 1) {
                return response()->json([ 'status' => 'error', 'message' => "Carteira não encontrada"]);
            }
            
        } catch (\Exception $e) {
            return response()->json([ 'status' => 'error', 'message' => "Erro inesperado wallet"]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $wallet
        ]);    
        
    }
}
