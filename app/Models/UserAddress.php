<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * UserAddress Model
 *
 * @author Davi Souto
 * @since 14/11/2020
 */
class UserAddress extends Model
{
    protected $table = 'users_address';

    const RESIDENTIAL_ADDRESS_TYPE = 'residential';
    const COMMERCIAL_ADDRESS_TYPE = 'commercial';

    const ADDRESS_STATE = [
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        'AP' => 'Amapá',
        'AM' => 'Amazonas',
        'BA' => 'Bahia',
        'CE' => 'Ceará',
        'DF' => 'Distrito Federal',
        'ES' => 'Espírito Santo',
        'GO' => 'Goiás',
        'MA' => 'Maranhão',
        'MT' => 'Mato Grosso',
        'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais',
        'PA' => 'Pará',
        'PB' => 'Paraíba',
        'PR' => 'Paraná',
        'PE' => 'Pernambuco',
        'PI' => 'Piauí',
        'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul',
        'RO' => 'Rondônia',
        'RR' => 'Roraima',
        'SC' => 'Santa Catarina',
        'SP' => 'São Paulo',
        'SE' => 'Sergipe',
        'TO' => 'Tocantins'
    ];

    protected $fillable = [
        'address_type',
        'zip_code',
        'state',
        'city',
        'neighborhood',
        'street_address',
        'number',
        'complement',
    ];

    protected $hidden = [
        'club_code',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the club
     */
    public function club()
    {
        return $this->belongsTo('App\Models\Club');
    }
}
