# GSB MVC [![StyleCI](https://styleci.io/repos/54356082/shield)](https://styleci.io/repos/54356082)

Site de test : http://gsb.anicet.xyz
Utilisateurs de test:

- Comptable :
   - Identifiant : lvillachane
   - Mot de passe : jux7g
- Visiteur : 
   - Identifiant : cbedos
   - Mot de passe : gmhxd

Documentation technique : [http://docs.gsbmvc.anicetreglat.me](http://docs.gsbmvc.anicetreglat.me/)

# Descriptif de la situation professionnelle

## Contexte

Le laboratoire Galaxy Swiss Bourdin (GSB) est issu de la fusion entre le géant américain Galaxy (spécialisé dans le secteur des maladies virales dont le SIDA et les hépatites) et le conglomérat européen Swiss Bourdin (travaillant sur des médicaments plus conventionnels), lui-même déjà union de trois petits laboratoires. En 2009, les deux géants pharmaceutiques ont uni leurs forces pour créer un leader de ce secteur industriel. L'entité Galaxy Swiss Bourdin Europe a établi son siège administratif à Paris. Le siège social de la multinationale est situé à Philadelphie, Pennsylvanie, aux Etats-Unis.

## Besoins

Dans le contexte GSB, le suivi des frais est actuellement géré de plusieurs façons selon le laboratoire d'origine des visiteurs. On souhaite uniformiser cette gestion. Il nous est donc demandé de réaliser une solution web permettant la gestion des fiches de frais pour l'ensemble des acteurs de l'entreprise

# Descriptif de l'environnement de réalisation

### Environnement XAMPP :

- Base de données MySQL
- Serveur Apache

### Développement grâce à l'IDE PHPStorm :

- Langages frontend : HTML5, CSS3, Javascript
- Langages backend : PHP, SQL
- Utilisation de la bibliothèque jQuery
- Utilisation du framework front-end foundation
- Utilisation du framework backend Fat Free Framework
- Utilisation d'une gestion de version GIT dans PHPStorm avec Github

### Productions réalisées

- Réécriture complète de l'application GSBMVC fournie par le CNED
- Développement de la partie comptable conforme au cahier des charges
- « Hashage » des mots de passes
- Interface d'autorisation de téléphones mobiles pour l'application GSB App

# Remarques préalables

Le modèle MVC proposé pour cette application est un modèle implémentant les normes PSR-0, PSR-1, PSR-2et PSR-4. Cette implémentation a été faite au-dessus de Fat Free Framework, en utilisant composer pour la gestion des dépendances et de l'autoload.

Le choix d'utiliser Fat Free Framework et de construire ma propre architecture a été fait de façon à optimiser au maximum les performances de mon application. Etant un utilisateur de Laravel 4.2, il en reprend certains des concepts que je trouve intéressants.

De même, je voulais être capable d'expliquer chacune des subtilités de l'application présentée, le choix d'une architecture « maison » s'est alors imposé.

# Pourquoi Fat Free Framework ?

Le développement complet d'un microframework aurait été très long, j'ai alors passé beaucoup de temps à comparer les microframeworks du marché afin d'utiliser le plus en adéquation avec mes besoins.

## Tableau comparatif

L'indicateur de comparaison est une note sur 10 que j'ai donnée arbitrairement en fonction de la simplicité d'utilisation et de sa présence ou non.

| **Point de comparaison** | **Slim** | **Lumen** | **Fat Free Framework** | **Silex** |
| --- | --- | --- | --- | --- |
| **Routeur** | 10 | 10 | 10 | 10 |
| **Gestion API REST** | 5 | 8 | 2 | 8 |
| **Flexibilité et modularité** | 8 | 3 | 9.5 | 9 |
| **ORM** | Non | Oui | Oui | Oui |
| **Qualité de documentation** | 6 | 7.5 | 9.5 | 6 |
| **Moteur de templates intégré** | Non | Non | Oui | Non |

## Conclusion

Après avoir effectué ces comparaisons, Fat Free Framework m'a paru être un choix avisé.
 Ultra modulaire, c'est un véritable couteau suisse. A la plupart des besoins il a la réponse, pas forcément la meilleure, mais une réponse quand même. Un bon exemple est sa gestion des templates : confortable mais perfectible.

# GSBMVC

## Architecture et organisation des fichiers
![alt tag](http://puu.sh/p1wbr/1be5aa682e.png)

**App :** contient les fichiers de travail disponible au développeur afin de créer le backend de l'application.

  - **Config :** Contient les éléments de configuration de l'application
    - ini : Les routes de l'appli
    - ini : La config d'environnement de l'appli
  - **Controllers :** Contient les contrôleurs de l'application
    - **MainController.php :** Le controlleur « père » pour toutes les routes, gère les besoins communs à tous les controlleurs.
    - **API :** Contient les controleurs d'API de l'application
      - **MainAPI.php :** Le controlleur « père » pour toutes les routes de l'API, gère les besoins communs à tous les controlleurs de l'API.
  - **Helpers :** Les class helpers de l'application.
  - **Models :** Les modèles d'accès aux données de l'application
    - **MainModel.php :** Le model « père » de tous les models de l'appli, gère les besoins communs à tous les models.
  - **Template :** Les templates de l'application au format phtml.
  - **App.php :** Le bootstrapper de l'application.
- **Public :** contient le fichier d'accès à l'appli ainsi que toutes les ressources client de l'application (CSS, JS, images… etc).
  - **Index.php :** fichier d'accès à l'appli
  - **.htaccess :** Gestion du rewriteEngine et des AccesOrigin/Headers
- **Tmp :** sert à stocker les fichiers temporaires de l'application (caches etc).
- **Vendor :** contient toutes les dépendances de l'application gérées par composer.
- **Conposer.json :** La configuration de composer et des dépendances de l'application.

## Cycle de vie d'une requête dans l'application

![alt tag](http://puu.sh/p1wfw/5375eba365.png)

## Normes de développement

Les normes de développement sont celles définies par les PSR cités plus haut :

- PSR-0 : [http://www.php-fig.org/psr/psr-0/](http://www.php-fig.org/psr/psr-0/)
- PSR-1 : [http://www.php-fig.org/psr/psr-1/](http://www.php-fig.org/psr/psr-1/)
- PSR-2 : [http://www.php-fig.org/psr/psr-2/](http://www.php-fig.org/psr/psr-2/)
- PSR-4 : [http://www.php-fig.org/psr/psr-4/](http://www.php-fig.org/psr/psr-4/)

Le respect de ces normes est assuré par les définitions de l'architecture (pour l'organisation des fichiers et l'autoload), quant à la présentation du code, elles sont assurées par un outil nommé StyleCI.

Cet outil développé par Laravel permet de sélectionner une norme de présentation du code, ou de définir la sienne, afin de l'appliquer à chaque commit réalisé sur le repo git sous forme de merge request. J'utilise personnellement celle par défaut, compatible avec les normes PSR-1 et PSR-2. https://styleci.io/


# Installation

## Pré-requis :
- Avoir installé nodeJS et l'avoir ajouté au PATH
- Avoir installé composer et l'avoir ajouté au PATH
- Avoir mis en place la base de donnée (gsb.sql)

## Initialisation de l'application :
```sh
> composer install
```

## Dépendances & sources de documentation :
- Fat Free Framework (http://fatfreeframework.com/)
- FF MVC (https://github.com/vijinho/FFMVC)
- PHP DebugBar (http://phpdebugbar.com/)
- Foundation (http://foundation.zurb.com/)

## Crédits
- ViJinho pour f3-boilerplate utilisé, modifié et simplifié pour gsb


