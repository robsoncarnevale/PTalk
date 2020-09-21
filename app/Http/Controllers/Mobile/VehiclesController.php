<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Requests\MyVehicleRequest;

use App\Models\Vehicle;

/**
 * Mobile Vehicles Controller
 *
 * @author Davi Souto
 * @since 06/08/2020
 */
class VehiclesController extends Controller
{
    /**
     * List vehicles
     *
     * @author Davi Souto
     * @since 06/08/2020
     */
    public function List(Request $request, $page = 1)
    {
        return (new \App\Http\Controllers\VehiclesController())->List($request);
    }

    /**
     * Get vehicle
     *
     * @author Davi Souto
     * @since 06/08/2020
     */
    public function Get(Request $request, $vehicle_id)
    {
        return (new \App\Http\Controllers\VehiclesController())->Get($request, $vehicle_id);
    }
    /**
     * List my vehicles
     *
     * @author Davi Souto
     * @since 06/08/2020
     */
    function ListMyVehicles(Request $request)
    {
        return (new \App\Http\Controllers\VehiclesController())->ListMyVehicles($request);
    }

    /**
     * Get my vehicle
     *
     * @author Davi Souto
     * @since 06/08/2020
     */
    function GetMyVehicle(MyVehicleRequest $request, $vehicle_id)
    {
        return (new \App\Http\Controllers\VehiclesController())->GetMyVehicle($request, $vehicle_id);
    }

     /**
     * Create my vehicle
     *
     * @author Davi Souto
     * @since 06/08/2020
     */
    public function CreateMyVehicle(MyVehicleRequest $request)
    {
        return (new \App\Http\Controllers\VehiclesController())->CreateMyVehicle($request);
    }

    /**
     * Update my vehicle
     *
     * @author Davi Souto
     * @since 06/08/2020
     */
    public function UpdateMyVehicle(MyVehicleRequest $request, Vehicle $vehicle)
    {
        return (new \App\Http\Controllers\VehiclesController())->UpdateMyVehicle($request, $vehicle);
    }

    /**
     * Delete my vehicle
     * 
     * @author Davi Souto
     * @since 06/08/2020
     */
    public function DeleteMyVehicle(MyVehicleRequest $request, Vehicle $vehicle)
    {
        return (new \App\Http\Controllers\VehiclesController())->DeleteMyVehicle($request, $vehicle);
    }

    /**
     * Upload photo to my vehicle
     * @since 20/09/2020
     */
    public function UploadMyVehiclePhoto(Request $request, Vehicle $vehicle)
    {
        return (new \App\Http\Controllers\VehiclesController())->UploadMyVehiclePhoto($request, $vehicle);
    }

    /**
     * Delete photo on my vehicle
     * @since 20/09/2020
     */
    public function DeteleMyVehiclePhoto(Request $request, Vehicle $vehicle, VehiclePhoto $vehicle_photo)
    {
        return (new \App\Http\Controllers\VehiclesController())->DeteleMyVehiclePhoto($request, $vehicle, $vehicle_photo);
    }
}
