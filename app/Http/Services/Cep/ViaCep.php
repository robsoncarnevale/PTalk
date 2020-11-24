<?php
  namespace App\Http\Services\Cep;

  use GuzzleHttp\Client;
  
  class ViaCep implements ICep 
  {
    private $client;

    public function __construct()
    {
      $this->client = new Client([
        'base_uri' => env('VIA_CEP_URL'),
        'timeout' => 10
      ]);
    }

    /**
     * Get address by cep
     * @param int $area
     * @param int phone
     * @param string $message
     * @return boolean
     * @author Davi Souo
     * @since 11/08/2020
     */
    public function getAddressByCep(string $cep): array
    {
      $cep = preg_replace("/[^0-9]*/is", '', $cep);

      try {
        if (strlen($cep) != 8) {
          throw new \Exception('Cep inválido');
        }

        $response = $this->client->get($cep . '/json');
        $data = json_decode($response->getBody()->getContents(), true);

        if (! is_array($data) || array_key_exists('erro', $data)) {
          throw new \Exception('Cep não encontrado', 404);
        }

        return [
          'status' => 'success',
          'data' => [
            'zip_code' => $data['cep'],
            'state_initials' => strtoupper($data['uf']),
            'state' => \App\Models\UserAddress::ADDRESS_STATE[strtoupper($data['uf'])],
            'city' => $data['localidade'],
            'neighborhood' => $data['bairro'],
            'street_address' => $data['logradouro'],
            'complement' => array_key_exists('complement', $data) ? $data['complement'] : '',

            // More data
            'more' => [
              'phone_ddd' => array_key_exists('ddd', $data) ? $data['ddd'] : '',
              'ibge' => array_key_exists('ibge', $data) ? $data['ibge'] : '',
              'gia' => array_key_exists('gia', $data) ? $data['gia'] : '',
              'siafi' => array_key_exists('siafi', $data) ? $data['siafi'] : '',
            ]
          ],
        ];
      } catch (\Exception $e) {
        throw $e;
        return [
          'status' => 'error',
          'message' => $e->getCode() == 404 ? 'Cep não encontrado' : 'Cep inválido ou cep não encontrado'
        ];
      }

      return [
        'status' => 'error',
        'message' => 'Erro ao buscar cep'
      ];
    }
  }