<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CarBrand;

/**
 * Car Brands Controller
 *
 * @author Davi Souto
 * @since 13/05/2020
 */
class CarBrandsController extends Controller
{
    protected $only_admin = false;
    protected $ignore_routes = [
        'car.brands.list',
        'car.brands.get',
    ];

    /**
     * List
     *
     * @author Davi Souto
     * @since 13/06/2020
     */
    function List(Request $request)
    {
        $club_code = getClubCode();

        if ($request->get('club_code')) {
            $club_code = $request->get('club_code');
        }

        $car_brands = CarBrand::select()
            ->where('club_code', $club_code)
            ->orderBy('name')
            ->get();

        return response()->json([ 'status' => 'success', 'data' => $car_brands ]);
    }

    /**
     * Get
     *
     * @author Davi Souto
     * @since 13/06/2020
     */
    function Get(Request $request, $car_brand_id)
    {
        $club_code = getClubCode();

        if ($request->get('club_code')) {
            $club_code = $request->get('club_code');
        }

        $car_brand = CarBrand::select()
            ->where('club_code', $club_code)
            ->where('id', $car_brand_id)
            ->first();
        
        if (! $car_brand)
            return response()->json([ 'status' => 'error', 'message' => __('car_brands.not-found') ]);

        return response()->json([ 'status' => 'success', 'data' => $car_brand ]);
    }

}
