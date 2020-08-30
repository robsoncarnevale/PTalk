<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CarModel;
use App\Models\CarBrand;
use App\Http\Resources\CarModel as CarModelResource;
use App\Http\Resources\CarModelCollection;

/**
 * Car Models Controller
 *
 * @author Davi Souto
 * @since 13/05/2020
 */
class CarModelsController extends Controller
{
    protected $only_admin = false;
    protected $ignore_routes = [
        'car.models.list',
        'car.models.get',
        'car.models.all',
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

        $car_models = CarModel::select()
            ->where('club_code', $club_code)
            ->orderBy('name')
            ->get();

        return response()->json([ 'status' => 'success', 'data' => (new CarModelCollection($car_models)) ]);
    }

    /**
     * Get
     *
     * @author Davi Souto
     * @since 13/06/2020
     */
    function Get(Request $request, $car_model_id)
    {
        $club_code = getClubCode();

        if ($request->get('club_code')) {
            $club_code = $request->get('club_code');
        }

        $car_model = CarModel::select()
            ->where('club_code', $club_code)
            ->where('id', $car_model_id)
            ->first();
        
        if (! $car_model)
            return response()->json([ 'status' => 'error', 'message' => __('car_models.not-found') ]);

        return response()->json([ 'status' => 'success', 'data' => (new CarModelResource($car_model)) ]);
    }

    /**
     * List all models with car brands
     *
     * @author Davi Souto
     * @since 14/06/2020
     */
    function ListAllModelsWithCarBrands(Request $request)
    {
        $club_code = getClubCode();

        if ($request->get('club_code')) {
            $club_code = $request->get('club_code');
        }

        $car_models = CarBrand::select('id', 'name')
            ->where('club_code', $club_code)
            ->with('car_models:id,name,car_brand_id')
            ->orderBy('name')
            ->get()
            ->toArray();

        foreach($car_models as $i_car_brands => $car_brands){
            usort($car_models[$i_car_brands]['car_models'], function($item1, $item2){
                return $item1['name'] <=> $item2['name'];
            });

            // $car_models[$i_car_brands]['car_models'] = collect($car_brands['car_models'])->sortBy('name');
        }

        return response()->json([ 'status' => 'success', 'data' => $car_models ]);
    }

}
