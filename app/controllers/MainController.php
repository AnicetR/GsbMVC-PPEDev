<?php
/**
 * Classe mère de tous les contrôleurs.
 *
 * Permet d'inclure dans tous les contrôleurs un comportement par défaut ainsi que les utility class dont ils ont besoin
 */
namespace App\Controllers;

use Base;
use FFMVC\Helpers as Helpers;
use Session;
use Template;
use View;
use App\Models\User;
use App\Models\Role;

class MainController
{
    protected $f3, $view, $flash;

    /**
     * Constructeur du contrôleur global.
     */
    public function __construct()
    {
        //Chargement des fonctionnalités du Framework
        $this->f3 = Base::instance();
        //Chargement du système de vues
        $this->view = Template::instance();
        //Chargement des FlashMessages
        $this->flash = Helpers\Messages::instance();

        if ($this->f3->get('application.environment') == 'developpement') {
            //Load debugbar
            $this->debugbar = $this->f3->get('debugbar');
            $this->f3->set('debugbarjs', $this->debugbar->getJavascriptRenderer('/debugbar'));
        }


    }

    /**
     * A faire avant tout routage.
     */
    public function beforeRoute()
    {
        $this->f3->set('menu', $this->generateMenu());
        $this->isConnectedOrReroute();
        $this->isUserAllowedToContentOrReroute();
    }

    /**
     * A faire après tout routage.
     */
    public function afterRoute()
    {
    }


    /**
     * Vérfie que l'utilisateur est bien connecté, sinon, renvoie à l'accueil
     */
    private function isConnectedOrReroute()
    {
        $route = $this->getCurrentRoute();
        if (($route['controller'] != 'Auth') && ($route['method'] != 'login' || $route['method'] != 'index')) {
            if (!$this->f3->get('SESSION.logged')) {
                $this->f3->reroute('/');
                exit;
            }
        }
    }


    private function isUserAllowedToContentOrReroute()
    {
        $allowed = $this->f3->get('menu');

        $current = $this->getCurrentRoute();
        $canGoFurther = false;

        if ($current['method'] != 'index' || empty($current['method'])) {
            $current = '/'.implode('/', $current);
        } else {
            $current = '/'.$current['controller'];
        }

        $this->debugbar['messages']->addMessage($allowed);
        foreach ($allowed as $item)
        {
            if($current == $item['link']){
                $canGoFurther = true;
                break;
            }

        }
        if(!$canGoFurther){
            $this->f3->reroute('/Manager');
            exit();
        }

    }

    /**
     * Connaitre le controlleur et la méthode utilisée pour la page en cours (la route).
     *
     * @return array ['controller' => nom du controller, 'method' => le nom de la méthode]
     */
    public function getCurrentRoute()
    {
        $hive = $this->f3->hive();
        $tmp = explode('->', $hive['ROUTES'][$this->f3->get('PATTERN')][3][$hive['VERB']][0]);
        //On skip les 17 premiers caractères (\App\Controllers\) et on récupère le premier mot
        preg_match('/.{17}(\w+)/', $tmp[0], $tmp[0]);
        $tmp[0] = $tmp[0][1];

        return ['controller' => $tmp[0], 'method' => $tmp[1]];
    }

    /**
     * Permet de générer le menu en fonction de l'élévation de l'utilisateur.
     *
     * @return array Le menu hierarchisé
     */
    private function generateMenu()
    {
        $menu = $this->f3->get('application.menu');
        $role = $this->f3->get('SESSION.role');

        //Récupération du controleur/méthode
        $current = $this->getCurrentRoute();

        if ($current['method'] != 'index' || empty($current['method'])) {
            $current = '/'.implode('/', $current);
        } else {
            $current = '/'.$current['controller'];
        }

        //Création des éléments du menu pour le site
        $return = [];
        foreach ($menu as $element) {
            if ($element['roleId'] == $role || $element['roleId'] == 0 || $role == 1) {
                foreach ($element['content'] as $label => $link) {
                    $return[] = ['label' => $label, 'link' => $link, 'active' => $current == $link ? true : false, 'class' => $current == $link ? '' : 'secondary'];
                }
            }
        }

        return $return;
    }

    /**
     * Récupération des informations du compte utilisateur.
     *
     * @return bool
     */
    protected function getUserInfos()
    {
        $user = new User();
        $tempUser = new \stdClass();
        $user->getByName($this->f3->get('SESSION.username'));
        foreach ($user as $key => $value) {
            if ($key != 'id' && $key != 'dateEmbauche' && $key != 'mdp') {
                $tempUser->$key = $value;
            }
        }
        $tempUser->role = Role::getRoleName($tempUser->role);
        if ($this->f3->set('userInfos', $tempUser)) {
            return true;
        }

        return false;
    }
}
