<?php
  namespace App\Http\Services\Cep;
  
  interface ICep 
  {
    /**
     * Get address by cep
     * @param string cep
     * @return array
     */
    public function getAddressByCep(string $cep): array;
  }
  