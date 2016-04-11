<?php

namespace App\Models;

use DB\SQL\Mapper as Mapper;
use Exception;
use Registry;

class Frais extends Mapper
{
    public function __construct($table)
    {
        parent::__construct(Registry::get('db'), $table);
    }

    /**
     * Récupère les éléments forfaitisés du mois en cours de l'utilisateur.
     *
     * @param string $userID
     *
     * @return mixed
     */
    public static function getCurrentBundled($userID)
    {
        $month = date('Ym');
        $lastMonth = date('Ym', strtotime('first day of previous month'));

        return self::getBundled($userID, $month, $lastMonth);
    }

    /**
     * Récupère les éléments forfaitisés de l'utilisateur.
     *
     * @param string $userID
     * @param string $month au format mmyyyy
     * @param string $lastMonth au format mmyyyy
     *
     * @return mixed
     */
    public static function getBundled($userID, $month, $lastMonth)
    {
        $db = Registry::get('db');
        $request = "
            SELECT fraisforfait.id AS idFrais, fraisforfait.libelle AS libelle, lignefraisforfait.quantite AS quantite, (lignefraisforfait.quantite * fraisforfait.montant) AS montant
            FROM lignefraisforfait
            INNER JOIN fraisforfait ON fraisforfait.id = lignefraisforfait.idfraisforfait
            WHERE lignefraisforfait.idVisiteur = '$userID' AND lignefraisforfait.mois = $month
            ORDER BY lignefraisforfait.idfraisforfait
        ";

        $bundled = $db->exec($request);

        if (!is_array($bundled) || empty($bundled)) {
            Fiche::createFiche($userID, $month);
            Fiche::closeFiche($userID, $lastMonth);
            return self::getBundled($userID, $month, $lastMonth);
        }

        return $bundled;
    }

