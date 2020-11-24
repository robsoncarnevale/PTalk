<?php
  namespace App\Http\Services;
  
  /**
   * Global CEP Service
   * @author Davi Souto
   * @since 11/08/2020
   */
  class CepService implements \App\Http\Services\Cep\ICep
  {
    public const VIA_CEP = \App\Http\Services\Cep\ViaCep::class;

    private $service = false;

    public function __construct($driver = '')
    {
      switch(strtolower($driver))
      {
        case 'via_cep': 
          $driver = CepService::VIA_CEP; 
        break;

        default:
          $driver = CepService::VIA_CEP;
      }

      $this->service = new $driver();

      return $this;
    }

    public function getAddressByCep(string $cep): array
    {
      return $this->service->getAddressByCep($cep);
    }
  }
  