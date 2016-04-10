<?php

return [
    //Tous les membres
    [
        'roleId'  => 0,
        'content' => [
            // 'libelle' => 'lien'
            'Accueil' => '/Manager',
        ],
    ],
    //Seulement les visiteurs (id 2)
    [
        'roleId'  => 2,
        'content' => [
            // 'libelle' => 'lien'
            'Saisie de fiche de frais' => '/Manager/setFiche',
            'Mes fiches de frais'      => '/Manager/fichesList',
            'Acces application'      => '/Manager/phoneAccess',
        ],
    ],
    //Seulement les comptables (id 3)
    [
        'roleId'  => 3,
        'content' => [
            // 'libelle' => 'lien'
            'Fiches de frais à valider' => '/Manager/pendingFiches',
            //'Historique des validations'      => '/Manager/validationHistory',
        ],
    ],
];
