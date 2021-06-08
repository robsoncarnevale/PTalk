<?php
  namespace App\Http\Services\Geolocation;
  
  interface IGeolocation 
  {
    /**
     * Get geolocation by address
     * @param string address
     * @return array
     */
    public function getGeolocationByAddress(string $address): array;
  }
  