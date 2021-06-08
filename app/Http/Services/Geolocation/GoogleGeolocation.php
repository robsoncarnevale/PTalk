<?php
  namespace App\Http\Services\Geolocation;

  use GuzzleHttp\Client;
  
  class GoogleGeolocation implements IGeolocation 
  {
    private $client;
    private $key = '';

    public function __construct()
    {
      $this->client = new Client([
        'base_uri' => 'https://maps.googleapis.com/maps/api/geocode/json',
        'timeout' => 10
      ]);

      $this->key = env('GOOGLE_API_KEY');
    }

    /**
     * Get geolocation by address
     * @param string $address
     * @return boolean
     * @author Davi Souo
     * @since 11/08/2020
     */
    public function getGeolocationByAddress(string $address): array
    {
      $address = trim($address);

      try {
        $response = $this->client->get("?key=" . $this->key . "&address=" . $address);
        $data = json_decode($response->getBody()->getContents(), true);

        if (! is_array($data) || array_key_exists('erro', $data)) {
          throw new \Exception('Geolcalização não encontrada', 404);
        }

        if (count($data['results']) < 0) {
            throw new \Exception('Geolocalização não encontrada', 404);
        }

        $result = $data['results'][0];
        $result = [
            'lat' => $result['geometry']['location']['lat'],
            'lon' => $result['geometry']['location']['lng'],
        ];

        return $result;
      } catch (\Exception $e) {
        return [
          'status' => 'error',
          'message' => $e->getCode() == 404 ? 'Geolocalização não encontrada' : 'Endereço inválido ou geolocalização não encontrada'
        ];
      }

      return [
        'status' => 'error',
        'message' => 'Erro ao buscar geolocalização'
      ];
    }
  }