App\Models\User
===============






* Class name: User
* Namespace: App\Models
* Parent class: [App\Models\MainModel](App-Models-MainModel.md)





Properties
----------


### $table

    protected mixed $table





* Visibility: **protected**


### $hash

    private mixed $hash





* Visibility: **private**


### $f3

    protected mixed $f3





* Visibility: **protected**


### $cache

    protected mixed $cache





* Visibility: **protected**


### $debugbar

    protected mixed $debugbar





* Visibility: **protected**


Methods
-------


### __construct

    mixed App\Models\MainModel::__construct()

MainModel constructor.



* Visibility: **public**
* This method is defined by [App\Models\MainModel](App-Models-MainModel.md)




### register

    mixed App\Models\User::register(string $username, string $password, integer $roleId)

Enregistre un nouvel utilisateur | non utilisé.



* Visibility: **public**


#### Arguments
* $username **string**
* $password **string**
* $roleId **integer**



### getByID

    array App\Models\User::getByID(string $userId)

Retourne l'utilisateur par l'ID.



* Visibility: **public**


#### Arguments
* $userId **string**



### getByName

    array App\Models\User::getByName(string $username)

Retourne l'utilisateur par son nom d'utilisateur.



* Visibility: **public**


#### Arguments
* $username **string**



### checkUser

    boolean App\Models\User::checkUser(object $user, string $password)

Vérifie vérifie que le mot de passe saisi et le mot de passe utilisateur est le bon.



* Visibility: **public**


#### Arguments
* $user **object** - &lt;p&gt;L&#039;utilisateur créé avec le model&lt;/p&gt;
* $password **string** - &lt;p&gt;Le mot de passe&lt;/p&gt;



### updateUser

    mixed App\Models\User::updateUser()

Permet d'update un compte | Non utilisé.



* Visibility: **public**



