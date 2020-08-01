<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
// use App\Models\AuthTokens;
// use App\Models\HasPrivilege;

/**
 * Authentication Mobile Controller
 *
 * @author Davi Souto
 * @since 01/08/2020
 */
class AuthController extends Controller
{
    /**
     * Request code to access mobile application
     * 
     * @author Davi Souto
     * @since 01/08/2020
     */
    public function RequestAccessCode(Request $request){
        $phone = preg_replace('#[^0-9]#is', '', $request->get('phone'));

        if (empty($phone))
            return response()->json(['message' => 'Invalid phone', 'status' => 'error' ], 404);

        $user = User::select()
            ->where('active', true)
            ->where('deleted', false)
            ->where('phone', $phone)
            ->where('club_code', $request->get('club_code'))
            ->first();

        if (! $user)
            return response()->json(['message' => 'User not found', 'status' => 'error' ], 404);

        $user = $user->generateNewAccessCode();
        $user->save();

        $client = new \Aws\Sns\SnsClient([
            'version' => '2010-03-31',
            'region' => 'us-east-1',
            'credentials' => new \Aws\Credentials\Credentials(
                env('AWS_ACCESS_KEY_ID'),
                env('AWS_SECRET_ACCESS_KEY')
            )
        ]);

        // $client->SetSMSAttributes([
        //     'attributes' => [
        //         'DefaultSMSType' => 'Transactional',
        //     ],
        // ]);

        $client->publish([
            'Message' => 'Seu código de acesso ao Porsche Talk é ' . $user->getAccessCode(),
            'PhoneNumber' => '+55' . $phone,
        ]);

        return response()->json(['message' => 'Your code has been sent', 'status' => 'success'], 200);
    }

    /**
     * Generate bearer token receiving phone and access code
     * 
     * @author Davi Souto
     * @since 01/08/2020
     */
    public function AccessWithCode(Request $request, $code){
        $phone = preg_replace('#[^0-9]#is', '', $request->get('phone'));

        if (empty($phone))
            return response()->json(['message' => 'Invalid phone', 'status' => 'error' ], 404);

        $user = User::select()
            ->where('active', true)
            ->where('deleted', false)
            ->where('phone', $phone)
            ->where('club_code', $request->get('club_code'))
            ->first();

        if (! $user)
            return response()->json(['message' => 'User not found', 'status' => 'error' ], 404);

        if (! $user->testAccessCode($code))
            return response()->json(['message' => 'Invalid or expired code', 'status' => 'error' ], 401);

        $session = $user->only(['id', 'name', 'email', 'type', 'photo_url', 'privilege_id']);
        $session['first_name'] = explode(" ", $session['name'])[0];
        // $session['last_name'] = end(explode(" ", $session['name']));

        // if ($session['first_name'] == $session['last_name'])
        //     $session['last_name'] = '';

        $session['club_code'] = $request->get('club_code');

        $header = base64_encode(json_encode([ 'alg' => 'HS256', 'typ' => 'JWT' ]));
        $payload = substr(base64_encode(json_encode($session)), 0, -1);
        $sign = substr(base64_encode(hash_hmac('sha256', $header . $payload, env('JWT_SECRET'), true)), 0, -1);

        $token = $header . '.' . $payload . '.' . $sign;

        // Delete last access code
        $user->access_code = null;
        $user->save();

        unset($session['club_code']);

        // $session['privileges'] = HasPrivilege::select('privilege_action')
        //     ->where('privilege_group_id', $session['privilege_id'])
        //     ->get()
        //     ->pluck('privilege_action');

        return response()->json([
            'data' => [
                'session' => $session,
                'access_token' => $token,
                'token_type' => 'bearer',
                // 'expires_in' => $expires_in,
            ],
            'status' => 'success',
            'code' => 200,
        ]);
    }

}
