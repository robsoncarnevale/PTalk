<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Config;

// use App\Http\Resources\AvailableData as AvailableDataResource;

/**
 * Config Controller
 *
 * @author Davi Souto
 * @since 23/08/2021
 */
class ConfigController extends Controller
{
    protected $only_admin = false;
   
    /**
     * Get config data
     * 
     * @author Davi Souto
     * @since 23/08/2021
     */
    public function GetData(Request $request)
    {
        $config = Config::select()
            ->where('club_code', getClubCode())
            ->first();

        if (! $config) {
            $config = new Config();
            $config->club_code = getClubCode();
            $config->save();
        }

        return response()->json([ 'status' => 'success', 'data' => $config ]);
    }

    /**
     * Save config data
     * 
     * @author Davi Souto
     * @since 23/08/2021
     */
    public function Save(Request $request)
    {
        $config = Config::select()
            ->where('club_code', getClubCode())
            ->first();

        if (! $config) {
            $config = new Config();
            $config->club_code = getClubCode();
        }

        $config->allow_negative_balance = $request->has('allow_negative_balance');
        $config->save();

        return response()->json([ 'status' => 'success', 'data' => $config ]);
    }
}