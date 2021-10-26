<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Vehicle;
use App\Models\VehiclePhoto;
use App\Models\User;
use App\Http\Requests\VehicleRequest;
use App\Http\Requests\MyVehicleRequest;

use App\Http\Resources\Vehicle as VehicleResource;
use App\Http\Resources\VehicleCollection;
use App\Http\Resources\VehiclePhoto as VehiclePhotoResource;

use Illuminate\Support\Facades\Storage;

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
        $filters = [];

        if ($request->has('filters')) {
            $filters = $request->get('filters');
        }

        $vehicles = Vehicle::select()
            ->with('user')
            ->whereHas('user', function($q) use ($filters){
                $q->where('deleted', false)
                  ->where('status', '<>', User::INACTIVE_STATUS)
                  ->where('status', '<>', User::BANNED_STATUS)
                  ->where('approval_status', 'approved')
                  ->where('club_code', getClubCode());
                //   ->where('id', '<>', User::getAuthenticatedUserId());

                // Filter by user name / nickname
                if (array_key_exists('name', $filters) && ! empty($filters['name'])) {
                    $q->where(function($q) use ($filters){
                        $name = str_replace(' ', '%', trim($filters['name']));

                        $q->whereRaw('LOWER(name) like ?', strtolower("%{$name}%"))
                          ->orWhereRaw('LOWER(nickname) like ?', strtolower("%{$name}%"));
                    });
                }
            })
            ->where('deleted', false);

        if ($request->has('filters')) {
            // Filter by car model
            if (array_key_exists('car_model_id', $filters) && ! empty($filters['car_model_id'])) {
                $vehicles->where('car_model_id', $filters['car_model_id']);
            }

            // Filter by carplate
            if (array_key_exists('carplate', $filters) && ! empty($filters['carplate'])) {
                $carplate = strtoupper(preg_replace("#[^0-9A-Z]#is", '', $filters['carplate']));

                $vehicles->whereRaw('carplate LIKE ?', '%'.strtoupper($carplate).'%');
            }
        }

        $vehicles = $vehicles->jsonPaginate(25, 3);

        return response()->json([ 'status' => 'success', 'data' => (new VehicleCollection($vehicles)) ]);
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
                  ->where('status', '<>', User::INACTIVE_STATUS)
                  ->where('status', '<>', User::BANNED_STATUS)
                  ->where('approval_status', 'approved')
                  ->where('club_code', getClubCode());
            })
            ->where('deleted', false)
            ->where('id', $vehicle_id)
            ->first();

        if (! $vehicle)
            return response()->json([ 'status' => 'error', 'message' => __('vehicles.not-found') ]);


        return response()->json([ 'status' => 'success', 'data' => (new VehicleResource($vehicle)) ]);
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
    
        if ($vehicle->carplate) {
            $vehicle->carplate = strtoupper(preg_replace("#[^0-9A-Z]#is", '', $vehicle->carplate));
   
            // Check if carplate is already registered
            $check_carplate = Vehicle::select('id')
                ->where('carplate', $vehicle->carplate)
                ->where('deleted', false)
                ->where('club_code', getClubCode())
                ->first();
        
            if ($check_carplate) {
                return response()->json([ 'status' => 'error', 'message' => __('vehicles.carplate-already-registered') ]);
            }
        }

        $vehicle->save();

        if ($request->has('photos')) {
            $vehicle->uploadPhotos($request->get('photos'));
        }

        // Pre-uploaded photos
        if ($request->has('add_photo')) {
            $photos = $request->get('add_photo');

            foreach($photos as $add_photo) {
                $add_photo = substr($add_photo, strpos($add_photo, getClubCode() . "/vehicle-photos"));

                $vehicle_photo = new VehiclePhoto();
                $vehicle_photo->club_code = getClubCode();
                $vehicle_photo->vehicle_id = $vehicle->id;
                $vehicle_photo->photo = $add_photo;
                $vehicle_photo->save();
            }
        }

        return response()->json([ 'status' => 'success', 'data' => (new VehicleResource($vehicle)), 'message' => __('vehicles.success-create') ]);
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

        if ($vehicle->deleted) {
            return abort(404);
        }
        
        $vehicle->fill($request->all());

        if ($vehicle->carplate) {
            $vehicle->carplate = strtoupper(preg_replace("#[^0-9A-Z]#is", '', $vehicle->carplate));

            // Check if carplate is already registered
            if ($request->has('carplate')) {
                $check_carplate = Vehicle::select('id')
                    ->where('carplate', $vehicle->carplate)
                    ->where('deleted', false)
                    ->where('club_code', getClubCode())
                    ->where('id', '<>', $vehicle->id)
                    ->first();
            
                if ($check_carplate) {
                    return response()->json([ 'status' => 'error', 'message' => __('vehicles.carplate-already-registered') ]);
                }
            }
        }

        
        $vehicle->save();

        return response()->json([ 'status' => 'success', 'data' => (new VehicleResource($vehicle)), 'message' => __('vehicles.success-update') ]);
    }

    /**
     * Delete vehicle
     * 
     * @author Davi Souto
     * @since 05/08/2020
     */
    public function Delete(Request $request, Vehicle $vehicle)
    {
        $this->validateClub($vehicle->club_code, 'vehicle');

        if ($vehicle->deleted) {
            return abort(404);
        }
        
        $vehicle->deleted = true;
        $vehicle->save();

        return response()->json([ 'status' => 'success', 'data' => (new VehicleResource($vehicle)), 'message' => __('vehicles.success-delete') ]);
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
            ->where('user_id', User::getAuthenticatedUserId())
            ->where('deleted', false);

        if (User::isMobile()) {
            $vehicles = $vehicles->get();

            return response()->json([ 'status' => 'success', 'data' => VehicleResource::collection($vehicles) ]);
        } else {
            $vehicles = $vehicles->jsonPaginate(25, 3);

            return response()->json([ 'status' => 'success', 'data' => (new VehicleCollection($vehicles)) ]);
        }

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
            ->where('deleted', false)
            ->first();

        if (! $vehicle)
            return response()->json([ 'status' => 'error', 'message' => __('vehicles.not-found') ]);


        return response()->json([ 'status' => 'success', 'data' => (new VehicleResource($vehicle)) ]);
    }

    /**
     * Create my vehicle
     *
     * @author Davi Souto
     * @since 05/08/2020
     */
    public function CreateMyVehicle(MyVehicleRequest $request)
    {
        $user = auth()->user();

        if($user->type == 'admin')
            return response()->json(['status' => 'error', 'message' => __('vehicles.error-create-administrator')]);

        $vehicle = new Vehicle();

        $vehicle->fill($request->all());
        $vehicle->user_id = User::getAuthenticatedUserId();
        $vehicle->club_code = getClubCode();

        if ($vehicle->carplate) {
            $vehicle->carplate = strtoupper(preg_replace("#[^0-9A-Z]#is", '', $vehicle->carplate));

            // Check if carplate is already registered
            $check_carplate = Vehicle::select('id')
                ->where('carplate', $vehicle->carplate)
                ->where('deleted', false)
                ->where('club_code', getClubCode())
                ->first();
            
            if ($check_carplate) {
                return response()->json([ 'status' => 'error', 'message' => __('vehicles.carplate-already-registered') ]);
            }
        }

        $vehicle->save();

        return response()->json([ 'status' => 'success', 'data' => (new VehicleResource($vehicle)), 'message' => __('vehicles.success-create') ]);
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

        if ($vehicle->user_id != User::getAuthenticatedUserId()) {
            abort(401);
        }

        if ($vehicle->deleted) {
            return abort(404);
        }
        
        $vehicle->fill($request->all());
        $vehicle->user_id = User::getAuthenticatedUserId();

        if ($vehicle->carplate) {
            $vehicle->carplate = strtoupper(preg_replace("#[^0-9A-Z]#is", '', $vehicle->carplate));

            // Check if carplate is already registered
            if ($request->has('carplate')) {
                $check_carplate = Vehicle::select('id')
                    ->where('carplate', $vehicle->carplate)
                    ->where('deleted', false)
                    ->where('club_code', getClubCode())
                    ->where('id', '<>', $vehicle->id)
                    ->first();
            
                if ($check_carplate) {
                    return response()->json([ 'status' => 'error', 'message' => __('vehicles.carplate-already-registered') ]);
                }
            }
        }

        $vehicle->save();

        return response()->json([ 'status' => 'success', 'data' => (new VehicleResource($vehicle)), 'message' => __('vehicles.success-update') ]);
    }

    /**
     * Delete vehicle
     * 
     * @author Davi Souto
     * @since 05/08/2020
     */
    public function DeleteMyVehicle(Request $request, Vehicle $vehicle)
    {
        $this->validateClub($vehicle->club_code, 'vehicle');

        if ($vehicle->user_id != User::getAuthenticatedUserId()) {
            abort(401);
        }

        if ($vehicle->deleted) {
            return abort(404);
        }

        $vehicle->deleted = true;
        $vehicle->save();

        return response()->json([ 'status' => 'success', 'data' => (new VehicleResource($vehicle)), 'message' => __('vehicles.success-delete') ]);
    }

    /**
     * Upload photo to vehicle
     * @since 28/09/2020
     */
    public function UploadVehiclePhoto(Request $request, Vehicle $vehicle)
    {
        $this->validateClub($vehicle->club_code, 'vehicle');

        if (! $request->has('photo')) {
            return response()->json([ 'status' => 'error', 'message' => __('vehicles.need-photo') ]);
        }

        $file = $request->file('photo');
        $upload_photo = Storage::disk('images')->putFile(getClubCode().'/vehicle-photos', $file);

        if (! $upload_photo){
            return response()->json([ 'status' => 'error', 'message' => __('vehicles.error-photo-upload') ]);
        }
            
        $vehicle_photo = new VehiclePhoto();
        $vehicle_photo->club_code = getClubCode();
        $vehicle_photo->vehicle_id = $vehicle->id;
        $vehicle_photo->photo = $upload_photo;
        $vehicle_photo->save();

        return response()->json([ 'status' => 'success', 'data' => (new VehiclePhotoResource($vehicle_photo)), 'message' => __('vehicles.success-photo-upload') ]);
    }

    /**
     * Delete photo on vehicle
     * @since 28/09/2020
     */
    public function DeteleVehiclePhoto(Request $request, Vehicle $vehicle, VehiclePhoto $vehicle_photo)
    {
        $this->validateClub($vehicle->club_code, 'vehicle');

        if (Storage::disk('images')->exists($vehicle_photo->photo)) {
            Storage::disk('images')->delete($vehicle_photo->photo);
        }

        $vehicle_photo->delete();

        return response()->json([ 'status' => 'success', 'data' => true, 'message' => __('vehicles.success-photo-remove') ]);
    }

    /**
     * Upload photo to my vehicle
     * @since 20/09/2020
     */
    public function UploadMyVehiclePhoto(Request $request, Vehicle $vehicle)
    {
        $this->validateClub($vehicle->club_code, 'vehicle');

        if ($vehicle->user_id != User::getAuthenticatedUserId()) {
            abort(401);
        }

        if (! $request->has('photo')) {
            return response()->json([ 'status' => 'error', 'message' => __('vehicles.need-photo') ]);
        }

        $file = $request->file('photo');
        $upload_photo = Storage::disk('images')->putFile(getClubCode().'/vehicle-photos', $file);

        if (! $upload_photo){
            return response()->json([ 'status' => 'error', 'message' => __('vehicles.error-photo-upload') ]);
        }
            
        $vehicle_photo = new VehiclePhoto();
        $vehicle_photo->club_code = getClubCode();
        $vehicle_photo->vehicle_id = $vehicle->id;
        $vehicle_photo->photo = $upload_photo;
        $vehicle_photo->save();

        return response()->json([ 'status' => 'success', 'data' => (new VehiclePhotoResource($vehicle_photo)), 'message' => __('vehicles.success-photo-upload') ]);
    }

    /**
     * Delete photo on my vehicle
     * @since 20/09/2020
     */
    public function DeteleMyVehiclePhoto(Request $request, Vehicle $vehicle, VehiclePhoto $vehicle_photo)
    {
        $this->validateClub($vehicle->club_code, 'vehicle');

        if ($vehicle->user_id != User::getAuthenticatedUserId()) {
            abort(401);
        }

        if ($vehicle_photo->vehicle_id != $vehicle->id) {
            abort(401);
        }

        if (Storage::disk('images')->exists($vehicle_photo->photo)) {
            Storage::disk('images')->delete($vehicle_photo->photo);
        }

        $vehicle_photo->delete();

        return response()->json([ 'status' => 'success', 'data' => true, 'message' => __('vehicles.success-photo-remove') ]);
    }

    /**
     * Upload photo without vehicle
     * @since 12/10/2020
     */
    public function UploadPhotoWithoutVehicle(Request $request)
    {
        if (! $request->has('photo')) {
            return response()->json([ 'status' => 'error', 'message' => __('vehicles.need-photo') ]);
        }

        $file = $request->file('photo');
        $upload_photo = Storage::disk('images')->putFile(getClubCode().'/vehicle-photos', $file);

        if (! $upload_photo){
            return response()->json([ 'status' => 'error', 'message' => __('vehicles.error-photo-upload') ]);
        }

        return response()->json([ 'status' => 'success', 'data' => Storage::disk('images')->url($upload_photo), 'message' => __('vehicles.success-upload-without-vehicle') ]);
    }
}
