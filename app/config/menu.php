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
    //Seulement les visiteurs (id 1)
    [
        'roleId'  => 2,
        'content' => [
            // 'libelle' => 'lien'
            'Saisie de fiche de frais' => '/Manager/setFiche',
            'Mes fiches de frais'      => '/Manager/fichesList',
        ],
    ],
];
