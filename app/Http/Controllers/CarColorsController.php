<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CarColor;

/**
 * Car Colors Controller
 *
 * @author Davi Souto
 * @since 13/05/2020
 */
class CarColorsController extends Controller
{
    protected $only_admin = false;
    protected $ignore_routes = [
        'car.colors.list',
        'car.colors.get',
    ];

    /**
     * List
     *
     * @author Davi Souto
     * @since 13/06/2020
     */
    function List(Request $request)
    {
        $car_colors = CarColor::select('id', 'name', 'value')
            ->where('club_code', getClubCode())
            ->orderBy('name')
            ->get();

        return response()->json([ 'status' => 'success', 'data' => $car_colors ]);
    }

    /**
     * Get
     *
     * @author Davi Souto
     * @since 13/06/2020
     */
    function Get(Request $request, $car_color_id)
    {
        $car_color = CarColor::select('id', 'name', 'value')
            ->where('club_code', getClubCode())
            ->where('id', $car_color_id)
            ->first();
        
        if (! $car_color)
            return response()->json([ 'status' => 'error', 'message' => __('car_color.not-found') ]);

        return response()->json([ 'status' => 'success', 'data' => $car_color ]);
    }

}
