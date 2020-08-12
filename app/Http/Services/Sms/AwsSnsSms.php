<?php
  namespace App\Http\Services\Sms;
  
  class AwsSnsSms implements ISms 
  {
    /**
     * Send sms with AWS SES
     * @param int $area
     * @param int phone
     * @param string $message
     * @return boolean
     * @author Davi Souo
     * @since 11/08/2020
     */
    public function send(int $area, string $phone, string $message): bool
    {
      try {
        $client = new \Aws\Sns\SnsClient([
          'version' => '2010-03-31',
          'region' => 'us-east-1',
          'credentials' => new \Aws\Credentials\Credentials(
            env('AWS_ACCESS_KEY_ID'),
            env('AWS_SECRET_ACCESS_KEY')
          ),
        ]);
        
        $client->SetSMSAttributes([
            'attributes' => [
                'DefaultSMSType' => 'Transactional',
            ],
        ]);

        $response = $client->publish([
          'Message' => $message,
          'PhoneNumber' => '+' . (string) $area . (string) $phone,
        ]);
      } catch (\Exception $e) {
        return false;
      }

      return true;
    }
  }