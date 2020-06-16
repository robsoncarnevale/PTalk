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
    /**
     * List
     *
     * @author Davi Souto
     * @since 13/06/2020
     */
    function List(Request $request)
    {
        $car_brands = CarBrand::select()
            ->where('club_code', getClubCode())
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
        $car_brand = CarBrand::select()
            ->where('club_code', getClubCode())
            ->where('id', $car_brand_id)
            ->first();
        
        if (! $user)
            return response()->json([ 'status' => 'error', 'message' => __('car_brands.not-found') ]);

        return response()->json([ 'status' => 'success', 'data' => $car_brand ]);
    }

}
