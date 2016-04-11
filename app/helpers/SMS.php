<?php
/**
 * Created by PhpStorm.
 * User: Anicet
 * Date: 10/04/2016
 * Time: 14:41
 */

namespace App\Helpers;

use Plivo\RestAPI;
use Base;

class SMS
{
    /**
     * Permet d'envoyer un SMS
     *
     * @param $phoneNumber Le numero de téléphone
     * @param $token Le message
     */
    public static function send($phoneNumber, $message){
        $f3 = Base::instance();
        $auth_id = $f3->get('sms.apiKey');
        $auth_token = $f3->get('sms.token');
        $p = new RestAPI($auth_id, $auth_token);
        // Send a message
        $params = array(
            'src' => 'GSB',
            'dst' => self::formatPhoneNumber($phoneNumber),
            'text' => $message
        );
        $p->send_message($params);
    }

    /**
     * Permet de formater le numero de telephone au format international
     *
     * @param $phoneNumber Numero de téléphone
     * @return string Numéro de téléphone avec le code pays
     */
    public static function formatPhoneNumber($phoneNumber){
        $phoneNumber = substr($phoneNumber, 1);
        $phoneNumber = '33'.$phoneNumber;
        return $phoneNumber;
    }
}