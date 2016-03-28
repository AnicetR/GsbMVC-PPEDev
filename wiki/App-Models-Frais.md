App\Models\Frais
===============






* Class name: Frais
* Namespace: App\Models
* Parent class: DB\SQL\Mapper







Methods
-------


### __construct

    mixed App\Models\Frais::__construct($table)





* Visibility: **public**


#### Arguments
* $table **mixed**



### getCurrentBundled

    mixed App\Models\Frais::getCurrentBundled(string $userID)

Récupère les éléments forfaitisés du mois en cours de l'utilisateur.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $userID **string**



### getBundled

    mixed App\Models\Frais::getBundled(string $userID, string $month)

Récupère les éléments forfaitisés de l'utilisateur.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $userID **string**
* $month **string** - &lt;p&gt;au forfait mmyyyy&lt;/p&gt;



### saveBundled

    boolean App\Models\Frais::saveBundled(array $data, string $userId)

Sauvegarde des éléments forfaitisés.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array**
* $userId **string**



### getCurrentNotBundled

    array App\Models\Frais::getCurrentNotBundled($userID)

Récupération des éléments non forfaitisés du mois courrant.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $userID **mixed** - &lt;p&gt;string ID de l&#039;utilisateur&lt;/p&gt;



### getNotBundled

    array App\Models\Frais::getNotBundled($userID, $month)

Récupération des éléments non forfaitisés du mois sélectionné.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $userID **mixed** - &lt;p&gt;string ID de l&#039;utilisateur&lt;/p&gt;
* $month **mixed** - &lt;p&gt;string Mois sélectionné&lt;/p&gt;



### saveNotBundled

    boolean App\Models\Frais::saveNotBundled(array $datas, string $userID)

Sauvegarde de l'élément non forfaitisé.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $datas **array**
* $userID **string** - &lt;p&gt;Id de l&#039;utilisateur&lt;/p&gt;



### deleteNotBundled

    boolean App\Models\Frais::deleteNotBundled(string $userID, integer $id)

Suppression de l'élément non forfaitisé.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $userID **string** - &lt;p&gt;L&#039;id de l&#039;utilisateur&lt;/p&gt;
* $id **integer** - &lt;p&gt;L&#039;id de la ligne de frait hors forfait&lt;/p&gt;


