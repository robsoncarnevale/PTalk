<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CarModel;
use App\Models\CarBrand;
use App\Http\Resources\CarModel as CarModelResource;
use App\Http\Resources\CarModelCollection;

use Storage;

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
            return response()->json([ 'status' => 'error', 'message' => __('car_model.not-found') ]);

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
            ->with('car_models:id,name,car_brand_id,picture')
            ->orderBy('name')
            ->get()
            ->toArray();

        foreach($car_models as $i_car_brands => $car_brands){
            usort($car_models[$i_car_brands]['car_models'], function($item1, $item2){
                return $item1['name'] <=> $item2['name'];
            });

            foreach($car_brands['car_models'] as $i_car_model => $car_model){
                $car_models[$i_car_brands]['car_models'][$i_car_model]['picture'] = \App\Http\Resources\CarModelPicture::get($car_models[$i_car_brands]['car_models'][$i_car_model]['picture']);
            }

            // $car_models[$i_car_brands]['car_models'] = collect($car_brands['car_models'])->sortBy('name');
        }

        return response()->json([ 'status' => 'success', 'data' => $car_models ]);
    }

    /**
     * Create
     * 
     * @author Davi Souto
     * @since 07/09/2020
     */
    public function Create(Request $request)
    {
        $validator = self::validate($request, [
            'name'  =>  'required',
            'car_brand_id' => 'required',
        ]);

        if ($validator) {
            return $validator;
        }

        // Validate duplication
        $check_car_model = CarModel::select('id')
            ->whereRaw('LOWER(name) = ?', strtolower($request->get('name')))
            ->where('club_code', getClubCode())
            ->first();

        if ($check_car_model) {
            return response()->json([ 'status' => 'error', 'message' => __('car_model.car-model-already-registered') ]);
        }

        $car_model = new CarModel();
        $car_model->fill($request->all());
        $car_model->club_code = getClubCode();

        // Picture upload
        if ($request->has('picture')) {
            $car_model->upload($request->file('picture'));
        }

        $car_model->save();

        return response()->json([ 'status' => 'success', 'data' => (new CarModelResource($car_model)), 'message' => __('car_model.success-create') ]);
    }

    /**
     * Update
     * 
     * @author Davi Souto
     * @since 07/09/2020
     */
    public function Update(Request $request, $car_model_id)
    {
        $car_model = CarModel::select()
            ->where('club_code', getClubCode())
            ->where('id', $car_model_id)
            ->first();

        if (! $car_model) {
            return response()->json([ 'status' => 'error', 'message' => __('car_model.not-found') ]);
        }

        $car_model->fill($request->all());

        // Validate duplication
        $check_car_model = CarModel::select('id')
            ->whereRaw('LOWER(name) = ?', strtolower($car_model->name))
            ->where('id', '<>', $car_model->id)
            ->where('club_code', getClubCode())
            ->first();

        if ($check_car_model) {
            return response()->json([ 'status' => 'error', 'message' => __('car_model.car-model-already-registered') ]);
        }

        // Picture upload
        if ($request->has('picture')) {
            $car_model->upload($request->file('picture'));
        }

        $car_model->save();

        return response()->json([ 'status' => 'success', 'data' => (new CarModelResource($car_model)), 'message' => __('car_model.success-update') ]);
    }

    /**
     * Delete
     * 
     * @author Davi Souto
     * @since 07/09/2020
     */
    public function Delete(Request $request, $car_model_id)
    {
        $car_model = CarModel::select()
            ->withCount('vehicles')
            ->where('club_code', getClubCode())
            ->where('id', $car_model_id)
            ->first();

        if (! $car_model) {
            return response()->json([ 'status' => 'error', 'message' => __('car_model.not-found') ]);
        }

        if ($car_model->vehicles_count > 0) {
            return response()->json([ 'status' => 'error', 'message' => __('car_model.delete-error-count') ]);
        }

        if (! empty($car_model->picture) && Storage::disk('images')->exists($car_model->picture)) {
            Storage::disk('images')->delete($car_model->picture);
        }

        $car_model->delete();

        return response()->json([ 'status' => 'success', 'data' => true, 'message' => __('car_model.success-delete') ]);
    }

}
