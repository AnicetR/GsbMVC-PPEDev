<?php
namespace App\Controllers\API;

use App\Models\User;
use Base;
use App\Models\ApiAccessManager;

class MainAPI{

    protected $f3, $accessManager;

    public function __construct(){
        $this->f3 = Base::instance();
        $this->accessManager = new ApiAccessManager();

    }

    public function beforeRoute()
    {
        $this->Auth();
    }

    public function afterRoute()
    {
        header('Content-Type: application/json');
    }

    public function Auth()
    {
        $phoneNumber = null;
        $apiKey = null;
        $userId = null;


        if ($this->f3->get('SERVER.PHP_AUTH_USER') !== null && $this->f3->get('SERVER.PHP_AUTH_PW') !== null ) {
            $phoneNumber = $this->f3->get('SERVER.PHP_AUTH_USER');
            $apiKey = $this->f3->get('SERVER.PHP_AUTH_PW');
            $userId = $this->accessManager->getAccess($phoneNumber, $apiKey);
        }
        if (is_null($userId)){
            header('WWW-Authenticate: Basic realm="GSB"');
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(false);
            die();
        }
        else {
            $this->f3->set('userId', $userId);
        }
    }

}