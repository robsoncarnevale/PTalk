<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Http\Services\GeolocationService;

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

    /////////////////////////

    public function findLatLon()
    {
        try {
            $address = $this->address_resume;
    
            $geolocation = new GeolocationService();
            $result = $geolocation->getGeolocationByAddress($address);
    
            if (is_array($result)) {
                $this->lat = $result['lat'];
                $this->lon = $result['lon'];

                $this->save();
            }
        } catch (\Exception $e){
            \Log::error("Não foi possível buscar a geolocalização: " . $e->getMessage());
        }
    }

    public function getAddressResumeAttribute()
    {
        $address = $this;
        $address_resume = '';

        if ($address){
            $address_resume = $address->street_address;

            if ($address->number) {
                $address_resume .= ', ' . $address->number;
            }

            if ($address->complement) {
                $address_resume .= ', ' . $address->complement;
            }

            $address_resume .= " - " . $address->neighborhood;
            $address_resume .= " - " . $address->city;
            $address_resume .= "/" . $address->state;
        }

        return $address_resume;
    }
}
