<?php

namespace App\Models;

use FFMVC\Helpers;

class User extends MainModel
{
    protected $table = 'utilisateur';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Enregistre un nouvel utilisateur | non utilisé.
     *
     * @param string  $username
     * @param string  $password
     * @param integer $roleId
     */
    public function register($username, $password, $roleId = 0)
    {
        $user = new self();
        $user->login = $username;
        $user->mdp = $password;
        if (Role::isRole($roleId)) {
            $user->role = $roleId;
        }
        $user->save();
    }

    /**
     * Retourne l'utilisateur par l'ID.
     *
     * @param string $userId
     *
     * @return array
     */
    public function getByID($userId)
    {
        $this->load(['id=?', $userId]);

        return $this->query;
    }

    /**
     * Retourne l'utilisateur par son nom d'utilisateur.
     *
     * @param string $username
     *
     * @return array
     */
    public function getByName($username)
    {
        $this->load(['login=?', $username]);

        return $this->query;
    }

    /**
     * Vérifie vérifie que le mot de passe saisi et le mot de passe utilisateur est le bon.
     *
     * @param object $user L'utilisateur créé avec le model
     * @param string $password Le mot de passe
     *
     * @return bool Retourne true si l'utilisateur/mot de passe sont les bons, false sinon.
     */
    public function checkUser($user, $password)
    {
        if ($this->hashPwdCheck($password,$user->mdp)) {
            return true;
        }

        return false;
    }

    /**
     * Permet d'update un compte | Non utilisé.
     *
     * @return mixed
     */
    public function updateUser()
    {
        $user = new self();
        $user->copyfrom('POST');

        return $user->save();
    }

    /**
     * Permet de hasher le mot de passe entré par l'utilisateur et de le comparer à celui enregistrer en bdd
     *
     * @param $password string Le mot de passe saisi par l'utilisateur
     * @param $dbPwd string Le mot de passe enregistré en bdd
     *
     * @return bool Vrai si les chaines sont identiques, faux sinon.
     */
    public function hashPwdCheck($password, $dbPwd)
    {
        $hash = $this->f3->get('application.hash');
        $len = strlen($dbPwd);
        $password = hash($hash, $password);
        $password = substr($password, 0, $len);
        return  $password == $dbPwd ? true : false;
    }
    
}
