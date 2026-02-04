# Laboratoire 2 6GEI466

Version Python: 3.12.6

## Description générale
Ce projet est un petit serveur web HTTP en **Python (Flask)** qui sert une page d’accueil contenant un formulaire.  
Lorsque l’utilisateur soumet le formulaire, un appel **AJAX (POST)** est effectué vers la route **`/horoscope`**.  
Le serveur valide les paramètres reçus, calcule le **signe du zodiaque** selon la date, puis retourne une réponse en **JSON** contenant :
- le prénom et le nom
- la date
- le signe du zodiaque
- le nom d’un fichier image associé
- un court texte d’horoscope

Le résultat est ensuite affiché dynamiquement sur la page via **manipulation du DOM** (jQuery).

---

## Démarrage du serveur
Le serveur peut être démarré localement en se plaçant dans le répertoire contenant le fichier `main.py` et en exécutant la commande : `python .\main.py` 
Il est également possible d’utiliser l’extension Python de Visual Studio Code.  
Dans tous les cas, un environnement virtuel doit être configuré et les dépendances doivent être installées à partir du fichier `requirements.txt` à l’aide de la commande : `pip install -r requirements.txt`

## Accès au serveur
Le serveur a été précisé à fonctionner sur le port 8000. Pour y acceder, dans le navigateur il faut inscrire https://localhost:8000 ou https://127.0.0.1:8000 puisque c'est https. Puisque l'application même a crée le certificat, le navigateur avertira que le certificat peut être dangereux.

## Structure des fichiers

Le projet est organisé de manière à séparer clairement la logique serveur, les gabarits HTML et les fichiers statiques. Cette structure facilite la maintenance du code et respecte les bonnes pratiques recommandées par Flask.

- **`main.py`**  
  Fichier principal de l’application Flask. Il contient :
  - la configuration du serveur,
  - la définition des routes (`/`, `/horoscope`),
  - la gestion des erreurs 404,
  - l’appel aux fonctions utilitaires pour la validation et le traitement des données.

- **`utils.py`**  
  Module utilitaire regroupant les fonctions de validation et de traitement :
  - validation du contenu des champs (`prenom`, `nom`),
  - validation complète des dates,
  - détermination du signe du zodiaque,
  - structure de données contenant les textes et images des horoscopes.

- **`templates/`**  
  Répertoire contenant les gabarits HTML rendus par Flask à l’aide du moteur Jinja2.
  - **`accueil.html`** : page d’accueil affichant le formulaire et la zone de résultats.
  - **`erreur404.html`** : gabarit affiché lorsqu’une route inexistante est demandée.

- **`static/`**  
  Répertoire réservé aux fichiers statiques servis automatiquement par Flask.
  - **`static/js/`** : fichiers JavaScript.
    - `ajax-form.js` : gestion de la requête AJAX et manipulation dynamique du DOM.
    - `datepicker.js` : configuration du DatePicker jQuery UI.
  - **`static/images/`** : images utilisées par l’application.
    - image générique des signes du zodiaque,
    - images spécifiques associées à chaque signe.

Cette organisation respecte la convention Flask selon laquelle les fichiers statiques sont servis depuis le répertoire `static` et les gabarits HTML depuis le répertoire `templates`.

## Routes

### /

La route `/` accepte uniquement la méthode GET.  
Elle retourne le gabarit `accueil.html`, qui affiche le formulaire permettant à l’utilisateur d’entrer son prénom, son nom et une date.  
Aucun horoscope n’est affiché tant que le formulaire n’est pas soumis.

### /horoscope

La route `/horoscope` accepte uniquement la méthode POST.  
Elle est appelée via une requête AJAX et reçoit les paramètres `prenom`, `nom` et `date`.

- Si un paramètre est manquant, une erreur 400 est retournée avec un message indiquant le paramètre manquant.
- Si la date est invalide, une erreur 400 est retournée avec le message « date invalide ».
- Si les données sont valides, une réponse JSON contenant les informations de l’horoscope est retournée.

### /static/*

Les appels dont l’adresse commence par `/static/*` retournent automatiquement les fichiers correspondants présents dans le répertoire `static`, conformément au comportement par défaut de Flask.

## AJAX
Le formulaire est intercepté à l’aide de jQuery afin d’empêcher l’envoi classique du formulaire.

Lors de la soumission :
- une requête AJAX POST est envoyée vers /horoscope,
- la réponse JSON est analysée,
- le DOM est modifié dynamiquement afin d’afficher l’horoscope,
- le formulaire est masqué et un bouton permet de revenir à l’état initial.

La page d'acceuil contient des composantes cachées et AJAX les rends visible à l'aide de `$("#id").show();`, tel que le div contenant l'horoscope. Ceci est la manipulation du DOM.

## Gestion des erreurs404
Flask fournit des mécanismes intégrés pour la gestion des erreurs HTTP.  
La fonction `app.errorhandler(404)` est utilisée afin d’intercepter les erreurs 404 et de retourner le gabarit `erreur404.html`.

Puisque cette page n’est pas entièrement statique, la fonction `render_template()` est utilisée.  
Le chemin de la route demandée est capté et transmis au gabarit afin d’être affiché à l’utilisateur.

## Sécurité

Avec la lib `pyopenssl`, il est possible de générer des certificats ssl et utiliser le protocole https. Puisque l'application même a crée le certificat, le navigateur avertira que le certificat peut être dangereux.

Par défaut, Jinja2 applique un échappement HTML automatique lors de l’affichage de variables à l’aide de la syntaxe `{{ }}`, ce qui constitue un premier niveau de protection contre les injections de code. L'ajout de `| e` dans `{{ }}` est redondant, mais démontre explicitement l'échappement HTML.

En complément, une fonction `validate_content()` a été implémentée afin de valider les données saisies dans le formulaire. Cette fonction vérifie notamment :
- la longueur des champs
- l’absence de caractères spéciaux non désirés

Un mécanisme similaire est utilisé pour l’affichage de la route invalide dans le gabarit de l’erreur 404.  
Cependant, cette validation est adaptée afin de permettre la présence de chiffres dans l’URL.  
Une vérification de la longueur est également effectuée afin d’éviter qu’une requête contenant un nombre excessif de caractères ne puisse nuire au serveur.