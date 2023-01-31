<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class ApiService 
{    
    protected $api = NULL;

    public function __construct($username, $password)
    {
        $this->api = Http::withBasicAuth($username, $password);
    }

    public static function run($apiConfig, $method, $params)
    {
        $class = new ApiService($apiConfig->username, $apiConfig->password);

        switch ($method) {
            case 'get':
            case 'GET':
                return $class->_get($apiConfig->endpoint, $params);
                break;

            case 'post':
            case 'POST':
                return $class->_post($apiConfig->endpoint, $params);
                break;
            
            default:
                return "method not defined";
                break;
        }
    }

    protected function _get($endpoint, $params = NULL)
    {
        $res = $params ? $this->api->get($endpoint, $params) : $this->api->get($endpoint);

        return $res;
    }

    protected function _post($endpoint, $params)
    {
        $res = $this->api->post($endpoint, $params);

        return $res;
    }

}