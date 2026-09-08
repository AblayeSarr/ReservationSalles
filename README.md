# ReservationSalles

Application web PHP de gestion des réservations de salles universitaires.

Le projet permet de gérer les salles disponibles dans une université ainsi que les réservations associées, tout en appliquant les règles métier nécessaires pour éviter les conflits de réservation.

---

# 1. Présentation du projet

L'application permet de :

* consulter les salles ;
* consulter le détail d'une salle ;
* créer une salle ;
* modifier une salle ;
* activer ou désactiver une salle ;
* consulter les réservations ;
* filtrer les réservations par salle ;
* consulter le détail d'une réservation ;
* créer une réservation ;
* annuler une réservation ;
* empêcher les réservations incompatibles avec les règles métier.

Le projet est réalisé en PHP orienté objet, sans framework complet.

Les composants utilisés sont :

* FastRoute pour le routage HTTP ;
* Respect\Validation pour la validation ;
* Eloquent ORM pour l'accès aux données ;
* PHP-DI pour l'injection de dépendances ;
* Dotenv pour la configuration de l'environnement ;
* PHPUnit pour les tests.

---

# 2. Prérequis

Pour installer et exécuter le projet, il faut disposer de :

* PHP 8.2 ou supérieur ;
* Composer ;
* MySQL 8 ou supérieur ;
* Git ;
* l'extension PHP PDO MySQL ;
* l'extension PHP mbstring ;
* l'extension PHP XML, nécessaire notamment pour PHPUnit.

Vérifier PHP :

```bash
php -v
```

Vérifier Composer :

```bash
composer --version
```

Vérifier Git :

```bash
git --version
```

Vérifier MySQL :

```bash
mysql --version
```

---

# 3. Récupération du projet

Cloner le dépôt :

```bash
git clone https://github.com/AblayeSarr/ReservationSalles.git
```

Entrer dans le projet :

```bash
cd ReservationSalles
```

---

# 4. Installation des dépendances

Installer les dépendances définies dans `composer.lock` :

```bash
composer install
```

Cette commande installe notamment :

* `nikic/fast-route`
* `respect/validation`
* `illuminate/database`
* `php-di/php-di`
* `vlucas/phpdotenv`

Les dépendances de développement, notamment PHPUnit, sont également installées.

Le dossier `vendor/` est généré automatiquement par Composer et n'est pas versionné.

---

# 5. Configuration de l'environnement

Copier le fichier d'exemple :

```bash
cp .env.example .env
```

Puis modifier `.env` avec les paramètres correspondant à votre installation MySQL.

Exemple :

```env
APP_ENV=development
APP_DEBUG=true
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reservation_salles
DB_USERNAME=reservebd
DB_PASSWORD=votre_mot_de_passe
```

Le fichier `.env` contient les informations propres à l'environnement local et ne doit pas être versionné.

Le fichier `.env.example` constitue uniquement un modèle de configuration et ne doit contenir aucun secret.

---

# 6. Création de la base de données

Créer la base de données MySQL :

```sql
CREATE DATABASE reservation_salles
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Créer ensuite l'utilisateur si nécessaire :

```sql
CREATE USER 'reservebd'@'localhost' IDENTIFIED BY 'votre_mot_de_passe';
```

Accorder les droits :

```sql
GRANT ALL PRIVILEGES ON reservation_salles.* TO 'reservebd'@'localhost';

