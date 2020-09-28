<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MemberClass;

use App\Http\Resources\MemberClass as MemberClassResource;

use DB;
use Exception;

/**
 * Members Classes Controller
 *
 * @author Davi Souto
 * @since 27/09/2020
 */
class MembersClassesController extends Controller
{
    protected $only_admin = false;

    /**
     * List
     *
     * @author Davi Souto
     */
    public function List(Request $request)
    {
        $classes = MemberClass::select()
            ->orderBy('id')
            ->get();

        return response()->json([ 'status' => 'success', 'data' => MemberClassResource::collection($classes) ]);
    }

    /**
     * Create member class
     * @author Davi Souto
     */
    public function Create(Request $request)
    {
    }

    /**
     * Update member class
     * @author Davi Souto
     */
    public function Update(Request $request)
    {
    }
   

    /**
     * Delete
     * @author Davi Souto
     */
    public function Delete(Request $request)
    {
    }

    /**
     * Get member class
     * @author Davi Souto
     */
    private static function Get(Request $request)
    {
        if (! $user) {
            return response()->json([ 'status' => 'error', 'message' => __('.not-found') ]);
        }

        return response()->json([ 'status' => 'success', 'data' => (new MemberClassResource($member_class)) ]);
    }
}
