<?php

namespace App\Models;

use DB\SQL\Mapper as Mapper;
use App\Models\Frais;
use Registry;


class Fiche extends Mapper
{

    public function __construct($table)
    {
        parent::__construct(Registry::get('db'), $table);
    }

    /**
     * Retourne la liste des mois disponibles pour l'utilisateur
     *
     * @param $userID L'id de l'utilisateur
     * @return array La liste des fiches
     */
    public static function getList($userID)
    {
        $frais = new self('fichefrais');
        $frais->load(['idVisiteur=?', $userID]);
        return $frais->query;
    }

    /**
     * Récupère la fiche de frais séléctionnée
     *
     * @param string $userID L'id de l'utilisateur
     * @param string $month Le mois concerné
     * @return array La fiche de frais si elle existe
     */
    public static function getFiche($userID, $month)
    {
        $ficheFrais = new \stdClass();
        $db = Registry::get('db');
        $request = "
            SELECT fichefrais.nbJustificatifs AS nbJustificatifs, 
                   fichefrais.dateModif AS dateModif, 
                   fichefrais.montantValide AS montantValide, 
                   etat.libelle AS libelle
            FROM fichefrais
            INNER JOIN etat ON fichefrais.idEtat = etat.id
            WHERE fichefrais.idVisiteur = '$userID' AND fichefrais.mois = $month
        ";

        $ficheFrais->fiche = $db->exec($request)[0];
        $ficheFrais->bundled = Frais::getBundled($userID, $month);
        $ficheFrais->notBundled = Frais::getNotBundled($userID, $month);

        return $ficheFrais;
    }

    public static function createFiche($userID, $month)
    {
        $db = Registry::get('db');
        $request = "SELECT id FROM fraisforfait";
        $fields = $db->exec($request);

        $db->begin();
        $db->exec("INSERT INTO fichefrais (idVisiteur, mois, nbJustificatifs, montantValide, dateModif, idEtat) VALUES ('$userID', '$month', 0, 0, CURRENT_DATE, 'CR')");
        foreach ($fields as $id) {
            $id = $id['id'];
            $db->exec("INSERT INTO lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) VALUES ('$userID', '$month', '$id', 0)");
        }
        $db->commit();
    }
}