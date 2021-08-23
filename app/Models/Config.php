<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Config Model
 *
 * @author Davi Souto
 * @since 23/08/2021
 */
class Config extends Model
{
    protected $table = 'config';

    protected $fillable = [
        'allow_negative_balance',
    ];

    public static function Get()
    {
        $config = Config::select()
            ->where('club_code', getClubCode())
            ->first();

        if (! $config) {
            $config = new Config();
            $config->club_code = getClubCode();
            $config->save();
        }

        return $config;
    }
}
