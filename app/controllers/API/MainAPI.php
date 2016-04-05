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
        $userId = "";

        if ($this->f3->get('SERVER.HTTP_AUTHORIZATION')) {
            if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']),'basic')===0)
                list($phoneNumber,$apiKey) = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
        }

        if (is_null($phoneNumber) || $userId = $this->accessManager->get($phoneNumber, $apiKey)) {
            header('WWW-Authenticate: Basic realm="GSB"');
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(false);
            die();

        } else {
            $this->f3->set('userId', $userId);
        }
    }

}