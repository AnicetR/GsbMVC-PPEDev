<?php
namespace App\Controllers\API;

use App\Models\User;
use Base;
use App\Models\ApiAccessManager;
use App\Helpers\SMS;

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


        if ($this->f3->get('SERVER.PHP_AUTH_USER') !== null && $this->f3->get('SERVER.PHP_AUTH_PW') !== null && $this->f3->get('SERVER.PHP_AUTH_PW') !== '') {
            $phoneNumber = $this->f3->get('SERVER.PHP_AUTH_USER');
            $apiKey = $this->f3->get('SERVER.PHP_AUTH_PW');
            $userId = $this->accessManager->getAccess($phoneNumber, $apiKey);
        }

        else if ($this->f3->get('SERVER.PHP_AUTH_USER') !== null && $this->f3->get('SERVER.PHP_AUTH_PW') == '' && $this->f3->get('GET') !== null) {
            $phoneNumber = $this->f3->get('SERVER.PHP_AUTH_USER');
            $getDatas = $this->f3->get('GET');
            if ($APIkey = $this->Authorize($phoneNumber, $getDatas)) {
                echo json_encode($APIkey);
                die();
            }
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

    public function Authorize($phoneNumber, $getData){
        $APIaccess = new ApiAccessManager();
        $expected = ['uuid', 'model', 'platform'];
        $tempData = [];
        foreach ($getData as $key => $value) {
            if(in_array($key, $expected)){

                $tempData[$key] = $value;
            }
        }
        if(count($tempData) == count($expected)){
            if($APIaccess->phoneExists($phoneNumber)){
                if($infos = $APIaccess->addPhone($phoneNumber, $tempData)){
                    SMS::send($phoneNumber, $infos['Token']);
                    return $infos['ApiKey'];
                }
            }
        }
    }

    protected function getPUT(){
        $body = $this->f3->get('BODY');
        return json_decode($body, true);
    }

}