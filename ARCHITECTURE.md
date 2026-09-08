# Architecture du projet ReservationSalles

## 1. Présentation

**ReservationSalles** est une application web permettant de gérer des salles et leurs réservations.

L'application permet notamment :

* de consulter les salles ;
* de créer et gérer des salles ;
* de consulter les réservations ;
* de créer une réservation ;
* d'annuler une réservation ;
* de vérifier la disponibilité d'une salle ;
* de valider les données saisies ;
* d'afficher des messages de succès ou d'erreur ;
* de gérer les erreurs et exceptions de manière centralisée.

Le projet est développé en PHP et suit une architecture organisée en couches afin de séparer les responsabilités.

---

# 2. Organisation générale du projet

L'organisation principale du projet est la suivante :

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
│   ├── index.php
│   └── assets/
│       └── style.css
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
│   │   ├── CreerReservationService.php
│   │   └── ReservationService.php
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
│   └── salle/
│
└── tests/
    ├── Integration/
    └── Unit/
```

Cette organisation permet de séparer les responsabilités et de limiter les dépendances entre les différentes parties de l'application.

---

# 3. Architecture MVC

L'application suit le principe **MVC (Model - View - Controller)**.

## 3.1 Model

Les modèles représentent les données et les concepts métier principaux de l'application.

Ils se trouvent dans :

```text
src/Model/
```

On y trouve notamment :

* `Salle.php`
* `Reservation.php`

Le modèle `Salle` représente une salle disponible dans l'application.

Le modèle `Reservation` représente une réservation associée à une salle.

Les modèles ne sont pas responsables de l'affichage HTML.

---

## 3.2 View

Les vues correspondent aux fichiers HTML/PHP utilisés pour présenter les données à l'utilisateur.

Elles se trouvent dans :

```text
templates/
```

Les vues sont organisées par fonctionnalité :

```text
templates/
├── home.php
├── reservation/
│   ├── form.php
│   ├── index.php
│   └── show.php
├── salle/
│   ├── form.php
│   ├── index.php
│   └── show.php
└── error/
    ├── 404.php
    ├── 405.php
    └── 500.php
```

Les vues sont responsables de la présentation des informations.

Le CSS principal se trouve dans :

```text
public/assets/style.css
```

---

## 3.3 Controller

Les contrôleurs reçoivent les requêtes HTTP et coordonnent les différentes couches nécessaires pour produire une réponse.

Ils se trouvent dans :

```text
src/Controller/
```

Les principaux contrôleurs sont :

* `HomeController`
* `SalleController`
* `ReservationController`

Le contrôleur ne doit pas contenir toute la logique métier.

Il délègue les opérations aux services, validateurs et repositories appropriés.

---

# 4. Front Controller

Le projet utilise le principe du **Front Controller**.

Le point d'entrée HTTP principal de l'application est :

```text
public/index.php
```

Toutes les requêtes web passent par ce point d'entrée.

Le Front Controller permet notamment :

* d'initialiser l'application ;
* de charger les dépendances ;
* de charger les routes ;
* de centraliser le traitement des requêtes ;
* de gérer les erreurs globales.

Cette approche évite d'avoir plusieurs points d'entrée PHP indépendants pour chaque fonctionnalité.

---

# 5. Router

Les routes sont regroupées dans :

```text
routes/web.php
```

Le routeur associe une URL et une méthode HTTP à une action d'un contrôleur.

Le principe est notamment de permettre une séparation entre :

```text
Requête HTTP
      ↓
Route
      ↓
Controller
      ↓
Service
      ↓
Repository
      ↓
Base de données
```

Le routage permet donc de déterminer quel contrôleur doit traiter chaque requête.

---

# 6. DTO

Le projet utilise des **DTO (Data Transfer Objects)** afin de transporter les données nécessaires à certaines opérations.

Les DTO se trouvent dans :

```text
src/DTO/
```

Exemples :

```text
CreerSalleDTO.php
CreerReservationDTO.php
```

Des builders sont également utilisés :

```text
CreerSalleDTOBuilder.php
CreerReservationDTOBuilder.php
```

Le DTO permet de ne pas transmettre directement toutes les données brutes de la requête aux différentes couches de l'application.

Le flux est donc notamment :

```text
Requête HTTP
      ↓
DTO
      ↓
Validation
      ↓
Service
```

Cela permet de mieux contrôler les données utilisées par la logique applicative.

---

# 7. Validation

La validation est isolée dans une couche dédiée :

```text
src/Validation/
```

Les principaux validateurs sont :

* `SalleValidator`
* `ReservationValidator`

Une interface commune est définie :

```text
ValidatorInterface
```

Le résultat de validation est représenté par :

```text
ValidationResult
```

Cette séparation permet de ne pas mélanger les règles de validation avec les contrôleurs ou les repositories.

Par exemple :

```text
Données utilisateur
        ↓
Validator
        ↓