FLUSH PRIVILEGES;
```

La configuration réelle utilisée par l'application est définie dans `.env`.

---

# 7. Configuration d'Eloquent

L'initialisation d'Eloquent est centralisée dans :

```text
config/database.php
```

Ce fichier :

1. charge les variables d'environnement ;
2. configure la connexion MySQL ;
3. initialise `Capsule\Manager` ;
4. configure Eloquent ;
5. rend Eloquent disponible pour les modèles.

L'ORM est initialisé une seule fois dans la partie infrastructure de l'application.

Les classes métier n'ont pas à créer elles-mêmes la connexion à la base de données.

---

# 8. Création des tables

Les migrations sont situées dans :

```text
database/migrations/
```

Elles sont composées de :

```text
001_create_salles_table.php
002_create_reservations_table.php
```

## Table `salles`

La table contient notamment :

* `id`
* `nom`
* `batiment`
* `capacite`
* `type`
* `active`
* `created_at`
* `updated_at`

Les types de salles prévus sont :

* `cours`
* `informatique`
* `laboratoire`
* `amphitheatre`
* `reunion`

## Table `reservations`

La table contient notamment :

* `id`
* `salle_id`
* `responsable`
* `email`
* `motif`
* `date_debut`
* `date_fin`
* `statut`
* `created_at`
* `updated_at`

La réservation possède une clé étrangère vers la salle.

---

# 9. Exécution des migrations

Les migrations de ce projet sont des scripts PHP.

Depuis la racine du projet, elles peuvent être exécutées avec PHP après avoir vérifié la configuration de la base de données.

Les scripts doivent être exécutés dans l'ordre :

```bash
php database/migrations/001_create_salles_table.php

php database/migrations/002_create_reservations_table.php
```

Après leur exécution, vérifier les tables :

```sql
USE reservation_salles;

SHOW TABLES;
```

Les tables attendues sont :

```text
salles
reservations
```

---

# 10. Ajout des données initiales

Le script de données initiales se trouve ici :

```text
database/seed.php
```

Il permet d'ajouter les salles initiales du projet.

Les données initiales comprennent notamment :

| Salle                | Bâtiment   | Capacité | Type         |
| -------------------- | ---------- | -------: | ------------ |
| Amphithéâtre A       | Bâtiment A |      250 | amphitheatre |
| Salle B12            | Bâtiment B |       40 | cours        |
| Laboratoire Chimie   | Bâtiment C |       24 | laboratoire  |
| Salle Informatique 1 | Bâtiment D |       30 | informatique |
| Salle de réunion     | Bâtiment E |       12 | reunion      |

Le seed vérifie l'existence d'une salle avant de l'insérer afin d'éviter les doublons.

Le script est donc reproductible.

Exécuter :

```bash
php database/seed.php
```

---

# 11. Lancement de l'application

Le point d'entrée HTTP unique de l'application est :

```text
public/index.php
```

Lancer le serveur PHP intégré depuis la racine du projet :

```bash
php -S localhost:8000 -t public
```

Puis ouvrir :

```text
http://localhost:8000
```

L'application utilise `public/index.php` comme Front Controller.

Les requêtes sont ensuite transmises au routeur FastRoute puis au contrôleur approprié.

---

# 12. Routage

Les routes sont définies dans :

```text
routes/web.php
```

Principales routes :

```text
GET  /
GET  /salles
GET  /salles/create
POST /salles
GET  /salles/{id}
GET  /salles/{id}/edit
POST /salles/{id}/edit
GET  /reservations
GET  /reservations/create
POST /reservations
GET  /reservations/{id}
POST /reservations/{id}/cancel
```

FastRoute permet notamment de gérer :

* les routes existantes ;
* les paramètres dynamiques ;
* les erreurs 404 ;
* les méthodes HTTP non autorisées avec une réponse 405 ;
* l'en-tête `Allow`.

---

# 13. Validation

La validation est séparée des contrôleurs.

Les validateurs se trouvent dans :

```text
src/Validation/
```

On trouve notamment :

```text
SalleValidator.php
ReservationValidator.php
ValidationResult.php
ValidatorInterface.php
```

La bibliothèque Respect\Validation est utilisée pour appliquer les contraintes de validation.

Exemples de contrôles :

* email valide ;
* responsable obligatoire ;
* capacité positive ;
* type de salle autorisé ;
* motif suffisamment long ;
* dates correctement renseignées.

La validation est effectuée avant d'utiliser les données dans la logique métier.

---

# 14. DTO

Les DTO se trouvent dans :

```text
src/DTO/
```

Les principaux DTO sont :

```text
CreerSalleDTO.php
CreerSalleDTOBuilder.php
CreerReservationDTO.php
CreerReservationDTOBuilder.php
```

Un DTO sert à transporter des données structurées entre les différentes couches.

Il ne contient pas la logique de persistance.

Il ne doit notamment pas appeler :

```php
$model->save();
```

La persistance appartient aux repositories.

Les dates validées sont transformées en objets `DateTimeImmutable` avant d'entrer dans la logique métier.

---

# 15. Modèles Eloquent

Les modèles se trouvent dans :

```text
src/Model/
```

Ils sont composés de :

```text
Salle.php
Reservation.php
```

La relation entre les deux modèles est une relation un-à-plusieurs.

Une salle peut avoir plusieurs réservations.

Une réservation appartient à une salle.

Le modèle `Salle` utilise notamment le mécanisme de remplissage autorisé d'Eloquent avec les champs :

```text
nom
batiment
capacite
type
active
```

Le champ `active` est casté en booléen.

Les champs de dates de `Reservation` sont castés en objets de date.

---

# 16. Repositories

Les repositories sont situés dans :

```text
src/Repository/
```

Ils permettent d'isoler l'accès aux données.

On trouve :

```text
SalleRepositoryInterface.php
SalleRepository.php
ReservationRepositoryInterface.php
ReservationRepository.php
```

Les services dépendent des interfaces plutôt que directement des implémentations.

Cela permet notamment :

* de réduire le couplage ;
* de centraliser les accès aux données ;
* de remplacer les repositories par des faux objets pendant les tests ;
* de mieux séparer la logique métier et la persistance.

---

# 17. Gestion des chevauchements

Une réservation confirmée empêche une autre réservation de se placer sur un créneau qui la chevauche.

La condition utilisée est :

```text
nouveau début < fin existante

