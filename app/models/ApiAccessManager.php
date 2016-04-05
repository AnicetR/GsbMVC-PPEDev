<?php

namespace app\models;


use App\Controllers\MainController;
class ApiAccessManager extends MainController
{
    private $table = 'accesapi';

    function __construct()
    {
        parent::construct($this->table);
    }

    public function add($userId, $phoneNumber, $key)
    {
        $apiAccess = new self();
        $apiAccess->userId = $userId;
        $apiAccess->phoneNumber = $phoneNumber;
        $apiAccess->apiKey = $key;
        $apiAccess->save();
    }

    public function remove($userId, $phoneNumber, $key)
    {
        $apiAccess = $this->load(['userId = ?, phoneNumber = ?, apiKey = ?', $userId, $phoneNumber, $key]);
        $apiAccess->erase();
    }

    public function get($phoneNumber, $key)
    {
        $apiAccess = $this->load(['phoneNumber = ?, apiKey = ?', $phoneNumber, $key]);
        return $apiAccess->userId ? $apiAccess->userId : false;
    }
}