<?php
/**
 * Class de gestion de la connexion.
 */
namespace App\Controllers;

use App\Models\User;

class Auth extends MainController
{
    /**
     * GET Route par défaut du contrôleur
     * Vue interface.
     */
    public function index()
    {
        // Début exemple de message de debug
            $this->debugbar['messages']->addMessage('Ceci est un message de debug afficher dans la PHP Debugbar (App\Controllers\Auth: ligne 19)');
        //Fin exemple
        echo $this->view->render('public/auth/index.phtml');
    }

    /**
     * POST Route
     * Connexion de l'utilisateur.
     */
    public function login()
    {
        $username = $this->f3->get('POST.username');
        $password = $this->f3->get('POST.password');

        if (empty($username) || empty($password)) {
            $this->flash->add('Champs vides', 'alert');
        } else {
            $user = new User();
            $user->getByName($username);
            if (!$user->dry() && $user->checkUser($user, $password)) {
                $this->f3->set('SESSION.username', $user->login);
                $this->f3->set('SESSION.userid', $user->id);
                $this->f3->set('SESSION.role', $user->role);
                $this->f3->set('SESSION.logged', true);
                $this->f3->reroute('/Manager');
                exit();
            } else {
                $this->flash->add('L\'association nom d\'utilisateur/mot de passe est incorrecte', 'alert');
            }
        }

        $this->f3->reroute('/');
    }

    /**
     * GET Route
     * Déconnexion de l'utilisateur.
     */
    public function logout()
    {
        $this->f3->set('SESSION.logged', false);
        $this->f3->set('SESSION.username', false);
        $this->f3->set('SESSION.role', false);
        $this->f3->reroute('/');
    }

}
