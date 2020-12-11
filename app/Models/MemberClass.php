<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MemberClass Model
 *
 * @author Davi Souto
 * @since 08/06/2020
 */
class MemberClass extends Model
{
    protected $table = 'members_classes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'color',
        'default',
    ];

    protected $hidden = [
        'club_code',
        'default',
    ];
}
