<?php

namespace App\Controllers\Manager;

use App\Controllers\MainController;
use App\Helpers\Frais as FraisHelper;
use App\Models\Fiche;
use App\Models\Frais;
use App\Models\Role;
use App\Models\User;

class Comptable extends MainController
{


    /**
     * GET Route par défaut du contrôleur
     * Vue interface.
     */
    public function index()
    {
        echo $this->view->render('public/manager/comptable/index.phtml');
    }

    public function pendingFiches()
    {
        $this->f3->set('fiches', Fiche::getClosedFiches());
        echo $this->view->render('public/manager/comptable/index.phtml');
    }

    public function validationFiche()
    {
        $userId = $this->f3->get('PARAMS.userid');
        $month = $this->f3->get('PARAMS.month');

        $ficheFrais = Fiche::getFiche($userId, $month);
        $ficheFrais->fiche['mois'] = FraisHelper::formatMonth($month);
        $this->f3->set('ficheFrais', $ficheFrais);

        $bundledCost = Frais::bundledTotal($userId,$month);
        $notBundledCost = Frais::notBundledTotal($userId,$month);
        $this->f3->set('bundledCost', $bundledCost);
        $this->f3->set('notBundledCost', $notBundledCost);

        echo $this->view->render('public/manager/comptable/validation.phtml');
    }

    public function saveValidationFiche()
    {

    }

    public function invalidateNotBundled()
    {
        $notBundledId = $this->f3->get('PARAMS.notBundledId');
        $userId = $this->f3->get('PARAMS.userid');
        $month = $this->f3->get('PARAMS.month');

        if(Frais::invalidateNotBundled($notBundledId))
            $this->flash->add('L\'élément non forfaitisé a bien été refusé.', 'success');
        else
            $this->flash->add('Un erreur est survenue, l\'élément non forfaitisé n\'a pas pu être refusé. <br/>Merci de réitérer votre tentative ou de contacter le service technique.', 'alert');

        $this->f3->reroute("/Manager/validationFiche/$userId/$month");
    }

    public function revertInvalidateNotBundled()
    {
        $notBundledId = $this->f3->get('PARAMS.notBundledId');
        $userId = $this->f3->get('PARAMS.userid');
        $month = $this->f3->get('PARAMS.month');

        if(Frais::revertInvalidateNotBundled($notBundledId))
            $this->flash->add('L\'élément non forfaitisé a bien été restauré.', 'success');
        else
            $this->flash->add('Un erreur est survenue, l\'élément non forfaitisé n\'a pas pu être restauré. <br/>Merci de réitérer votre tentative ou de contacter le service technique..', 'alert');

        $this->f3->reroute("/Manager/validationFiche/$userId/$month");
    }
}
