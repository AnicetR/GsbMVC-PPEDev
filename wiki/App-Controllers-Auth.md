App\Controllers\Auth
===============






* Class name: Auth
* Namespace: App\Controllers
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

    mixed App\Controllers\Auth::index()

GET Route par défaut du contrôleur
Vue interface.



* Visibility: **public**




### login

    mixed App\Controllers\Auth::login()

POST Route
Connexion de l'utilisateur.



* Visibility: **public**




### logout

    mixed App\Controllers\Auth::logout()

GET Route
Déconnexion de l'utilisateur.



* Visibility: **public**




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



