<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Vehicle;
use App\Models\User;
use App\Http\Requests\VehicleRequest;
use App\Http\Requests\MyVehicleRequest;

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
            ->with('user:id,name,photo,type', 'car_model:id,name,car_brand_id,picture', 'car_model.car_brand:id,name', 'car_color:id,name')
            ->whereHas('user', function($q){
                $q->where('deleted', false)
                  ->where('active', true)
                  ->where('approval_status', 'approved')
                  ->where('club_code', getClubCode())
                  ->where('id', '<>', User::getAuthenticatedUserId());
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
            ->with('user:id,name,photo,type', 'car_model:id,name,car_brand_id,picture', 'car_color:id,name')
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

    /**
     * Create vehicle
     *
     * @author Davi Souto
     * @since 05/08/2020
     */
    public function Create(VehicleRequest $request)
    {
        $vehicle = new Vehicle();

        $vehicle->fill($request->all());
        $vehicle->club_code = getClubCode();
        $vehicle->save();

        return response()->json([ 'status' => 'success', 'data' => $vehicle ]);
    }

    /**
     * Update vehicle
     *
     * @author Davi Souto
     * @since 05/08/2020
     */
    public function Update(VehicleRequest $request, Vehicle $vehicle)
    {
        $this->validateClub($vehicle->club_code, 'vehicle');
        
        $vehicle->fill($request->all());
        $vehicle->save();

        return response()->json([ 'status' => 'success', 'data' => $vehicle ]);
    }

    /**
     * Delete vehicle
     * 
     * @author Davi Souto
     * @since 05/08/2020
     */
    public function Delete(VehicleRequest $request, Vehicle $vehicle)
    {
        $this->validateClub($vehicle->club_code, 'vehicle');

        $vehicle->deleted = true;
        $vehicle->save();

        return response()->json([ 'status' => 'success', 'data' => $vehicle ]);
    }

    /**
     * List my vehicles
     * 
     * @author Davi Souto
     * @since 05/08/2020
     */
    public function ListMyVehicles(Request $request)
    {
        $vehicles = Vehicle::select()
            ->with('car_model:id,name,car_brand_id,picture', 'car_model.car_brand:id,name', 'car_color:id,name')
            ->where('user_id', User::getAuthenticatedUserId())
            ->jsonPaginate(25, 3);

        return response()->json([ 'status' => 'success', 'data' => $vehicles ]);
    }

    /**
     * Get vehicle
     *
     * @author Davi Souto
     * @since 19/06/2020
     */
    public function GetMyVehicle(MyVehicleRequest $request, $vehicle_id)
    {
        $vehicle = Vehicle::select()
            ->with('car_model:id,name,car_brand_id,picture', 'car_color:id,name')
            ->where('user_id', User::getAuthenticatedUserId())
            ->where('id', $vehicle_id)
            ->first();

        if (! $vehicle)
            return response()->json([ 'status' => 'error', 'message' => __('vehicles.not-found') ]);


        return response()->json([ 'status' => 'success', 'data' => $vehicle ]);
    }

    /**
     * Create my vehicle
     *
     * @author Davi Souto
     * @since 05/08/2020
     */
    public function CreateMyVehicle(MyVehicleRequest $request)
    {
        $vehicle = new Vehicle();

        $vehicle->fill($request->all());
        $vehicle->user_id = User::getAuthenticatedUserId();
        $vehicle->club_code = getClubCode();
        $vehicle->save();

        return response()->json([ 'status' => 'success', 'data' => $vehicle ]);
    }

    /**
     * Update vehicle
     *
     * @author Davi Souto
     * @since 05/08/2020
     */
    public function UpdateMyVehicle(MyVehicleRequest $request, Vehicle $vehicle)
    {
        $this->validateClub($vehicle->club_code, 'vehicle');

        if ($vehicle->user_id != User::getAuthenticatedUserId())
            abort(401);
        
        $vehicle->fill($request->all());
        $vehicle->user_id = User::getAuthenticatedUserId();
        $vehicle->save();

        return response()->json([ 'status' => 'success', 'data' => $vehicle ]);
    }

    /**
     * Delete vehicle
     * 
     * @author Davi Souto
     * @since 05/08/2020
     */
    public function DeleteMyVehicle(MyVehicleRequest $request, Vehicle $vehicle)
    {
        $this->validateClub($vehicle->club_code, 'vehicle');

        if ($vehicle->user_id != User::getAuthenticatedUserId())
            abort(401);

        $vehicle->deleted = true;
        $vehicle->save();

        return response()->json([ 'status' => 'success', 'data' => $vehicle ]);
    }
}
