App\Models\Fiche
===============






* Class name: Fiche
* Namespace: App\Models
* Parent class: DB\SQL\Mapper







Methods
-------


### __construct

    mixed App\Models\Fiche::__construct(string $table)

Fiche constructor.



* Visibility: **public**


#### Arguments
* $table **string** - &lt;p&gt;Le nom de la table&lt;/p&gt;



### getList

    array App\Models\Fiche::getList(string $userID)

Retourne la liste des mois disponibles pour l'utilisateur.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $userID **string** - &lt;p&gt;L&#039;id de l&#039;utilisateur&lt;/p&gt;



### getFiche

    array App\Models\Fiche::getFiche(string $userID, string $month)

Récupère la fiche de frais séléctionnée.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $userID **string** - &lt;p&gt;L&#039;id de l&#039;utilisateur&lt;/p&gt;
* $month **string** - &lt;p&gt;Le mois concerné&lt;/p&gt;



### createFiche

    mixed App\Models\Fiche::createFiche(string $userID, integer $month)

Créé la fiche correspondant au mois donné pour l'utilisateur donné



* Visibility: **public**
* This method is **static**.


#### Arguments
* $userID **string**
* $month **integer** - &lt;p&gt;au forfait mmyyyy&lt;/p&gt;


