<?php

namespace App\Helpers;

use Plivo\RestAPI;
use Base;

/**
 * Class SMS
 * Helper permettant l'envoi de SMS à travers l'API de Plivo
 * @package App\Helpers
 */
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
     * 
     * @return string Numéro de téléphone avec le code pays
     */
    public static function formatPhoneNumber($phoneNumber){
        $phoneNumber = substr($phoneNumber, 1);
        $phoneNumber = '33'.$phoneNumber;
        return $phoneNumber;
    }
}