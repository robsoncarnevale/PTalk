<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;

use App\Models\CarColor;

/**
 * Mobile Car Colors Controller
 *
 * @author Davi Souto
 * @since 03/08/2020
 */
class CarColorsController extends Controller
{
    /**
     * List
     *
     * @author Davi Souto
     * @since 03/08/2020
     */
    function List(Request $request)
    {
        return (new \App\Http\Controllers\CarColorsController())->List($request);
    }

    /**
     * Get
     *
     * @author Davi Souto
     * @since 03/08/2020
     */
    function Get(Request $request, $car_color_id)
    {
        return (new \App\Http\Controllers\CarColorsController())->Get($request, $car_color_id);
    }

}
