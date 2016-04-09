<?php

namespace App\Models;


class ApiAccessManager extends MainModel
{
    protected $table = 'accesapi';

    function __construct()
    {
        parent::__construct();
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
        $apiAccess = $this->load(['userId = ?  AND phoneNumber = ? AND apiKey LIKE ?', $userId, $phoneNumber, $key]);
        $apiAccess->erase();
    }

    public function getAccess($phoneNumber, $key)
    {
        $apiAccess = $this->load(["phoneNumber = ? AND apiKey = ?", $phoneNumber, $key]);
        $apiAccess = $apiAccess->query[0];
        return $apiAccess->userid;
    }
}