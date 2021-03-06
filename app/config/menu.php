<?php

return [
    /**
     * Tous les membres
     */
    [
        'roleId'  => 0,
        'content' => [
            'Accueil' => '/Manager',
        ],
    ],
    /**
     * Seulement les visiteurs (id 2)
     */
    [
        'roleId'  => 2,
        'content' => [
            // 'libelle' => 'lien'
            'Saisie de fiche de frais' => '/Manager/setFiche',
            'Mes fiches de frais'      => '/Manager/fichesList',
            'Gestion des acces smartphones'      => '/Manager/phoneManagement',
        ],
    ],
    /**
     * Seulement les comptables (id 3)
     */
    [
        'roleId'  => 3,
        'content' => [
            'Fiches de frais à valider' => '/Manager/pendingFiches',
        ],
    ],
];
