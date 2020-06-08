<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * AuthTokens Model
 *
 * @author Davi Souto
 * @since 22/05/2020
 */
class AuthTokens extends Model
{
    protected $table = 'auth_tokens';

    /**
     * Save token in the database
     */
    public static function createToken($token){
        $instance = new self();

        $instance->club_code = 'porsche';
        $instance->user_id = 1;
        $instance->email = 'admin@porsche.com';
        $instance->token = $token;
        $instance->expires_in = date("Y-m-d H:i:s");

        $instance->save();
    }
}
