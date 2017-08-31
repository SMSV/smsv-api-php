<?php

namespace SMSV;

class api {
    protected $apiUrl = 'https://api.smsv.me/v1.0';

    private $accessKey;

    /**
     * api constructor.
     * @param $access_key
     */
    public function __construct($access_key)
    {
        $this->accessKey = $access_key;
    }

    /**
     * Sends OTP to phone
     * @param $phone string International phone without spaces and +
     * @return string string Session key for validation.
     * @throws \Exception
     */
    public function send( $phone) {
        $phone = (string)$phone;

        $response = json_decode(file_get_contents("{$this->apiUrl}/otp/send/?ak={$this->accessKey}&p=$phone"));

        //var_dump($response);

        if (!$response->success) {
            throw new \Exception("Unable to send one-time password. Errors are: ".implode(',',$response->errors));
        }

        return $response->data;
    }

    /**
     * Validates user-provided code with current session
     * @param $sessionKey string Session key
     * @param $code string User-provided code
     * @return boolean Is code valid or not
     * @throws \Exception
     */
    public function validate($sessionKey,$code) {

        $sessionKey = (string)$sessionKey;

        $code = (string)$code;

        $response = json_decode(file_get_contents("{$this->apiUrl}/otp/validate/?ak={$this->accessKey}&sk=$sessionKey&c=$code"));

        if (!$response->success) {
            throw new \Exception("One-time password is invalid. Errors are: ".implode(',',$response->errors));
        }

        return $response->success;
    }
}