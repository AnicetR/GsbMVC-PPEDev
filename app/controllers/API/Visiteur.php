<?php
namespace App\Controllers\API;

use App\Models\User;
use App\Models\Frais;
use App\Helpers\Frais as FraisHelper;

class Visiteur extends MainAPI{

    private $user;
    private $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
    }

    public function getUserInfos()
    {

        $user = $this->user->getByID($this->f3->get('userId'))[0];
        foreach ($user as $fieldName => $value)
            $this->data[$fieldName] = $value;
        echo json_encode($this->data);
    }

    public function getAllDatas()
    {
        $userId = $this->f3->get('userId');
        $this->data['fraisForfait'] = Frais::getCurrentBundled($userId);
        $this->data['fraisHorsForfait'] = Frais::getCurrentNotBundled($userId);
        echo json_encode($this->data);
    }

    public function saveCurrentBundled()
    {
        $infos = [];
        $userId = $this->f3->get('userId');
        $postDatas = $this->f3->get('GET');
        if(Frais::saveBundled($postDatas, $userId))
            $infos[] = ['success' => "Les frais forfaitisés ont bien été enregistrés."];
        else
            $infos[] = ['error' => "Une erreur est survenue, veuillez réessayer."];

        echo json_encode($infos);
    }

    public function saveCurrentNotBundled()
    {
        $userId = $this->f3->get('userId');
        $postDatas = $this->f3->get('GET');
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
            if (Frais::saveNotBundled($postDatas, $userId)) {
                $infos[] = ['success' => 'L\'élément non forfaitisé a bien été enregistré.'];
            } else {
                $infos[] = ['error' => 'Un erreur est survenue, l\'élément non forfaitisé n\'a pas pu être enregistré. <br/>Merci de réitérer votre saisie ou de contacter le service technique.'];
            }
        }

        echo json_encode($infos);
    }

    public function index(){
        json_encode('test');
    }

}