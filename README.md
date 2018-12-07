# Tindart

**Important: ce repo ne contient que le côté serveur. Le repo du client se trouve ici : [https://github.com/Treast/angular-tindar](https://github.com/Treast/angular-tindar)**

Tindart est une application web créée en Symfony 4.2 (serveur) et Angular 7 (client). Elle permet de lister les lieux culturels autour de soi, de voir les événements s'y passant et de s'y inscrire.

## Sommaire
- [Installation](#installation)
	- [Installation du serveur](#installation-du-serveur)
	- [Installation du client](#installation-du-client)
- [Routes implémentées dans le client](#routes-implémentées-dans-le-client)
	- [Authentication](#authentication)
	- [Places](#places)
	- [Events](#events)
	- [Users](#users)

## Installation
### Installation du serveur
Téléchargez la dernière version du projet via Git:
```
git clone https://github.com/Treast/symfony-tindart.git
composer install
```
Il faut ensuite remplacer les variables d'environnement pour la base de données:
```
DATABASE_URL=mysql://MYSQL_USERNAME:MYSQL_PASSWORD@127.0.0.1:3306/MYSQL_DATABASE
```

Puis lancez les migrations:
```
php bin/console doctrine:migrations:migrate
```

Et enfin peuplez votre base de données:
```
php bin/console doctrine:fixtures:load
```

Vous pouvez lancer le serveur PHP:
```
php bin/console server:run
```

#### Credientials
**Email:** `test@test.fr`
**Password:** `test`


La documentation de l'API est disponible ici : [http://127.0.0.1:8000/api/doc](http://127.0.0.1:8000/api/doc)


### Installation du client
Téléchargez la dernière version du projet via Git:
```
git clone https://github.com/Treast/angular-tindar.git
npm install
```

Configurer enfin le client en changeant l'adresse du serveur dans le fichier `angular-tindart/src/app/config.ts` par l'adresse de votre serveur:
```
static BASE_URL = 'http://127.0.0.1:8000';
```

Vous pouvez enfin lancer le client et y accéder [http://localhost:4200](http://localhost:4200) (de préférence en vue adaptative ou sur smartphone):
```
ng serve
```

*Il est possible que vous soyez obiger d'installer @angular/cli pour lancer le serveur. En cas d'erreur, lancez `npm install -g @angular/cli` puis recommencez.*

## Routes implémentées dans le client
### Authentication
|Méthode|URI|Description|
|--|--|--|
|`POST`| `/login` |Connexion
### Places
|Méthode|URI|Description|
|--|--|--|
|`GET`| `/places` |Liste des places
|`GET`| `/places/{place}` |Détails d'une place
### Events
|Méthode|URI|Description|
|--|--|--|
|`GET`| `/places/{place}/events/{event}` |Détails d'un événement
|`POST`| `/places/{place}/events` |Création d'un événement
|`POST`| `/places/{place}/events/{event}/users` |Création d'une participation d'un utilisateur à un événement
|`DELETE`| `/places/{place}/events/{event}/users` |Suppression d'une participation d'un utilisateur à un événement
|`POST`| `/places/searches` |Recherche des lieux culturels autour de la position actuelle de l'utilisateur (500 mètres)
### Users
|Méthode|URI|Description|
|--|--|--|
|`GET`| `/users/{user}` |Détails d'un utilisateur
