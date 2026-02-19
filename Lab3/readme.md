# Laboratoire 3 — 6GEI466

Faire fonctionner le site d'horoscope du laboratoire 2 en remplaçant le backend Flask :

- Partie 1 : PHP
- Partie 2 : NodeJS / Express

# Structure générale

Dans les deux parties :

- Les fichiers front-end (HTML, CSS, JS, images) demeurent inchangés
- Seul le backend est réécrit
- Un contrôleur central reçoit toutes les requêtes HTTP
- Des fonctions utilitaires et dictionnaire d'information d'horoscope sont dans des fichiers utils.xxx & horoscope.xxx


# Partie 1 — PHP

## Démarrage du serveur

Pour démarrer le serveur, il faut avoir l’interpréteur PHP installé et se placer dans le dossier contenant index.php.

Commande : `php -S 127.0.0.1:8000 index.php`

Préciser `index.php` au démarrage permet de faire passer toutes les requêtes par ce fichier (front controller).

## Structure des fichiers
```
Partie1/
├─ static/
│  ├─ images/
│  ├─ js/
├─ templates/
│  ├─ accueil.html
│  ├─ erreur404.html
├─ horoscopes.php
├─ index.php
├─ utils.php
```


## Comportement du contrôleur PHP

Le fichier index.php agit comme contrôleur unique.

Toutes les requêtes HTTP (/ , /static/*, /horoscope, etc.) passent par ce fichier, qui décide de la réponse selon :

- la méthode HTTP via `$_SERVER['REQUEST_METHOD']`
- l’URL via `$_SERVER['REQUEST_URI']` et `parse_url()`

Au démarrage, il charge :

- `utils.php` → fonctions utilitaires
- `horoscopes.php` → textes et images des signes



### 1) Fichiers statiques — /static/*

Si l’URL commence par `/static/` :

- Le serveur cherche le fichier dans le dossier static/
- Retourne le fichier avec le bon type MIME
- Sinon → page 404 personnalisée



### 2) Page d’accueil — GET /

- Lecture du gabarit templates/accueil.html
- Remplacement de sections template (ex. {{ titre }}) avec une vérification de texte: `$html = str_replace("{{ titre }}", "HOROSCOPE", $html);`
- Retour du HTML au navigateur



### 3) Service horoscope — POST /horoscope

Appelé via AJAX depuis le formulaire.

Traitement :

1. Récupération du formulaire :
   - prenom
   - nom
   - date
2. Validation :
   - Champ manquant → 400 "parametre manquant"
   - Date invalide → 400 "date invalide"
3. Calcul du signe avec fonction utilitaire
4. Recupération des informations d'horoscope du fichier horoscope.php (path image & text)
5. Retour JSON :
```
{
  "prenom": "...",
  "nom": "...",
  "sign": "...",
  "image": "...",
  "text": "..."
}
```


### 4) Routes inconnues → 404

Toute route non gérée retourne :

- Code HTTP 404
- Gabarit templates/erreur404.html
- Chemin injecté dans la page



# Partie 2 — NodeJS / Express

## Installation et démarrage

Initialisation :
```
npm init -y
npm install express
```
Démarrage : `node server.js` ou `npm start`



## Structure des fichiers
```
Partie2/
├─ static/
│  ├─ images/
│  ├─ js/
├─ templates/
│  ├─ accueil.html
│  ├─ erreur404.html
├─ horoscopes.js
├─ server.js
├─ utils.js
```


## Comportement du contrôleur NodeJS

Le fichier server.js implémente le serveur HTTP avec Express.

Il agit comme contrôleur central et traite les requêtes selon :

- Méthode HTTP
- URL
- Données reçues

Modules chargés :

- express → routing
- fs, path → système de fichiers
- horoscopes.js → textes + images
- utils.js → fonctions utilitaires

Serveur accessible sur : `http://127.0.0.1:8000`



### 1) Middleware POST

`app.use(express.urlencoded({ extended: true }));` - Permet de lire les données envoyées par formulaire/AJAX via req.body.



### 2) Fichiers statiques — /static/*

`app.use("/static", express.static(path.join(__dirname, "static")));` - Toutes les requêtes /static/ servent automatiquement les fichiers du dossier static/.



### 3) Page d’accueil — GET /

- Lecture de templates/accueil.html
- Remplacement de {{ titre }}
- Retour HTML



### 4) Service horoscope — POST /horoscope

Traitement :

1. Lecture prenom, nom, date
2. Validation
3. Calcul du signe
4. Lecture horoscope
5. Retour JSON



### 5) Routes inconnues → 404

Toutes les routes non gérées retournent le template erreur404.html avec le chemin injecté.



### 6) Démarrage du serveur
```
app.listen(PORT, "127.0.0.1", () => {
  console.log(`Node server running at http://127.0.0.1:${PORT}`);
});
```