    /**
     * Sauvegarde des éléments forfaitisés.
     *
     * @param array $data
     * @param string $userId
     *
     * @return bool
     */
    public static function saveBundled(array $data, $userId)
    {
        $date = date('Ym');
        try {
            $frais = new self('lignefraisforfait');
            foreach ($data as $key => $value) {
                $frais->reset();
                $frais->load(['idVisiteur=? AND mois=? AND idFraisForfait =?', $userId, $date, $key]);
                $frais->idVisiteur = $userId;
                $frais->idFraisForfait = $key;
                $frais->mois = $date;
                $frais->quantite = $value;
                $frais->save();
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Récupération des éléments non forfaitisés du mois courrant.
     *
     * @param $userID string ID de l'utilisateur
     *
     * @return array Les éléments non forfaitisés du mois courrant
     */
    public static function getCurrentNotBundled($userID)
    {
        $month = date('Ym');

        return self::getNotBundled($userID, $month);
    }

    /**
     * Récupération des éléments non forfaitisés du mois sélectionné.
     *
     * @param $userID string ID de l'utilisateur
     * @param $month string Mois sélectionné
     *
     * @return array Les éléments non forfaitisés du mois sélectionné
     */
    public static function getNotBundled($userID, $month)
    {
        $frais = new self('lignefraishorsforfait');
        $frais->load(['idVisiteur=? AND mois=?', $userID, $month]);

        return $frais->query;
    }

    /**
     * Sauvegarde de l'élément non forfaitisé.
     *
     * @param array $data  les données à enregistrer, au format ['']
     * @param string $userID Id de l'utilisateur
     *
     * @return bool renvoie true si l'élément a été enregistré, false si ce n'est pas le cas
     */
    public static function saveNotBundled(array $datas, $userID)
    {
        $month = date('Ym');
        $frais = new self('lignefraishorsforfait');
        $frais->idVisiteur = $userID;
        $frais->mois = $month;
        $frais->copyfrom($datas);
        if ($frais->save()) {
            return true;
        }

        return false;
    }

    /**
     * Suppression de l'élément non forfaitisé.
     *
     * @param string $userID L'id de l'utilisateur
     * @param int $id     L'id de la ligne de frait hors forfait
     *
     * @return bool renvoie true si l'élément existe et a été supprimé, false si ce n'est pas le cas
     */
    public static function deleteNotBundled($userID, $id)
    {
        $frais = new self('lignefraishorsforfait');
        $frais->load(['id=? AND idVisiteur=?', $userID, $id]);
        if (!empty($frais->id) && $frais->erase()) {
            return true;
        }

        return false;
    }

    /**
     * Calcule le total des couts des éléments forfaitisés pour le mois selectionné
     *
     * @param string $userID L'id de l'utilisateur
     * @param string $month Le mois au format 201603
     * 
     * @return array Un array contenant le total des couts
     */
    public static function bundledTotal($userID, $month)
    {
        $db = Registry::get('db');
        $request = "
            SELECT SUM(lignefraisforfait.quantite * fraisforfait.montant) AS total
            FROM lignefraisforfait
            INNER JOIN fraisforfait ON fraisforfait.id = lignefraisforfait.idfraisforfait
            WHERE lignefraisforfait.idVisiteur = '$userID' AND lignefraisforfait.mois = $month
        ";
        $bundled = $db->exec($request);

        return $bundled[0];

    }

    /**
     * Calcule le total des couts des éléments non forfaitisés pour le mois selectionné
     *
     * @param string $userID L'id de l'utilisateur
     * @param string $month Le mois au format 201603
     *
     * @return array Un array contenant le total des couts
     */
    public static function notBundledTotal($userID, $month)
    {
        $db = Registry::get('db');
        $request = "
            SELECT SUM(montant) AS total
            FROM lignefraishorsforfait
            WHERE idVisiteur = '$userID' AND mois = $month AND libelle NOT LIKE 'REFUSE :%'
        ";

        $notBundled = $db->exec($request);

        return $notBundled[0];
    }

    /**
     * Refuse un frais non forfaitisé
     *
     * @param int $id l'ID du frais
     *
     * @return bool Vrai si le frais a été refusé, faux s'il y a eu une erreur
     */
    public static function invalidateNotBundled($id)
    {
        $frais = new self('lignefraishorsforfait');
        $sauvegarde = new self('lignefraishorsforfait_sauvegarde');

        $frais->load(['id=?', $id]);
        $newId = self::addToCurrentNotBundled($frais);
        $sauvegarde->id = $frais->id;
        $sauvegarde->newid = $newId;
        $sauvegarde->libelle = $frais->libelle;
        $frais->libelle = 'REFUSE : '.$frais->libelle;

        if($sauvegarde->save() && $frais->save())
            return true;
        return false;
    }

    /**
     * Restaure le frais non forfaitisé refusé
     *
     * @param int $id l'ID du frais
     *
     * @return bool Vrai si le frais a été restauré, faux s'il y a eu une erreur
     */
    public static function revertInvalidateNotBundled($id)
    {
        $frais = new self('lignefraishorsforfait');
        $newFrais = new self('lignefraishorsforfait');
        $sauvegarde = new self('lignefraishorsforfait_sauvegarde');

        $sauvegarde->load(['id=?', $id]);
        $frais->load(['id=?', $id]);
        $newFrais->load(['id=?', $sauvegarde->newid]);

        $frais->libelle = $sauvegarde->libelle;

        if($sauvegarde->erase() && $frais->save() && $newFrais->erase())
            return true;
        return false;
    }

    /**
     * Ajout de la ligne de frais non forfaitisé à la fiche de frais du mois en cours.
     *
     * @param object $frais Le frais
     *
     * @return int L'id de la ligne de frais hors forfait que l'on vient de créer.
     */
    public static function addToCurrentNotBundled($frais)
    {
        $db = Registry::get('db');
        $lastMonthReq = "SELECT idVisiteur, MAX(mois) as mois FROM lignefraisforfait WHERE idVisiteur = '$frais->idVisiteur'";
        $lastMonth = $db->exec($lastMonthReq);

        $last = new self('lignefraishorsforfait');
        $last->idVisiteur = $frais->idVisiteur;
        $last->libelle = $frais->libelle;
        $last->montant = $frais->montant;
        $last->date = $frais->date;
        $last->mois = $lastMonth[0]['mois'];
        $last->save();

        return $last->id;
    }
}
