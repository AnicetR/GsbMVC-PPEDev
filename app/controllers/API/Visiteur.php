<?php
namespace App\Controllers\API;

use App\Models\User;
use App\Models\Frais;
class Visiteur extends MainAPI{

    private $user, $userID;
    private $data = [];
    
    function __construct()
    {
        parent::__construct();
        $this->user = new User();
        $this->userID = $this->f3->get('userId');
    }

    function getUserInfos()
    {
        foreach ($this->user->getByID($this->userID) as $fieldName => $value)
            $data[$fieldName] = $value;
        echo json_encode($data);
    }

    function getAllDatas()
    {
        $data['fraisForfait'] = Frais::getCurrentBundled($this->userID);
        $data['fraisHorsForfait'] = Frais::getCurrentNotBundled($this->userID);
        echo json_encode($data);
    }

    function saveCurrentBundled()
    {
        $postDatas = $this->f3->get('PUT');
        echo json_encode(Frais::saveBundled($postDatas, $this->userID) ? true : false);
    }

    function saveCurrentNotBundled()
    {
        $postDatas = $this->f3->get('PUT');
        $infos = [];
        $save = true;
        try {
            $exception = false;
            $expected = ['date', 'libelle', 'montant'];
            foreach ($expected as $key) {
                if (!array_key_exists($key, $postDatas)) {
                    $infos[] = ['error' => 'Le champs '.$key.' doit être renseigné'];
                    $exception = true;
                }
            }
            if ($exception) {
                throw new \Exception();
            } elseif (!$date = \DateTime::createFromFormat('Y-m-d', $postDatas['date'])) {
                $infos[] = ['error' => '\'La date d\'engagement doit être valide'];
                throw new \Exception();
            } elseif (!FraisHelper::isLessThanOneYearOld($date)) {
                $infos[] = ['error' => 'La date d\'engagement doit se situer dans l’année écoulée'];
                throw new \Exception();
            }
        } catch (\Exception $e) {
            $save = false;
        }

        if ($save) {
            if (Frais::saveNotBundled($postDatas, $this->f3->get('SESSION.userid'))) {
                $infos[] = ['success' => 'L\'élément non forfaitisé a bien été enregistré.'];
            } else {
                $infos[] = ['error' => 'Un erreur est survenue, l\'élément non forfaitisé n\'a pas pu être enregistré. <br/>Merci de réitérer votre saisie ou de contacter le service technique.'];
            }
        }

        echo json_encode($infos);
    }

}