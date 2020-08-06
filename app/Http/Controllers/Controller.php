<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
// use Illuminate\Validation\Validator;
use Illuminate\Http\Request;

use App\Models\HasPrivilege;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var boolean
     */
    protected $only_admin = true;

    /**
     * @var boolean
     */
    protected $ignore_privileges = false;

    /**
     * @var boolean
     */
    protected $ignore_routes = [];

    /**
     * Make validation
     *
     * @author Davi Souto
     * @since 08/06/2020
     */
    // protected function validate(Request $request, $rules)
    public static function validate(Request $request, $rules)
    {
        $validator = \Validator::make($request->all(),$rules);

        if ($validator->fails())
        {
            $errors = $validator->errors()->all();
            $first_error = $errors[0];

            return response()->json([ 'status' => 'error', 'message' => $first_error, 'data' => [ 'errors' => $errors ], 'code' => '900' ]);
        }

        return false;
    }

    protected function validateClub($club_code, $attr_name)
    {
        \Illuminate\Support\Facades\Validator::make([ 'club_code' => $club_code ], [
            'club_code' => new \App\Rules\ClubCodeValid($attr_name),
        ])->validate();
    }

    /**
     * Check if only users controller or ignore privileges and automatically check if
     * have privilege to access this route
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    public function callAction($method, $parameters)
    {
        $action = request()->route()->getName();
        $session = session()->get('session');
        $authorized = false;

        // Check if controller ignore privileges or if route is ignored
        if ($this->ignore_privileges || in_array($action, $this->ignore_routes))
            return parent::callAction($method, $parameters);

        // Check if controller accepts only admins
        if ($this->only_admin)
        {
            if ($session && $session['type'] && $session['type'] != 'admin')
                return response()->json([ 'status' => 'error', 'message' => __('privileges.unauthorized'), 'code' => 402 ]);
        }

        // Remove .post from route action
        if (substr($action, -5) == ".post")
            $action = substr($action, 0, -5);

        // Check if have permissions to access action
        if (! $this->isAuthorized($action))
            return response()->json([ 'status' => 'error', 'message' => __('privileges.unauthorized'), 'code' => 402 ]);

        return parent::callAction($method, $parameters);
    }

    /**
     * Check if user have privilege to perform action
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    public function isAuthorized($privilege)
    {
        $authorized = false;
        $session = auth()->guard()->user();

        if ($session)
        {
            $permissions = HasPrivilege::select('privilege_action')
                ->where('privilege_group_id', $session->privilege_id)
                ->get()
                ->pluck('privilege_action')
                ->toArray();


            if ($permissions && in_array($privilege, $permissions))
                $authorized = true;
        }

        return $authorized;
    }

    /**
     * Check if user have privilege to perform action and returns unauthorized if not
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    // public function makeAuthorization($privilege)
    // {
    //     if (! $this->isAuthorized($privilege))
    //         return redirect_now(route('index'));
    // }
}
