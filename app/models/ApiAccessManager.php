<?php

namespace App\Models;

/**
 * Class ApiAccessManager
 * Model gérant les accès à l'API
 * @package App\Models
 */

class ApiAccessManager extends MainModel
{
    protected $table = 'accesapi';

    /**
     * ApiAccessManager constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Ajout de l'autorisation d'un numéro de téléphone pour l'utilisateur
     *
     * @param string $userId L'id de l'utilisateur
     * @param string $phoneNumber Le numero de téléphone de l'utilisateur
     */
    public function addAccess($userId, $phoneNumber)
    {
        $apiAccess = new self();
        $apiAccess->userId = $userId;
        $apiAccess->phoneNumber = $phoneNumber;
        $apiAccess->save();
    }

    /**
     * Suppression de l'autorisation d'un numero de téléphone pour un utilisateur
     *
     * @param string $userId
     * @param string $phoneNumber
     */
    public function removeAccess($userId, $phoneNumber, $key)
    {
        $apiAccess = $this->load(['userId = ?  AND phoneNumber = ? ', $userId, $phoneNumber]);
        $apiAccess->erase();
    }

    /**
     * Récupération de l'accès pour un utilisateur
     *
     * @param string $phoneNumber Le numéro de téléphone
     * @param string $key au format apiKey*Token
     *
     * @return string L'id de l'utilisateur
     */
    public function getAccess($phoneNumber, $key)
    {
        $key = explode('*', $key);
        $apiAccess = $this->load(["phoneNumber = ? AND apiKey = ? AND token = ?", $phoneNumber, $key[0], $key[1]]);
        $apiAccess = $apiAccess->query[0];
        return $apiAccess->userid;
    }

    /**
     * Vérifie que le numéro de téléphone est bien dans la base de donnée
     *
     * @param string $phoneNumber Le numéro de téléphone
     *
     * @return bool Vrai si il y est, faux sinon
     */
    public function phoneExists($phoneNumber)
    {
        return ($this->count(['phoneNumber = ?', $phoneNumber]) == 1);
    }

    /**
     * Ajout des éléments d'authentification d'un téléphone
     *
     * @param string $phoneNumber Le numéro de téléphone
     * @param array $datas Les données du téléphone
     *
     * @return array|bool Un array contenant la clé d'API et le Token si la sauvegarde s'est bien passée, faux sinon.
     */
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
     * Génération de la clé d'API en fonction des infos du téléphone
     *
     * @param array $datas Les infos du téléphone
     * 
     * @return string La clé d'API
     */
    public function generateKey($datas)
    {
        $key = implode($datas);
        $key = md5($key);
        $key = str_shuffle($key);
        $key = substr($key, 0, 10);
        return $key;
    }

    /**
     * Génération d'un token composé de GSB- et d'une clé numérique à 5 caractères
     *
     * @return string GSB-le Token
     */
    public function generateToken()
    {
        $token = str_shuffle(rand(0,5000).time());
        return 'GSB-'.substr($token, 0, 5);

    }
}