<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;

use App\Models\CarModel;
use App\Models\CarBrand;

/**
 * Mobile Car Models Controller
 *
 * @author Davi Souto
 * @since 03/08/2020
 */
class CarModelsController extends Controller
{
    /**
     * List
     *
     * @author Davi Souto
     * @since 03/08/2020
     */
    function List(Request $request)
    {
        return (new \App\Http\Controllers\CarModelsController())->List($request);
    }

    /**
     * Get
     *
     * @author Davi Souto
     * @since 03/08/2020
     */
    function Get(Request $request, $car_model_id)
    {
        return (new \App\Http\Controllers\CarModelsController())->Get($request, $car_model_id);
    }

    /**
     * List all models with car brands
     *
     * @author Davi Souto
     * @since 03/08/2020
     */
    function ListAllModelsWithCarBrands(Request $request)
    {
        return (new \App\Http\Controllers\CarModelsController())->ListAllModelsWithCarBrands($request);
    }

}
