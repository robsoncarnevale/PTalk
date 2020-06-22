<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Vehicle;

use DB;
use Exception;

/**
 * Vehicle Controller
 *
 * @author Davi Souto
 * @since 19/06/2020
 */
class VehiclesController extends Controller
{
    protected $only_admin = false;

    /**
     * List vehicles
     *
     * @author Davi Souto
     * @since 19/06/2020
     */
    public function List(Request $request, $page = 1)
    {
        $vehicles = Vehicle::select()
            ->with('user:id,name,photo,type', 'car_model:id,name,car_brand_id', 'car_model.car_brand:id,name', 'car_color:id,name')
            ->whereHas('user', function($q){
                $q->where('deleted', false)
                  ->where('active', true)
                  ->where('approval_status', 'approved')
                  ->where('club_code', getClubCode())
                  ->where('id', '<>', auth()->guard()->user()->id);
            })
            ->jsonPaginate(25, 3);

        return response()->json([ 'status' => 'success', 'data' => $vehicles ]);
    }

    /**
     * Get vehicle
     *
     * @author Davi Souto
     * @since 19/06/2020
     */
    public function Get(Request $request, $vehicle_id)
    {
        $vehicle = Vehicle::select()
            ->with('user:id,name,photo,type', 'car_model:id,name,car_brand_id', 'car_color:id,name')
            ->whereHas('user', function($q){
                $q->where('deleted', false)
                  ->where('active', true)
                  ->where('approval_status', 'approved')
                  ->where('club_code', getClubCode());
            })
            ->where('id', $vehicle_id)
            ->first();

        if (! $vehicle)
            return response()->json([ 'status' => 'error', 'message' => __('vehicles.not-found') ]);


        return response()->json([ 'status' => 'success', 'data' => $vehicle ]);
    }
}
