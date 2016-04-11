<?php

namespace App\Controllers\Manager;

use App\Controllers\MainController;
use App\Helpers\Frais as FraisHelper;
use App\Models\Fiche;
use App\Models\Frais;
use App\Models\Role;
use App\Models\User;

class Visiteur extends MainController
{
     /**
     * GET Route par défaut du contrôleur
     * Vue interface.
     */
    public function index()
    {
        $this->getUserInfos();
        echo $this->view->render('public/manager/visiteur/index.phtml');
    }

    /**
     * GET Route
     * Affichage des infos du compte.
     */
    public function account()
    {
        $this->getUserInfos();
        echo $this->view->render('public/manager/visiteur/account.phtml');
        die();
    }

    /**
     * GET Route
     * Affichage de la saisie des fiches.
     */
    public function setFiche()
    {
        $this->getUserInfos();

        $this->f3->set('lignefraisforfait', Frais::getCurrentBundled($this->f3->get('SESSION.userid')));
        $this->f3->set('lignefraishorsforfait', Frais::getCurrentNotBundled($this->f3->get('SESSION.userid')));

        echo $this->view->render('public/manager/visiteur/setFiche.phtml');
        die();
    }

    /**
     * GET Route
     * Affichage de la liste des fiches.
     */
    public function fichesList()
    {
        $this->getUserInfos();

        $ficheList = Fiche::getList($this->f3->get('SESSION.userid'));
        foreach ($ficheList as &$fiche) {
            $fiche->mois = FraisHelper::formatMonth($fiche->mois);
        }
        $this->f3->set('ficheList', $ficheList);

        if (null !== $this->f3->get('PARAMS.month')) {
            $ficheFrais = Fiche::getFiche($this->f3->get('SESSION.userid'), $this->f3->get('PARAMS.month'));
            $ficheFrais->fiche['mois'] = FraisHelper::formatMonth($this->f3->get('PARAMS.month'));
            $this->f3->set('ficheFrais', $ficheFrais);
        }

        echo $this->view->render('public/manager/visiteur/ficheList.phtml');
        die();
    }

    /**
     * POST Route
     * Sauvegarder les éléments forfaitisés.
     */
    public function saveBundled()
    {
        $postDatas = $this->f3->get('POST');

        if (Frais::saveBundled($postDatas, $this->f3->get('SESSION.userid'))) {
            $this->flash->add('Les éléments forfaitisés ont bien été enregistrés.', 'success');
        } else {
            $this->flash->add('Un erreur est survenue, les éléments forfaitisés n\'ont pas pu être enregistré. <br/>Merci de réitérer votre saisie ou de contacter le service technique.', 'alert');
        }

        $this->f3->reroute('/Manager/setFiche');
        exit();
    }

    /**
     * POST Route
     * Sauvegarde d'un élément non forfaitisé
     */
    public function saveNotBundled()
    {
        $postDatas = $this->f3->get('POST');
        $save = true;
        try {
            $exception = false;
            $expected = ['date', 'libelle', 'montant'];
            foreach ($expected as $key) {
                if (!array_key_exists($key, $postDatas)) {
                    $this->flash->add('Le champs <b>'.$key.'</b> doit être renseigné', 'alert');
                    $exception = true;
                }
            }
            if ($exception) {
                throw new \Exception();
            } elseif (!$date = \DateTime::createFromFormat('Y-m-d', $postDatas['date'])) {
                $this->flash->add('\'La date d\'engagement doit être valide', 'alert');
                throw new \Exception();
            } elseif (!FraisHelper::isLessThanOneYearOld($date)) {
                $this->flash->add('La date d\'engagement doit se situer dans l’année écoulée', 'alert');
                throw new \Exception();
            }
        } catch (\Exception $e) {
            $save = false;
        }

        if ($save) {
            if (Frais::saveNotBundled($postDatas, $this->f3->get('SESSION.userid'))) {
                $this->flash->add('L\'élément non forfaitisé a bien été enregistré.', 'success');
            } else {
                $this->flash->add('Un erreur est survenue, l\'élément non forfaitisé n\'a pas pu être enregistré. <br/>Merci de réitérer votre saisie ou de contacter le service technique.', 'alert');
            }
        }

        $this->f3->reroute('/Manager/setFiche');
        exit();
    }

    /**
     * POST Route
     * Suppression d'un élément non forfaitisé.
     *
     * @param $id int id de l'élément non forfaitisé a supprimer
     */
    public function deleteNotBundled()
    {
        if (Frais::deleteNotBundled($this->f3->get('PARAMS.id'), $this->f3->get('SESSION.userid'))) {
            $this->flash->add('L\'élément non forfaitisé a bien été supprimé.', 'success');
        } else {
            $this->flash->add('Un erreur est survenue, l\'élément non forfaitisé n\'a pas pu être supprimé. <br/>Merci de réitérer votre tentative ou de contacter le service technique.', 'alert');
        }

        $this->f3->reroute('/Manager/setFiche');
        exit();
    }


}
