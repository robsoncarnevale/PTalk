<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BlacklistRequest;

use App\Models\User;
use App\Models\Blacklist;
use App\Models\BlacklistHistory;

use App\Http\Resources\Blacklist as BlacklistResource;
use App\Http\Resources\BlacklistCollection;

use Illuminate\Support\Facades\Storage;

use DB;
use Exception;

/**
 * Blacklist Controller
 *
 * @author Davi Souto
 * @since 19/06/2020
 */
class BlacklistController extends Controller
{
    protected $only_admin = true;

    /**
     * List blacklist
     *
     * @author Davi Souto
     * @since 31/10/2020
     */
    public function List(Request $request, $page = 1)
    {
        $blacklist = Blacklist::select()
            ->where('club_code', getClubCode())
            ->jsonPaginate(100, 3);

        return response()->json([ 'status' => 'success', 'data' => (new BlacklistCollection($blacklist)) ]);
    }

    /**
     * Get vehicle
     *
     * @author Davi Souto
     * @since 19/06/2020
     */
    public function Get(Request $request, $blacklist_id)
    {
        $blacklist = Blacklist::select()
            ->with([ 'history' => function($q){
                $q->orderBy('updated_at', 'desc');
            } ])
            ->where('club_code', getClubCode())
            ->where('id', $blacklist_id)
            ->first();

        if (! $blacklist) {
            return response()->json([ 'status' => 'error', 'message' => __('blacklist.not-found') ]);
        }

        return response()->json([ 'status' => 'success', 'data' => (new BlacklistResource($blacklist)) ]);
    }

    /**
     * Create blacklist
     *
     * @author Davi Souto
     * @since 31/10/2020
     */
    public function Create(BlacklistRequest $request)
    {
        $blacklist = new Blacklist();
        $blacklist->club_code = getClubCode();
        $blacklist->created_by = User::getAuthenticatedUserId();
        $blacklist->updated_by = User::getAuthenticatedUserId();
        $blacklist->fill($request->all());
        $blacklist->save();
        $blacklist->saveHistory();

        return response()->json([ 'status' => 'success', 'data' => (new BlacklistResource($blacklist)), 'message' => __('blacklist.success-create') ]);
    }

    /**
     * Update blacklist
     *
     * @author Davi Souto
     * @since 31/10/2020
     */
    public function Update(BlacklistRequest $request, Blacklist $blacklist)
    {
        $this->validateClub($blacklist->club_code, 'blacklist');

        $blacklist->fill($request->all());
        $blacklist->updated_by = User::getAuthenticatedUserId();
        $blacklist->save();
        $blacklist->saveHistory();

        return response()->json([ 'status' => 'success', 'data' => (new BlacklistResource($blacklist)), 'message' => __('blacklist.success-update') ]);
    }
}
