
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


## Étape 4 — Questions théoriques

### 1. Quelle différence entre une migration et un seeder ?

Une migration sert à créer ou modifier la **structure de la base de données** : tables, colonnes, clés étrangères, contraintes, etc.
Un seeder sert à insérer des **données initiales** ou des données de démonstration dans les tables.
Dans ce projet, les migrations créent les tables `salles` et `reservations`, tandis que `database/seed.php` ajoute les salles initiales.

### 2. Pourquoi les données initiales doivent-elles être reproductibles ?

Les données initiales doivent être reproductibles afin de pouvoir exécuter le script plusieurs fois, notamment lors de l'installation ou du développement, sans provoquer d'erreurs ni créer plusieurs fois les mêmes données.
Un seed reproductible permet donc de retrouver un état initial cohérent de la base de données.

### 3. Comment éviter les doublons ?

Avant de créer une salle, le script vérifie si une salle ayant déjà le même **nom et le même bâtiment** existe.
Si elle existe, le script ne la crée pas. Sinon, il l'ajoute à la base de données.
Cette vérification permet d'exécuter plusieurs fois `database/seed.php` sans créer de doublons.


## Étape 5 — Validation

### 1. Pourquoi valider les données avant de les utiliser ?

La validation permet de vérifier que les données reçues respectent les règles attendues avant de les utiliser dans l'application.
Elle permet d'éviter les données invalides, incomplètes ou mal typées et de réduire les risques d'erreurs lors du traitement ou de l'enregistrement en base de données.

### 2. Pourquoi utiliser Respect\Validation ?

Respect\Validation fournit des règles de validation déjà prêtes à l'emploi.
Cela évite de réécrire manuellement les mêmes contrôles et permet d'avoir une validation plus claire, centralisée et facilement maintenable.

### 3. Pourquoi ne pas mettre toute la validation dans le contrôleur ?

Mettre toute la validation dans le contrôleur rendrait celui-ci trop volumineux et mélangerait plusieurs responsabilités.
Une classe dédiée comme `SalleValidator` ou `ReservationValidator` permet de séparer la validation du traitement HTTP et de rendre le code plus facile à tester et à maintenir.

### 4. Quelle différence existe entre validation et règle métier ?

La validation vérifie principalement que les données reçues respectent un format ou une contrainte attendue.
Une règle métier concerne le comportement fonctionnel de l'application.
Par exemple, vérifier qu'un email est valide relève de la validation, tandis que vérifier qu'une salle n'est pas déjà réservée sur le même créneau relève d'une règle métier.

## Étape 6 — DTO

### 1. Quelle différence existe entre un DTO et un modèle Eloquent ?

Un DTO (Data Transfer Object) sert à transporter des données structurées entre différentes couches de l'application.
Un modèle Eloquent représente une donnée persistée en base de données et permet également d'utiliser les fonctionnalités de l'ORM.
Le DTO sert donc au transport des données, tandis que le modèle Eloquent sert notamment à représenter et manipuler les données persistées.

### 2. Pourquoi le DTO ne doit-il pas appeler `save()` ?

Le DTO ne doit pas appeler `save()` car il ne doit pas connaître la base de données ni la manière dont les données sont persistées.
Son rôle est uniquement de transporter des données correctement typées.
La responsabilité de l'enregistrement appartient au Repository.

### 3. À quel moment transforme-t-on les chaînes en dates ?

Les dates reçues depuis HTTP sont initialement des chaînes de caractères.
Après leur validation, elles sont transformées en objets `DateTimeImmutable` lors de la création du DTO.
Le reste de l'application peut ainsi manipuler directement des objets de date plutôt que des chaînes.

### 4. Le DTO doit-il contenir la règle de chevauchement ?

Non.
Le chevauchement est une règle métier concernant les réservations.
Le DTO doit uniquement transporter les données nécessaires à la réservation. La vérification du chevauchement appartient au service métier.

## Étape 7 — Repository

### 1. Eloquent constitue-t-il déjà un accès aux données ?

Oui.
Eloquent fournit déjà des fonctionnalités permettant de rechercher, créer, modifier et supprimer des données en base de données.
Les modèles Eloquent constituent donc déjà une forme d'accès aux données.

### 2. Pourquoi ajouter un Repository au-dessus d'Eloquent ?

Le Repository permet d'isoler l'accès aux données du reste de l'application.
Les contrôleurs et les services n'ont ainsi pas besoin de connaître directement les requêtes Eloquent utilisées pour récupérer ou modifier les données.
Cela permet également de centraliser les requêtes liées à une même entité.

### 3. Cette abstraction est-elle toujours nécessaire ?

Non.
Pour une petite application très simple, utiliser directement Eloquent peut être suffisant.
Dans notre projet, cette abstraction est cependant pertinente car l'architecture demandée impose une séparation entre la logique métier et l'accès aux données.

### 4. Quel avantage apporte-t-elle ?

Le Repository réduit le couplage entre l'application et Eloquent.
Grâce aux interfaces `SalleRepositoryInterface` et `ReservationRepositoryInterface`, les services peuvent dépendre d'un contrat plutôt que d'une implémentation précise.
Cela facilite également les tests, car on peut remplacer le Repository réel par une implémentation en mémoire ou un double de test.