ET

nouvelle fin > début existant
```

Sous forme logique :

```text
new_start < existing_end

AND

new_end > existing_start
```

Deux réservations voisines ne sont pas considérées comme conflictuelles.

Par exemple :

```text
Réservation existante : 10h00 → 12h00

Nouvelle réservation  : 12h00 → 14h00
```

Cette nouvelle réservation est autorisée.

Les réservations annulées ne bloquent pas un créneau.

---

# 18. Services métier

Les services se trouvent dans :

```text
src/Service/
```

Les principaux services sont :

```text
CreerReservationService.php
AnnulerReservationService.php
```

Le service de création de réservation applique les règles métier avant de créer une réservation.

Les règles principales sont :

1. la salle doit exister ;
2. la salle doit être active ;
3. le responsable doit être renseigné ;
4. l'adresse email doit être valide ;
5. le motif doit contenir entre 5 et 255 caractères ;
6. la date de début doit être avant la date de fin ;
7. la réservation ne doit pas dépasser 4 heures ;
8. la réservation doit commencer dans le futur ;
9. aucune réservation confirmée ne doit chevaucher le créneau demandé.

Les règles métier ne sont donc pas placées dans les contrôleurs.

---

# 19. Exceptions métier

Les exceptions métier se trouvent dans :

```text
src/Exception/
```

Notamment :

```text
SalleIndisponibleException.php
ReservationIntrouvableException.php
```

Elles permettent de représenter explicitement certaines situations métier.

Par exemple, lorsqu'une salle est déjà réservée sur le créneau demandé, le service signale que la salle est indisponible.

---

# 20. Contrôleurs

Les contrôleurs se trouvent dans :

```text
src/Controller/
```

Les contrôleurs principaux sont :

```text
HomeController.php
SalleController.php
ReservationController.php
```

## SalleController

Il permet notamment de :

* afficher les salles ;
* afficher le détail d'une salle ;
* afficher le formulaire de création ;
* créer une salle ;
* afficher le formulaire de modification ;
* modifier une salle ;
* gérer l'état actif/inactif de la salle.

## ReservationController

Il permet notamment de :

* afficher les réservations ;
* filtrer les réservations par salle ;
* afficher le détail d'une réservation ;
* afficher le formulaire de création ;
* créer une réservation ;
* annuler une réservation.

Les contrôleurs ne réalisent pas directement les requêtes métier complexes et ne contiennent pas les règles de chevauchement.

---

# 21. Vues

Les vues sont situées dans :

```text
templates/
```

Structure :

```text
templates/

