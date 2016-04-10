<?php
/**
 * Created by PhpStorm.
 * User: Anicet
 * Date: 10/04/2016
 * Time: 14:41
 */

namespace App\Helpers;

use Plivo\RestAPI;

class SMS
{
    public static function send($phoneNumber, $token){
        $auth_id = "MAY2E0NTJLMJM4MTE5ZT";
        $auth_token = "OWQ2ZDgxNzlmZmU4ZDMwYzNhMWQ0ZmE5M2JjMmE1";
        $p = new RestAPI($auth_id, $auth_token);
        // Send a message
        $params = array(
            'src' => 'GSB',
            'dst' => self::formatPhoneNumber($phoneNumber),
            'text' => $token
        );
        $p->send_message($params);
    }

    public static function formatPhoneNumber($phoneNumber){
        $phoneNumber = substr($phoneNumber, 1);
        $phoneNumber = '33'.$phoneNumber;
        return $phoneNumber;
    }
}