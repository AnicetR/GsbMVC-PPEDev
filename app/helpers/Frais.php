<?php

namespace App\Helpers;

use DateTime;

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
