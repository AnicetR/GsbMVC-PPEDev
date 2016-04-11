<?php

namespace App\Models;

use DB\SQL\Mapper as Mapper;
use Registry;

class Fiche extends Mapper
{
    /**
     * Fiche constructor.
     * @param string $table Le nom de la table
     */
    public function __construct($table)
    {
        parent::__construct(Registry::get('db'), $table);
    }

    /**
     * Retourne la liste des mois disponibles pour l'utilisateur.
     *
     * @param string $userID L'id de l'utilisateur
     *
     * @return array La liste des fiches
     */
    public static function getList($userID)
    {
        $frais = new self('fichefrais');
        $frais->load(['idVisiteur=?', $userID]);

        return $frais->query;
    }

    /**
     * Récupère la fiche de frais séléctionnée.
     *
     * @param string $userID L'id de l'utilisateur
     * @param string $month  Le mois concerné
     *
     * @return array La fiche de frais si elle existe
     */
    public static function getFiche($userID, $month, $lastMonth = null)
    {
        if($lastMonth == null)
            $lastMonth = date('Ym', strtotime('first day of previous month'));

        $ficheFrais = new \stdClass();
        $db = Registry::get('db');
        $request = "
            SELECT fichefrais.nbJustificatifs AS nbJustificatifs, 
                   fichefrais.dateModif AS dateModif, 
                   fichefrais.montantValide AS montantValide, 
                   etat.libelle AS libelle
            FROM fichefrais
            INNER JOIN etat ON fichefrais.idEtat = etat.id
            WHERE fichefrais.idVisiteur = '$userID' AND fichefrais.mois = '$month'
        ";

        $ficheFrais->fiche = $db->exec($request)[0];
        $ficheFrais->bundled = Frais::getBundled($userID, $month, $lastMonth);
        $ficheFrais->notBundled = Frais::getNotBundled($userID, $month);

        return $ficheFrais;
    }

    /**
     * Créé la fiche correspondant au mois donné pour l'utilisateur donné en cloturant celle du mois précèdent
     *
     * @param string $userID
     * @param int $month au forfait mmyyyy
     */
    public static function createFiche($userID, $month)
    {
        $db = Registry::get('db');
        $request = 'SELECT id FROM fraisforfait';
        $fields = $db->exec($request);

        $db->begin();
        $db->exec("UPDATE fichefrais  SET idEtat = 'CL', dateModif = CURRENT_DATE WHERE idVisiteur = '$userID' AND mois = $month");
        $db->exec("INSERT INTO fichefrais (idVisiteur, mois, nbJustificatifs, montantValide, dateModif, idEtat) VALUES ('$userID', '$month', 0, 0, CURRENT_DATE, 'CR')");
        foreach ($fields as $id) {
            $id = $id['id'];
            $db->exec("INSERT INTO lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) VALUES ('$userID', '$month', '$id', 0)");
        }
        $db->commit();
    }

    /**
     * Cloturer la fiche
     *
     * @param string $userID
     * @param int $month au forfait mmyyyy
     */
    public static function closeFiche($userID, $month)
    {
        $frais = new self('fichefrais');
        $frais->load(['idVisiteur=?', 'mois=?', $userID, $month]);
            $frais->idEtat = 'CL';
            $frais->dateModif = date('Y-m-d H:i:s');
        $frais->save();
    }

    /**
     * Récupération de la liste des fiches cloturées
     *
     * @return array Le listing des fiches.
     */
    public static function getClosedFiches()
    {
        $db = Registry::get('db');
        $request = "SELECT ficheFrais.idVisiteur, ficheFrais.mois, utilisateur.nom, utilisateur.prenom FROM fichefrais
                    LEFT JOIN utilisateur ON fichefrais.idVisiteur = utilisateur.id 
                    WHERE idEtat = 'CL'";
        return $db->exec($request);
    }

}
