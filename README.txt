Chez Léon — Plateforme IoT pour un restaurant gastronomique

Projet de développement web réalisé dans le cadre de l’ING1 à CY Tech (2025-2026).



Présentation

Chez Léon est une application web développée avec Laravel. Le projet simule le fonctionnement d’un restaurant gastronomique utilisant des objets connectés pour gérer certains aspects de son activité comme le suivi des températures ou la supervision d’équipements.

L’objectif est de comprendre comment l’IoT peut être intégré dans un projet web concret.



Fonctionnement

Le site propose plusieurs niveaux d’accès selon les utilisateurs. Un visiteur peut consulter les pages publiques. Une fois connecté, l’utilisateur a accès à certaines fonctionnalités liées aux objets connectés et à son profil.

Un niveau plus avancé permet de gérer les équipements, et un espace administrateur permet de gérer les utilisateurs et l’ensemble du système.



Aspects techniques

Le projet est développé avec Laravel 11 et PHP 8.3. Les accès sont gérés avec des middlewares pour contrôler les rôles et les permissions.

Un système de points d’expérience a été ajouté pour suivre l’activité des utilisateurs. Le cache Laravel est utilisé pour améliorer les performances.

L’interface utilise Bootstrap avec quelques personnalisations CSS.


Installation et lancement

Prérequis
Il faut avoir PHP 8.3 ou plus, Composer, Node.js et Git.


Installation

Cloner le projet :
git clone https://github.com/Neimad1612/Projet-dev-web.git cd Projet-dev-web 

Installer les dépendances PHP :
composer install 

Installer les dépendances front :
npm install npm run build 

Configurer l’environnement :
cp .env.example .env php artisan key:generate 

Créer la base de données et remplir les données de test :
php artisan migrate:fresh --seed 

Lancer le serveur :
php artisan serve 

Le site est ensuite accessible sur http://127.0.0.1:8000


Organisation du projet

Le projet est organisé de façon classique avec une partie pour la logique métier, une pour les vues, une pour les routes et une pour la base de données.


Équipe

Le projet a été réalisé en groupe avec une répartition entre backend, frontend et base de données.


Licence

Projet académique réalisé à CY Tech. Usage pédagogique uniquement.
