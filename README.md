
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

