<?php

namespace App\Models;



class User extends MainModel
{
    protected $table = 'utilisateur';
    private $hash;

    public function __construct()
    {
        parent::__construct();
        $this->hash = $this->f3->get('application.hash');
    }

    /**
     * Enregistre un nouvel utilisateur | non utilisé.
     *
     * @param $id
     * @param $username
     * @param $password
     * @param int $roleId
     */
    public function register($id, $username, $password, $roleId = 0)
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
     * @param $userId
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
     * @param $username
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
     * @param $user L'utilisateur créé avec le model
     * @param $password Le mot de passe
     *
     * @return bool Retourne true si l'utilisateur/mot de passe sont les bons, false sinon.
     */
    public function checkUser($user, $password)
    {
        if ($password == $user->mdp) {
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
}
