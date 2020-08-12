<?php
  namespace App\Http\Services;
  
  /**
   * Global SMS Service
   * @author Davi Souto
   * @since 11/08/2020
   */
  class SmsService implements \App\Http\Services\Sms\ISms
  {
    public const AWS_SNS = \App\Http\Services\Sms\AwsSnsSms::class;

    private $sms = false;

    public function __construct($driver)
    {
      switch(strtolower($driver))
      {
        case 'aws_sns': 
          $driver = SmsService::AWS_SNS; 
        break;

        default:
          $driver = SmsService::AWS_SNS;
      }

      $this->sms = new $driver();

      return $this;
    }

    public function send(int $area, string $phone, string $message): bool
    {
      return $this->sms->send($area, $phone, $message);
    }
  }
  