├── error/
│   ├── 404.php
│   ├── 405.php
│   └── 500.php
│
├── layout/
│   └── base.php
│
├── reservation/
│   ├── form.php
│   ├── index.php
│   └── show.php
│
├── salle/
│   ├── form.php
│   ├── index.php
│   └── show.php
│
└── home.php
```

Les vues :

* n'appellent pas directement Eloquent ;
* n'accèdent pas au conteneur DI ;
* ne contiennent pas les règles métier ;
* échappent les données dynamiques affichées.

Un layout commun permet de centraliser la structure HTML et les messages de succès ou d'erreur.

---

# 22. Gestion des erreurs HTTP

L'application gère notamment :

## 404 — Page introuvable

Une route inconnue retourne :

```text
404
```

avec une vue dédiée :

```text
templates/error/404.php
```

## 405 — Méthode non autorisée

Lorsqu'une route existe mais que la méthode HTTP utilisée n'est pas autorisée, l'application retourne :

```text
405
```

avec un en-tête :

```text
Allow
```

La vue correspondante est :

```text
templates/error/405.php
```

## 500 — Erreur interne

Les exceptions non prévues sont interceptées par l'application et affichées via :

```text
templates/error/500.php
```

Cela permet d'éviter d'afficher directement une erreur technique à l'utilisateur.

---

# 23. Messages de succès et d'erreur

Les messages temporaires sont stockés en session.

Exemples :

* salle créée avec succès ;
* salle modifiée avec succès ;
* réservation créée avec succès ;
* réservation annulée avec succès ;
* erreur lors d'une opération.

Ils sont affichés dans le layout :

```text
templates/layout/base.php
```

Les messages sont supprimés après leur affichage afin de fonctionner comme des messages flash.

---

# 24. Conteneur d'injection de dépendances

Le projet utilise PHP-DI.

La configuration se trouve dans :

```text
config/container.php
```

Le conteneur est responsable notamment de construire :

* les repositories ;
* les validateurs ;
* les services ;
* les factories ;
* les contrôleurs ;
* l'application ;
* le dispatcher FastRoute.

Le point d'entrée :

```text
public/index.php
```

est la seule partie qui récupère directement l'application depuis le conteneur.

Cette organisation permet de respecter l'injection des dépendances et l'inversion de contrôle.

---

# 25. Factory

La création d'une réservation est également isolée dans une factory.

Fichiers :

```text
src/Factory/ReservationFactoryInterface.php
src/Factory/ReservationFactory.php
```

La factory transforme le DTO en modèle `Reservation`.

Cela permet au service métier de se concentrer sur les règles métier plutôt que sur les détails de construction du modèle.

---

# 26. Tests

Les tests utilisent PHPUnit.

Ils sont organisés dans :

```text
tests/

├── Integration/
│   └── SalleIntegrationTest.php
│
└── Unit/
    ├── CreerReservationServiceTest.php
    ├── FakeReservationFactory.php
    ├── FakeReservationRepository.php
    ├── FakeSalleRepository.php
    ├── ReservationValidatorTest.php
    └── SalleValidatorTest.php
```

Lancer toute la suite :

```bash
vendor/bin/phpunit
```

Résultat attendu pour la version actuelle :

```text
OK (17 tests, 25 assertions)
```

Les tests unitaires du service utilisent des faux repositories afin de tester les règles métier sans dépendre d'une base MySQL réelle.

Les tests couvrent notamment :

* réservation valide ;
* salle inexistante ;
* salle inactive ;
* date de fin avant la date de début ;
* réservation de plus de quatre heures ;
* réservation dans le passé ;
* conflit de réservation ;
* réservations adjacentes ;
* validation d'email ;
* responsable obligatoire ;
* capacité invalide ;
* type de salle inconnu ;
* dates invalides ;
* intégration avec Eloquent.

---

# 27. Scénarios fonctionnels principaux

## Scénario 1 — Réservation valide

Une réservation dans une salle active avec :

* des dates valides ;
* une durée inférieure ou égale à quatre heures ;
* un responsable ;
* un email valide ;
* un motif valide ;

doit être confirmée.

## Scénario 2 — Chevauchement

Une réservation existante :

```text
10h00 → 12h00
```

et une nouvelle demande :

```text
11h30 → 13h00
```

doivent provoquer un conflit.

## Scénario 3 — Réservations voisines

Une réservation existante :

```text
10h00 → 12h00
```

et une nouvelle demande :

```text
12h00 → 14h00
```

doivent être autorisées.

## Scénario 4 — Salle inactive

Une salle désactivée ne peut pas être réservée.

## Scénario 5 — Durée excessive

Une réservation :

```text
08h00 → 14h00
```

doit être refusée car elle dépasse quatre heures.

## Scénario 6 — Formulaire invalide

Des données invalides doivent :

* empêcher l'insertion ;
* afficher les erreurs ;
* conserver les valeurs valides saisies.

## Scénario 7 — URL inconnue

```text
GET /inconnue
```

doit retourner :

```text
404
```

## Scénario 8 — Méthode non autorisée

```text
DELETE /salles
```

doit retourner :

```text
405
```

avec l'en-tête `Allow`.

---

# 28. Architecture générale

Le flux principal de l'application est :

```text
Navigateur

    ↓

