<?php

namespace App\Models;


class ApiAccessManager extends MainModel
{
    protected $table = 'accesapi';

    function __construct()
    {
        parent::__construct();
    }

    public function addAccess($userId, $phoneNumber)
    {
        $apiAccess = new self();
        $apiAccess->userId = $userId;
        $apiAccess->phoneNumber = $phoneNumber;
        $apiAccess->save();
    }

    public function removeAccess($userId, $phoneNumber, $key)
    {
        $apiAccess = $this->load(['userId = ?  AND phoneNumber = ? AND apiKey LIKE ?', $userId, $phoneNumber, $key]);
        $apiAccess->erase();
    }

    public function getAccess($phoneNumber, $key)
    {
        $key = explode('*', $key);
        $apiAccess = $this->load(["phoneNumber = ? AND apiKey = ? AND token = ?", $phoneNumber, $key[0], $key[1]]);
        $apiAccess = $apiAccess->query[0];
        return $apiAccess->userid;
    }

    public function phoneExists($phoneNumber)
    {
        return ($this->count(['phoneNumber = ?', $phoneNumber]) == 1);
    }

    public function addPhone($phoneNumber, $datas)
    {
        $this->load(['phoneNumber = ?', $phoneNumber]);
        $this->apiKey = $this->generateKey($datas);
        $this->token = $this->generateToken();
        $this->model = $datas['model'];
        $this->platform = $datas['platform'];
        if($this->save())
            return ['ApiKey' => $this->apiKey, 'Token' => $this->token];
        return false;
    }

    /**
     * @param $datas
     * @return string
     */
    public function generateKey($datas)
    {
        $key = implode($datas);
        $key = md5($key);
        $key = str_shuffle($key);
        $key = substr($key, 0, 10);
        return $key;
    }

    public function generateToken()
    {
        $token = str_shuffle(rand(0,5000).time());
        return 'GSB-'.substr($token, 0, 5);

    }
}