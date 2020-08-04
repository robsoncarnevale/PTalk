<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;

use App\Models\CarBrand;

/**
 * Mobile Car Brands Controller
 *
 * @author Davi Souto
 * @since 03/08/2020
 */
class CarBrandsController extends Controller
{
    /**
     * List
     *
     * @author Davi Souto
     * @since 03/08/2020
     */
    function List(Request $request)
    {
        return (new \App\Http\Controllers\CarBrandsController())->List($request);
    }

    /**
     * Get
     *
     * @author Davi Souto
     * @since 03/08/2020
     */
    function Get(Request $request, $car_brand_id)
    {
        return (new \App\Http\Controllers\CarBrandsController())->Get($request, $car_brand_id);
    }

}