Données valides ?
     ↙       ↘
   Oui        Non
    ↓          ↓
 Service    Erreurs
```

---

# 8. Repository

L'accès aux données est isolé dans la couche Repository :

```text
src/Repository/
```

On trouve notamment :

* `SalleRepository`
* `ReservationRepository`

Des interfaces sont également définies :

* `SalleRepositoryInterface`
* `ReservationRepositoryInterface`

Le rôle d'un repository est de centraliser les opérations d'accès aux données.

Le service ne doit donc pas avoir à connaître directement les détails des requêtes SQL.

Le principe est :

```text
Service
   ↓
Repository
   ↓
Base de données
```

Cela facilite notamment les tests unitaires grâce au remplacement du repository réel par un fake.

---

# 9. ORM et persistance

Le projet utilise **Eloquent ORM** grâce au package `illuminate/database`.

L'initialisation et la configuration d'Eloquent sont centralisées dans :

```text
config/database.php
```

Cette configuration permet notamment de :

* charger les variables d'environnement ;
* configurer la connexion à la base de données MySQL ;
* initialiser Eloquent ;
* rendre l'ORM disponible pour les modèles.

Les migrations se trouvent dans :

```text
database/migrations/
```

Elles permettent notamment de créer les tables :

```text
salles
reservations
```

Les modèles du projet utilisent Eloquent pour représenter les données et effectuer les opérations de persistance.

Les repositories encapsulent l'accès aux données et limitent ainsi le couplage direct entre la logique applicative et Eloquent.

Cette organisation permet de conserver une séparation claire entre :

* la logique métier ;
* la logique applicative ;
* la logique de persistance ;
* l'accès à la base de données.

L'utilisation des repositories facilite également les tests, notamment grâce aux interfaces et aux faux repositories utilisés dans les tests unitaires.


# 10. Services

La logique applicative est regroupée dans :

```text
src/Service/
```

On trouve notamment :

* `CreerReservationService`
* `AnnulerReservationService`
* `ReservationService`

Les services orchestrent les opérations nécessaires à l'exécution d'un cas d'utilisation.

Par exemple, la création d'une réservation peut suivre le principe :

```text
Controller
    ↓
DTO
    ↓
Validator
    ↓
Service
    ↓
Repository
    ↓
Base de données
```

Cette organisation évite de placer toute la logique métier dans les contrôleurs.

---

# 11. Gestion des exceptions

Les exceptions métier spécifiques sont regroupées dans :

```text
src/Exception/
```

Exemples :

```text
ReservationIntrouvableException
SalleIndisponibleException
```

Elles permettent de représenter explicitement certaines situations métier.

Par exemple :

* une réservation demandée n'existe pas ;
* une salle n'est pas disponible pour la période demandée.

Les exceptions permettent ensuite au niveau applicatif de transformer ces erreurs en réponses adaptées à l'utilisateur.

Les vues d'erreur sont notamment présentes dans :

```text
templates/error/
```

avec :

* `404.php`
* `405.php`
* `500.php`

---

# 12. Factory

Le projet utilise une Factory pour la création des réservations :

```text
src/Factory/
```

Elle est accompagnée d'une interface :

```text
ReservationFactoryInterface
```

et d'une implémentation :

```text
ReservationFactory
```

La Factory centralise la création d'un objet `Reservation`.

Cette approche permet de ne pas disperser la logique de construction de l'objet dans plusieurs contrôleurs ou services.

---

# 13. Dependency Injection

Le projet utilise la **Dependency Injection (DI)**.

Les dépendances sont fournies aux objets au lieu d'être créées directement à l'intérieur de ceux-ci lorsque cela est pertinent.

La configuration des dépendances est notamment regroupée dans :

```text
config/container.php
```

Par exemple, un service peut dépendre d'une interface de repository :

```text
Service
   ↓
ReservationRepositoryInterface
```

L'implémentation concrète peut alors être fournie par le conteneur.

Cette approche permet de réduire le couplage entre les composants.

---

# 14. Inversion of Control

L'**Inversion of Control (IoC)** consiste à ne pas laisser chaque classe gérer elle-même la création de toutes ses dépendances.

Dans ce projet, cette responsabilité est en partie confiée à la configuration du conteneur :

```text
config/container.php
```

Le principe est :

```text
Sans IoC :

Service
 └── crée directement Repository

Avec IoC :

Container
 ├── crée Repository
 └── injecte Repository dans Service
```

Le service dépend ainsi d'une abstraction plutôt que de gérer lui-même la construction de ses dépendances.

---

# 15. SOLID

L'organisation du projet applique plusieurs principes **SOLID**.

## 15.1 S — Single Responsibility Principle

Chaque composant possède une responsabilité principale.

Exemples :

* Controller → traitement des requêtes et coordination ;
* Validator → validation ;
* DTO → transport des données ;
* Service → logique applicative ;
* Repository → accès aux données ;
* Factory → création d'objets.

Cela limite les classes qui font trop de choses différentes.

---

## 15.2 O — Open/Closed Principle

Les abstractions et interfaces permettent d'ajouter ou de modifier des implémentations sans modifier nécessairement les composants qui les utilisent.

Par exemple :

```text
ReservationRepositoryInterface
             ↑
             │
