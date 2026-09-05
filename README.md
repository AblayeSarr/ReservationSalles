
## Réponses aux questions de l’Étape 1

### 1. Quel est le rôle de Composer ?

Composer est le gestionnaire de dépendances de PHP. Il permet d’installer les bibliothèques externes nécessaires au projet, de gérer leurs versions et leurs dépendances, et de générer un système d’autoloading permettant de charger automatiquement les classes PHP sans effectuer de nombreux `require` manuels.

### 2. Quelle différence existe entre `require` et `require-dev` ?

`require` contient les dépendances nécessaires au fonctionnement de l’application, notamment en production.
`require-dev` contient les dépendances uniquement utiles au développement, par exemple PHPUnit pour effectuer les tests. Ces dépendances peuvent être exclues lors d'une installation destinée à la production avec `composer install --no-dev`.

### 3. Pourquoi faut-il versionner `composer.lock` ?

`composer.lock` enregistre les versions exactes des dépendances installées ainsi que leurs dépendances. Le versionner permet à tous les environnements du projet d'utiliser les mêmes versions et garantit ainsi une installation reproductible.

### 4. Pourquoi ne versionne-t-on pas `vendor/` ?

Le dossier `vendor/` contient les bibliothèques installées par Composer. Il peut contenir un grand nombre de fichiers et peut être entièrement reconstruit à partir de `composer.json` et surtout de `composer.lock` avec la commande `composer install`. Il n'est donc pas nécessaire de le versionner dans Git.


# Étape 2 — Configurer Eloquent
## Réponses aux 4 questions théoriques

### Question 1 — Quel rôle joue `Capsule\Manager` ?
`Capsule\Manager` est le composant qui permet d'utiliser **Eloquent ORM en dehors du framework Laravel**.

Il sert à configurer et initialiser Eloquent : il reçoit les paramètres de connexion à la base de données, initialise les différents composants nécessaires et permet aux modèles Eloquent de fonctionner de manière autonome.

### Question 2 — Pourquoi Eloquent peut-il fonctionner sans Laravel ?
Eloquent peut fonctionner sans Laravel parce qu'il est disponible sous forme de composants découplés, notamment avec le package `illuminate/database`.
Laravel utilise ces composants, mais Eloquent n'a pas besoin de tout le framework pour fonctionner.
En configurant manuellement la connexion à la base de données et en utilisant `Capsule\Manager` pour initialiser Eloquent, on peut utiliser l'ORM sans avoir besoin des autres composants de Laravel comme les routes, les vues ou les middlewares.

### Question 3 — Où doit se trouver le démarrage de l'ORM ?
Le démarrage de l'ORM doit être effectué **une seule fois**, dans la partie infrastructure de l'application.
Dans notre projet, cette initialisation se trouve dans :

config/database.php

Ce fichier est responsable du chargement de la configuration et de l'initialisation d'Eloquent.
Les classes métier ne doivent pas initialiser elles-mêmes la connexion à la base de données.
Cette organisation permet de centraliser la configuration et d'éviter de répéter l'initialisation de l'ORM dans plusieurs classes.

### Question 4 — Quelle différence existe entre ORM et SQL écrit à la main ?

**SQL écrit à la main**

* **Syntaxe :** les requêtes sont écrites directement en SQL.
* **Abstraction :** faible, car le code est directement lié au langage SQL et au SGBD.
* **Maintenabilité :** peut devenir difficile lorsque le nombre de requêtes augmente.
* **Sécurité :** il faut gérer correctement les paramètres et utiliser des requêtes préparées pour éviter les injections SQL.
* **Performance :** permet un contrôle direct et précis des requêtes SQL.
* **Lisibilité :** le SQL permet de voir directement les opérations effectuées sur la base de données.

**ORM avec Eloquent**

* **Syntaxe :** les données sont manipulées avec des méthodes et des objets PHP.
* **Abstraction :** plus forte, car l'application manipule des modèles plutôt que des requêtes SQL directement.
* **Maintenabilité :** facilite l'organisation et la maintenance du code grâce aux modèles Eloquent.
* **Sécurité :** facilite l'utilisation de requêtes paramétrées lorsqu'il est utilisé correctement.
* **Performance :** ajoute une couche d'abstraction, mais propose des mécanismes permettant d'optimiser les accès aux données.
* **Lisibilité :** le code est généralement plus proche du modèle métier de l'application.


## Étape 3 — Modèles Eloquent

### 1. Quel type de relation Eloquent existe entre Salle et Reservation ?

La relation entre `Salle` et `Reservation` est une relation **un-à-plusieurs**.
Une salle peut avoir plusieurs réservations. Le modèle `Salle` possède donc plusieurs `Reservation`.
À l'inverse, une réservation appartient à une seule salle.
On utilise donc une relation `hasMany` du côté de `Salle` et une relation `belongsTo` du côté de `Reservation`.

### 2. Pourquoi utiliser `$fillable` ou `$guarded` ?

`$fillable` et `$guarded` permettent de contrôler les attributs qui peuvent être remplis automatiquement lors d'une affectation de masse.
Cela permet d'éviter qu'un utilisateur puisse modifier certains champs sensibles ou techniques du modèle.
Dans notre projet, nous avons choisi `$fillable` afin de définir explicitement les champs que nous autorisons à être remplis.

### 3. Pourquoi caster `active` en booléen ?

La colonne `active` indique si une salle est active ou non. Elle représente donc une valeur vrai/faux.
Le cast en booléen permet à Eloquent de convertir automatiquement la valeur provenant de la base de données en un véritable booléen PHP.
Cela rend la manipulation de cette donnée plus cohérente avec sa signification métier.

### 4. Pourquoi convertir les dates en objets ?

Les champs `date_debut` et `date_fin` représentent des dates et des heures.
Les convertir en objets de date permet de les manipuler et de les comparer plus facilement qu'avec de simples chaînes de caractères.
Cette conversion sera particulièrement utile pour appliquer les règles métier des réservations, notamment vérifier que la date de début est avant la date de fin, que la réservation commence dans le futur et qu'il n'existe pas de chevauchement avec une autre réservation.