# Étape 8 — Questions

## 1. Pourquoi ces règles ne sont-elles pas dans le contrôleur ?

Les règles métier ne sont pas placées dans le contrôleur car le contrôleur doit principalement gérer les requêtes HTTP et transmettre les données au service.
Les règles métier sont placées dans le service afin de centraliser la logique de l’application, éviter les répétitions et faciliter la maintenance et les tests.
Cette séparation permet également de respecter le principe de responsabilité unique.

## 2. Pourquoi le service dépend-il d’une interface de Repository ?

Le service dépend d’une interface de Repository afin de ne pas être directement lié à une technologie ou à une implémentation particulière.
Cette approche permet de séparer la logique métier de l’accès aux données.
Elle facilite également les tests, car le Repository réel peut être remplacé par un faux Repository lors des tests.
Cette organisation respecte notamment le principe d’inversion des dépendances de SOLID.

## 3. Quelle exception doit être levée en cas de conflit ?

En cas de conflit de réservation, l’exception à lever est l’exception indiquant que la salle est indisponible.
Elle permet de signaler clairement que la salle est déjà réservée sur le créneau demandé et qu’une nouvelle réservation ne peut donc pas être créée.
Les réservations annulées ne doivent plus empêcher une nouvelle réservation.

## 4. Comment tester le service sans MySQL ?

Le service peut être testé sans MySQL en utilisant des mocks ou des stubs à la place des véritables repositories.
Ces faux repositories permettent de simuler différentes situations : une salle inexistante, une salle inactive, un créneau déjà occupé ou encore une réservation valide.
Cette méthode permet de tester uniquement la logique métier du service, sans dépendre d’une base de données réelle.
Cela rend les tests plus rapides, plus simples et plus faciles à contrôler.


# Étape 9 — Contrôleurs et vues

## 1. Pourquoi utiliser des contrôleurs ?

Les contrôleurs servent d'intermédiaires entre les requêtes HTTP et les différentes couches de l'application.
Dans notre projet, les contrôleurs reçoivent les données provenant des formulaires, appellent les validateurs, construisent les DTO nécessaires, utilisent les services métier et redirigent l'utilisateur après une opération réussie.
Ils ne doivent pas contenir directement les règles métier ni effectuer eux-mêmes les requêtes Eloquent.

## 2. Quels contrôleurs ont été créés ?

Deux contrôleurs principaux ont été créés :

* `SalleController` pour gérer les salles ;
* `ReservationController` pour gérer les réservations.

### SalleController

`SalleController` permet de :

* afficher la liste des salles ;
* afficher le détail d'une salle ;
* afficher le formulaire de création ;
* enregistrer une nouvelle salle ;
* afficher le formulaire de modification ;
* enregistrer une modification.

### ReservationController

`ReservationController` permet de :

* afficher la liste des réservations ;
* filtrer les réservations par salle ;
* afficher le détail d'une réservation ;
* afficher le formulaire de création ;
* créer une réservation ;
* annuler une réservation.

## 3. Responsabilité de `store()`

La méthode `store()` d'un contrôleur suit plusieurs étapes :

1. récupérer les données HTTP ;
2. transmettre ces données au validateur ;
3. réafficher le formulaire si les données sont invalides ;
4. transformer les données validées en DTO ;
5. transmettre le DTO au service métier ;
6. rediriger l'utilisateur après une création réussie.

Cette organisation permet au contrôleur de rester centré sur la gestion HTTP et de laisser les règles métier au service.

## 4. Pourquoi les contrôleurs n'utilisent-ils pas directement Eloquent ?

Les contrôleurs ne doivent pas effectuer directement des requêtes comme :

```php
Salle::query()
Reservation::where(...)
$model->save()
```

L'accès aux données est isolé dans les repositories.

Le contrôleur utilise donc les interfaces de repository et les services qui lui sont injectés. Cela permet de respecter la séparation des responsabilités et de réduire le couplage avec Eloquent.

## 5. Les vues

Les vues sont organisées dans le dossier `templates/`.

### Vues des salles

templates/salle/
├── index.php
├── show.php
└── form.php

### Vues des réservations

templates/reservation/
├── index.php
├── show.php
└── form.php

### Vues d'erreur

templates/error/
├── 404.php
├── 405.php
└── 500.php

Les vues utilisent également un layout commun :
templates/layout/base.php

## 6. Contraintes appliquées aux vues

Les vues ne doivent :

* ni appeler Eloquent ;
* ni accéder au conteneur d'injection ;
* ni contenir de règles métier.

Toutes les données dynamiques affichées sont échappées afin d'éviter l'injection de contenu HTML ou JavaScript.
Après une requête POST réussie, le contrôleur effectue une redirection afin d'éviter de resoumettre le formulaire lors d'un rafraîchissement de la page.

## 7. Séparation des responsabilités
L'organisation obtenue est donc :

Requête HTTP
     ↓
Contrôleur
     ↓
Validateur
     ↓
DTO
     ↓
Service métier
     ↓
Repository
     ↓
Eloquent
     ↓
Base de données

Cette séparation permet de maintenir une architecture claire et de tester les différentes parties de l'application indépendamment.
