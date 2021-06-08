<?php
  namespace App\Http\Services;
  
  /**
   * Global Geolocation Service
   * @author Davi Souto
   * @since 08/06/2021
   */
  class GeolocationService implements \App\Http\Services\Geolocation\IGeolocation
  {
    public const GOOGLE_GEOLOCATION = \App\Http\Services\Geolocation\GoogleGeolocation::class;

    private $service = false;

    public function __construct($driver = '')
    {
      switch(strtolower($driver))
      {
        case 'google': 
          $driver = GeolocationService::GOOGLE_GEOLOCATION; 
        break;

        default:
          $driver = GeolocationService::GOOGLE_GEOLOCATION;
      }

      $this->service = new $driver();

      return $this;
    }

    public function getGeolocationByAddress(string $address): array
    {
      return $this->service->getGeolocationByAddress($address);
    }
  }
  