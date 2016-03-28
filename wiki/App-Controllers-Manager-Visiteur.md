App\Controllers\Manager\Visiteur
===============






* Class name: Visiteur
* Namespace: App\Controllers\Manager
* Parent class: [App\Controllers\MainController](App-Controllers-MainController.md)





Properties
----------


### $f3

    protected mixed $f3





* Visibility: **protected**


### $view

    protected mixed $view





* Visibility: **protected**


### $flash

    protected mixed $flash





* Visibility: **protected**


Methods
-------


### index

    mixed App\Controllers\Manager\Visiteur::index()

GET Route par défaut du contrôleur
Vue interface.



* Visibility: **public**




### account

    mixed App\Controllers\Manager\Visiteur::account()

GET Route
Affichage des infos du compte.



* Visibility: **public**




### setFiche

    mixed App\Controllers\Manager\Visiteur::setFiche()

GET Route
Affichage de la saisie des fiches.



* Visibility: **public**




### fichesList

    mixed App\Controllers\Manager\Visiteur::fichesList()

GET Route
Affichage de la liste des fiches.



* Visibility: **public**




### saveBundled

    mixed App\Controllers\Manager\Visiteur::saveBundled()

POST Route
Sauvegarder les éléments forfaitisés.



* Visibility: **public**




### saveNotBundled

    mixed App\Controllers\Manager\Visiteur::saveNotBundled()

POST Route
Sauvegarde d'un élément non forfaitisé



* Visibility: **public**




### deleteNotBundled

    mixed App\Controllers\Manager\Visiteur::deleteNotBundled()

Suppression d'un élément non forfaitisé.



* Visibility: **public**




### getUserInfos

    boolean App\Controllers\Manager\Visiteur::getUserInfos()

Récupération des informations du compte utilisateur.



* Visibility: **private**




### __construct

    mixed App\Controllers\MainController::__construct()

Constructeur du contrôleur global.



* Visibility: **public**
* This method is defined by [App\Controllers\MainController](App-Controllers-MainController.md)




### beforeRoute

    mixed App\Controllers\MainController::beforeRoute()

A faire avant tout routage.

Vérfie que l'utilisateur est bien connecté, sinon, renvoie à l'accueil

* Visibility: **public**
* This method is defined by [App\Controllers\MainController](App-Controllers-MainController.md)




### afterRoute

    mixed App\Controllers\MainController::afterRoute()

A faire après tout routage.



* Visibility: **public**
* This method is defined by [App\Controllers\MainController](App-Controllers-MainController.md)




### getCurrentRoute

    array App\Controllers\MainController::getCurrentRoute()

Connaitre le controlleur et la méthode utilisée pour la page en cours (la route).



* Visibility: **public**
* This method is defined by [App\Controllers\MainController](App-Controllers-MainController.md)




### generateMenu

    array App\Controllers\MainController::generateMenu()

Permet de générer le menu en fonction de l'élévation de l'utilisateur.



* Visibility: **private**
* This method is defined by [App\Controllers\MainController](App-Controllers-MainController.md)



