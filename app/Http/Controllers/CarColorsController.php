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
        $club_code = getClubCode();

        if ($request->get('club_code')) {
            $club_code = $request->get('club_code');
        }

        $car_colors = CarColor::select('id', 'name', 'value')
            ->where('club_code', $club_code)
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
        $club_code = getClubCode();

        if ($request->get('club_code')) {
            $club_code = $request->get('club_code');
        }

        $car_color = CarColor::select('id', 'name', 'value')
            ->where('club_code', $club_code)
            ->where('id', $car_color_id)
            ->first();
        
        if (! $car_color)
            return response()->json([ 'status' => 'error', 'message' => __('car_color.not-found') ]);

        return response()->json([ 'status' => 'success', 'data' => $car_color ]);
    }

    /**
     * Create
     * 
     * @author Davi Souto
     * @since 07/09/2020
     */
    function Create(Request $request)
    {
        $validator = self::validate($request, [
            'name'  =>  'required',
            'value' => 'required|min:6',
        ]);

        if ($validator) {
            return $validator;
        }

        $car_color = new CarColor();
        $car_color->fill($request->all());
        $car_color->club_code = getClubCode();

        if (strlen($car_color->value) <= 6 && strpos($car_color->value, '#') === false) {
            $car_color->value = '#' . $car_color->value;
        }

        $car_color->save();

        return response()->json([ 'status' => 'success', 'data' => $car_color, 'message' => __('car_color.success-create') ]);
    }

    /**
     * Update
     * 
     * @author Davi Souto
     * @since 07/09/2020
     */
    function Update(Request $request, $car_color_id)
    {
        $car_color = CarColor::select()
            ->where('club_code', getClubCode())
            ->where('id', $car_color_id)
            ->first();

        if (! $car_color) {
            return response()->json([ 'status' => 'error', 'message' => __('car_color.not-found') ]);
        }

        $car_color->fill($request->all());

        if (strlen($car_color->value) <= 6 && strpos($car_color->value, '#') === false) {
            $car_color->value = '#' . $car_color->value;
        }

        $car_color->save();

        return response()->json([ 'status' => 'success', 'data' => $car_color, 'message' => __('car_color.success-update') ]);
    }

    /**
     * Delete
     * 
     * @author Davi Souto
     * @since 07/09/2020
     */
    public function Delete(Request $request, $car_color_id)
    {
        $car_color = CarColor::select()
            ->withCount('vehicles')
            ->where('club_code', getClubCode())
            ->where('id', $car_color_id)
            ->first();

        if (! $car_color) {
            return response()->json([ 'status' => 'error', 'message' => __('car_color.not-found') ]);
        }

        if ($car_color->vehicles_count > 0) {
            return response()->json([ 'status' => 'error', 'message' => __('car_color.delete-error-count') ]);
        }

        $car_color->delete();

        return response()->json([ 'status' => 'success', 'data' => true, 'message' => __('car_color.success-delete') ]);
    }
}
