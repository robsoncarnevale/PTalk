<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * EventAddress Model
 *
 * @author Davi Souto
 * @since 29/05/2021
 */
class EventAddress extends Model
{
    protected $table = 'events_address';

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
     * Get the event
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    /**
     * Get the club
     */
    public function club()
    {
        return $this->belongsTo('App\Models\Club');
    }
}
