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

    const TYPE_ADMIN = 'admin';
    const TYPE_MEMBER = 'member';

    const APPROVED_STATUS_APPROVAL = 'approved';
    const MEMBER_STEP_STATUS_APPROVAL = 'member_step';
    const WAITING_STATUS_APPROVAL = 'waiting';
    const REFUSED_STATUS_APPROVAL = 'refused';

    const ACTIVE_STATUS = 'active'; // User is active
    const INACTIVE_STATUS = 'inactive'; // User is inactive and not appears on lists
    const SUSPENDED_STATUS = 'suspended'; // User is suspended for a defined time
    const BLOCKED_STATUS = 'blocked'; // User is blocked for undefined time
    const BANNED_STATUS = 'banned'; // User is permanently banned

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'nickname',
        'email', 
        'password', 
        'company', 
        'company_activities', 
        'comercial_address', 
        'home_address', 
        'comercial_address',
        'document_cpf',
        'document_rg',
        'indicated_by',
        // 'photo',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token', 
        'access_code', 
        'access_code_valid_until',
        'new_password_token',
        'new_password_token_duration',
        'forget_password_token',
        'forget_password_token_duration'
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

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_code', 'code');
    }

    public function indicator()
    {
        return $this->belongsTo(User::class, 'indicated_by', 'id');
    }

    public function participation_request_information()
    {
        return $this->hasOne(ParticipationRequestInformation::class);
    }

    /**
     * Get the users addresses
     */
    public function addresses()
    {
        return $this->hasMany('App\Models\UserAddress');
    }

    /**
     * Get the bank account
     */
    public function bank_account()
    {
        return $this->hasOne('App\Models\BankAccount');
    }

    /**
     * Get the user class
     */
    public function member_class()
    {
        return $this->belongsTo('App\Models\MemberClass');
    }

    /**
     * Get the user approval history
     */
    public function approval_history()
    {
        return $this->hasMany('App\Models\UserApprovalHistory');
    }

    /**
     * Get the user status history
     */
    public function status_history()
    {
        return $this->hasMany('App\Models\UserStatusHistory');
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
     * Generate user password
     * 
     * @author Davi Souto
     * @since 04/08/2020
     */
    public function generatePassword($password)
    {
        $this->password = Hash::make($password);

        return $this;
    }

    /**
     * 
     */
    public function getMobilePrivilege()
    {
        $privilege = PrivilegeGroup::select('id')
            ->where('type', User::TYPE_MEMBER)
            ->where('name', 'Membro')
            ->first();

        $this->privilege_id = $privilege['id'];

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
     * Upload user photo
     * 
     * @author Davi Souto
     * @since 05/08/2020
     */
    public function upload($file)
    {
        $upload_photo = Storage::disk('images')->putFile(getClubCode().'/users', $file);

        if ($upload_photo)
        {
            if (! empty($this->photo) && Storage::disk('images')->exists($this->photo))
                Storage::disk('images')->delete($this->photo);
            
            $this->photo = $upload_photo;
        }

        return $this;
    }

    /**
     * Save the actual status to history
     * 
     * @author Davi Souto
     * @since 19/08/2020
     */
    public function saveStatusHistory()
    {
        $last_status = \App\Models\UserStatusHistory::select()
            ->where('user_id', $this->id)
            ->orderBy('created_at', 'DESC')
            ->first();

        // Verify if status has changed
        if ($last_status && $last_status->status == $this->status)
        {
            $last_status->suspended_time = $this->suspended_time;
            $last_status->reason = $this->status_reason;
            $last_status->created_by = $this->getAuthenticatedUserId();
            
            $last_status->save();

            return $last_status;
        }
        
        $status_history = new \App\Models\UserStatusHistory();
        $status_history->club_code = $this->club_code;
        $status_history->user_id = $this->id;
        $status_history->status = $this->status;
        $status_history->reason = $this->status_reason;
        $status_history->created_by = $this->getAuthenticatedUserId();

        if ($this->status == User::SUSPENDED_STATUS)
            $status_history->suspended_time = $this->suspended_time;

        $status_history->save();

        return $status_history;
    }

    /**
     * Save the actual approval status to history
     * 
     * @author Davi Souto
     * @since 19/08/2020
     */
    public function saveApprovalHistory()
    {
        $last_status = \App\Models\UserApprovalHistory::select()
            ->where('user_id', $this->id)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (! $last_status || $last_status->status != $this->approval_status) {
            $status_history = new \App\Models\UserApprovalHistory();
            $status_history->club_code = $this->club_code;
            $status_history->user_id = $this->id;
            $status_history->approval_status = $this->approval_status;
            $status_history->reason = $this->refused_reason;
            $status_history->created_by = $this->getAuthenticatedUserId();
    
            $status_history->save();
            
            return $status_history;
        }

        return $last_status;
    }

    /**
     * Create bank account to user
     * 
     * @author Davi Souto
     * @since 25/11/2020
     */
    public function createBankAccount()
    {
        $check = \App\Models\BankAccount::select()
            ->where('user_id', $this->id)
            ->first();

        if ($check) {
            return false;
        }

        $bank_account = new \App\Models\BankAccount();
        $bank_account->club_code = $this->club_code;
        $bank_account->user_id = $this->id;
        $bank_account->account_number = preg_replace("#[^0-9]*#", "", $this->phone);
        $bank_account->account_holder = $this->name;
        $bank_account->balance = 0.00;
        $bank_account->status = \App\Models\BankAccount::ACTIVE_STATUS;
        $bank_account->save();

        return $bank_account;
    }

    /**
     * {@inheritdoc}
     * Add field photo_url if has photo
     *
     * @author Davi Souto
     * @since 09/06/2020
     * @deprecated 05/08/2020
     */
    // public function toArray()
    // {
    //     $values = parent::toArray();
    //     $values = $this->add_photo_url($values);
        
    //     return $values;
    // }

    /**
     * {@inheritdoc}
     * Add field photo_url if has photo
     *
     * @author Davi Souto
     * @since 09/06/2020
     * @deprecated 05/08/2020
     */
    // public function only($keys)
    // {
    //     $original_keys = $keys;

    //     if (in_array('photo_url', $keys) && ! in_array('photo', $keys))
    //         $keys[] = 'photo';

    //     $values = parent::only($keys);

    //     if (array_key_exists('photo', $values) && in_array('photo_url', $original_keys))
    //         $values = $this->add_photo_url($values);

    //     if (! in_array('photo', $original_keys) && array_key_exists('photo', $values))
    //         unset($values['photo']);
        
    //     return $values;
    // }

    ///////////////////////

    /**
     * Add field photo_url if has photo
     *
     * @param array $values
     * @return array
     * @author Davi Souto
     * @since 09/06/2020
     * @deprecated 05/08/2020
     */
    // private function add_photo_url($values)
    // {
    //     if (! empty($values) && is_array($values) && isset($values['photo']))
    //     {
    //         $values['photo_url'] = false;

    //         if (! empty($values['photo']))
    //         {
    //             $values['photo_url'] = Storage::disk('images')->url($values['photo']);
    //         } else 
    //         {
    //             $values['photo_url'] = Storage::disk('images')->url(getClubCode().'/teste.png');
    //         }
    //     }

    //     return $values;
    // }

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
     * Returns user authenticated id (mobile or web)
     * @return int
     */
    public static function getAuthenticatedUserId()
    {
        if (self::$mobile_auth)
            return self::$mobile_auth->id;

        if (auth()->guard()->user())
            return auth()->guard()->user()->id;

        return false;
    }

    /**
     * Returns user authenticated (mobile or web)
     * @return \App\Models\User
     * @author Davi Souto
     * @since 24/05/2021
     */
    public static function getAuthenticatedUser()
    {
        $id = false;

        if (self::$mobile_auth) {
            $id = self::$mobile_auth->id;
        }

        if (auth()->guard()->user()) {
            $id = auth()->guard()->user()->id;
        }

        if ($id) {
            return self::select()
                ->where('club_code', getClubCode())
                ->where('id', $id)
                ->first();
        }

        return false;
    }

    /**
     * Returns true if user is authenticated on mobile
     * @return bool
     * @since 16/09/2020
     */
    public static function isMobile(){
        if (self::$mobile_auth) {
            return true;
        }

        return false;
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

    /**
     * Get de nickname if exists or first name + last name
     * @author Davi Souto
     * @ return string
     */
    public function getDisplayNameAttribute()
    {
        if (! empty($this->nickname)) {
            return $this->nickname;
        }

        $name_exploded = explode(" ", $this->name);

        $first_name = $name_exploded[0];
        $last_name =  (count($name_exploded) > 1) ? end($name_exploded) : '';

        return trim($first_name . ' ' . $last_name);
    }

    /**
     * Get formatted phone number
     * @author Davi Souto
     * @since 31/10/2020
     */
    private function getPhoneFormattedAttribute()
    {
        $formatedPhone = preg_replace('/[^0-9]/', '', $this->phone);

        $matches = [];
        preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formatedPhone, $matches);

        if ($matches) {
            return '('.$matches[1].') '.$matches[2].'-'.$matches[3];
        }
    
        return $phone; // return number without format
    }

    public function getSuspendedTimeBrAttribute()
    {
        return dateDatabaseToBr(substr($this->suspended_time, 0, 10));
    }
}
