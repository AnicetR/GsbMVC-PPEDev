<?php

namespace App\Helpers;

use DateTime;

/**
 * Class Frais
 * Helper pour les frais.
 * @package App\Helpers
 */
class Frais
{
    /**
     * Retourne le mois formaté correctement pour l'affichage dans la liste de frais.
     *
     * @param string $date La date au format 201603
     *
     * @return string La date au format 03/2016
     */
    public static function formatMonth($date)
    {
        $year = substr($date, 0, 4);
        $month = substr($date, 4, 2);

        return $month.'/'.$year;
    }

    /**
     * Retourne le mois formaté en version compacte.
     *
     * @param string $date La date au format 03/2016
     *
     * @return string La date au format 201603
     */
    public static function deformatMonth($date)
    {
        $date = explode('/', $date);

        return $date[1].$date[0];
    }

    /**
     * Permet de vérifier que la date n'est pas plus vieille qu'un an ou n'est pas dans le futur.
     *
     * @param $date une instance de DateTime
     * 
     * @return bool vrai si la date est inférieure à un an ou qu'elle n'est pas dans le futur, faux sinon
     */
    public static function isLessThanOneYearOld($date)
    {
        $currentDate = new DateTime();
        $interval = $date->diff($currentDate)->y;
        $invert = $date->diff($currentDate)->invert;
        if ($interval == 0 && !$invert) {
            return true;
        }
        return false;
    }
}
