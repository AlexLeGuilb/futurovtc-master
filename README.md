# FUTUROVTC
## présentation
FuturoVTC est un projet réalisé à 4 dans le cadre de ma licence pro.
Chaque groupe avait un projet différent, le but était de construire une application en client léger.
Le choix des techno était libre, mais la base de données devait être MySQL (WAMP de l'univ)

Il nous a été fournit un cahier des charges, un prof représentait le client avec une date limite. A nous de nous organiser pour analyser la demande, poser les questions, étudier la faisabilité et réaliser le projet.

Ce projet a été réalisé avec Laravel et MDB.
Ce projet fonctionne toujours, si le dossier n'est pas sur un serveur web, la commande ```php lancementPeuplement.php``` fonctionne aussi

## readme original
# **Projet futuroVTC**

**Pour installer et commencer à utiliser l'application web il faut suivre ces étapes :**
### 1. Créer une base de données :
    L'application génère les tables mais ne créé pas la base. Pour cela la base doit avoir la configuration suivante :
        - nom 'vtc_web'
        - encodage 'utf8_general_ci'
        - un utilisateur avec comme nom 'vtc_user', mot de passe 'caribou' et lui donner les droits sur la base
### 2. Cloner le git :
    https://gitlab.com/n.renault/futurovtc.git.
### 3. Composer :
    En ligne de commandes dans le dossier "futurovtc/vtc_web/" faire la commande : composer install
### 4. Modifier le .env :
    Dans le .env il faut modifier l'adresse de la base de données pour correspondre a votre serveur
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1 (adresse du serveur de la BDD)
        DB_PORT=3306

### 5. Générer les tables :
    En ligne de commandes dans le dossier "futurovtc/vtc_web/" faire la commande : php artisan migrate:fresh

### 6. Peupler la base de données :
    Dans le navigateur, se rendre dans le fichier "http://localhost/futurovtc/Documentation/BDD/peuplement.php" (remplacer localhost par l'adresse du serveur si besoin)

### 7. Lancer l'application :
    En ligne de commandes dans le dossier "futurovtc/vtc_web/" faire la commande : php artisan serve
        _Le site sera accessible par l'adresse localhost:8000_
    
    Ou alors, créer un virtualHost avec comme racine le dossier "futurovtc/vtc_web/public/"

### 8. Connexion au site :
    Le mot de passe de tous les utilisateurs en base est "caribou". Cependant il y a un utilisateur à part qui a tous les droits avec les login suivants => mail : "admin@futurovtc.fr" mdp : "admin"