public/index.php

    ↓

Application

    ↓

FastRoute

    ↓

Controller

    ↓

Validator

    ↓

DTO

    ↓

Service

    ↓

Repository

    ↓

Eloquent

    ↓

MySQL
```

Les dépendances sont construites par PHP-DI.

Cette séparation permet de distinguer :

* le transport HTTP ;
* la validation ;
* le transport des données ;
* la logique métier ;
* l'accès aux données ;
* la persistance.

Une documentation détaillée des choix architecturaux est disponible dans :

```text
ARCHITECTURE.md
```

---

# 29. Structure du projet

```text
ReservationSalles/

│

├── config/
│   ├── container.php
│   └── database.php
│
├── database/
│   ├── migrations/
│   │   ├── 001_create_salles_table.php
│   │   └── 002_create_reservations_table.php
│   └── seed.php
│
├── public/
│   ├── assets/
│   │   └── style.css
│   └── index.php
│
├── routes/
│   └── web.php
│
├── src/
│   ├── Application.php
│   │
│   ├── Controller/
│   │   ├── HomeController.php
│   │   ├── ReservationController.php
│   │   └── SalleController.php
│   │
│   ├── DTO/
│   │   ├── CreerReservationDTO.php
│   │   ├── CreerReservationDTOBuilder.php
│   │   ├── CreerSalleDTO.php
│   │   └── CreerSalleDTOBuilder.php
│   │
│   ├── Exception/
│   │   ├── ReservationIntrouvableException.php
│   │   └── SalleIndisponibleException.php
│   │
│   ├── Factory/
│   │   ├── ReservationFactory.php
│   │   └── ReservationFactoryInterface.php
│   │
│   ├── Model/
│   │   ├── Reservation.php
│   │   └── Salle.php
│   │
│   ├── Repository/
│   │   ├── ReservationRepository.php
│   │   ├── ReservationRepositoryInterface.php
│   │   ├── SalleRepository.php
│   │   └── SalleRepositoryInterface.php
│   │
│   ├── Service/
│   │   ├── AnnulerReservationService.php
│   │   └── CreerReservationService.php
│   │
│   └── Validation/
│       ├── ReservationValidator.php
│       ├── SalleValidator.php
│       ├── ValidationResult.php
│       └── ValidatorInterface.php
│
├── templates/
│   ├── error/
│   ├── layout/
│   ├── reservation/
│   ├── salle/
│   └── home.php
│
├── tests/
│   ├── Integration/
│   └── Unit/
│
├── .env.example
├── .gitignore
├── ARCHITECTURE.md
├── CHANGELOG.md
├── composer.json
├── composer.lock
├── phpunit.xml
├── QuestionsReponses.md
└── README.md
```

---

# 30. Commandes principales

## Installer le projet

```bash
composer install
```

## Configurer l'environnement

```bash
cp .env.example .env
```

## Créer les tables

```bash
php database/migrations/001_create_salles_table.php

php database/migrations/002_create_reservations_table.php
```

## Ajouter les données initiales

```bash
php database/seed.php
```

## Lancer le serveur

```bash
php -S localhost:8000 -t public
```

## Exécuter les tests

```bash
vendor/bin/phpunit
```

## Vérifier la syntaxe d'un fichier PHP

```bash
php -l chemin/vers/fichier.php
```

---

# 31. Git et versionnement

Le projet est développé progressivement avec des branches dédiées aux différentes étapes.

Exemples :

```text
feature/01-composer

