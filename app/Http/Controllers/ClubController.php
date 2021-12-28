<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Club;
use App\Models\Event;
use App\Models\Vehicle;
use App\Models\User;

use App\Models\UserAddress;
use App\Models\CarModel;
use App\Models\CarColor;

use App\Http\Resources\AvailableData as AvailableDataResource;
use App\Http\Requests\ClubRequest;

/**
 * Club Controller
 *
 * @author Davi Souto
 * @since 03/06/2020
 */
class ClubController extends Controller
{
    protected $only_admin = false;
    protected $ignore_routes = [
        'club.data',
        'club.available-data',
    ];

    /**
     * Returns club status for dashboard
     *
     * @author Davi Souto
     * @since 03/06/2020
     */
    public function GetStatus(Request $request)
    {
        $members_count = User::where('club_code', getClubCode())
            ->where('deleted', false)
            ->whereNotIn('status', [
                User::INACTIVE_STATUS,
                User::BANNED_STATUS
            ])
            ->where('approval_status', User::APPROVED_STATUS_APPROVAL)
            ->where('type', 'member')
            ->count();

        $vehicles_count = Vehicle::where('club_code', getClubCode())
            ->whereHas('user', function($q){
                $q->where('deleted', false)
                  ->where('status', '<>', User::INACTIVE_STATUS)
                  ->where('status', '<>', User::BANNED_STATUS)
                  ->where('approval_status', User::APPROVED_STATUS_APPROVAL)
                  ->where('club_code', getClubCode());
            })
            ->where('deleted', false)
            ->count();

        $next_events = (int) Event::select('id')
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->whereIn('status', [Event::ACTIVE_STATUS, Event::CLOSED_STATUS])
            ->where('date', '>=', date('Y-m-d'))
            ->count();

        $status = [
            'vehicles'  =>  $vehicles_count,
            'members'   =>  $members_count,
            'next_events'   =>  $next_events,
        ];

        if ($status['vehicles'] < 1)
            $status['vehicles'] = '-';

        if ($status['members'] < 1)
            $status['members'] = '-';

        if ($status['next_events'] < 1)
            $status['next_events'] = '-';

        return response()->json([ 'status' => 'success', 'data' => $status ]);
    }

    /**
     * Get data from club
     * 
     * @author Davi Souto
     * @since 26/11/2020
     */
    public function getData(Request $request)
    {
        $club = Club::select()
            ->where('code', getClubCode())
            ->first();

        if (! $club) {
            return response()->json([ 'status' => 'error', 'message' => '404' ], 404);
        }

        return response()->json([ 'status' => 'success', 'data' => $club ]);
    }

    /**
     * Get available data for filters from club
     * 
     * @author Davi Souto
     * @since 10/12/2020
     */
    public function GetAvailableData(Request $request)
    {
        $type = $request->get('type', false);

        $addresses = UserAddress::select('state', 'city', 'user_id')
            ->distinct('state', 'city')
            ->whereHas('user', function($q) use ($type) {
                $q->where('deleted', false)
                  ->where('approval_status', \App\Models\User::APPROVED_STATUS_APPROVAL);

                if ($type) {
                    if ($type == \App\Models\User::TYPE_ADMIN) {
                        $q->where('type', \App\Models\User::TYPE_ADMIN);
                    }

                    if ($type == \App\Models\User::TYPE_MEMBER) {
                        $q->where('type', \App\Models\User::TYPE_MEMBER);
                    }
                }
            })
            ->get();

        $find_car_models = CarModel::select('id', 'name', 'car_brand_id')
            ->with('car_brand')
            ->distinct('id')
            ->whereHas('vehicles', function($q) use ($type) {
                $q->where('deleted', false);

                $q->whereHas('user', function($q) use ($type) {
                    $q->where('deleted', false)
                      ->where('approval_status', \App\Models\User::APPROVED_STATUS_APPROVAL);

                    if ($type) {
                        if ($type == \App\Models\User::TYPE_ADMIN) {
                            $q->where('type', \App\Models\User::TYPE_ADMIN);
                        }
    
                        if ($type == \App\Models\User::TYPE_MEMBER) {
                            $q->where('type', \App\Models\User::TYPE_MEMBER);
                        }
                    }
                });
            })
            ->get();

        $find_car_colors = CarColor::select('id', 'name', 'value')
        ->distinct('value')
        ->whereHas('vehicles', function($q) use ($type) {
                $q->where('deleted', false);

                $q->whereHas('user', function($q) use ($type) {
                    $q->where('deleted', false)
                      ->where('approval_status', \App\Models\User::APPROVED_STATUS_APPROVAL);

                    if ($type) {
                        if ($type == \App\Models\User::TYPE_ADMIN) {
                            $q->where('type', \App\Models\User::TYPE_ADMIN);
                        }
    
                        if ($type == \App\Models\User::TYPE_MEMBER) {
                            $q->where('type', \App\Models\User::TYPE_MEMBER);
                        }
                    }
                });
            })
            ->get();

        $states = [];
        $cities = [];
        $car_models = [];
        $car_colors = [];

        foreach($addresses as $address) {
            $this_state = strtoupper($address->state);
            $this_city = $address->city;

            $city_key = strtolower(trim($this_city));
            $this_city = ucfirst($this_city);

            if (! array_key_exists($city_key, $cities)) {
                $cities[$city_key] = $this_city;
            }

            if (! array_key_exists($this_state, $states)) {
                $states[$this_state] = $this_state;
            }
        }

        foreach($find_car_models as $car_model){
            $car_brand_key = strtolower(str_replace(' ', '_', $car_model->car_brand->name));
            $car_model_key = strtolower(str_replace(' ', '_', $car_model->name));

            if (! array_key_exists($car_brand_key, $car_models)){
                $car_models[$car_brand_key] = array(
                    'car_brand_id' => $car_model->car_brand->id,
                    'car_brand_name' => $car_model->car_brand->name,
                    'car_models' => array(),
                );
            }

            if (! array_key_exists($car_model_key, $car_models[$car_brand_key]['car_models'])){
                $car_models[$car_brand_key]['car_models'][$car_model_key] = array(
                    'car_model_id' => $car_model->id,
                    'car_model_name' => $car_model->name,
                );
            }
        }

        foreach($find_car_colors as $car_color){
            if (! array_key_exists($car_color->id, $car_colors)){
                $car_colors[$car_color->value] = array(
                    'car_color_id' => $car_color->id,
                    'car_color_name' => $car_color->name,
                    'car_color_value' => $car_color->value,
                );
            }
        }

        $data = [
            'states' => $states,
            'cities' => $cities,
            'car_models' => $car_models,
            'car_colors' => $car_colors,
        ];

        return response()->json([ 'status' => 'success', 'data' => new AvailableDataResource($data) ]);
    }

    public function store(ClubRequest $request)
    {
        try
        {
            $club = Club::first();

            if(!$club)
                throw new \Exception(__('club.not-found'));

            $update = $club->update($request->only(
                'name',
                'contact_mail',
                'primary_color',
                'url'
            ));

            return response()->json([
                'status' => 'success',
                'message' => __('club.updated')
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
