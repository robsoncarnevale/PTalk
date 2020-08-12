<?php
  namespace App\Http\Services\Sms;
  
  interface ISms 
  {
    /**
     * Send sms
     * @param int $area
     * @param int phone
     * @param string $message
     * @return boolean
     */
    public function send(int $area, string $phone, string $message): bool;
  }
  