feature/02-eloquent

feature/03-modeles

feature/04-donnees-initiales

feature/05-routage

feature/06-dto

feature/07-repositories

feature/08-services

feature/09-interface-web

feature/10-router

feature/11-container

feature/12-tests
```

Les versions intermédiaires sont matérialisées par des tags :

```text
v0.0.0
v0.1.0
v0.2.0
v0.3.0
v0.4.0
v0.5.0
v0.6.0
v0.7.0
v0.8.0
v0.9.0
v0.10.0
v0.11.0
v0.12.0
```

La version finale sera publiée après validation complète sous la forme :

```text
release/1.0.0
```

avec le tag :

```text
v1.0.0
```

---

# 32. Sécurité et bonnes pratiques

Les principes suivants sont appliqués :

* les secrets ne sont pas stockés dans Git ;
* `.env` est ignoré ;
* `.env.example` ne contient pas de secret réel ;
* les dépendances sont versionnées via `composer.lock` ;
* `vendor/` n'est pas versionné ;
* les données utilisateur sont validées ;
* les données affichées dans les vues sont échappées ;
* les responsabilités sont séparées entre les différentes couches ;
* les dépendances sont injectées par constructeur ;
* les règles métier sont centralisées dans les services.

---

# 33. Objectifs pédagogiques

Le projet permet de mettre en pratique :

* PHP orienté objet ;
* architecture MVC ;
* Front Controller ;
* routage avec FastRoute ;
* validation avec Respect\Validation ;
* DTO ;
* Builder ;
* ORM avec Eloquent ;
* Active Record ;
* Repository ;
* Service métier ;
* Factory ;
* injection par constructeur ;
* conteneur d'injection de dépendances ;
* autowiring ;
* inversion de contrôle ;
* principes SOLID ;
* tests unitaires ;
* tests d'intégration ;
* gestion des erreurs HTTP ;
* Git et versionnement.

---

# 34. Documentation complémentaire

Les choix architecturaux détaillés sont documentés dans :

```text
ARCHITECTURE.md
```

L'historique des évolutions du projet est disponible dans :

```text
CHANGELOG.md
```

Les questions et réponses pédagogiques relatives aux différentes étapes du projet sont disponibles dans :

```text
QuestionsReponses.md
```

Le diagramme de classes fait partie des livrables du projet et se trouve dans :

```text
docs/class-diagram.png
docs/class-diagram.puml
```

---

# 35. État actuel du projet

La version actuelle contient :

* configuration Composer ;
* connexion MySQL avec Eloquent ;
* migrations ;
* données initiales ;
* modèles Eloquent ;
* routage FastRoute ;
* validation ;
* DTO ;
* Builder ;
* repositories ;
* services métier ;
* factory ;
* contrôleurs ;
* vues ;
* gestion des erreurs 404/405/500 ;
* messages flash ;
* conteneur PHP-DI ;
* tests unitaires ;
* tests d'intégration ;
* documentation d'architecture ;
* diagramme de classes ;
* documentation pédagogique ;
* documentation des évolutions.

Les vérifications finales réalisées comprennent notamment :

* vérification du fonctionnement de l'application ;
* vérification des règles de réservation ;
* vérification de la gestion des salles actives et inactives ;
* vérification des erreurs HTTP 404, 405 et 500 ;
* vérification des messages de succès et d'erreur ;
* vérification de la suite de tests ;
* vérification de la syntaxe PHP ;
* vérification de l'absence d'erreurs Git avec `git diff --check` ;
* vérification de l'installation depuis un clone propre.

La suite du versionnement final consiste à :

1. effectuer la dernière revue des fichiers et de la documentation ;
2. préparer la branche `release/1.0.0` ;
3. effectuer le commit final ;
4. créer le tag `v1.0.0`.

---

# 36. Auteur

Projet réalisé individuellement par :

**Ablaye Sarr**

Projet universitaire — Gestion des réservations de salles universitaires.
