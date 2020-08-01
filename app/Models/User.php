<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Clean access code to retrive outside class after generation
     * 
     * @var string
     */
    private $access_code_clean = false;

    /**
     * Saved mobile session if is authenticated
     * 
     * @var array
     */
    private static $mobile_auth = false;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    ///////////////////////

    /**
     * privilege_id => privileges_groups.id
     */
    public function privilege_group()
    {
        return $this->hasOne('App\Models\PrivilegeGroup', 'id', 'privilege_id');
    }

    /**
     * vehicles.user_id => user.id
     */
    public function vehicles()
    {
        return $this->hasMany('App\Models\Vehicle');
    }

    ///////////////////////

    /**
     * Generate user access code
     * 
     * @author Davi Souto
     * @since 01/08/2020
     */
    public function generateNewAccessCode($valid_hours = 1, $len = 6)
    {
        $access_code = substr(mt_rand(), 0, $len);

        $this->access_code = Hash::make($access_code);
        $this->access_code_clean = $access_code;
        $this->access_code_valid_until = date('Y-m-d H:i:s', time() + ($valid_hours * 60 * 60));

        return $this;
    }

    /**
     * Returns clean access code if previously generated
     * 
     * @return string
     * @author Davi Souto
     * @since 01/08/2020
     */
    public function getAccessCode()
    {
        if ($this->access_code_clean)
            return $this->access_code_clean;

        return $this->access_code;
    }

    /**
     * Test if access code is valid
     * 
     * @return bool
     * @author Davi Souto
     * @since 01/08/2020
     */
    public function testAccessCode($code)
    {
        if(time() > strtotime($this->access_code_valid_until))
            return false;

        return Hash::check($code, $this->access_code);
    }

    /**
     * {@inheritdoc}
     * Add field photo_url if has photo
     *
     * @author Davi Souto
     * @since 09/06/2020
     */
    public function toArray()
    {
        $values = parent::toArray();
        $values = $this->add_photo_url($values);
        
        return $values;
    }

    /**
     * {@inheritdoc}
     * Add field photo_url if has photo
     *
     * @author Davi Souto
     * @since 09/06/2020
     */
    public function only($keys)
    {
        $original_keys = $keys;

        if (in_array('photo_url', $keys) && ! in_array('photo', $keys))
            $keys[] = 'photo';

        $values = parent::only($keys);

        if (array_key_exists('photo', $values) && in_array('photo_url', $original_keys))
            $values = $this->add_photo_url($values);

        if (! in_array('photo', $original_keys) && array_key_exists('photo', $values))
            unset($values['photo']);
        
        return $values;
    }

    ///////////////////////

    /**
     * Add field photo_url if has photo
     *
     * @param array $values
     * @return array
     * @author Davi Souto
     * @since 09/06/2020
     */
    private function add_photo_url($values)
    {
        if (! empty($values) && is_array($values) && isset($values['photo']))
        {
            $values['photo_url'] = false;

            if (! empty($values['photo']))
                $values['photo_url'] = Storage::disk('images')->url($values['photo']);
        }

        return $values;
    }

    /**
     * Get mobile auth
     *
     * @author Davi Souto
     * @since 01/08/2020
     */
    public static function getMobileSession()
    {
        return self::$mobile_auth;
    }

    /**
     * Set mobile auth
     *
     * @author Davi Souto
     * @since 01/08/2020
     */
    public static function setMobileSession($mobile_auth)
    {
        return self::$mobile_auth = $mobile_auth;
    }
}
