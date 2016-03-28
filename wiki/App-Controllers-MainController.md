App\Controllers\MainController
===============






* Class name: MainController
* Namespace: App\Controllers





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


### __construct

    mixed App\Controllers\MainController::__construct()

Constructeur du contrôleur global.



* Visibility: **public**




### beforeRoute

    mixed App\Controllers\MainController::beforeRoute()

A faire avant tout routage.

Vérfie que l'utilisateur est bien connecté, sinon, renvoie à l'accueil

* Visibility: **public**




### afterRoute

    mixed App\Controllers\MainController::afterRoute()

A faire après tout routage.



* Visibility: **public**




### getCurrentRoute

    array App\Controllers\MainController::getCurrentRoute()

Connaitre le controlleur et la méthode utilisée pour la page en cours (la route).



* Visibility: **public**




### generateMenu

    array App\Controllers\MainController::generateMenu()

Permet de générer le menu en fonction de l'élévation de l'utilisateur.



* Visibility: **private**



