<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd("Index ProductController");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {       
            if ($request->input('product_id')) {
                $product = Product::find($request->input('product_id'));

                if ($request->input('img_url') == null) {
                    $request->merge(['img_url' => $product->img_url]);
                }

                $update = $product->update($request->all());

                if ($update) {
                    $msg = "updated";
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Erro ao atualizar o pedido'
                    ], 500);
                }
            } else {
                Product::create($request->all());
                $msg = "created";
            }

            return response()->json([
                'status' => 'success',
                'message' => __('product.'.$msg)
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        dd("show ProductController");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        dd("edit ProductController");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        dd("update ProductController");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_product)
    {
        try
        {       
            $product = new Product();
            $product = Product::find($id_product);
            $product->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('product.deleted')
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function adRegistration() {
        dd("adRegistration");
    }

    public function get($id_product) {
        $product = Product::find($id_product);
        return $product;
    }

    public function inactiveAds() {
        $list = Product::where('active',0)->get();
        return $list;
    }

    public function deactivate($id) {
        try
        {
            $product = Product::select()
            ->where('id', $id)
            ->get();
            
            if($product)
            {
                $product = $product[0];
                $product->active = 0;
                $product->save();

            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro ao desativar o produto'
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'message' => __('product.deactivated')
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function activate($id) {
        try
        {
            $product = Product::select()
            ->where('id', $id)
            ->get();
            
            if($product)
            {
                $product = $product[0];
                $product->active = 1;
                $product->save();

            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro ao ativar o produto'
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'message' => __('product.activate')
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function salesHistory() {
        dd("salesHistory");
    }

    public function list() {
        
        $list = Product::where('active',1)->get();

        return $list;

    }

    public function discountCoupon() {
        dd("discountCoupon");
    }
}