ReservationRepository
```

Le service dépend de l'interface plutôt que directement de l'implémentation.

---

## 15.3 L — Liskov Substitution Principle

Lorsqu'une classe implémente une interface, elle doit pouvoir être utilisée à la place de cette abstraction sans modifier le comportement attendu du composant client.

Les interfaces de repository et de validation permettent notamment cette substitution.

---

## 15.4 I — Interface Segregation Principle

Les interfaces sont spécialisées selon les responsabilités.

Par exemple :

```text
SalleRepositoryInterface
ReservationRepositoryInterface
ValidatorInterface
ReservationFactoryInterface
```

Cela évite de créer une interface unique contenant des méthodes sans rapport entre elles.

---

## 15.5 D — Dependency Inversion Principle

Les couches supérieures dépendent d'abstractions plutôt que directement des détails d'implémentation.

Par exemple :

```text
Service
   ↓
ReservationRepositoryInterface
   ↓
ReservationRepository
```

Le service ne dépend donc pas directement des détails de la classe concrète.

---

# 16. Séparation des responsabilités

Le flux global de l'application peut être représenté ainsi :

```text
                     UTILISATEUR
                          │
                          ▼
                  public/index.php
                   Front Controller
                          │
                          ▼
                       Router
                          │
                          ▼
                      Controller
                          │
                          ▼
                         DTO
                          │
                          ▼
                      Validator
                          │
                  ┌───────┴───────┐
                  │               │
                Erreur           OK
                  │               │
                  ▼               ▼
                View            Service
                                  │
                                  ▼
                              Repository
                                  │
                                  ▼
                           Base de données
```

Les réponses sont ensuite retournées vers l'utilisateur :

```text
Base de données
      ↓
Repository
      ↓
Service
      ↓
Controller
      ↓
View
      ↓
Réponse HTTP
```

---

# 17. Tests

Les tests sont organisés dans :

```text
tests/
```

avec deux catégories principales :

```text
tests/
├── Unit/
└── Integration/
```

Les tests unitaires permettent de tester les composants individuellement, notamment :

* les validators ;
* les services ;
* les repositories simulés ;
* les factories.

Les tests d'intégration permettent de vérifier le fonctionnement de plusieurs composants ensemble.

La suite finale du projet contient actuellement :

```text
17 tests
25 assertions
```

et l'exécution de PHPUnit donne :

```text
OK (17 tests, 25 assertions)
```

---

# 18. Avantages de cette architecture

Cette organisation apporte plusieurs avantages.

### Maintenabilité

Chaque responsabilité est localisée dans une couche précise.

### Testabilité

Les interfaces permettent d'utiliser des implémentations de test comme les Fake Repositories.

### Réutilisabilité

Les services, validators et repositories peuvent être réutilisés par plusieurs contrôleurs.

### Faible couplage

La Dependency Injection et les interfaces limitent les dépendances directes entre les classes.

### Évolutivité

Il est possible d'ajouter de nouvelles fonctionnalités sans concentrer toute la logique dans un seul fichier.

---

# 19. Résumé des responsabilités

| Élément                | Responsabilité                           |
| ---------------------- | ---------------------------------------- |
| `public/index.php`     | Front Controller / point d'entrée HTTP   |
| `routes/web.php`       | Définition des routes                    |
| `Controller/`          | Traitement des requêtes et coordination  |
| `Model/`               | Représentation des objets métier         |
| `DTO/`                 | Transport structuré des données          |
| `Validation/`          | Validation des données                   |
| `Service/`             | Logique applicative                      |
| `Repository/`          | Accès aux données                        |
| `Factory/`             | Création des objets                      |
| `Exception/`           | Exceptions métier                        |
| `templates/`           | Présentation / vues                      |
| `config/container.php` | Configuration des dépendances            |
| `database/`            | Migrations et initialisation des données |
| `tests/`               | Tests unitaires et d'intégration         |

---

# 20. Conclusion

L'architecture de `ReservationSalles` repose sur une séparation claire des responsabilités autour d'une organisation MVC enrichie par plusieurs patterns et principes d'architecture :

* MVC ;
* Front Controller ;
* Router ;
* DTO ;
* Validator ;
* Repository ;
* Service ;
* Factory ;
* Dependency Injection ;
* Inversion of Control ;
* principes SOLID.

Cette organisation permet de construire une application plus maintenable, testable et évolutive, tout en limitant le couplage entre les différentes couches.

L'objectif est que chaque couche ait une responsabilité clairement définie et que les dépendances entre couches soient maîtrisées.
