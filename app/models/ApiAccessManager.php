<?php

namespace App\Models;


class ApiAccessManager extends MainModel
{
    protected $table = 'accesapi';

    function __construct()
    {
        parent::__construct($this->table);
    }

    public function addAccess($userId, $phoneNumber, $key)
    {
        $apiAccess = new self();
        $apiAccess->userId = $userId;
        $apiAccess->phoneNumber = $phoneNumber;
        $apiAccess->apiKey = $key;
        $apiAccess->save();
    }

    public function removeAccess($userId, $phoneNumber, $key)
    {
        $apiAccess = $this->load(['userId = ?, phoneNumber = ?, apiKey = ?', $userId, $phoneNumber, $key]);
        $apiAccess->erase();
    }

    public function getAccess($phoneNumber, $key)
    {
        $apiAccess = $this->load(['phoneNumber = ?, apiKey = ?', $phoneNumber, $key]);
        return $apiAccess->userId ? $apiAccess->userId : false;
    }
}